print("INICIANDO PROCESO")
import mysql.connector  # Traemos la herramienta para abrir archiveros digitales (Bases de Datos).

# 1. Configuración de la conexión a MySQL
# Este es el "post-it" con la dirección de la oficina, el nombre del guardia y la llave.
config = {
    'user': 'root',      # El nombre con el que te identificas.
    'password': '123',   # Tu clave secreta.
    'host': '127.0.0.1', # La dirección de la oficina (en este caso, tu propia compu).
    'database': 'covid'  # El nombre del cuarto donde están los expedientes.
}

# 2. Lista de palabras clave para identificar mortalidad
# Son las "palabras de alerta" que el sistema debe buscar con lupa.
palabras_clave = [
    "falleció",
    "fallece",
    "muerto",
    "defunción",
    "fallecimiento",
    "óbito"
]

try:
    # Establecer conexión: Metemos la llave y entramos a la oficina.
    conn = mysql.connector.connect(**config)

    # El 'cursor' es como nuestro asistente personal que va y viene trayendo papeles.
    cursor = conn.cursor(dictionary=True)

    # Extraer los resúmenes: Le pedimos al asistente que traiga todos los ID y las notas médicas.
    print("Extrayendo datos de la tabla registro_A...")

    cursor.execute(
        "SELECT nuevos, resclin FROM registro_A"
    )

    # Ponemos todas las hojas de los pacientes sobre nuestra mesa de trabajo.
    filas = cursor.fetchall()

    # Mensaje de ánimo porque sabemos que son muchos registros.
    print(f"Procesando {len(filas)} registros. Por favor espera...")

    # Empezamos a revisar cada hoja una por una.
    for fila in filas:

        # Anotamos el ID del paciente actual.
        id_paciente = fila['nuevos']

        # Leemos lo que escribió el doctor en su nota.
        texto = fila['resclin']

        # Al inicio, asumimos que NO falleció.
        mortalidad_detectada = 0

        # Si la nota del doctor no está vacía, la analizamos.
        if texto:

            # Convertimos todo a minúsculas para que no se escape nada.
            texto = texto.lower()

            # Revisamos cada palabra clave de nuestra lista.
            for palabra in palabras_clave:

                # Si encontramos una coincidencia...
                if palabra in texto:

                    # ¡Alerta! Detectamos un posible fallecimiento.
                    mortalidad_detectada = 1

                    # Como ya encontramos lo que buscábamos,
                    # dejamos de revisar esta nota.
                    break

        # 3. Actualizar la base de datos:
        # Le pedimos al asistente que selle la hoja original.
        sql_update = """
            UPDATE registro_A
            SET mortalidad = %s
            WHERE nuevos = %s
        """

        cursor.execute(
            sql_update,
            (mortalidad_detectada, id_paciente)
        )

    # Guardar cambios: Es el equivalente a darle al botón de "Guardar Archivo".
    conn.commit()

    print("¡Proceso completado! Ya todos los expedientes tienen su sello de mortalidad.")

except mysql.connector.Error as err:

    # Si tropezamos al entrar a la oficina o se nos pierde la llave.
    print(f"Error de base de datos: {err}")

finally:

    # Al final, sin importar qué pase, cerramos el archivero y devolvemos las llaves.
    if 'conn' in locals() and conn.is_connected():

        cursor.close()
        conn.close()

        print("Conexión cerrada correctamente.")
