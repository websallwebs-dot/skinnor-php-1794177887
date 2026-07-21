<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Superadmin Login — Skinnor</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
        <!-- Logo -->
        <div class="text-center mb-10">
            <div class="inline-flex items-center justify-center bg-primary-600 p-3 rounded-xl shadow-lg shadow-primary-200 mb-4">
                <i class="fas fa-crown text-white text-3xl"></i>
            </div>
            <h1 class="text-3xl font-bold tracking-tight text-gray-900">Skinnor</h1>
            <p class="text-sm font-medium text-gray-500 mt-2 italic">Panel de Control Central</p>
        </div>

        <!-- Login Card -->
        <div class="rounded-2xl bg-white shadow-xl ring-1 ring-gray-950/5 p-8 sm:p-10">
            <h2 class="text-xl font-semibold text-gray-900 mb-8 text-center">Identificación</h2>

            @if($errors->any())
                <div class="rounded-xl border border-red-200 bg-red-50 p-4 mb-8">
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

            <form method="POST" action="{{ route('superadmin.login') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium leading-6 text-gray-950">Correo electrónico</label>
                    <div class="mt-2">
                        <input type="email" name="email" id="email" value="{{ old('email') }}" class="block w-full rounded-lg border-0 py-2.5 text-gray-950 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 bg-white" placeholder="admin@skinnor.com" required autofocus>
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium leading-6 text-gray-950">Contraseña</label>
                    <div class="mt-2">
                        <input type="password" name="password" id="password" class="block w-full rounded-lg border-0 py-2.5 text-gray-950 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 bg-white" placeholder="••••••••" required>
                    </div>
                </div>

                <button type="submit" class="flex w-full justify-center rounded-lg bg-primary-600 px-3 py-3 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-primary-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600 transition duration-150">
                    Entrar al panel central
                </button>
            </form>

            <div class="mt-10 pt-8 border-t border-gray-100">
                <div class="rounded-xl bg-primary-50/50 p-4 border border-primary-100">
                    <h3 class="text-xs font-bold text-primary-900 uppercase tracking-wider mb-2">Credenciales Administrador</h3>
                    <div class="text-xs text-primary-700 space-y-1 font-mono">
                        <p><strong>USR:</strong> admin@skinnor.com</p>
                        <p><strong>PWD:</strong> password</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Back link -->
        <p class="text-center mt-8">
            <a href="/" class="text-sm font-semibold text-primary-600 hover:text-primary-500 transition flex items-center justify-center gap-x-2">
                <i class="fas fa-arrow-left text-xs"></i>
                <span>Volver a la página principal</span>
            </a>
        </p>
    </div>
</body>
</html>
