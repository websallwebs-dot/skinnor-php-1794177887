from fastapi import FastAPI, HTTPException
from pydantic import BaseModel
from huggingface_hub import from_pretrained_keras, hf_hub_download, snapshot_download
import tensorflow as tf
from PIL import Image
from io import BytesIO
import requests
import numpy as np
import os
from pathlib import Path
from dotenv import load_dotenv
import logging

# Configurar logging
logging.basicConfig(level=logging.INFO)
logger = logging.getLogger("ai-service")

# Cargar variables desde el .env de Laravel
if os.path.exists(os.path.join(os.path.dirname(__file__), '..', '.env')):
    load_dotenv(dotenv_path=os.path.join(os.path.dirname(__file__), '..', '.env'))
    logger.info("Cargando variables de entorno desde skinnor/.env")
else:
    load_dotenv(dotenv_path=os.path.join(os.path.dirname(__file__), '..', '..', '.env'))
    logger.info("Cargando variables de entorno desde repo root .env")

app = FastAPI(title="Derm Foundation AI Service")

# Variables globales del modelo
loaded_model = None
infer = None
model_lock = None

# Token de Hugging Face (si aplica)
HF_TOKEN = os.getenv('HF_TOKEN') or os.getenv('HUGGINGFACE_HUB_TOKEN') or os.getenv('HF_HUB_TOKEN')
if HF_TOKEN:
    logger.info("Token de Hugging Face detectado")
else:
    logger.info("Sin token de Hugging Face (el modelo es público)")

MODEL_REPO = "google/derm-foundation"
MODEL_CACHE_DIR = os.path.expanduser("~/.cache/huggingface/hub")


def load_model():
    """
    Carga el modelo Google Derm Foundation desde Hugging Face Hub
    Intenta múltiples métodos:
    1. snapshot_download + tf.saved_model.load (Keras 3 / TF 2.16+ compatible)
    2. from_pretrained_keras (fallback Keras 2)
    """
    global loaded_model, infer
    
    logger.info("="*60)
    logger.info("INICIANDO CARGA DEL MODELO DERM FOUNDATION")
    logger.info("="*60)
    logger.info(f"Modelo: {MODEL_REPO}")
    logger.info(f"Cache dir: {MODEL_CACHE_DIR}")
    
    try:
        # MÉTODO 1: snapshot_download + tf.saved_model.load (Recomendado para Keras 3)
        logger.info("\n[MÉTODO 1] Intentando cargar modelo...")
        try:
            # Intentar primero desde caché local sin red
            try:
                logger.info("  Intentando cargar desde caché local...")
                model_dir = snapshot_download(
                    repo_id=MODEL_REPO,
                    cache_dir=MODEL_CACHE_DIR,
                    local_files_only=True
                )
            except Exception:
                logger.info("  Modelo no encontrado en caché, descargando...")
                model_dir = snapshot_download(
                    repo_id=MODEL_REPO,
                    token=HF_TOKEN,
                    cache_dir=MODEL_CACHE_DIR
                )
            
            logger.info(f"  Directorio del modelo: {model_dir}")
            logger.info("  Cargando con tf.saved_model.load()...")
            
            loaded_model = tf.saved_model.load(model_dir)
            infer = loaded_model.signatures["serving_default"]
            
            logger.info("  ✓ Modelo cargado exitosamente vía tf.saved_model.load()")
            logger.info(f"  Signatures disponibles: {list(loaded_model.signatures.keys())}")
            return True
            
        except Exception as e1:
            logger.warning(f"  ✗ Método 1 falló: {str(e1)}")
            logger.info("\n[MÉTODO 2] Fallback - Intentando from_pretrained_keras()...")
            
            # MÉTODO 2: from_pretrained_keras (Keras 2)
            try:
                loaded_model = from_pretrained_keras(
                    MODEL_REPO,
                    token=HF_TOKEN,
                    cache_dir=MODEL_CACHE_DIR
                )
                
                infer = loaded_model.signatures["serving_default"]
                
                logger.info("  ✓ Modelo cargado exitosamente vía from_pretrained_keras()")
                return True
                
            except Exception as e2:
                logger.error(f"  ✗ Todos los intentos fallaron")
                logger.error(f"    Error 1: {str(e1)}")
                logger.error(f"    Error 2: {str(e2)}")
                raise e2
    
    except Exception as e:
        logger.error("="*60)
        logger.error("✗ NO SE PUDO CARGAR EL MODELO")
        logger.error("="*60)
        logger.error(f"Error: {str(e)}")
        logger.error("\nSoluciones:")
        logger.error("1. Verifica que tienes internet (se descarga ~2GB)")
        logger.error("2. Verifica espacio en disco (~2GB)")
        logger.error("3. Verifica que Python 3.10+ está instalado")
        logger.error("4. Intenta ejecutar nuevamente")
        import traceback
        logger.error("\nStack trace completo:")
        logger.error(traceback.format_exc())
        return False


# Cargar modelo al iniciar el servidor
logger.info("\n" + "="*60)
logger.info("INICIANDO SERVIDOR FASTAPI")
logger.info("="*60)

try:
    loaded_ok = load_model()
    if loaded_ok:
        logger.info("\n✓ SERVIDOR LISTO PARA RECIBIR SOLICITUDES")
        logger.info(f"  POST /embed - Genera embeddings de imágenes dermatológicas")
        logger.info(f"  GET /docs - Documentación interactiva")
    else:
        logger.warning("\n⚠ El modelo no se cargó al inicio")
        logger.warning("  Se intentará cargar bajo demanda en la primera solicitud /embed")
except Exception as e:
    logger.error(f"\n✗ Error inesperado: {str(e)}")
    import traceback
    logger.error(traceback.format_exc())


class ImageRequest(BaseModel):
    image_url: str
    
    class Config:
        json_schema_extra = {
            "example": {
                "image_url": "https://storage.googleapis.com/dx-scin-public-data/dataset/images/3445096909671059178.png"
            }
        }


@app.get("/health")
async def health_check():
    """Verifica que el servidor está corriendo"""
    return {
        "status": "ok",
        "model_loaded": infer is not None,
        "service": "Derm Foundation AI"
    }


@app.post("/embed")
async def get_embedding(request: ImageRequest):
    """
    Genera un embedding (vector de 6144 dimensiones) desde una imagen dermatológica
    
    Args:
        image_url: URL pública de la imagen (PNG o JPG)
    
    Returns:
        embedding: Array de 6144 valores float
    """
    try:
        global infer
        
        # Cargar modelo si no está cargado
        if infer is None:
            logger.info("Modelo no cargado, intentando cargar bajo demanda...")
            loaded_ok = load_model()
            if not loaded_ok or infer is None:
                raise HTTPException(
                    status_code=503,
                    detail="Modelo no disponible. Revisa los logs del servidor para detalles."
                )
        
        # CAMBIO: Detectar si es una URL local (local-image o storage) y leer del disco
        if "/local-image/" in request.image_url or "/storage/" in request.image_url:
            # Extraer el path relativo
            if "/local-image/" in request.image_url:
                relative_path = request.image_url.split('/local-image/')[1]
                # Buscar en HAM10000
                search_dirs = [
                    'C:\\Users\\munoz\\Downloads\\HAM10000\\HAM10000_images_part_2',
                    'C:\\Users\\munoz\\Downloads\\HAM10000\\HAM10000_images_part_1',
                ]
            else:
                relative_path = request.image_url.split('/storage/')[1]
                # Buscar en storage/app/public
                search_dirs = [
                    os.path.join(os.path.dirname(__file__), '..', 'storage', 'app', 'public'),
                ]
            
            local_path = None
            for p in search_dirs:
                potential_path = os.path.join(p, relative_path)
                if os.path.exists(potential_path):
                    local_path = potential_path
                    break
            
            if local_path:
                logger.info(f"Leyendo imagen desde disco: {local_path}")
                with open(local_path, "rb") as f:
                    image_bytes = f.read()
                logger.info(f"Imagen leída con éxito, tamaño: {len(image_bytes)} bytes")
            else:
                 logger.error(f"Imagen no encontrada localmente: {relative_path}")
                 raise HTTPException(status_code=404, detail="Imagen no encontrada localmente")
        else:
            # Descarga normal solo para URLs externas
            logger.info(f"Descargando imagen externa: {request.image_url}")
            response = requests.get(request.image_url, stream=True, timeout=30)
            response.raise_for_status()
            image_bytes = response.content
        
        # Abrir imagen y convertir a RGB
        try:
            img = Image.open(BytesIO(image_bytes))
            logger.info("Imagen abierta con éxito con PIL")
            buf = BytesIO()
            img.convert('RGB').save(buf, 'PNG')
            image_bytes = buf.getvalue()
        except Exception as e:
            logger.error(f"Error procesando imagen con PIL: {str(e)}")
            raise HTTPException(status_code=400, detail="Error procesando la imagen")
        
        logger.info(f"Imagen procesada: {len(image_bytes)} bytes")
        
        # Formatear entrada para TensorFlow Serving
        input_tensor = tf.train.Example(features=tf.train.Features(
            feature={'image/encoded': tf.train.Feature(
                bytes_list=tf.train.BytesList(value=[image_bytes])
            )}
        )).SerializeToString()
        
        # Ejecutar inferencia
        logger.info("Ejecutando inferencia...")
        output = infer(inputs=tf.constant([input_tensor]))
        embedding_vector = output['embedding'].numpy().flatten().tolist()
        
        logger.info(f"✓ Embedding generado: {len(embedding_vector)} dimensiones")
        
        return {
            "embedding": embedding_vector,
            "dimensions": len(embedding_vector),
            "model": "google/derm-foundation"
        }
        
    except HTTPException:
        raise
    except Exception as e:
        logger.error(f"✗ Error en /embed: {str(e)}")
        import traceback
        logger.error(traceback.format_exc())
        raise HTTPException(status_code=500, detail=str(e))


@app.get("/")
async def root():
    """Página principal con información del servicio"""
    return {
        "service": "Google Derm Foundation AI Service",
        "version": "1.0.0",
        "endpoints": {
            "GET /health": "Verificar estado del servidor",
            "POST /embed": "Generar embedding de imagen dermatológica",
            "GET /docs": "Documentación interactiva (Swagger)"
        },
        "model_status": "loaded" if infer is not None else "not loaded",
        "docs_url": "/docs"
    }

