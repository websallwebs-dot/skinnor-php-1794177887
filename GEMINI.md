# Guía Maestra de Filament PHP v3
*Basada en las lecciones de Code With Dary, Code With Tony, Larafant y El Rincón de Isma.*

Este documento resume las mejores prácticas, configuraciones y flujos de trabajo aprendidos en los tutoriales de Filament v3.

*Consulte la [Guía de Estilo UI/UX](standards/FILAMENT_UI_STYLE_GUIDE.md) para mantener la coherencia visual del proyecto.*


## 🛠️ 1. Instalación y Configuración Inicial

### Requisitos
*   PHP 8.1+
*   Laravel 10+
*   Livewire v3 (incluido en Filament v3)

### Proceso de Instalación
1.  **Instalar el core:**
    ```bash
    composer require filament/filament:"^3.0" -W
    ```
2.  **Instalar el Panel Builder:**
    ```bash
    php artisan filament:install --panels
    ```
    *Esto generará `app/Providers/Filament/AdminPanelProvider.php`.*
3.  **Crear el primer usuario:**
    ```bash
    php artisan make:filament-user
    ```

## 🏗️ 2. El Concepto de Paneles (Panels)
En la v3, puedes tener múltiples áreas administrativas independientes.
*   **Crear un nuevo panel (ej. para clientes):**
    ```bash
    php artisan make:filament-panel app
    ```
*   **Configuración:** Cada panel se configura en su propio `PanelProvider`. Aquí puedes cambiar el tema, colores, logo y navegación.

## 📦 3. Recursos (Resources)
Los recursos gestionan el CRUD de tus modelos.

### Generación
```bash
php artisan make:filament-resource Post --generate --view
```
*   `--generate`: Lee la base de datos para crear el formulario y la tabla automáticamente.
*   `--view`: Crea una página de visualización (Infolist).

### Estructura de un Resource
*   `form(Form $form)`: Define los campos de creación/edición.
*   `table(Table $table)`: Define las columnas, filtros y acciones de la lista.
*   `getRelations()`: Define gestores de relaciones (Relation Managers).
*   `getPages()`: Rutas para List, Create, Edit y View.

## 📝 4. Formularios Avanzados

### Componentes Comunes
*   `TextInput::make('title')->required()->maxLength(255)`
*   `RichEditor::make('content')->columnSpanFull()`
*   `DateTimePicker::make('published_at')`
*   `Toggle::make('is_published')->label('¿Publicado?')`
*   `FileUpload::make('image')->image()->directory('blog')`

### Gestión de Relaciones (Select)
Una de las mejoras más potentes de la v3 es el método `relationship`, que automatiza la carga y guardado de claves foráneas.

*   **Implementación Básica:**
    ```php
    Select::make('author_id')
        ->relationship(name: 'author', titleAttribute: 'name')
    ```
*   **Optimización para Grandes Volúmenes:**
    *   `searchable()`: Activa la búsqueda vía AJAX (ideal para miles de registros).
    *   `preload()`: Carga los primeros 50 resultados inmediatamente para mejorar la UX.
*   **Creación Dinámica (`createOptionForm`):**
    Permite crear un registro relacionado desde un modal sin salir del formulario actual:
    ```php
    Select::make('author_id')
        ->relationship('author', 'name')
        ->createOptionForm([
            TextInput::make('name')->required(),
            TextInput::make('email')->required()->email(),
        ])
    ```
*   **Filtros Personalizados en la Relación:**
    Puedes filtrar u ordenar las opciones disponibles usando una clausura:
    ```php
    Select::make('author_id')
        ->relationship(
            name: 'author',
            titleAttribute: 'name',
            modifyQueryUsing: fn (Builder $query) => $query->where('is_active', true)->orderBy('name'),
        )
    ```
*   **Relaciones Many-to-Many:**
    Simplemente añade `multiple()` y Filament gestionará la tabla pivote automáticamente:
    ```php
    Select::make('tags')
        ->relationship('tags', 'name')
        ->multiple()
    ```
*   **Etiquetas Complejas (Múltiples Columnas):**
    Para mostrar algo como "Nombre + Apellido", define un atributo virtual (Accessor) en tu Modelo y úsalo como `titleAttribute`, o usa:
    ```php
    ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->first_name} {$record->last_name}")
    ```

## 📊 5. Tablas y Listados

### Columnas
*   `TextColumn::make('name')->searchable()->sortable()`
*   `ImageColumn::make('image')->circular()`
*   `IconColumn::make('is_published')->boolean()`
*   `ToggleColumn::make('status')` (Permite editar directamente desde la lista).

### Filtros
```php
->filters([
    SelectFilter::make('status')
        ->options([
            'draft' => 'Borrador',
            'published' => 'Publicado',
        ]),
    Filter::make('is_featured')
        ->query(fn (Builder $query) => $query->where('is_featured', true))
])
```

## 🎨 6. Navegación y UX

### Personalización del Resource
```php
protected static ?string $navigationIcon = 'heroicon-o-document-text';
protected static ?string $navigationGroup = 'Contenido';
protected static ?string $navigationLabel = 'Artículos';
protected static ?int $navigationSort = 1;
```

### Modo SPA
Filament v3 soporta navegación instantánea. Actívalo en tu `PanelProvider`:
```php
->spa()
```

## 🔄 7. Diferencias Clave v2 vs v3
| Característica | v2 | v3 |
| :--- | :--- | :--- |
| **Arquitectura** | Un solo `/admin` | Múltiples Paneles |
| **Icons** | Heroicons v1 | Heroicons v2 |
| **Infolists** | Limitado | Constructor completo de vistas |
| **Acciones** | Locales | Acciones Globales |
| **Personalización** | Difícil (CSS) | Temas Tailwind nativos |

## 💡 Tips de Oro
1.  **Validaciones:** Usa `->unique(ignoreRecord: true)` para evitar errores al editar.
2.  **Rendimiento:** Usa `->preload()` en los Selects de relación si no hay miles de registros.
3.  **Layouts:** Usa `Section::make()` y `Grid::make()` para organizar formularios complejos.
4.  **Bulk Actions:** Personaliza `DeleteBulkAction` o crea acciones masivas propias para procesos pesados.

---
*Este GEMINI.md sirve como la base de conocimiento para el desarrollo con Filament en este proyecto.*
