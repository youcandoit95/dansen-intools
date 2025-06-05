<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Kasir App</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="w-full max-w-sm p-6 bg-white rounded-xl shadow-md">
        <h1 class="text-2xl font-bold text-center mb-6">Login Dansen System</h1>

        @if($errors->any())
        <div class="mb-4 text-red-600 text-sm">
            {{ $errors->first() }}
        </div>
        @endif

        <form method="POST" action="{{ url('/login') }}" class="space-y-4" id="loginForm">
            @csrf
            <div>
                <label class="block text-sm font-medium">Username</label>
                <input type="text" name="username" id="username" required autofocus
                    class="w-full mt-1 p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <div>
                <label class="block text-sm font-medium">Password</label>
                <input type="password" name="password" required
                    class="w-full mt-1 p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <div class="flex items-center">
                <input type="checkbox" id="rememberMe" class="mr-2">
                <label for="rememberMe" class="text-sm">Ingat saya</label>
            </div>
            <div>
                <button type="submit"
                    class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">
                    Masuk
                </button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const usernameInput = document.getElementById('username');
            const passwordInput = document.querySelector('input[name="password"]');
            const rememberMe = document.getElementById('rememberMe');
            const savedUsername = localStorage.getItem('rememberedUsername');

            if (savedUsername) {
                usernameInput.value = savedUsername;
                rememberMe.checked = true;
                passwordInput.focus(); // Langsung fokus ke password jika username sudah ada
            } else {
                usernameInput.focus(); // Jika tidak ada username tersimpan, fokus ke username
            }

            document.getElementById('loginForm').addEventListener('submit', function() {
                const remember = rememberMe.checked;
                const username = usernameInput.value;

                if (remember) {
                    localStorage.setItem('rememberedUsername', username);
                } else {
                    localStorage.removeItem('rememberedUsername');
                }
            });
        });
    </script>

</body>

</html>
