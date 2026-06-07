import os
import psycopg2
from psycopg2.extras import RealDictCursor

print("INICIANDO PROCESO")

# 1. Configuración de conexión a PostgreSQL usando variables de Render
config = {
    "host": os.getenv("dpg-d8ibcreq1p3s73ehs3jg-a.oregon-postgres.render.com"),
    "database": os.getenv("covid_j5k2"),
    "user": os.getenv("render"),
    "password": os.getenv("VgW6Q0UOE1VQEnurU2nIhSPpnhct8M4U"),
    "port": os.getenv("DB_PORT", "5432")
}

# 2. Lista de palabras clave para identificar mortalidad
palabras_clave = [
    "falleció",
    "fallece",
    "muerto",
    "defunción",
    "fallecimiento",
    "óbito"
]

try:
    conn = psycopg2.connect(**config)

    cursor = conn.cursor(cursor_factory=RealDictCursor)

    print("Extrayendo datos de la tabla registro_a...")

    cursor.execute("""
        SELECT nuevos, resclin
        FROM registro_a
    """)

    filas = cursor.fetchall()

    print(f"Procesando {len(filas)} registros. Por favor espera...")

    for fila in filas:
        id_paciente = fila["nuevos"]
        texto = fila["resclin"]

        mortalidad_detectada = 0

        if texto:
            texto = texto.lower()

            for palabra in palabras_clave:
                if palabra in texto:
                    mortalidad_detectada = 1
                    break

        cursor.execute(
            """
            UPDATE registro_a
            SET mortalidad = %s
            WHERE nuevos = %s
            """,
            (mortalidad_detectada, id_paciente)
        )

    conn.commit()

    print("¡Proceso completado! Ya todos los expedientes tienen su sello de mortalidad.")

except Exception as err:
    print(f"Error de base de datos: {err}")

finally:
    if "conn" in locals():
        cursor.close()
        conn.close()
        print("Conexión cerrada correctamente.")
