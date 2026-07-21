# 🏥 Google Derm Foundation - Setup Guide

## Requisitos Previos
- Python 3.10+ (verificar: `python --version`)
- Windows 10+ o Linux
- Espacio en disco: ~2GB para modelo + dependencias

## Instalación Rápida (Windows)

### Opción 1: Script Automático (RECOMENDADO)
```bash
cd C:\Users\munoz\Downloads\Skinnor-PHP\skinnor\ai-service
setup_derm.bat
```

### Opción 2: Manual - Paso a Paso

**1. Crear virtual environment**
```bash
cd C:\Users\munoz\Downloads\Skinnor-PHP\skinnor\ai-service
python -m venv venv
venv\Scripts\activate.bat
```

**2. Actualizar pip**
```bash
python -m pip install --upgrade pip setuptools wheel
```

**3. Instalar dependencias**
```bash
pip install -r requirements.txt
```

Esto tardará ~5-10 minutos en descargar TensorFlow (350MB)

**4. Verificar instalación**
```bash
python -c "import tensorflow; import huggingface_hub; print('✓ Instalación exitosa')"
```

## Ejecutar el Servidor

Con el virtual environment activado:

```bash
# Linux/Mac
source venv/bin/activate
python -m uvicorn app:app --host 0.0.0.0 --port 8001 --reload

# Windows
venv\Scripts\activate.bat
python -m uvicorn app:app --host 0.0.0.0 --port 8001 --reload
```

**Output esperado:**
```
INFO:     Uvicorn running on http://0.0.0.0:8001
INFO:     Application startup complete
[ai-service] Iniciando carga del modelo Derm Foundation...
[ai-service] Intentando cargar con from_pretrained_keras() desde google/derm-foundation...
[ai-service] Modelo cargado vía from_pretrained_keras().
```

## Probar el API

En otra terminal (con venv activado):

```bash
curl -X POST http://localhost:8001/embed \
  -H "Content-Type: application/json" \
  -d '{"image_url": "https://storage.googleapis.com/dx-scin-public-data/dataset/images/3445096909671059178.png"}'
```

**Respuesta esperada:**
```json
{
  "embedding": [0.123, -0.456, 0.789, ...]  // Array de 6144 valores
}
```

## Problemas Comunes

### ❌ "ModuleNotFoundError: No module named 'tensorflow'"
**Solución:** Verifica que el venv esté activado (`which python` debe mostrar la ruta del venv)

### ❌ "RuntimeError: Module tensorflow has no attribute 'train'"
**Solución:** Versión de TensorFlow/Protobuf incompatible. Reinstala:
```bash
pip install --force-reinstall tensorflow==2.21.0
```

### ❌ Error al descargar modelo de Hugging Face
**Solución:** Necesita internet. Si tienes problemas, descarga el modelo manualmente:
```python
from huggingface_hub import from_pretrained_keras
model = from_pretrained_keras("google/derm-foundation")
```

### ❌ Port 8001 already in use
**Solución:** Cambia el puerto:
```bash
python -m uvicorn app:app --host 0.0.0.0 --port 8002
```

## Endpoints Disponibles

### POST /embed
Genera embedding de una imagen dermatológica

**Request:**
```json
{
  "image_url": "https://example.com/imagen.png"
}
```

**Response:**
```json
{
  "embedding": [0.1, 0.2, ..., 0.6144]  // Array de 6144 dimensiones
}
```

## Variables de Entorno

Si tienes token de Hugging Face (opcional para acceso privado):
```bash
# En Windows
set HF_TOKEN=tu_token_aqui

# O edita skinnor/.env
HF_TOKEN=tu_token_aqui
```

## Estructura del Proyecto
```
ai-service/
├── app.py                 # Servidor FastAPI
├── requirements.txt       # Dependencias Python
├── setup_derm.bat        # Script de instalación (Windows)
├── setup_derm.sh         # Script de instalación (Linux/Mac)
├── SETUP_DERM.md        # Este archivo
└── venv/                 # Virtual environment (creado por setup)
```

## Referencias
- 🔗 [Google Derm Foundation Docs](https://developers.google.com/health-ai-developer-foundations/derm-foundation)
- 📓 [Quick Start Notebook](https://colab.research.google.com/github/google-health/derm-foundation/blob/master/notebooks/quick_start_with_hugging_face.ipynb)
- 🏠 [Hugging Face Model](https://huggingface.co/google/derm-foundation)
- 📚 [GitHub Repo](https://github.com/google-health/derm-foundation)

---

¿Preguntas? Revisa los logs en `app.py` para más detalles.
