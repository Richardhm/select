<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>BmSyS - Select</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <script src="{{asset('jquery.min.js')}}"></script>
        <script src="{{asset('sweetalert2@11.js')}}"></script>
        <link rel="stylesheet" href="{{asset('toastr.min.css')}}">
        <script src="{{asset('toastr.min.js')}}"></script>
        <script src="{{asset('jquery.inputmask.min.js')}}"></script>
        <script src="{{asset('jquery.mask.min.js')}}"></script>
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @yield('css')
        <script>
            toastr.options = {
                closeButton: true,
                progressBar: true,
                timeOut: 5000, // Tempo de exibição (5 segundos)
                positionClass: "toast-top-center", // Ainda usamos uma posição padrão
                onShown: function() {
                    $("#toast-container").addClass("custom-toast-center");
                }
            };
        </script>
        <style>
            .whatsapp-container {
                position: fixed;
                bottom: 20px;
                right: 20px;
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 5px;
                z-index: 1000;
            }

            .suporte-texto {
                color: white;
                font-weight: bold;
                font-size: 14px;
                text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
                background: rgba(0,0,0,0.3);
                padding: 2px 8px;
                border-radius: 5px;
            }

            .whatsapp-button {
                background-color: #25D366;
                padding: 10px;
                border-radius: 50%;
                box-shadow: 0 4px 6px rgba(0,0,0,0.2);
                transition: transform 0.3s ease;
            }

            .whatsapp-button:hover {
                transform: scale(1.1);
            }

            #user-modal {
                opacity: 0; /* Inicialmente invisível */
                transform: translateX(100%);
                transition: transform 0.5s ease-in-out, opacity 0.5s ease-in-out;
            }
            #user-modal.translate-x-0 {
                opacity: 1; /* Tornar visível */
                transform: translateX(0);
            }

            #toast-container {
                top: 10% !important;
                left: 50% !important;
                transform: translate(-50%, -50%) !important;
                width:500px !important;
                position: fixed !important;
            }
            .ajax_load {display:none;position:fixed;left:0;top:0;width:100%;height:100%;background:rgba(0,0,0,.5);z-index:1000;}
            .ajax_load_box{margin:auto;text-align:center;color:#fff;font-weight:var(700);text-shadow:1px 1px 1px rgba(0,0,0,.5)}
            .ajax_load_box_circle{border:16px solid #e3e3e3;border-top:16px solid #61DDBC;border-radius:50%;margin:auto;width:80px;height:80px;-webkit-animation:spin 1.2s linear infinite;-o-animation:spin 1.2s linear infinite;animation:spin 1.2s linear infinite}
            @-webkit-keyframes spin{0%{-webkit-transform:rotate(0deg)}100%{-webkit-transform:rotate(360deg)}}
            @keyframes spin{0%{transform:rotate(0deg)}100%{transform:rotate(360deg)}}

            @keyframes handClick {
                0%, 100% { transform: translateX(0) scale(1); }
                50% { transform: translateX(8px) scale(1.1); }
            }

            @keyframes textBlink {
                0%, 100% { opacity: 1; }
                50% { opacity: 0.3; }
            }

            .animate-handClick {
                animation: handClick 1s infinite ease-in-out;
            }

            .animate-textBlink {
                animation: textBlink 1.5s infinite ease-in-out;
            }

            .switch {position: relative;display: flex;width: 40px;height: 20px;margin:0 0 0 10px;padding:0;justify-content: center;}
            .switch input {opacity: 0;width: 0;height: 0;}
            .slider {position: absolute;cursor: pointer;top: 0;left: 0;right: 0;bottom: 0;background-color: #ccc;transition: .4s;border-radius: 20px;}
            .slider:before {position: absolute;content: "";height: 16px;width: 16px;left: 2px;bottom: 2px;background-color: white;transition: .4s;border-radius: 50%;}
            input:checked + .slider {background-color: #4caf50;}
            input:checked + .slider:before {transform: translateX(20px);}

            .toast {
                min-width: 400px !important;
                max-width: 90vw;
                width: auto !important;
                font-size: 14px;
            }



            @media (max-width: 768px) {
                body {
                    background-position: top center;
                    background-size: contain;
                    background-repeat: no-repeat;
                    background-color: #ffffff; /* fundo branco para não ficar vazio */
                }
            }

        </style>
    </head>
    <body style="background-color: #6e4feb;">
        <div class="ajax_load">
            <div class="ajax_load_box">
                <div class="ajax_load_box_circle"></div>
                <p class="ajax_load_box_title">Aguarde, carregando...</p>
            </div>
        </div>





        <div class="min-h-screen">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
        @yield('scripts')
    </body>
</html>
