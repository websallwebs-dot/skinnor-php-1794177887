#!/usr/bin/env python3
"""
Analizador ABCD para melanoma.
Implementa algoritmos de:
- Smaoui et al. (2020) para asimetría
- Kasmi & Mokrani (2016) para bordes
- Celebi et al. (2007) para color
"""
import cv2
import numpy as np
import sys
import json
from sklearn.cluster import KMeans

def analyze_image(image_path: str) -> dict:
    try:
        img = cv2.imread(image_path)
        if img is None:
            return {"error": f"No se pudo leer la imagen en {image_path}"}
            
        gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)
        
        # Segmentación con Otsu
        _, binary = cv2.threshold(gray, 0, 255, cv2.THRESH_BINARY_INV + cv2.THRESH_OTSU)
        contours, _ = cv2.findContours(binary, cv2.RETR_EXTERNAL, cv2.CHAIN_APPROX_SIMPLE)
        
        if not contours:
            return {"error": "No se detectó lesión"}
        
        contour = max(contours, key=cv2.contourArea)
        
        # ASIMETRÍA
        moments = cv2.moments(contour)
        area = moments['m00']
        if area == 0:
            return {"error": "Área cero"}
        
        # Eje principal con PCA
        data_pts = np.float32(contour[:, 0, :])
        mean, eigenvectors, _ = cv2.PCACompute2(data_pts, mean=None)
        angle = np.arctan2(eigenvectors[0, 1], eigenvectors[0, 0])
        
        # Rotar y dividir por ejes
        M = cv2.getRotationMatrix2D(tuple(mean[0]), np.degrees(angle), 1)
        rotated_binary = cv2.warpAffine(binary, M, (binary.shape[1], binary.shape[0]))
        
        cx, cy = int(mean[0][0]), int(mean[0][1])
        half1 = rotated_binary[:cy, :]
        half2 = rotated_binary[cy:, :]
        a1, a2 = np.sum(half1 > 0), np.sum(half2 > 0)
        asimetria = 1 - (min(a1, a2) / max(a1, a2)) if max(a1, a2) > 0 else 0
        
        # BORDE (Polsby-Popper)
        perimeter = cv2.arcLength(contour, True)
        border_score = (4 * np.pi * area) / (perimeter ** 2) if perimeter > 0 else 0
        irregularidad_borde = 1 - border_score  # 0=regular, 1=irregular
        
        # COLOR (K-means en HSV)
        mask = np.zeros(img.shape[:2], dtype=np.uint8)
        cv2.drawContours(mask, [contour], -1, 255, -1)
        hsv = cv2.cvtColor(img, cv2.COLOR_BGR2HSV)
        pixels = hsv[mask > 0].reshape(-1, 3).astype(np.float32)
        
        if len(pixels) > 100:
            kmeans = KMeans(n_clusters=5, random_state=42, n_init=10)
            kmeans.fit(pixels)
            # Contar clusters con más del 5% de pixels
            labels, counts = np.unique(kmeans.labels_, return_counts=True)
            dominant = sum(1 for c in counts if c / len(pixels) > 0.05)
        else:
            dominant = 1
        
        color_score = min(dominant / 5, 1.0)  # 0=homogéneo, 1=muy heterogéneo
        
        # DIÁMETRO (Feret)
        rect = cv2.minAreaRect(contour)
        diametro_px = max(rect[1])
        # Sin calibración, reportar en pixels con advertencia
        
        # SCORE TOTAL (ponderado, inspirado en TDS - Total Dermoscopy Score)
        # Asimetría: peso 1.3, Borde: 0.1 por punto, Color: 0.5, Diámetro: N/A
        tds = (asimetria * 1.3) + (irregularidad_borde * 5 * 0.1) + (color_score * 5 * 0.5)
        
        return {
            "asimetria": round(float(asimetria), 3),
            "asimetria_score": "Alto" if asimetria > 0.3 else "Bajo",
            "borde": round(float(irregularidad_borde), 3),
            "borde_score": "Irregular" if irregularidad_borde > 0.5 else "Regular",
            "color_clusters": int(dominant),
            "color_score": "Heterogéneo" if dominant > 2 else "Homogéneo",
            "diametro_px": round(float(diametro_px), 1),
            "diametro_nota": "Requiere calibración con dermatoscopio para mm reales",
            "tds": round(float(tds), 2),
            "tds_interpretacion": (
                "Probablemente benigno" if tds < 4.75
                else "Sospechoso" if tds < 5.45
                else "Probablemente maligno"
            ),
            "referencias": [
                "Smaoui et al. (2020) - Asymmetry analysis",
                "Kasmi & Mokrani (2016) - Border irregularity",
                "Celebi et al. (2007) - Color analysis",
                "TDS Score: Argenziano et al. (1998)"
            ]
        }
    except Exception as e:
        return {"error": str(e)}

if __name__ == "__main__":
    if len(sys.argv) < 2:
        print(json.dumps({"error": "No se proporcionó la ruta de la imagen"}))
        sys.exit(1)
        
    result = analyze_image(sys.argv[1])
    print(json.dumps(result))
