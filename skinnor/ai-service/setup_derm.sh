#!/bin/bash

# ============================================================================
#  Google Derm Foundation - Setup Script for Linux/Mac
# ============================================================================

set -e

echo ""
echo "========================================"
echo "  Derm Foundation Setup"
echo "========================================"
echo ""

# Check Python version
if ! command -v python3 &> /dev/null; then
    echo "[ERROR] Python 3 no encontrado. Instala Python 3.10+"
    echo "Descarga desde: https://www.python.org"
    exit 1
fi

python3 --version

echo "[1/4] Creando virtual environment..."
if [ -d "venv" ]; then
    echo "    Eliminando venv existente..."
    rm -rf venv
fi
python3 -m venv venv
echo "[OK] Virtual environment creado"

echo ""
echo "[2/4] Activando virtual environment..."
source venv/bin/activate
echo "[OK] Virtual environment activado"

echo ""
echo "[3/4] Instalando dependencias (esto puede tardar 5-10 minutos)..."
echo "       - Descargando TensorFlow 2.21.0 (350MB)"
echo "       - Instalando huggingface_hub, FastAPI, uvicorn..."
echo ""

python -m pip install --upgrade pip setuptools wheel --quiet
pip install -r requirements.txt
echo "[OK] Todas las dependencias instaladas"

echo ""
echo "[4/4] Verificando instalación..."
python -c "import tensorflow; import huggingface_hub; import fastapi; print('[OK] Verificacion exitosa')" || \
    echo "[WARNING] Verificacion incompleta, pero puedes intentar ejecutar el servidor"

echo ""
echo "========================================"
echo "  ✓ Setup Completado!"
echo "========================================"
echo ""
echo "Próximos pasos:"
echo ""
echo "1. Ejecuta el servidor:"
echo "   python -m uvicorn app:app --host 0.0.0.0 --port 8001 --reload"
echo ""
echo "2. En otra terminal, prueba el API:"
echo "   curl -X POST http://localhost:8001/embed \\"
echo "     -H 'Content-Type: application/json' \\"
echo "     -d '{\"image_url\": \"https://storage.googleapis.com/dx-scin-public-data/dataset/images/3445096909671059178.png\"}'"
echo ""
echo "3. El modelo se descargará automáticamente en la primera solicitud (~2GB)"
echo ""
echo "Lee SETUP_DERM.md para más detalles"
echo ""
