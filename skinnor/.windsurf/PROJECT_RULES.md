# Reglas del Proyecto Skinnor

**IMPORTANTE:** Lee este documento completo antes de escribir una sola línea de código. Síguelo estrictamente.

---

## CONTEXTO DEL PROYECTO

**Nombre:** Skinnor — Plataforma SaaS multi-tenant para dermatología digital

**Stack:**
- PHP 8.4.21
- Laravel 13.11.2
- Filament v5.6.5 (4 paneles)
- Livewire v4.3.0
- MySQL (XAMPP/Herd)
- Stancl/Tenancy v3 (database-based tenancy)
- Spatie Media Library + filament/spatie-laravel-media-library-plugin
- Storage: Local (disk: public/local)

**DB local:** localhost:3306 (user: root, password: "")
**DB producción:** (Por definir - MySQL)

---

## PANELES FILAMENT

| Panel | URL | Carpeta | Rol |
|-------|-----|---------|-----|
| Superadmin | /superadmin/login | Filament/Superadmin/superadmin | superadmin |
| Admin | /admin/login | Filament/Admin/admin | admin |
| Profesional | /profesional | Filament/Profesional/profesional | profesional |
| Paciente | /paciente | Filament/Paciente/paciente | paciente |

---

## REGLAS CRÍTICAS — NUNCA VIOLAR

### Filament v5

**✅ CORRECTO:**
```php
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

public static function form(Schema $form): Schema { ... }
```

**❌ NUNCA:**
```php
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\EditAction;

public static function form(Form $form): Form { ... }
```

### Navigation labels — SIEMPRE dinámicos

**✅ CORRECTO:**
```php
public static function getNavigationLabel(): string { return __('nav.pacientes'); }
public static function getModelLabel(): string { return __('nav.pacientes'); }
public static function getPluralModelLabel(): string { return __('nav.pacientes'); }
```

**❌ NUNCA:**
```php
protected static ?string $navigationLabel = 'Pacientes';
```

### Traducciones — SIEMPRE usar __()

**✅:**
```php
->label(__('fields.nombre'))
->placeholder(__('fields.placeholder_consulta'))
```

**❌:**
```php
->label('Nombre')
->placeholder('Escribe tu nombre...')
```

### Storage — SIEMPRE Spatie Media Library

**✅ Para fotos de perfil (single):**
```php
SpatieMediaLibraryFileUpload::make('foto')
    ->collection('foto')
    ->image()
    ->imageEditor()
    ->columnSpanFull()
```

**✅ Para fotos clínicas (múltiples):**
```php
SpatieMediaLibraryFileUpload::make('fotos_clinicas')
    ->collection('fotos_clinicas')
    ->image()
    ->multiple()
    ->maxSize(10240)
```

**❌ NUNCA:**
```php
Forms\Components\FileUpload::make('foto')
    ->disk('public')
    ->directory('fotos')
```

### MySQL — Sintaxis correcta

**✅ CORRECTO para migraciones:**
```php
$table->enum('role', ['admin', 'profesional', 'paciente']);
```

### Migraciones — DOS carpetas separadas

- `database/migrations/` → migraciones CENTRALES (tenants, users superadmin/admin)
- `database/migrations/tenant/` → migraciones TENANT (profesionales, pacientes, lesiones, etc.)

**NUNCA mezclarlas. NUNCA mover archivos de tenant/ a la carpeta principal.**

### Tenancy — Database-based

- Cada clínica tiene su propia base de datos MySQL: `tenant_CLINICAID`
- Al crear una Clinica, Stancl crea automáticamente la base de datos y ejecuta las migraciones de `database/migrations/tenant/`
- **NO usar InitializeTenancyByDomain sin configurar dominios**

### Modelos con HasMedia

**✅ Todos los modelos con fotos implementan HasMedia:**
```php
class Profesional extends Model implements HasMedia
{
    use InteractsWithMedia;
    
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('foto')->singleFile();
    }
}
```

---

## ERRORES CONOCIDOS Y SUS SOLUCIONES

### Error: Class "Filament\Forms\Components\Section" not found

**Causa:** Namespace incorrecto de v3

**Solución:** `use Filament\Schemas\Components\Section;`

### Error: Class "Filament\Tables\Actions\EditAction" not found

**Causa:** Namespace incorrecto de v3

**Solución:** `use Filament\Actions\EditAction;`

### Error: Could not check compatibility between form(Form $form) and form(Schema $schema)

**Causa:** Resource escrito con sintaxis Filament v3

**Solución:** Cambiar firma a `public static function form(Schema $form): Schema`

### Error: Duplicate column en migraciones

**Causa:** Migración de tenant movida a carpeta central, o viceversa

**Solución:** Verificar que migraciones de negocio estén SOLO en `database/migrations/tenant/`

### Error: relation "pacientes" does not exist

**Causa:** Tablas de tenant no creadas — las migraciones de tenant no corrieron

**Solución:** `php artisan tenants:migrate` o verificar que Stancl ejecuta las migraciones al crear el tenant

---

## BUENAS PRÁCTICAS OBLIGATORIAS

1. **Siempre limpiar caché después de cambios de configuración:**
   ```bash
   php artisan optimize:clear
   ```

2. **Siempre verificar migraciones antes de ejecutar:**
   ```bash
   php artisan migrate --pretend
   ```

3. **Los Resources de Filament siempre tienen slug explícito si el nombre de clase puede generar URL rara:**
   ```php
   protected static ?string $slug = 'profesionales';
   ```

4. **Todas las tablas de la DB central no deben tener datos clínicos** — solo tenants, domains, users (superadmin/admin)

5. **Los archivos de lang deben estar en los 4 idiomas:** es, en, pt, fr

6. **Nunca hardcodear labels en Resources:**
   ```php
   // ❌
   ->label('Profesional asignado')
   // ✅
   ->label(__('fields.profesional_asignado'))
   ```

7. **Bootstrap/app.php NO debe tener lógica de aplicación** — solo configuración del framework

8. **Vendor no se modifica** — si necesitas modificar vendor, busca otro camino

---

## ARQUITECTURA DE DATOS

### DB central (skinnor):

- `tenants` — clínicas (id, nombre, rut, logo, email, telefono, direccion, pais, ciudad, plan, activa)
- `domains` — dominios de tenants
- `users` — solo superadmin y admin central
- `sessions`, `cache`, `jobs`, `migrations`

### DB tenant (ej. tenant_CLINICAID):

- `users` — admin, profesional, paciente de esa clínica
- `profesionales` — (user_id, foto, bio, telefono, especialidad, numero_licencia, rut, universidad, anos_experiencia)
- `pacientes` — (user_id, profesional_id, foto, rut, fecha_nacimiento, genero, telefono, nombre_contacto_emergencia, telefono_emergencia, historial_medico, alergias, medicamentos)
- `lesiones` — (paciente_id, nombre, parte_del_cuerpo, descripcion, fecha_inicio, estado)
- `seguimientos` — (lesion_id, profesional_id, fecha, hora, nota, subido_por)
- `consultas` — (paciente_id, profesional_id, mensaje, respuesta, leida, respondida_at)
- `imagenes_seguimiento` — (seguimiento_id, ruta, nombre_original, tipo, tamanio)
- `media` — tabla de Spatie Media Library

### Storage (Local):

- `livewire-tmp/` — uploads temporales de Livewire
- `profesionales/fotos/` — fotos de perfil de profesionales
- `pacientes/fotos/` — fotos de perfil de pacientes
- `seguimientos/` — fotos clínicas de seguimientos

---

## CREDENCIALES DE DESARROLLO

- `super@skinnor.cl` / `password` → `/superadmin/login`
- `admin@skinnor.cl` / `password` → `/admin/login`
- `prof@skinnor.cl` / `password` → `/profesional`
- `paciente@skinnor.cl` / `password` → `/paciente`

---

## COMANDOS ÚTILES

```bash
php artisan serve --port=8080     # Servidor Laravel
php artisan optimize:clear        # Limpiar caché
php artisan migrate --force       # Correr migraciones centrales
php artisan tenants:migrate       # Migrar todos los tenants
php artisan migrate:status        # Ver estado migraciones
php artisan tinker                # Consola interactiva
```

---

## PARCHES APLICADOS (Windows - tmpfile() issues)

Los siguientes archivos vendor han sido parcheados para evitar problemas con `tmpfile()` en Windows:

1. `vendor/livewire/livewire/src/Features/SupportFileUploads/TemporaryUploadedFile.php`
   - Usa `storage_path('app/tmp')` en lugar de `tmpfile()`

2. `vendor/livewire/livewire/src/Features/SupportFileUploads/WithFileUploads.php`
   - Fuerza flujo local para evitar `UploadedFile::fake()`

3. `vendor/spatie/laravel-medialibrary/src/InteractsWithMedia.php`
   - Usa `storage_path('app/tmp')` en lugar de `tempnam()`

**IMPORTANTE:** Estos parches son específicos para el entorno de desarrollo en Windows. En producción (Linux) no deberían ser necesarios.
