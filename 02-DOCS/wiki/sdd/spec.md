# Spec — Accesos, Landing y Logins (Skinnor-PHP)

## 1. Objetivo

Landing page informativa (modo Persuade) con solo dos botones de acceso: "Iniciar sesión" y "Crear cuenta". Admin y Superadmin acceden por URL directa, sin enlaces visibles desde la landing pública. El formulario de login es simple: solo email y contraseña, sin selector de roles.

## 2. Alcance (In scope)

### 2.1 Landing page pública (`/`)
- Modo Persuade: página informativa y de conversión.
- Cuando el usuario **no está autenticado**, mostrar **solo** dos botones:
  - "Iniciar sesión" → `/login`
  - "Crear cuenta" → `/register`
- No mostrar ningún enlace o referencia a Admin, Profesional, Paciente ni Superadmin.
- No revelar información sensible (no mostrar directorio de clínicas, dominios, IDs, ni datos internos).
- Cuando el usuario **está autenticado**, mostrar perfil, nombre de usuario y botón "Salir".

### 2.2 Login de Tenant (`/login`)
- Formulario simple: solo correo electrónico y contraseña.
- **Sin selector de roles** (paciente/profesional/admin).
- Tras autenticación exitosa, redirigir según el rol del usuario autenticado:
  - `paciente` → `/paciente`
  - `profesional` → `/profesional`
  - `admin` → `/admin`

### 2.3 Login de Superadmin (`/superadmin/login`)
- Sin cambios funcionales. Solo accesible por URL directa.
- Tras autenticación, redirigir a `/superadmin`.

### 2.4 Registro (`/register`)
- Sin cambios. Solo pacientes.

## 3. Fuera de alcance (Out of scope)

- Cambios en los Panel Providers (Filament v5).
- Cambios en migraciones o base de datos.
- Cambios en modelos de usuario.
- Cambios en el panel Superadmin existente.
- Selector de roles en el formulario de login.
- Internacionalización (i18n).

## 4. Criterios de aceptación

1. Al visitar `/` sin autenticar, el usuario ve **solo** botones "Iniciar sesión" y "Crear cuenta".
2. No hay enlaces a `/admin`, `/profesional`, `/paciente` ni `/superadmin/login` en la landing pública.
3. No se revela información sensible (clínicas, dominios, IDs, directorio) en la landing pública.
4. El formulario de login tiene solo email y contraseña (sin selector de roles).
5. Haciendo clic en "Iniciar sesión" se va a `/login` y al loguearse se redirige según el rol del usuario.
6. Haciendo clic en "Crear cuenta" se va a `/register`.
7. El login de Superadmin (`/superadmin/login`) funciona por URL directa y solo acepta rol `superadmin`.
8. `http://127.0.0.1:8000/login` retorna 200 (no error de tenancy).
