<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mot de passe oublié - Pro Contact</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full bg-white rounded-lg shadow-md p-8">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Pro Contact</h1>
                <p class="text-gray-600 mt-2">Réinitialisation du mot de passe</p>
            </div>

            <!-- Success Message -->
            @if (session('status'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-md">
                    {{ session('status') }}
                </div>
            @endif

            <div class="mb-6 text-sm text-gray-600">
                <p>Vous avez oublié votre mot de passe ? Pas de problème. Indiquez-nous simplement votre adresse email et nous vous enverrons un lien de réinitialisation.</p>
            </div>

            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                
                <div class="mb-6">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Adresse email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition duration-200 mb-4">
                    Envoyer le lien de réinitialisation
                </button>
            </form>

            <div class="text-center">
                <p class="text-gray-600">
                    Vous vous souvenez de votre mot de passe ? 
                    <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800">Se connecter</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
