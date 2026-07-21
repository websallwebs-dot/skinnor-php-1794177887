<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skinnor — Registro</title>
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
<body class="bg-gray-50 font-sans antialiased text-gray-950 h-full flex items-center justify-center py-12 px-6">
    <div class="max-w-2xl w-full">
        <div class="text-center mb-10">
            <h1 class="text-3xl font-bold tracking-tight text-gray-900">Skinnor</h1>
            <p class="text-sm font-medium text-gray-500 mt-2">Crear cuenta de paciente</p>
        </div>

        <div class="rounded-2xl bg-white shadow-xl ring-1 ring-gray-950/5 p-8 sm:p-10">
            @if ($errors->any())
                <div class="rounded-xl border border-red-200 bg-red-50 p-4 mb-8 text-sm font-medium text-red-800">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="/register">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-6 mb-8">
                    <div class="sm:col-span-2">
                        <label for="name" class="block text-sm font-medium leading-6 text-gray-950">Nombre completo</label>
                        <div class="mt-2">
                            <input type="text" name="name" id="name" value="{{ old('name') }}" class="block w-full rounded-lg border-0 py-2.5 text-gray-950 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 bg-white" required>
                        </div>
                    </div>

                    <div>
                        <label for="rut" class="block text-sm font-medium leading-6 text-gray-950">RUT</label>
                        <div class="mt-2">
                            <input type="text" name="rut" id="rut" value="{{ old('rut') }}" placeholder="12.345.678-9" class="block w-full rounded-lg border-0 py-2.5 text-gray-950 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 bg-white" required>
                        </div>
                    </div>

                    <div>
                        <label for="telefono" class="block text-sm font-medium leading-6 text-gray-950">Teléfono</label>
                        <div class="mt-2">
                            <input type="text" name="telefono" id="telefono" value="{{ old('telefono') }}" placeholder="+56 9 1234 5678" class="block w-full rounded-lg border-0 py-2.5 text-gray-950 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 bg-white" required>
                        </div>
                    </div>

                    <div>
                        <label for="fecha_nacimiento" class="block text-sm font-medium leading-6 text-gray-950">Fecha de nacimiento</label>
                        <div class="mt-2">
                            <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}" class="block w-full rounded-lg border-0 py-2.5 text-gray-950 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 bg-white" required>
                        </div>
                    </div>

                    <div>
                        <label for="genero" class="block text-sm font-medium leading-6 text-gray-950">Género</label>
                        <div class="mt-2">
                            <select name="genero" id="genero" class="block w-full rounded-lg border-0 py-2.5 text-gray-950 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 bg-white" required>
                                <option value="">Seleccionar...</option>
                                <option value="masculino" @selected(old('genero') == 'masculino')>Masculino</option>
                                <option value="femenino" @selected(old('genero') == 'femenino')>Femenino</option>
                                <option value="otro" @selected(old('genero') == 'otro')>Otro</option>
                            </select>
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="email" class="block text-sm font-medium leading-6 text-gray-950">Correo electrónico</label>
                        <div class="mt-2">
                            <input type="email" name="email" id="email" value="{{ old('email') }}" class="block w-full rounded-lg border-0 py-2.5 text-gray-950 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 bg-white" required>
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium leading-6 text-gray-950">Contraseña</label>
                        <div class="mt-2">
                            <input type="password" name="password" id="password" class="block w-full rounded-lg border-0 py-2.5 text-gray-950 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 bg-white" required>
                        </div>
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium leading-6 text-gray-950">Confirmar contraseña</label>
                        <div class="mt-2">
                            <input type="password" name="password_confirmation" id="password_confirmation" class="block w-full rounded-lg border-0 py-2.5 text-gray-950 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 bg-white" required>
                        </div>
                    </div>
                </div>

                <button type="submit" class="flex w-full justify-center rounded-lg bg-primary-600 px-3 py-3 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-primary-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600 transition duration-150">
                    Crear cuenta de paciente
                </button>
            </form>

            <p class="mt-8 text-center text-sm text-gray-500">
                ¿Ya tienes cuenta?
                <a href="/login" class="font-semibold text-primary-600 hover:text-primary-500">Iniciar sesión</a>
            </p>
        </div>
    </div>
</body>
</html>
