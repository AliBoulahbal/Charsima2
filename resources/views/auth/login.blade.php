<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.3/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <form method="POST" action="{{ url('/login') }}" class="bg-white p-6 rounded shadow-md w-96">
        @csrf
        <h2 class="text-2xl font-bold mb-4 text-center">Connexion Admin</h2>
        @error('email')
            <div class="text-red-500 mb-2">{{ $message }}</div>
        @enderror
        <input type="email" name="email" placeholder="Email" required class="w-full mb-2 p-2 border rounded">
        <input type="password" name="password" placeholder="Mot de passe" required class="w-full mb-2 p-2 border rounded">
        <button type="submit" class="w-full bg-blue-600 text-white p-2 rounded">Se connecter</button>
    </form>
</body>
</html>
