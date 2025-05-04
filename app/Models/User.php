<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\CustomVerifyEmail;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'imagem',
        'cpf',
        'birth_date',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new CustomVerifyEmail());
    }

    // Verificar se o usuário logado é administrador da assinatura vai ter acesso ao users/manage
    public function isAdmin()
    {
        $emailAssinatura = EmailAssinatura::whereHas('assinatura', function ($query) {
            $query->where('user_id', $this->id);
        })->first();
        if ($emailAssinatura && $emailAssinatura->is_administrador == 1) {
            return true;
        }
        return false;
    }

    public function isDesenvolvedor()
    {
        $emailsPermitidos = [
            'richardjonhshm@gmail.com',
            // ...
        ];

        return in_array($this->email, $emailsPermitidos);
    }

    public function isFolder()
    {
        $assinaturaId = \App\Models\EmailAssinatura::where('email', $this->email)->first()?->assinatura_id;

        if (!$assinaturaId) {
            return false;
        }

        $folder = \App\Models\Assinatura::find($assinaturaId)?->folder;

        return $folder ?: false;
    }

    public function isOnTrial()
    {
        return $this->assinatura->status === 'trial' &&
            now()->lt($this->assinatura->trial_ends_at);
    }

    public function estaEmTrial()
    {
        return $this->assinaturaStatus()
            ->where('status', 'trial')
            ->whereNotNull('trial_ends_at')
            ->where('trial_ends_at', '>', Carbon::now())
            ->exists();
    }

    public function isActive()
    {
        return $this->status == 1; // Retorna verdadeiro se status for 1 (ativo)
    }

    public function assinaturaUser()
    {

        return EmailAssinatura::whereHas('assinatura', function ($query) {
            $query->where('user_id', $this->id);
        })->toSql();
    }

    public function assinaturaStatus()
    {

        return $this->hasMany(\App\Models\Assinatura::class, 'user_id');
    }

    public function assinatura()
    {
        return $this->hasOne(Assinatura::class);
    }

    public function tabelasOrigens()
    {
        return $this->hasManyThrough(
            TabelaOrigens::class,
            AssinaturaCidade::class,
            'assinatura_id',    // Foreign key na tabela assinaturas_cidade
            'id',               // Foreign key na tabela tabela_origens
            'id',               // Local key na tabela users (não direto, precisa do through)
            'tabela_origem_id'  // Local key na tabela assinaturas_cidade
        )->through('assinatura');
    }

    public function assinaturas()
    {
        return $this->hasOneThrough(
            Assinatura::class,
            EmailAssinatura::class,
            'user_id',       // Foreign key na tabela emails_assinatura
            'id',            // Foreign key na tabela assinaturas
            'id',            // Local key na tabela users
            'assinatura_id'  // Local key na tabela emails_assinatura
        );
    }



}
