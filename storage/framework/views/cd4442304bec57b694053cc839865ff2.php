<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mine Data - Panel Principal</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f6f9;
            overflow-x: hidden; 
        }

        /* --- SIDEBAR ESTRUCTURAL --- */
        .sidebar {
            width: 260px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background: #0f172a;
            color: #fff;
            display: flex;
            flex-direction: column;
            z-index: 1000;
        }

        .sidebar-header {
            padding: 1rem;
            flex-shrink: 0;
        }

        .sidebar-menu {
            flex-grow: 1;
            overflow-y: auto;
            padding-bottom: 20px;
        }

        /* Scrollbar Fino */
        .sidebar-menu::-webkit-scrollbar { width: 5px; }
        .sidebar-menu::-webkit-scrollbar-track { background: transparent; }
        .sidebar-menu::-webkit-scrollbar-thumb { background: #334155; border-radius: 4px; }
        .sidebar-menu::-webkit-scrollbar-thumb:hover { background: #475569; }

        .sidebar-footer {
            flex-shrink: 0;
            padding: 1rem;
            background: #0f172a;
            border-top: 1px solid #1e293b;
        }

        /* --- ENLACES --- */
        .sidebar a {
            display: block;
            padding: 12px 20px;
            margin: 4px 10px;
            color: #cbd5e1;
            text-decoration: none;
            font-size: 15px;
            border-radius: 6px;
            transition: 0.2s;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sidebar a:hover {
            background: #1e293b;
            color: #fff;
            transform: translateX(5px);
        }

        .sidebar .active {
            background: #2563eb;
            color: #fff;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        /* --- BOTÓN LOGOUT --- */
        .logout-btn {
            width: 100%;
            padding: 12px 20px;
            background: #dc2626;
            color: white;
            text-align: left;
            border: none;
            border-radius: 6px;
            font-size: 15px;
            transition: 0.2s;
        }
        .logout-btn:hover { background: #b91c1c; }
        
        .menu-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            color: #64748b;
            margin: 15px 20px 5px;
            font-weight: bold;
            letter-spacing: 0.05em;
        }

        /* --- CONTENIDO --- */
        .content {
            margin-left: 260px;
            padding: 25px;
            min-height: 100vh;
            transition: margin-left 0.3s;
        }

        /* --- RESPONSIVE --- */
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); transition: transform 0.3s ease-in-out; }
            .sidebar.show { transform: translateX(0); }
            .content { margin-left: 0; }
        }

        nav[role="navigation"] p, nav[role="navigation"] svg { display: none !important; }
        nav[role="navigation"] > div:last-child { display: flex; justify-content: center; width: 100%; }
    </style>
</head>
<body>

<?php if(auth()->guard()->check()): ?>
    <?php endif; ?>

<div class="sidebar">

    <div class="sidebar-header text-center">
        <a href="<?php echo e(route('dashboard')); ?>">
            <img src="<?php echo e(asset('MineData.jpg')); ?>" alt="Logo" class="img-fluid p-1 bg-white rounded" style="max-height: 50px;">
        </a>
    </div>

    <div class="sidebar-menu">
        
     <div class="menu-label">Principal</div>

<a href="<?php echo e(route('dashboard')); ?>"
   class="<?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>">
    <i class="bi bi-person-badge-fill me-2"></i>
    Panel de Control
</a>

<div class="menu-label">Algoritmos de datos</div>

<a href="<?php echo e(route('linear.index')); ?>"
   class="<?php echo e(request()->routeIs('linear.*') ? 'active' : ''); ?>">
    <i class="bi bi-graph-up-arrow me-2"></i>
    Regresión Lineal
</a>

<a href="<?php echo e(route('kdd.index')); ?>"
   class="<?php echo e(request()->routeIs('kdd.index') ? 'active' : ''); ?>">
    <i class="bi bi-diagram-3-fill me-2"></i>
    KDD
</a>

<a href="<?php echo e(route('randomforest.index')); ?>"
   class="<?php echo e(request()->routeIs('randomforest.index') ? 'active' : ''); ?>">
    <i class="bi bi-diagram-3-fill me-2"></i>
    Random Forest
</a>

<a href="<?php echo e(route('neural.index')); ?>"
   class="<?php echo e(request()->routeIs('neural.index') ? 'active' : ''); ?>">
    <i class="bi bi-diagram-3-fill me-2"></i>
    Neuronal
</a>

<div class="menu-label">Datos</div>

<a href="<?php echo e(route('datos.index')); ?>"
   class="<?php echo e(request()->routeIs('datos.index') ? 'active' : ''); ?>">
    <i class="bi bi-database-fill me-2"></i>
    Cargar y ver datos
</a>
       
<div class="menu-label">Tablas</div>
<a href="<?php echo e(route('ventas.index')); ?>"
   class="<?php echo e(request()->routeIs('ventas.*') ? 'active' : ''); ?>">
    <i class="bi bi-cash-coin me-2"></i>
    Ventas
</a>

        </form>
    </div>

</div>

<div class="content">
    <?php echo $__env->yieldContent('content'); ?>
</div>

<div id="flash-data" 
     data-success="<?php echo e(session('success')); ?>" 
     data-error="<?php echo e(session('error')); ?>" 
     data-errors="<?php echo e(json_encode($errors->all())); ?>"
     style="display: none;">
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // 1. Obtenemos el elemento puente
        const flashDiv = document.getElementById('flash-data');

        if (!flashDiv) return; // Seguridad por si acaso

        // 2. Leemos los datos (JS Puro)
        const msgSuccess = flashDiv.getAttribute('data-success');
        const msgError = flashDiv.getAttribute('data-error');
        
        let validationErrors = [];
        try {
            const rawErrors = flashDiv.getAttribute('data-errors');
            if (rawErrors) {
                validationErrors = JSON.parse(rawErrors);
            }
        } catch (e) {
            console.error("Error al leer validaciones", e);
        }

        // 3. Mostramos las alertas
        
        // Alerta de Éxito
        if (msgSuccess) {
            Swal.fire({
                icon: 'success', title: '¡Éxito!', text: msgSuccess,
                timer: 3000, timerProgressBar: true, showConfirmButton: false, toast: true, position: 'top-end', background: '#fff', iconColor: '#28a745'
            });
        }

        // Alerta de Error General
        if (msgError) {
            Swal.fire({
                icon: 'error', title: 'Oops...', text: msgError, confirmButtonText: 'Entendido', confirmButtonColor: '#dc3545'
            });
        }

        // Alerta de Errores de Formulario
        if (validationErrors && validationErrors.length > 0) {
            let listHtml = '<ul style="text-align: left;">';
            validationErrors.forEach(function(err) {
                listHtml += '<li>' + err + '</li>';
            });
            listHtml += '</ul>';

            Swal.fire({
                icon: 'warning', title: 'Revisa el formulario:', html: listHtml, confirmButtonText: 'Corregir', confirmButtonColor: '#f39c12'
            });
        }
    });
</script>

<?php echo $__env->yieldContent('scripts'); ?>

</body>
</html><?php /**PATH C:\Users\Cerea\Documents\Tareas\Septimo Semestre\mineria de datos\ProyectoFinal\Proyecto Mineria\resources\views/layouts/app.blade.php ENDPATH**/ ?>