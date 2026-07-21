# Documentación del Sistema de Traducción

El proyecto utiliza el sistema nativo de localización de Laravel (`lang/`) integrado con Filament v5, basado en un modelo robusto de claves de traducción para garantizar una cobertura del 100% de la UI.

## Estructura de Idiomas
- `es`: Español
- `en`: Inglés
- `fr`: Francés
- `pt`: Portugués

## Categorías de Traducción (Ubicación en `lang/{idioma}/`)
- `nav.php`: Etiquetas de navegación (sidebar, titles).
- `fields.php`: Labels de campos de formularios y datos de usuario.
- `tables.php`: Títulos de tablas, columnas y placeholders de datos.
- `actions.php`: Textos de botones, acciones y notificaciones.
- `messages.php`: Mensajes del sistema y confirmaciones.

## Implementación Técnica (Claves Globales)
Para asegurar que toda la interfaz sea dinámica, **prohibido el uso de strings estáticos**. Se debe utilizar la función global `__('archivo.clave')`.

### Ejemplos:
- **Navigation Label**: `protected static ?string $navigationLabel = 'nav.mis_pacientes';`
- **Form Fields**: `Forms\Components\TextInput::make('name')->label(__('fields.nombre'))`
- **Table Columns**: `Tables\Columns\TextColumn::make('email')->label(__('fields.email'))`
- **Actions**: `\Filament\Actions\ViewAction::make()->label(__('actions.ver'))`

## Selector de Idiomas
El cambio de idioma es gestionado por el plugin `bezhansalleh/filament-language-switch`, configurado globalmente en `AppServiceProvider.php` para persistir automáticamente en la sesión y base de datos.

