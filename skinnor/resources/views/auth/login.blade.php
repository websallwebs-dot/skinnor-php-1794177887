<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skinnor — Iniciar sesión</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'ui-sans-serif', 'system-ui'],
                    },
                    colors: {
                        primary: {
                            50: '#f5f3ff',
                            100: '#ede9fe',
                            200: '#ddd6fe',
                            300: '#c4b5fd',
                            400: '#a78bfa',
                            500: '#8b5cf6',
                            600: '#7c3aed',
                            700: '#6d28d9',
                            800: '#5b21b6',
                            900: '#4c1d95',
                            950: '#2e1065',
                        },
                    },
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 font-sans antialiased text-gray-950 h-full flex items-center justify-center p-6">
    <div class="max-w-md w-full">
        <div class="text-center mb-10">
            <h1 class="text-3xl font-bold tracking-tight text-gray-900">Skinnor</h1>
            <p class="text-sm font-medium text-gray-500 mt-2">Acceso a Clínica</p>
        </div>

        <div class="rounded-2xl bg-white shadow-xl ring-1 ring-gray-950/5 p-8 sm:p-10">
            @if ($errors->any())
                <div class="rounded-xl border border-red-200 bg-red-50 p-4 mb-8 text-sm font-medium text-red-800">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="/login" class="space-y-6">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium leading-6 text-gray-950">Correo electrónico</label>
                    <div class="mt-2">
                        <input type="email" name="email" id="email" value="{{ old('email') }}" class="block w-full rounded-lg border-0 py-2.5 text-gray-950 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 bg-white" placeholder="tu@email.com" required autofocus>
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium leading-6 text-gray-950">Contraseña</label>
                    <div class="mt-2">
                        <input type="password" name="password" id="password" class="block w-full rounded-lg border-0 py-2.5 text-gray-950 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 bg-white" placeholder="••••••••" required>
                    </div>
                </div>

                <button type="submit" class="flex w-full justify-center rounded-lg bg-primary-600 px-3 py-3 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-primary-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600 transition duration-150">
                    Iniciar sesión
                </button>
            </form>

            <p class="mt-8 text-center text-sm text-gray-500">
                ¿No tienes cuenta?
                <a href="/register" class="font-semibold text-primary-600 hover:text-primary-500">Regístrate como paciente</a>
            </p>
        </div>
    </div>
</body>
</html>
