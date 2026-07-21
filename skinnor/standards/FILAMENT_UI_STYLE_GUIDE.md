# Guía de Estilo UI/UX de Skinnor

Esta guía define los estándares estéticos y de componentes para mantener la coherencia visual en todos los paneles de Filament (Superadmin, Admin, Profesional, Paciente).

## 🎨 1. Temas y Colores
Respetar estrictamente la identidad visual por panel:
*   **Admin:** `Color::Indigo`
*   **Superadmin:** `Color::Amber`
*   **Roles/Estados:**
    *   `superadmin` / `warning`
    *   `admin` / `danger`
    *   `profesional` / `success` (o `info`)
    *   `paciente` / `info` (o `success`)

## 🏗️ 2. Estructura de Formularios (Forms)
*   **Sections:** Usar `Section::make(__('fields.titulo'))` para agrupar campos lógicos.
*   **Columns:** Aplicar `->columns(2)` para formularios estándar. Usar `->columnSpanFull()` para campos de texto largo, áreas de texto o previsualizaciones.
*   **Media:** Usar exclusivamente `SpatieMediaLibraryFileUpload` (ver `PROJECT_RULES.md`).

## 📊 3. Tablas y Listados
*   **Badges:** Siempre usar `->badge()` en estados o categorías para mejorar la legibilidad.
*   **Formato de Fecha:** `->date('d/m/Y')`.
*   **UX de listas:** Usar `->searchable()` y `->sortable()` en columnas clave. Usar `->wrap()` en columnas de texto largo o notas para evitar desbordamientos horizontales.
*   **Miniaturas:** Para imágenes en tablas, usar `ImageColumn::make('...')->circular()` para perfiles o lógica de previsualización (ver `SeguimientoFotos`).

## 📝 4. Localización y Etiquetas
*   **NUNCA hardcodear strings:** Todas las etiquetas, placeholders y mensajes deben usar `__()`.
*   **Navegación Dinámica:** Seguir la regla de `getNavigationLabel()`, `getModelLabel()`, `getPluralModelLabel()` para usar traducciones.

## 💡 Regla de Oro
La interfaz debe sentirse "limpia" y "espaciosa". Evitar el uso excesivo de componentes anidados. Si un formulario es demasiado complejo, divídelo en varias `Section` o utiliza pestañas (`Tabs`).
