# LABORATORIO_PLAN.md - Implementación Laboratorio Dermatológico

Este documento detalla el plan de implementación para el módulo de **Laboratorio** en el Panel Profesional de Skinnor.

## 1. Arquitectura de Datos y Flujo
- **Modelos involucrados:** 
  - `Paciente`: Filtrado por `profesional_id`.
  - `Lesion`: Relacionado con el paciente.
  - `Seguimiento`: Contiene las fotos clínicas mediante Spatie Media Library (colección `fotos_clinicas`).
- **Relaciones:** Profesional -> Pacientes -> Lesiones -> Seguimientos.
- **Storage:** Local Public (disk `public`). Las URLs se generarán mediante `getFirstMediaUrl('fotos_clinicas')`.

## 2. Componentes a Crear

### A. Filament Page & Vista
- `App\Filament\Profesional\Pages\Laboratorio.php`: Página principal de Livewire.
  - Gestión de estado: `$pacienteSeleccionado`, `$herramientaActiva`, `$seguimientoActivo`.
  - Métodos: `activarHerramienta`, `seleccionarSeguimiento`.
- `resources/views/filament/profesional/pages/laboratorio.blade.php`: Layout principal.
- Partiales para herramientas:
  - `laboratorio-metricas.blade.php`
  - `laboratorio-prediccion.blade.php`
  - `laboratorio-similares.blade.php`

### B. Módulo de Métricas (ABCD)
- `scripts/abcde_analyzer.py`: Script Python usando OpenCV y scikit-learn para análisis de Asimetría, Borde, Color y Diámetro.
- `App\Services\Metricas\AbcdeAnalyzer.php`: Bridge en PHP para ejecutar el script y procesar el JSON de salida.

### C. Módulo de Similitud (IA)
- `App\Services\Similares\CasosSimilaresService.php`: Cliente para:
  - Google DermFoundation (Embeddings).
  - Pinecone (Vector Search).
  - Google Cloud Storage (Imágenes del dataset HAM10000).

## 3. Dependencias Requeridas
- **Python 3**: `opencv-python`, `numpy`, `scikit-learn`.
- **PHP**: `Http` facade para APIs externas.

## 4. Archivos de Traducción
- `lang/es/lab.php` (y otros idiomas): Definición de etiquetas para el laboratorio.
- `lang/es/nav.php`: Registro de la etiqueta de navegación.

## 5. Configuración (.env & services.php)
- Configuración de APIs de Pinecone y DermFoundation.
- Verificación de rutas de storage para Spatie Media Library.

## 6. Flujo de Prueba - Resultados
1.  **Entorno:** MySQL y Laravel operativos localmente.
2.  **Acceso:** Login con `prof@skinnor.cl` / `password` exitoso. "Laboratorio" visible en sidebar.
3.  **Selección:** Paciente "Juan Perez" cargado correctamente. Grid de seguimientos muestra "Lunar Sospechoso".
4.  **Métricas:** Interfaz implementada. *Nota: Requiere `pip install opencv-python numpy scikit-learn` para ejecución real del script.*
5.  **IA:** Mockups de modelos A, B y C visualizados correctamente.
6.  **Similitud:** Buscador Pinecone implementado. Muestra estado de espera/vacío si no hay API Keys.

## 7. Correcciones Técnicas Realizadas
- **Laboratorio Page:** Se corrigió el error de declaración de `$view` (debe ser no estática) y el typehint de `$navigationIcon` para compatibilidad con Filament.
- **Configuración:** Se saneó `config/services.php` tras una duplicación accidental de llaves durante la edición.
- **Traducciones:** Implementadas en `es`, `en`, `pt`, `fr` tanto para navegación como para etiquetas del laboratorio.

---
**Implementación finalizada el 31/05/2026**
