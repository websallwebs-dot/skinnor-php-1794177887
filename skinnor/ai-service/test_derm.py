#!/usr/bin/env python3
"""
Quick test script for Derm Foundation API
Uso: python test_derm.py
"""

import requests
import json
import sys
from pathlib import Path

# URL del servidor
API_URL = "http://localhost:8004/embed"

# Imagen de prueba desde Google
TEST_IMAGE_URL = "https://storage.googleapis.com/dx-scin-public-data/dataset/images/3445096909671059178.png"

def test_api():
    """Test the Derm Foundation embedding API"""
    print("\n" + "="*50)
    print("  Derm Foundation API Test")
    print("="*50)
    
    print(f"\n[INFO] Conectando a: {API_URL}")
    print(f"[INFO] Imagen de prueba: {TEST_IMAGE_URL}")
    
    payload = {
        "image_url": TEST_IMAGE_URL
    }
    
    print("\n[INFO] Enviando solicitud...")
    print(f"        Payload: {json.dumps(payload, indent=2)}")
    
    try:
        response = requests.post(
            API_URL,
            json=payload,
            timeout=300  # 5 minutos para la primera carga del modelo
        )
        
        print(f"\n[INFO] Status Code: {response.status_code}")
        
        if response.status_code == 200:
            data = response.json()
            embedding = data.get("embedding", [])
            
            print(f"\n[✓] ¡Éxito!")
            print(f"    Embedding generado:")
            print(f"    - Dimensiones: {len(embedding)}")
            print(f"    - Primeros 10 valores: {embedding[:10]}")
            print(f"    - Últimos 10 valores: {embedding[-10:]}")
            print(f"    - Rango: [{min(embedding):.4f}, {max(embedding):.4f}]")
            
            return True
        else:
            print(f"\n[✗] Error: {response.status_code}")
            print(f"    Response: {response.text}")
            return False
            
    except requests.exceptions.ConnectionError:
        print("\n[✗] Error de conexión!")
        print("    ¿Está el servidor corriendo?")
        print("    Ejecuta: python -m uvicorn app:app --host 0.0.0.0 --port 8001")
        return False
    except requests.exceptions.Timeout:
        print("\n[✗] Timeout (300s)")
        print("    El modelo está tardando mucho en cargar.")
        print("    Intenta de nuevo - la primera carga es lenta (~5-10 min)")
        return False
    except Exception as e:
        print(f"\n[✗] Error: {str(e)}")
        return False
    finally:
        print("\n" + "="*50 + "\n")

if __name__ == "__main__":
    success = test_api()
    sys.exit(0 if success else 1)
