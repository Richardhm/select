<?php

namespace App\Http\Controllers;

use App\Jobs\SendVerificationEmail;
use App\Models\Assinatura;
use App\Models\EmailAssinatura;
use App\Models\User;
use Carbon\Carbon;
use Efi\EfiPay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AssinaturaController extends Controller
{
    private $efi;

    public function __construct()
    {
        $mode = config('gerencianet.mode');
        $certificate = config("gerencianet.{$mode}.certificate_name");

        $client_id = config("gerencianet.{$mode}.client_id");
        $client_secret = config("gerencianet.{$mode}.client_secret");
        $certificate_path = base_path("certs/{$certificate}");

        $options = [
            'client_id' => $client_id,
            'client_secret' => $client_secret,
            'sandbox' => true,
            'debug' => false
        ];

        $this->efi = new EfiPay($options);

    }

    public function createIndividual()
    {
        return view('assinaturas.individual.create');
    }

    public function storeIndividual(Request $request)
    {
        $isTrial = $request->has('trial');
        $validationRules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'cpf' => 'required|string',
            'birth_date' => 'required|date|before:-18 years',
            'phone' => 'required|string',
            'password' => 'required|confirmed|min:8',
            'imagem' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ];

        if (!$isTrial) {
            $validationRules['paymentToken'] = 'required|string';
            // Endereço pode ser obrigatório fora do trial
        }

        $request->validate($validationRules);

        $imagePath = null;
        if ($request->hasFile('imagem')) {
            $imagePath = $request->file('imagem')->store('users', 'public');
        }

        // Transactions!
        DB::beginTransaction();
        try {
            if ($isTrial) {
                $user = $this->createUser($request, $imagePath, true);
                $assinatura = $this->createTrialAssinatura($user);
            } else {
                // Antes de salvar o user, tente criar cobrança na Efi
                $efiResponse = $this->createEfiSubscription($request);
                if (!isset($efiResponse['data']['subscription_id'])) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Falha na assinatura. Tente novamente.',
                        'data' => $efiResponse
                    ]);
                }
                $user = $this->createUser($request, $imagePath, false);
                $assinatura = $this->createPaidAssinatura($user, $efiResponse['data']['subscription_id']);
            }

            EmailAssinatura::create([
                'assinatura_id' => $assinatura->id,
                'email' => $user->email,
                'user_id' => $user->id,
                'is_administrador' => true,
            ]);

            if (method_exists($this, 'administradoraPlanos')) {
                $this->administradoraPlanos($assinatura->id);
            }
            SendVerificationEmail::dispatch($user);
            DB::commit();
            return response()->json([
                'success' => true,
                'redirect' => route('bemvindo', ['user' => $user->id])
            ]);
        } catch (EfiException $e) {
            DB::rollBack();
            \Log::error("[Assinatura] Falha Efi: " . $e->getMessage());
            return response()->json([
                'error' => true,
                'message' => $e->getMessage(),
                'code' => $e->getCode()
            ], 400);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("[Assinatura] Erro: " . $e->getMessage());
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /** Criação centralizada do usuário */
    private function createUser($request, $imagePath, $trial = false)
    {

        return User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'phone' => $request->phone,
            'imagem' => $imagePath,
            'cpf' => preg_replace('/[^0-9]/', '', $request->cpf),
            'birth_date' => $request->birth_date,
        ]);
    }

    /** Criação da assinatura trial */
    private function createTrialAssinatura($user)
    {
        return Assinatura::create([
            'user_id' => $user->id,
            'tipo_plano_id' => 1,
            'status' => 'trial',
            'trial_ends_at' => now()->addDays(7),
            'emails_permitidos' => 3,
            'emails_extra' => 1,
            'preco_base' => 0,
            'preco_total' => 0,
        ]);
    }

    public function historicoPagamentos()
    {

        try {
            $assinatura = Assinatura::where('user_id', auth()->id())->firstOrFail();
            $params = ['id' => $assinatura->subscription_id];

            // Buscar detalhes da assinatura
            $response = $this->efi->detailSubscription($params);

            $dados = [
                'proxima_fatura' => [
                    'data' => Carbon::parse($response['data']['next_execution'])->format('d/m/Y'),
                    'valor' => number_format($response['data']['value'] / 100, 2, ',', '.')
                ],
                'historico' => []
            ];

            foreach ($response['data']['history'] as $evento) {
                // Buscar detalhes completos da cobrança
                $chargeResponse = $this->efi->detailCharge(['id' => $evento['charge_id']]);

                // Formatar dados conforme nova estrutura
                $dados['historico'][] = [
                    'charge_id' => $chargeResponse['data']['charge_id'],
                    'status' => $chargeResponse['data']['status'],
                    'valor_total' => number_format($chargeResponse['data']['total'] / 100, 2, ',', '.'),
                    'data_criacao' => Carbon::parse($chargeResponse['data']['created_at'])->format('d/m/Y H:i'),
                    'metodo_pagamento' => $chargeResponse['data']['payment']['method'] ?? 'N/A',
                    'ultima_mensagem' => end($chargeResponse['data']['history'])['message'] ?? 'Sem informações',
                    'cliente' => [
                        'nome' => $chargeResponse['data']['customer']['name'],
                        'email' => $chargeResponse['data']['customer']['email']
                    ],
                    'items' => array_map(function($item) {
                        return [
                            'nome' => $item['name'],
                            'valor_unitario' => number_format($item['value'] / 100, 2, ',', '.'),
                            'quantidade' => $item['amount']
                        ];
                    }, $chargeResponse['data']['items'])
                ];
            }

            return view('assinaturas.historico', compact('dados'));

        } catch (\Exception $e) {
            \Log::error("Erro ao buscar histórico: " . $e->getMessage());
            return redirect()->back()->withErrors('Erro ao carregar histórico de pagamentos');
        }
    }






    /** Criação da assinatura paga */
    private function createPaidAssinatura($user, $subscription_id)
    {
        // Defina valores corretos ao seu projeto
        $precoBase = 39.90;
        $emailsPermitidos = 1;
        $emailsExtra = 0;
        $precoTotal = $precoBase + ($emailsExtra * 37.90);

        return Assinatura::create([
            'user_id' => $user->id,
            'tipo_plano_id' => 1,
            'preco_base' => $precoBase,
            'emails_permitidos' => $emailsPermitidos,
            'emails_extra' => $emailsExtra,
            'preco_total' => $precoTotal,
            'status' => 'ativo',
            'subscription_id' => $subscription_id
        ]);
    }

    /** Criação da assinatura Efi */
    private function createEfiSubscription($request)
    {
        $params = [
            "id" => 13464 // Novo ID do seu plano Select na Efi
        ];
        $items = [
            [
                "name" => "Plano Select",
                "amount" => 1,
                "value" => 3990 // R$39,90 em centavos
            ]
        ];
        $customer = [
            "name" => $request->name,
            "cpf" => preg_replace('/[^0-9]/', '', $request->cpf),
            "phone_number" => preg_replace('/[^0-9]/', '', $request->phone),
            "email" => $request->email,
            "birth" => $request->birth_date
        ];
        $billingAddress = [
            "street" => $request->street,
            "number" => !empty($request->number) ? $request->number : "S/N",
            "neighborhood" => $request->neighborhood,
            "zipcode" => str_replace('-', '', $request->zipcode),
            "city" => $request->city,
            "state" => $request->state,
        ];
        $body = [
            "items" => $items,
            "payment" => [
                "credit_card" => [
                    "billing_address" => $billingAddress,
                    "payment_token" => $request->paymentToken,
                    "customer" => $customer
                ]
            ],
            "metadata" => [
                "notification_url" => "https://select.bmsys.com.br"
            ]
        ];

        return $this->efi->createOneStepSubscription($params, $body);
    }




}
