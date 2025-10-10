<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-r from-gray-900 via-black to-gray-900 relative overflow-hidden">
        <!-- Effet flou noir -->
        <div class="absolute inset-0 bg-black bg-opacity-60 backdrop-blur-md"></div>

        <!-- Formulaire centré -->
        <div class="relative max-w-md w-full bg-white/90 p-8 rounded-xl shadow-2xl backdrop-blur-sm">
            <!-- Titre -->
            <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Réinitialisation du mot de passe</h2>

            <!-- Texte explicatif -->
            <div class="mb-4 text-sm text-gray-700">
                Mot de passe oublié ? Pas de problème. Indiquez simplement votre adresse e-mail et nous vous enverrons un lien pour réinitialiser votre mot de passe afin que vous puissiez en choisir un nouveau.
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4 text-green-500" :status="session('status')" />

            <!-- Formulaire -->
            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <!-- Email -->
                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                        :value="old('email')" required autofocus />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <x-primary-button>
                        {{ __('Envoyer le lien de réinitialisation') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
