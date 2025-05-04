<section>
    <header class="w-full">
        <div class="w-full flex justify-between items-center">
            <div class="flex flex-col">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    {{ __('Informações de Perfil') }}
                </h2>

                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    {{ __("Atualize as informações do perfil e o endereço de e-mail da sua conta.") }}
                </p>
            </div>

            <div class="flex flex-col items-center">
                @if ($user->imagem)
                    <div class="mt-4">
                        <img src="{{ $user->imagem ? Storage::url($user->imagem) : 'placeholder.jpg' }}"
                             class="user-avatar w-24 h-24 rounded-full object-cover">
                    </div>
                @else
                    <div class="mt-4">
                        <img src="https://via.placeholder.com/150" alt="Default Profile Image" class="w-24 h-24 rounded-full shadow-lg">
                    </div>
                @endif
            </div>


        </div>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Nome')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="phone" :value="__('Telefone')" />
            <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $user->phone)" required autocomplete="tel" />
            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
        </div>

        <div>
            <x-input-label for="imagem" :value="__('Upload Imagem')" />
            <input id="imagem" name="imagem" type="file" class="mt-1 block w-full" accept="image/*" />
            <x-input-error class="mt-2" :messages="$errors->get('imagem')" />
        </div>



        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Salvar') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
