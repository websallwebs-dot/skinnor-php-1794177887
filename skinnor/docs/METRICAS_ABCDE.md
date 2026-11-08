# Métricas ABCDE - Evaluación de Lesiones Cutáneas

> **Especificación técnica y científica para el módulo de evaluación de lesiones**
> Basado en la regla ABCD de dermatoscopia (Stolz et al., 1994) y extensiones validadas clínicamente.

---

## 1. Resumen ejecutivo

El sistema de puntuación ABCDE es un método estandarizado para la evaluación dermatoscópica de lesiones pigmentadas de la piel. Permite cuantificar objetivamente el riesgo de malignidad (especialmente melanoma) a través de cinco parámetros: **Asimetría, Bordes, Color, Diámetro y Evolución**.

El sistema implementado en Skinnor calcula automáticamente el **TDS (Total Dermoscopy Score)** y clasifica cada lesión según el riesgo estimado, siguiendo la evidencia científica publicada.

---

## 2. Fundamento científico

### 2.1 Origen y validación

La regla ABCD fue descrita originalmente por **Stolz et al. (1994)** y posteriormente validada en múltiples estudios clínicos:

| Referencia | Año | Aporte clave |
|------------|-----|--------------|
| Stolz et al. | 1994 | Creación de la regla ABCD y el TDS (Total Dermoscopy Score) |
| Argenziano et al. | 1998-2004 | Validación en múltiples poblaciones y validación de la regla de 7 puntos |
| Ahnlide et al. | 2016 | Validación clínica en práctica diaria; sensibilidad 83%, especificidad variable |
| Kittler et al. | Varios años | Validación de algoritmos de dermoscopia para melanoma |
| Dinnes et al. | 2018 | Revisión sistemática Cochrane: dermoscopia mejora significativamente el diagnóstico sobre inspección visual sola |

### 2.2 Umbrales de clasificación (estándar)

| TDS | Clasificación | Interpretación clínica | Acción recomendada |
|-----|---------------|------------------------|--------------------|
| **< 4.75** | Benigno | Baja probabilidad de malignidad | Seguimiento rutinario |
| **4.76 – 5.45** | Sospechoso | Riesgo intermedio de melanoma | Revisión en 3-6 meses o biopsia |
| **> 5.45** | Alto riesgo | Alta probabilidad de melanoma | Biopsia/exéresis inmediata |

---

## 3. Definición de parámetros

### 3.1 A - Asimetría (0 a 2 puntos)

Evalúa la simetría de forma y color de la lesión en dos ejes perpendiculares.

| Puntuación | Descripción |
|------------|-------------|
| **0** | Simétrica en ambos ejes |
| **1** | Asimétrica en un solo eje |
| **2** | Asimétrica en ambos ejes |

**Peso en TDS:** valor × 1.3

### 3.2 B - Bordes (0 a 8 puntos)

Evalúa la definición del límite de la lesión respecto a la piel circundante.

| Puntuación | Descripción |
|------------|-------------|
| **0** | Bordes nítidos, bien definidos |
| **1-2** | Bordes ligeramente irregulares |
| **3-4** | Bordes irregulares moderados |
| **5-6** | Bordes claramente irregulares |
| **7-8** | Bordes muy irregulares / discontinuos |

**Peso en TDS:** valor × 0.1

### 3.3 C - Color (1 a 5 puntos)

Evalúa la cantidad de colores distintos presentes en la lesión.

| Puntuación | Descripción |
|------------|-------------|
| **1** | Un solo color |
| **2** | Dos colores |
| **3** | Tres colores |
| **4** | Cuatro colores |
| **5** | Cinco o más colores (incluye blanco, azul, gris, negro, etc.) |

**Peso en TDS:** valor × 0.5

### 3.4 D - Diámetro (1 a 5 puntos)

Evalúa el diámetro máximo de la lesión en milímetros.

| Puntuación | Descripción |
|------------|-------------|
| **1** | < 6 mm |
| **2** | 6-7 mm |
| **3** | 7-8 mm |
| **4** | 8-9 mm |
| **5** | > 9 mm |

**Peso en TDS:** valor × 0.5

### 3.5 E - Evolución (1 a 5 puntos)

Evalúa cambios en la lesión a lo largo del tiempo (cambio de tamaño, forma, color, sintomatología).

| Puntuación | Descripción |
|------------|-------------|
| **1** | Estable, sin cambios |
| **2** | Cambios mínimos |
| **3** | Cambios moderados |
| **4** | Cambios evidentes |
| **5** | Cambios rápidos / progresión rápida |

**Peso en TDS:** valor × 0.5

---

## 4. Cálculo del Total Dermoscopy Score (TDS)

```
TDS = (A × 1.3) + (B × 0.1) + (C × 0.5) + (D × 0.5) + (E × 0.5)
```

**Nota:** Algunos protocolos tradicionales no incluyen la "E" (Evolución) en el cálculo del TDS clásico, pero para el seguimiento longitudinal se incluye como criterio de riesgo adicional.

---

## 5. Clasificación de riesgo por TDS

| Rango TDS | Clasificación | Nivel de riesgo | Acción recomendada |
|-----------|---------------|-----------------|--------------------|
| **< 4.75** | Bajo (Benigno) | Bajo | Seguimiento rutinario cada 6-12 meses |
| **4.76 – 5.45** | Medio (Sospechoso) | Intermedio | Reevaluación en 3-6 meses o biopsia |
| **> 5.45** | Alto (Maligno) | Alto | Biopsia o exéresis quirúrgica |

---

## 6. Métricas de evolución longitudinale

Para el seguimiento de una misma lesión en el tiempo, se recomienda registrar:

| Métrica | Fórmula | Umbral de alerta |
|---------|---------|------------------|
| **ΔTDS** | TDS_actual − TDS_previo | Δ ≥ 1 punto: revisión prioritaria |
| **ΔDiámetro** | D_actual − D_previo (mm) | Δ ≥ 2 mm: sospecha de crecimiento |
| **ΔÁrea** | Área_actual − Área_previo | Crecimiento relativo > 25% |
| **Cambio de categoría** | Cambio de categoría TDS entre evaluaciones | Cualquier cambio a categoría superior |
| **Tiempo entre evaluaciones** | Fecha_actual − Fecha_previo | Intervalo recomendado: 3-6 meses |

---

## 7. Métricas de desempeño diagnóstico (para validación)

Si se realiza validación clínica contra histopatología (estándar de oro):

| Métrica | Fórmula | Interpretación |
|---------|---------|----------------|
| **Sensibilidad** | TP / (TP + FN) | Capacidad de detectar lesiones malignas |
| **Especificidad** | TN / (TN + FP) | Capacidad de identificar correctamente lesiones benignas |
| **Precisión (PPV)** | TP / (TP + FP) | Probabilidad de que una lesión "sospechosa" sea realmente maligna |
| **Valor Predictivo Negativo (NPV)** | TN / (TN + FN) | Probabilidad de que una lesión "benigna" sea realmente benigna |
| **Exactitud** | (TP + TN) / Total | Proporción de clasificaciones correctas totales |
| **AUC-ROC** | Área bajo la curva ROC | Capacidad global de discriminación (0.5 = azar, 1.0 = perfecto) |

---

## 8. Referencias científicas

1. **Stolz W, et al.** (1994). *The ABCD rule of dermatoscopy: High prospective value in the diagnosis of doubtful melanocytic skin lesions.* J Am Acad Dermatol. 30(4):551-9. PMID: 8157780.
2. **Ahnlide I, et al.** (2016). *Validity of ABCD rule of dermoscopy in clinical practice.* Acta Derm Venereol. 96(3):367-72. PMID: 26351008.
3. **Dinnes J, et al.** (2018). *Visual inspection and dermoscopy for diagnosing keratinocyte skin cancers in adults.* Cochrane Database Syst Rev. PMID: 30521688.
4. **Vestergaard ME, et al.** (2008). *Dermoscopy compared with naked eye examination for the diagnosis of primary melanoma: a meta-analysis.* Br J Dermatol. 159(3):669-76. PMID: 18616769.
5. **Tas B, et al.** (2020). *Faster Evaluation of ABCD Rule and Total Dermoscopic Score for Nevomelanocytic Lesions: Dermoscopic Score Scale.* J Skin Stem Cell. 6(2):e102138.
6. **Kasmi et al.** (2016). *Classification of malignant melanoma and benign skin lesions: implementation of automatic ABCD rule.* IET Image Processing.

---

## 7. Implementación en Skinnor

El modelo `EvaluacionLesion` almacena las puntuaciones individuales (A, B, C, D, E) y calcula automáticamente el TDS y la clasificación de riesgo. Cada evaluación queda vinculada a una lesión específica y al profesional que la realizó, permitiendo trazabilidad completa y seguimiento longitudinal.

**Endpoint sugerido:** `POST /profesional/pacientes/{paciente}/lesiones/{lesion}/evaluaciones`

**Campos requeridos:** puntuacion_a, puntuacion_b, puntuacion_c, puntuacion_d, puntuacion_e, notas (opcional).

---
