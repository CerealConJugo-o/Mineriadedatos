# -- coding: utf-8 --
import sys
import os

# --- PARCHE DE RUTAS (CRUCIAL) ---
# Agregamos la carpeta "Roaming" donde se escondió dateutil
ruta_roaming = r"C:\Users\TU_USUARIO\AppData\Roaming\Python\Python311\site-packages"
if ruta_roaming not in sys.path:
    sys.path.append(ruta_roaming)
# ---------------------------------

import json
# Ahora Pandas podrá encontrar a sus amigos
import pandas as pd
from sklearn.linear_model import LinearRegression
import numpy as np
import warnings

warnings.filterwarnings("ignore")

# --- LEER DATOS DESDE STDIN ---
try:
    input_data = sys.stdin.read()
    
    if not input_data:
        # Si llega vacío, intentamos leer de argumentos por si acaso
        if len(sys.argv) > 1:
            input_data = sys.argv[1]
        else:
            raise ValueError("No se recibieron datos (Stdin vacio)")
        
    datos = json.loads(input_data)

except Exception as e:
    print(json.dumps({"error": "Error leyendo datos en Python", "detalle": str(e)}))
    sys.exit(1)

# 2. Convertir a DataFrame
df = pd.DataFrame(datos)

# 3. Entrenar Modelo
X = df[['mes']] 
y = df['total'] 

modelo = LinearRegression()
modelo.fit(X, y)

# 4. Predecir
ultimo_mes = df['mes'].max()
siguiente_mes_num = int(ultimo_mes) + 1
siguiente_mes_array = [[siguiente_mes_num]]

prediccion = modelo.predict(siguiente_mes_array)

# 5. Resultado
resultado = {
    "mes_predicho": siguiente_mes_num,
    "venta_estimada": round(float(prediccion[0]), 2),
    "tendencia": "Creciente" if modelo.coef_[0] > 0 else "Decreciente"
}

print(json.dumps(resultado))
