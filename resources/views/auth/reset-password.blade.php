<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nouveau mot de passe - Pro Contact</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full bg-white rounded-lg shadow-md p-8">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Pro Contact</h1>
                <p class="text-gray-600 mt-2">Créer un nouveau mot de passe</p>
            </div>

            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                
                <input type="hidden" name="token" value="{{ $token }}">
                
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Adresse email</label>
                    <input type="email" id="email" name="email" value="{{ request()->get('email') ?? old('email') }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Nouveau mot de passe</label>
                    <input type="password" id="password" name="password" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('password') border-red-500 @enderror">
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-sm text-gray-500 mt-1">Minimum 8 caractères</p>
                </div>

                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirmer le mot de passe</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition duration-200">
                    Réinitialiser le mot de passe
                </button>
            </form>

            <div class="text-center mt-6">
                <p class="text-gray-600">
                    <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800">Retour à la connexion</a>
                </p>
            </div>
        </div>
    </div>

    <script>
        // Password confirmation validation
        document.getElementById('password_confirmation').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmation = this.value;
            
            if (password !== confirmation && confirmation.length > 0) {
                this.setCustomValidity('Les mots de passe ne correspondent pas');
                this.classList.add('border-red-500');
            } else {
                this.setCustomValidity('');
                this.classList.remove('border-red-500');
            }
        });
    </script>
</body>
</html>
