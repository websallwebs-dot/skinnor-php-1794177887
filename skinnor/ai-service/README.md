# 🏥 Google Derm Foundation - AI Service

Servicio FastAPI + Uvicorn para generar embeddings de imágenes dermatológicas usando el modelo Google Derm Foundation.

## 📋 Archivos Incluidos

- **app.py** — Servidor FastAPI con endpoint `/embed`
- **requirements.txt** — Dependencias Python (TensorFlow 2.21, FastAPI, Uvicorn, etc.)
- **setup_derm.bat** — Script de instalación automática (Windows)
- **setup_derm.sh** — Script de instalación automática (Linux/Mac)
- **test_derm.py** — Script para probar el API
- **SETUP_DERM.md** — Guía de instalación detallada

## ⚡ Inicio Rápido

### Windows
```bash
cd C:\Users\munoz\Downloads\Skinnor-PHP\skinnor\ai-service
setup_derm.bat
```

### Linux / Mac
```bash
cd /ruta/a/skinnor/ai-service
bash setup_derm.sh
```

Después de la instalación, el script mostrará los próximos pasos.

## 🚀 Ejecutar el Servidor

Con el virtual environment activado:

```bash
python -m uvicorn app:app --host 0.0.0.0 --port 8001 --reload
```

Verás:
```
INFO:     Uvicorn running on http://0.0.0.0:8001
[ai-service] Iniciando carga del modelo Derm Foundation...
[ai-service] Modelo cargado vía from_pretrained_keras().
```

## 🧪 Probar el API

En otra terminal (con venv activado):

```bash
python test_derm.py
```

O directamente con curl:

```bash
curl -X POST http://localhost:8001/embed \
  -H "Content-Type: application/json" \
  -d '{"image_url": "https://storage.googleapis.com/dx-scin-public-data/dataset/images/3445096909671059178.png"}'
```

## 📡 API Endpoint

### POST /embed

**Request:**
```json
{
  "image_url": "https://example.com/skin-image.png"
}
```

**Response:**
```json
{
  "embedding": [0.123, -0.456, ..., 0.789]  // Array de 6144 valores
}
```

## 🎯 Características

- ✅ Generación de embeddings de 6144 dimensiones
- ✅ Soporta URLs públicas de imágenes
- ✅ Lazy loading del modelo (carga en la primera solicitud)
- ✅ Logs detallados para debugging
- ✅ Optimizado para CPU y GPU

## 📖 Documentación Completa

Abre **SETUP_DERM.md** para:
- Instalación paso a paso
- Solución de problemas comunes
- Referencias a documentación oficial
- Ejemplos avanzados

## 🔗 Referencias

- [Google Derm Foundation Docs](https://developers.google.com/health-ai-developer-foundations/derm-foundation)
- [Hugging Face Model](https://huggingface.co/google/derm-foundation)
- [GitHub Repository](https://github.com/google-health/derm-foundation)

## ⚙️ Requisitos del Sistema

- Python 3.10+
- 4GB RAM mínimo
- 2GB espacio en disco (modelo + dependencias)
- Conexión a internet (primera instalación y descarga de modelo)

---

¡Haz que comience la instalación ejecutando `setup_derm.bat` (Windows) o `bash setup_derm.sh` (Linux/Mac)!
