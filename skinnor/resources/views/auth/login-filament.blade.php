<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Skinnor — Iniciar sesión' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 font-sans antialiased text-gray-950 h-full flex items-center justify-center p-4">
    <div class="max-w-md w-full">
        <!-- Login Card -->
        <div class="rounded-2xl bg-white shadow-xl ring-1 ring-gray-950/5 p-6 sm:p-8">
            <h2 class="text-xl font-bold tracking-tight text-gray-900 text-center mb-1">
                Skinnor @if($panelName)— {{ $panelName }}@endif
            </h2>
            <h3 class="text-lg font-semibold text-gray-900 text-center mb-6">
                Entre a su cuenta
            </h3>

            @if ($errors->any())
                <div class="rounded-xl border border-red-200 bg-red-50 p-4 mb-6">
                    <div class="flex items-start gap-x-3">
                        <i class="fas fa-exclamation-circle text-red-500 mt-0.5"></i>
                        <div class="text-sm font-medium text-red-800">
                            @foreach($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ $action }}" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                        Correo electrónico <span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                        class="w-full rounded-lg border border-gray-300 py-2.5 px-4 text-gray-950 placeholder-gray-400 focus:ring-2 focus:ring-indigo-600 focus:border-indigo-500 sm:text-sm bg-white"
                        placeholder="tu@email.com" required autofocus>
                </div>

                <div class="relative">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                        Contraseña <span class="text-red-500">*</span>
                    </label>
                    <div class="flex items-center">
                        <input type="password" name="password" id="password"
                            class="w-full pr-10 rounded-lg border border-gray-300 py-2.5 px-4 text-gray-950 placeholder-gray-400 focus:ring-2 focus:ring-indigo-600 focus:border-indigo-500 sm:text-sm bg-white"
                            placeholder="••••••••" required>
                        <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="flex items-center">
                    <input id="remember" name="remember" type="checkbox"
                        class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-600">
                    <label for="remember" class="ml-2 text-sm text-gray-700">Recordarme</label>
                </div>

                <button type="submit"
                    class="w-full flex justify-center rounded-lg bg-indigo-600 px-3 py-3 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition duration-150">
                    Entrar
                </button>
            </form>

            @if ($registerUrl):
                <p class="mt-6 text-center text-sm text-gray-500">
                    ¿No tienes cuenta?
                    <a href="{{ $registerUrl }}" class="font-semibold text-indigo-600 hover:text-indigo-500">Crear cuenta</a>
                </p>
            @endif
        </div>
    </div>
</body>
</html>
