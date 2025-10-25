<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center relative">
        <!-- Overlay noir flouté identique aux autres pages -->
        <div class="absolute inset-0 bg-black bg-opacity-40 backdrop-blur-md"></div>

        <!-- Conteneur central -->
        <div class="relative max-w-md w-full p-8 rounded-xl shadow-2xl bg-black bg-opacity-20 backdrop-blur-sm">
            <h2 class="text-2xl font-bold text-center text-white mb-6">Vérification de l'email</h2>

            <div class="mb-4 text-sm text-white">
                {{ __('Merci pour votre inscription ! Avant de commencer, veuillez vérifier votre adresse e-mail en cliquant sur le lien que nous venons de vous envoyer. Si vous n\'avez pas reçu l\'email, nous serons ravis de vous en envoyer un autre.') }}
            </div>

            @if (session('status') == 'verification-link-sent')
                <div class="mb-4 font-medium text-sm text-green-500">
                    {{ __('Un nouveau lien de vérification a été envoyé à l\'adresse e-mail que vous avez fournie lors de l\'inscription.') }}
                </div>
            @endif

            <div class="mt-4 flex items-center justify-between">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <x-primary-button>
                        {{ __('Renvoyer l\'email de vérification') }}
                    </x-primary-button>
                </form>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="underline text-sm text-white hover:text-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        {{ __('Se déconnecter') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
