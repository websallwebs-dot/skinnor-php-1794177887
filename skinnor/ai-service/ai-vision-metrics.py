from fastapi import FastAPI, UploadFile, File, HTTPException
import cv2
import numpy as np
import io
import base64

app = FastAPI(title="API de Métricas ABCD")

def get_image(contents):
    nparr = np.frombuffer(contents, np.uint8)
    return cv2.imdecode(nparr, cv2.IMREAD_COLOR)

@app.post("/metricas/{tipo}")
async def calcular_metrica(tipo: str, file: UploadFile = File(...)):
    contents = await file.read()
    img = get_image(contents)
    if img is None:
        raise HTTPException(status_code=400, detail="Imagen no válida")
    
    gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)
    _, thresh = cv2.threshold(gray, 127, 255, cv2.THRESH_BINARY_INV)

    if tipo == "asimetria":
        flipped = cv2.flip(thresh, 1)
        xor_diff = cv2.bitwise_xor(thresh, flipped)
        ai = np.sum(xor_diff > 0) / (thresh.shape[0] * thresh.shape[1])
        _, buffer = cv2.imencode('.png', xor_diff)
        return {"valor": round(float(ai * 10), 2), "image_base64": base64.b64encode(buffer).decode('utf-8')}

    elif tipo == "bordes":
        edges = cv2.Canny(img, 100, 200)
        _, buffer = cv2.imencode('.png', edges)
        contours, _ = cv2.findContours(thresh, cv2.RETR_EXTERNAL, cv2.CHAIN_APPROX_SIMPLE)
        circularidad = 0
        if contours:
            cnt = max(contours, key=cv2.contourArea)
            perimeter = cv2.arcLength(cnt, True)
            area = cv2.contourArea(cnt)
            circularidad = (4 * np.pi * area) / (perimeter**2) if perimeter > 0 else 0
        return {"valor": round(float(circularidad), 2), "image_base64": base64.b64encode(buffer).decode('utf-8')}

    elif tipo == "color":
        hsv = cv2.cvtColor(img, cv2.COLOR_BGR2HSV)
        v_channel = hsv[:, :, 2]
        std_v = np.std(v_channel)
        # Visualización: Mapa de calor (Jet)
        heatmap = cv2.applyColorMap(v_channel, cv2.COLORMAP_JET)
        _, buffer = cv2.imencode('.png', heatmap)
        return {"valor": round(float(std_v), 2), "image_base64": base64.b64encode(buffer).decode('utf-8')}

    elif tipo == "diametro":
        contours, _ = cv2.findContours(thresh, cv2.RETR_EXTERNAL, cv2.CHAIN_APPROX_SIMPLE)
        diametro = 0
        img_copy = img.copy()
        if contours:
            cnt = max(contours, key=cv2.contourArea)
            rect = cv2.minAreaRect(cnt)
            box = cv2.boxPoints(rect)
            box = np.int0(box)
            cv2.drawContours(img_copy, [box], 0, (0, 0, 255), 2)
            diametro = max(rect[1]) * 0.1 # Escala ficticia
        _, buffer = cv2.imencode('.png', img_copy)
        return {"valor": round(float(diametro), 2), "image_base64": base64.b64encode(buffer).decode('utf-8')}
            
    raise HTTPException(status_code=404, detail="Métrica no soportada")
