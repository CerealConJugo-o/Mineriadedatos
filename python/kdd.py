# -*- coding: utf-8 -*-
"""
KDD - Knowledge Discovery in Databases (Mineria de datos)
---------------------------------------------------------
Aplica el PROCESO KDD completo sobre la tabla registro_a para descubrir
que palabras del texto clinico 'resclin' estan mas asociadas a la
mortalidad. La tecnica de mineria es un Arbol de Decision.

Etapas KDD:
  1. Seleccion        -> obtener los datos relevantes (SELECT).
  2. Preprocesamiento -> limpiar nulos / vacios, balancear clases.
  3. Transformacion   -> convertir texto a numeros (TF-IDF).
  4. Mineria de datos -> entrenar un Arbol de Decision.
  5. Interpretacion   -> exactitud + palabras mas informativas.

IMPORTANTE (SEGURIDAD): SOLO LECTURA. Unicamente hace SELECT. Nunca
escribe en la base de datos de produccion.

Salida: lineas de progreso y, al final, UNA linea JSON (empieza con '{').
"""
import sys
import json

import psycopg2
from psycopg2.extras import RealDictCursor

from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.tree import DecisionTreeClassifier
from sklearn.model_selection import train_test_split
from sklearn.metrics import accuracy_score, f1_score

config = {
    "host": "dpg-d8ibcreq1p3s73ehs3jg-a.oregon-postgres.render.com",
    "database": "covid_j5k2",
    "user": "root",
    "password": "VgW6Q0UOE1VQEnurU2nIhSPpnhct8M4U",
    "port": "5432",
    "sslmode": "require",
}


def main():
    etapas = []

    # ---------- ETAPA 1: SELECCION ----------
    print("[1/5] Seleccion: conectando y extrayendo datos...")
    conn = psycopg2.connect(**config)
    cursor = conn.cursor(cursor_factory=RealDictCursor)

    cursor.execute("""
        SELECT resclin
        FROM registro_a
        WHERE mortalidad = 1 AND resclin IS NOT NULL AND resclin <> ''
    """)
    positivos = [f["resclin"] for f in cursor.fetchall()]
    n_pos = len(positivos)

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
    etapas.append({
        "etapa": "1. Seleccion",
        "detalle": f"{n_pos + len(negativos)} registros con texto clinico extraidos.",
    })

    # ---------- ETAPA 2: PREPROCESAMIENTO ----------
    print("[2/5] Preprocesamiento: balanceando y etiquetando...")
    textos = positivos + negativos
    etiquetas = [1] * len(positivos) + [0] * len(negativos)

    if len(set(etiquetas)) < 2:
        print(json.dumps({"error": "No hay datos suficientes de ambas clases."}))
        return

    etapas.append({
        "etapa": "2. Preprocesamiento",
        "detalle": f"Muestra balanceada: {len(positivos)} fallecidos + {len(negativos)} vivos.",
    })

    # ---------- ETAPA 3: TRANSFORMACION ----------
    print("[3/5] Transformacion: vectorizando texto con TF-IDF...")
    vectorizer = TfidfVectorizer(max_features=1500, lowercase=True)
    X = vectorizer.fit_transform(textos)
    y = etiquetas
    nombres = vectorizer.get_feature_names_out()
    etapas.append({
        "etapa": "3. Transformacion",
        "detalle": f"Texto convertido a {X.shape[1]} caracteristicas TF-IDF.",
    })

    # ---------- ETAPA 4: MINERIA DE DATOS ----------
    print("[4/5] Mineria: entrenando Arbol de Decision...")
    X_train, X_test, y_train, y_test = train_test_split(
        X, y, test_size=0.2, random_state=42, stratify=y
    )
    modelo = DecisionTreeClassifier(max_depth=8, random_state=42)
    modelo.fit(X_train, y_train)
    etapas.append({
        "etapa": "4. Mineria de datos",
        "detalle": "Arbol de Decision (profundidad maxima 8) entrenado.",
    })

    # ---------- ETAPA 5: INTERPRETACION ----------
    print("[5/5] Interpretacion: evaluando y extrayendo conocimiento...")
    y_pred = modelo.predict(X_test)
    exactitud = round(accuracy_score(y_test, y_pred) * 100, 2)
    f1 = round(f1_score(y_test, y_pred, zero_division=0) * 100, 2)

    # Conocimiento descubierto: palabras mas informativas del arbol.
    importancias = modelo.feature_importances_
    indices = importancias.argsort()[::-1][:10]
    palabras_clave = [
        {"palabra": str(nombres[i]), "peso": round(float(importancias[i]), 4)}
        for i in indices if importancias[i] > 0
    ]
    etapas.append({
        "etapa": "5. Interpretacion",
        "detalle": f"Exactitud {exactitud}% en el conjunto de prueba.",
    })

    resultado = {
        "modelo": "KDD - Arbol de Decision",
        "registros_analizados": len(textos),
        "exactitud": exactitud,
        "f1": f1,
        "etapas": etapas,
        "palabras_clave": palabras_clave,
    }

    # ensure_ascii=True (por defecto): escapa acentos como ó para que la
    # salida sea ASCII puro y PHP/json_decode la lea sin corromperse en Windows.
    print(json.dumps(resultado))


if __name__ == "__main__":
    try:
        main()
    except Exception as err:
        print(json.dumps({"error": str(err)}))
        sys.exit(1)
