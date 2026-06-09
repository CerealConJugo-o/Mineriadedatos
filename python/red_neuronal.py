# -*- coding: utf-8 -*-
"""
RED NEURONAL (MLPClassifier) - Mineria de datos
------------------------------------------------
Predice la columna 'mortalidad' (1 = fallecio, 0 = vivio) a partir del
texto clinico 'resclin' de la tabla registro_a.

IMPORTANTE (SEGURIDAD): este script es de SOLO LECTURA. Unicamente hace
SELECT sobre registro_a. NUNCA escribe (no UPDATE / DELETE / DROP), por lo
que no altera la base de datos de produccion en Render.

Salida: imprime lineas de progreso y, al final, UNA sola linea con un
objeto JSON (empieza con '{') que el controlador de Laravel leera.
"""
import sys
import json

import psycopg2
from psycopg2.extras import RealDictCursor

from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.neural_network import MLPClassifier
from sklearn.model_selection import train_test_split
from sklearn.metrics import (
    accuracy_score,
    precision_score,
    recall_score,
    f1_score,
    confusion_matrix,
)

# --- Conexion a la PostgreSQL remota (misma que el resto del proyecto) ---
config = {
    "host": "dpg-d8ibcreq1p3s73ehs3jg-a.oregon-postgres.render.com",
    "database": "covid_j5k2",
    "user": "root",
    "password": "VgW6Q0UOE1VQEnurU2nIhSPpnhct8M4U",
    "port": "5432",
    "sslmode": "require",
}


def main():
    print("Conectando a la base de datos...")
    conn = psycopg2.connect(**config)
    cursor = conn.cursor(cursor_factory=RealDictCursor)

    # 1) SELECCION DE DATOS (solo lectura).
    #    Los datos estan desbalanceados (~2.2% de fallecimientos), asi que
    #    armamos una muestra balanceada: todos los positivos + igual numero
    #    de negativos tomados al azar. Asi el modelo aprende de ambas clases.
    print("Extrayendo casos positivos (mortalidad = 1)...")
    cursor.execute("""
        SELECT resclin
        FROM registro_a
        WHERE mortalidad = 1 AND resclin IS NOT NULL AND resclin <> ''
    """)
    positivos = [f["resclin"] for f in cursor.fetchall()]

    n_pos = len(positivos)
    print(f"Positivos encontrados: {n_pos}")

    print("Extrayendo una muestra balanceada de negativos (mortalidad = 0)...")
    cursor.execute("""
        SELECT resclin
        FROM registro_a
        WHERE mortalidad = 0 AND resclin IS NOT NULL AND resclin <> ''
        ORDER BY random()
        LIMIT %s
    """, (max(n_pos, 1),))
    negativos = [f["resclin"] for f in cursor.fetchall()]

    cursor.close()
    conn.close()

    textos = positivos + negativos
    etiquetas = [1] * len(positivos) + [0] * len(negativos)

    if len(set(etiquetas)) < 2:
        print(json.dumps({"error": "No hay datos suficientes de ambas clases."}))
        return

    # 2) TRANSFORMACION: convertir el texto en numeros con TF-IDF.
    print("Vectorizando texto con TF-IDF...")
    vectorizer = TfidfVectorizer(max_features=2000, lowercase=True)
    X = vectorizer.fit_transform(textos)
    y = etiquetas

    # 3) Separar en entrenamiento (80%) y prueba (20%).
    X_train, X_test, y_train, y_test = train_test_split(
        X, y, test_size=0.2, random_state=42, stratify=y
    )

    # 4) MINERIA: entrenar la Red Neuronal (Perceptron Multicapa).
    print("Entrenando la Red Neuronal (MLPClassifier)...")
    modelo = MLPClassifier(
        hidden_layer_sizes=(64, 32),
        activation="relu",
        max_iter=300,
        random_state=42,
    )
    modelo.fit(X_train, y_train)

    # 5) EVALUACION sobre el conjunto de prueba.
    print("Evaluando el modelo...")
    y_pred = modelo.predict(X_test)
    cm = confusion_matrix(y_test, y_pred).tolist()

    resultado = {
        "modelo": "Red Neuronal (MLPClassifier)",
        "muestras_total": len(textos),
        "muestras_positivas": len(positivos),
        "muestras_negativas": len(negativos),
        "capas_ocultas": [64, 32],
        "exactitud": round(accuracy_score(y_test, y_pred) * 100, 2),
        "precision": round(precision_score(y_test, y_pred, zero_division=0) * 100, 2),
        "sensibilidad": round(recall_score(y_test, y_pred, zero_division=0) * 100, 2),
        "f1": round(f1_score(y_test, y_pred, zero_division=0) * 100, 2),
        "matriz_confusion": cm,
    }

    # Linea final: JSON (el controlador la detecta porque empieza con '{').
    print(json.dumps(resultado, ensure_ascii=False))


if __name__ == "__main__":
    try:
        main()
    except Exception as err:
        print(json.dumps({"error": str(err)}, ensure_ascii=False))
        sys.exit(1)
