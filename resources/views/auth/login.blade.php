<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión | OpticsSight</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background: #0f172a;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: white;
        }

        .login-card {
            width: 380px;
            padding: 30px;
            background: #1e293b;
            border-radius: 12px;
            box-shadow: 0px 4px 15px rgba(0,0,0,0.3);
        }

        .login-card input {
            background: #0f172a;
            border: none;
            color: white;
        }

        .login-card input:focus {
            border: 1px solid #3b82f6;
            box-shadow: none;
            /* Mantenemos el fondo oscuro al enfocar */
            background: #0f172a; 
            color: white;
        }

        .form-check-input {
            background-color: #0f172a;
            border-color: #475569;
        }
        .form-check-input:checked {
            background-color: #3b82f6;
            border-color: #3b82f6;
        }

        .btn-login {
            background: #3b82f6;
            border: none;
            color: white;
            font-weight: 500;
        }

        .btn-login:hover {
            background: #2563eb;
            color: white;
        }
    </style>
</head>
<body>

    <div class="login-card">

        <h3 class="text-center mb-4">
            <i class="bi bi-shield-lock"></i>
            Acceso al Sistema
        </h3>

        @if ($errors->any())
            <div class="alert alert-danger py-2">
                Credenciales incorrectas.
            </div>
        @endif

        <form action="/login" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required autocomplete="off" value="{{ old('email') }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Contraseña</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" name="remember" id="remember">
                <label class="form-check-label" for="remember">Recuérdame</label>
            </div>

            <button class="btn btn-login w-100 mt-2">
                <i class="bi bi-box-arrow-in-right"></i> Ingresar
            </button>

        </form>

    </div>

</body>
</html>