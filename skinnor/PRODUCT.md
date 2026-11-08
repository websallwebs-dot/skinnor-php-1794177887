# Product

<!-- impeccable:product-schema 1 -->

## Platform

web

## Users

Skinnor es una plataforma multi-sided que sirve a tres audiencias:

- **Clínicas dermatológicas** — dueños/administradores que gestionan su operación, doctores y pacientes mediante un panel admin multi-tenant.
- **Profesionales (dermatólogos)** — realizan seguimiento de lesiones con imágenes, notas clínicas y consultas. Usan el panel Profesional.
- **Pacientes** — visualizan sus lesiones, seguimientos e imágenes desde su propio panel. También pueden registrarse directamente.

Cada clínica opera su propia base de datos física (multi-tenancy). Un superadmin global gestiona las clínicas desde el panel central.

## Product Purpose

Skinnor es un SaaS de gestión clínica dermatológica que centraliza pacientes, lesiones, imágenes y consultas, permitiendo a los profesionales hacer seguimiento visual preciso de la evolución de lesiones y comunicarse con el paciente. Su propósito es reemplazar sistemas clínicos tradicionales con una plataforma moderna, accesible y potenciada por IA.

## Positioning

**Diferenciador principal:** Análisis predictivo con IA para seguimiento de lesiones dermatológicas. Skinnor no solo almacena imágenes clínicas — las analiza, compara evoluciones y asiste al diagnóstico. Ninguna plataforma competidora en LATAM ofrece esta capacidad integrada.

## Operating Context

- SaaS web multi-tenant (stancl/tenancy v3). Cada tenant = base de datos MySQL independiente.
- 4 paneles Filament v5: Superadmin (central), Admin (tenant), Profesional (tenant), Paciente (tenant).
- Flujo principal: Profesional registra lesión → sube imagen → IA analiza → Paciente visualiza.
- Idiomas: español, inglés, portugués, francés — todos actualizados en paralelo.
- Entorno local: Laravel 13.8 + PHP 8.3 + MySQL 8.0 + Node.js (Vite + Tailwind v4).
- Servidor local: Laravel Herd + DBngin (Windows).

## Capabilities and Constraints

**Capacidades confirmadas:**
- Multi-tenancy con BD separadas por clínica.
- CRUD completo de pacientes, profesionales, lesiones, seguimientos, consultas.
- Carga de imágenes clínicas (Spatie Media Library).
- 4 roles con paneles Filament separados.
- Traducciones en es, en, pt, fr.
- Integración con Pinecone para búsqueda semántica de casos similares.
- API de IA (DERMFOUNDATION_ENDPOINT) para análisis de imágenes.
- Landing page pública con registro y login.

**Constraints técnicas:**
- No usar Filament v3/v4 — estrictamente v5 con Schemas.
- No hardcodear strings UI — usar claves de traducción.
- File uploads siempre con Spatie Media Library.
- No poner tablas de negocio en migraciones centrales (solo en database/migrations/tenant/).
- Vendor patches para tmpfile() en Windows (Livewire + Spatie).

**Por decidir:**
- Logo oficial (aún no definido).
- Estrategia de deployment/productivo.

## Brand Commitments

- **Nombre:** Skinnor (ya definido).
- **Color primario:** Azul y blanco (#2563eb / azul base).
- **Estilo visual:** Bordes rectangulares, uso de gradients, apariencia moderna y profesional.
- **Logo:** No definido — libertad creativa para proponer uno.
- **Voice:** Profesional, claro, moderno. En español e inglés según la audiencia.
- **Personalidad:** Confiable (datos clínicos), innovador (IA predictiva), accesible.

## Evidence on Hand

- Código funcional con 4 paneles Filament, multi-tenancy operativo, migraciones centrales y de tenant.
- Landing page existente (Tailwind CDN) que necesita reemplazo completo.
- Integración Pinecone + API de IA (endpoint en .env).
- Tests Pest configurados.
- Docs del proyecto en docs/PROYECTO.md, AGENTS.md, standards/.

**No fabricar:** Testimonios, casos de estudio, precios, benchmarks de rendimiento, o claims de clientes reales sin evidencia.

## Product Principles

1. **IA como núcleo, no como añadido** — la landing y el producto deben comunicar que la IA es parte esencial del seguimiento dermatológico, no una feature secundaria.
2. **Multi-audiencia, una experiencia** — pacientes, doctores y clínicas tienen necesidades distintas pero deben sentir que pertenecen al mismo ecosistema.
3. **Multi-idioma nativo** — el producto se diseña y escribe en 4 idiomas simultáneamente desde el inicio.
4. **Datos clínicos, diseño moderno** — la seriedad de los datos médicos no está reñida con una interfaz moderna, limpia y agradable.
5. **Landing como puerta de entrada** — la landing page debe servir tanto para marketing (atraer clínicas) como para acceso funcional (login, registro).

## Accessibility & Inclusion

- Soporte multi-idioma (es, en, pt, fr) desde el diseño.
- Público LATAM con posibilidad de expansión global.
- Sin requisitos específicos de accesibilidad documentados aún.