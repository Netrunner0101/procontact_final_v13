<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inscription - Pro Contact</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full bg-white rounded-lg shadow-md p-8">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Pro Contact</h1>
                <p class="text-gray-600 mt-2">Créez votre compte</p>
            </div>

            <!-- Success Message -->
            @if (session('status'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-md">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf
                
                <div class="mb-4">
                    <label for="nom" class="block text-sm font-medium text-gray-700 mb-2">Nom</label>
                    <input type="text" id="nom" name="nom" value="{{ old('nom') }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('nom') border-red-500 @enderror">
                    @error('nom')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="prenom" class="block text-sm font-medium text-gray-700 mb-2">Prénom</label>
                    <input type="text" id="prenom" name="prenom" value="{{ old('prenom') }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('prenom') border-red-500 @enderror">
                    @error('prenom')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="telephone" class="block text-sm font-medium text-gray-700 mb-2">Téléphone (optionnel)</label>
                    <input type="tel" id="telephone" name="telephone" value="{{ old('telephone') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('telephone') border-red-500 @enderror">
                    @error('telephone')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Mot de passe</label>
                    <input type="password" id="password" name="password" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('password') border-red-500 @enderror">
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirmer le mot de passe</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200">
                    Créer mon compte
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    Déjà un compte ?
                    <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-500 font-medium">Se connecter</a>
                </p>
            </div>

            <!-- Social Registration -->
            <div class="mt-6">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500">Ou s'inscrire avec</span>
                    </div>
                </div>

                <div class="mt-6 space-y-3">
                    <!-- Google Registration -->
                    <a href="{{ route('auth.google') }}" class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 transition duration-200">
                        <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                        </svg>
                        S'inscrire avec Google
                    </a>

                    <!-- Apple Registration -->
                    <a href="{{ route('auth.apple') }}" class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm bg-black text-sm font-medium text-white hover:bg-gray-800 transition duration-200">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12.017 0C8.396 0 8.025.044 6.79.207 5.56.37 4.703.644 3.967 1.006c-.757.372-1.4.861-2.04 1.5-.64.64-1.128 1.283-1.5 2.04C.044 5.282-.23 6.14.207 7.37.37 8.604.044 8.975.044 12.596s.326 3.992.489 5.227c.163 1.23.437 2.088.799 2.825.372.757.861 1.4 1.5 2.04.64.64 1.283 1.128 2.04 1.5.737.362 1.595.636 2.825.799 1.235.163 1.606.207 5.227.207s3.992-.044 5.227-.207c1.23-.163 2.088-.437 2.825-.799.757-.372 1.4-.861 2.04-1.5.64-.64 1.128-1.283 1.5-2.04.362-.737.636-1.595.799-2.825.163-1.235.207-1.606.207-5.227s-.044-3.992-.207-5.227c-.163-1.23-.437-2.088-.799-2.825-.372-.757-.861-1.4-1.5-2.04-.64-.64-1.283-1.128-2.04-1.5-.737-.362-1.595-.636-2.825-.799C15.009.044 14.638 0 11.017 0h1z"/>
                        </svg>
                        S'inscrire avec Apple
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
