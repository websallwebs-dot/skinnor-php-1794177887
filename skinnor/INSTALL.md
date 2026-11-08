# Skinnor - Guía de Instalación y Despliegue

## 📋 Requisitos del Sistema

- PHP 8.4 o superior
- Composer 2.x
- Node.js y NPM (para assets frontend)
- SQLite 3 (base de datos incluida por defecto)
- Git

---

## 🚀 Instalación Rápida

### 1. Clonar el repositorio

```bash
git clone https://github.com/TU_USUARIO/skinnor.git
cd skinnor
```

### 2. Instalar dependencias de PHP

```bash
composer install
```

### 3. Instalar dependencias de Node.js

```bash
npm install
npm run build
```

### 4. Configurar entorno

Copiar el archivo de entorno ejemplo:

```bash
cp .env.example .env
```

O crear uno nuevo:

```bash
cp .env.example .env
```

**Importante:** Ajustar los valores en `.env` según tu entorno:
- `APP_URL=http://tu-dominio.com`
- `DB_CONNECTION=sqlite` (por defecto usa SQLite)

### 5. Generar clave de aplicación

```bash
php artisan key:generate
```

### 6. Ejecutar migraciones de base de datos

```bash
php artisan migrate
```

Esto creará todas las tablas necesarias en la base de datos SQLite incluida.

### 7. Ejecutar seeders (opcional - datos de ejemplo)

```bash
php artisan db:seed
```

### 8. Iniciar el servidor

```bash
php artisan serve
```

O para acceso desde otros dispositivos en la red:

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

Luego accede desde tu navegador a: `http://tu-ip:8000`

---

## 🗄️ Base de Datos

El proyecto utiliza **SQLite** por defecto para facilitar el desarrollo local. La base de datos se encuentra en:

```
database/database.sqlite
```

Para cambiar a MySQL o PostgreSQL, modificar el archivo `.env`:

```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=skinnor
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña
```

Luego ejecutar:

```bash
php artisan migrate
php artisan db:seed
```

---

## 👥 Cuentas de Prueba

Después de ejecutar `php artisan db:seed`, tendrás los siguientes usuarios:

| Rol | Email | Contraseña | Panel |
|-----|-------|------------|-------|
| Superadmin | `admin@skinnor.com` | `password` | `/superadmin` |
| Admin | `admin@skinnor.cl` | `password` | `/admin` |
| Profesional | `prof@skinnor.cl` | `password` | `/profesional` |
| Paciente | `paciente@skinnor.cl` | `password` | `/paciente` |

---

## 📋 Estructura del Proyecto

```
skinnor/
├── app/
│   ├── Filament/           # Paneles de administración (Filament)
│   │   ├── Admin/          # Panel Admin
│   │   ├── Profesional/    # Panel Profesional
│   │   ├── Paciente/       # Panel Paciente
│   │   └── Superadmin/     # Panel Superadmin
│   ├── Models/             # Modelos Eloquent
│   ├── Http/Controllers/   # Controladores
│   └── Providers/          # Service Providers
├── database/
│   ├── migrations/         # Migraciones de base de datos
│   ├── seeders/            # Seeders para datos de prueba
│   └── database.sqlite     # Base de datos SQLite (local)
├── routes/
│   └── web.php             # Rutas de la aplicación
├── resources/
│   └── views/              # Vistas Blade
├── tests/                  # Pruebas automatizadas (Pest)
├── config/                 # Configuración de Laravel
├── public/                 # Archivos públicos (CSS, JS, imágenes)
├── storage/                # Archivos de almacenamiento
└── .env                    # Configuración de entorno
```

---

## 🧪 Ejecutar Pruebas

```bash
php artisan test
```

O con cobertura:

```bash
php artisan test --coverage
```

---

## 🔧 Comandos Útiles

| Comando | Descripción |
|---------|-------------|
| `php artisan serve` | Iniciar servidor de desarrollo |
| `php artisan migrate` | Ejecutar migraciones |
| `php artisan migrate:refresh` | Reiniciar migraciones |
| `php artisan db:seed` | Ejecutar seeders |
| `php artisan test` | Ejecutar pruebas |
| `npm run dev` | Compilar assets en modo desarrollo |
| `npm run build` | Compilar assets para producción |

---

## 📦 Estructura de Paneles

| Panel | URL | Rol | Uso |
|-------|-----|-----|-----|
| Superadmin | `/superadmin` | superadmin | Gestión de clínicas, dominios, sistema |
| Admin | `/admin` | admin | Gestión de clínica, usuarios, pacientes |
| Profesional | `/profesional` | profesional | Gestión de pacientes, seguimientos, laboratorio |
| Paciente | `/paciente` | paciente | Perfil, lesiones, consultas, seguimientos |

---

## 🐛 Solución de Problemas

### Error de permisos en storage/storage

```bash
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/
```

### Error de conexión a base de datos

Verificar que el archivo `database/database.sqlite` existe y tiene permisos de escritura:

```bash
touch database/database.sqlite
chmod 664 database/database.sqlite
```

### Error de dependencias PHP

```bash
composer install --optimize-autoloader
```

### Error de dependencias Node.js

```bash
rm -rf node_modules
npm install
npm run build
```

---

## 📄 Licencia

Este proyecto está bajo la licencia MIT. Consulta el archivo `LICENSE` para más información.

---

## 🤝 Contribución

1. Fork el repositorio
2. Crea una rama para tu característica (`git checkout -b feature/nueva-funcionalidad`)
3. Haz commit de tus cambios (`git commit -am 'Agregar nueva funcionalidad'`)
4. Push a la rama (`git push origin feature/nueva-funcionalidad`)
5. Abre un Pull Request

---

## 📞 Soporte

Para soporte técnico o consultas, abrir un issue en el repositorio de GitHub.