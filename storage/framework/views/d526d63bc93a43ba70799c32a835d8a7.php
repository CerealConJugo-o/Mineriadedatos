

<?php $__env->startSection('content'); ?>

<div class="container-fluid">

    <h2 class="mb-4"><i class="bi bi-people-fill"></i> Gestión de Empleados</h2>

    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <div class="card shadow-sm p-4" style="border-radius: 15px;">

        <div class="d-flex justify-content-between align-items-center mb-4">
            
            <h4 class="mb-0">Lista de empleados</h4>

            <a href="<?php echo e(route('empleados.create')); ?>" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Agregar Empleado
            </a>
        </div>

        <!-- Buscador elegante -->
        <form action="<?php echo e(route('empleados.index')); ?>" method="GET" class="mb-4">
            <div class="input-group">
                <input type="text" name="search" class="form-control"
                       placeholder="Buscar por NSS, nombre o apellidos..."
                       value="<?php echo e($search ?? ''); ?>">

                <button class="btn btn-primary">
                    <i class="bi bi-search"></i>
                </button>

                <?php if(!empty($search)): ?>
                <a href="<?php echo e(route('empleados.index')); ?>" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i>
                </a>
                <?php endif; ?>
            </div>
        </form>

        <table class="table table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>NSS</th>
                    <th>Nombre Completo</th>
                    <th>Teléfono</th>
                    <th>Correo</th>
                    <th>Rol</th>
                    <th>Salario</th>
                    <th style="width: 130px;">Acciones</th>
                </tr>
            </thead>

            <tbody>

                <?php $__empty_1 = true; $__currentLoopData = $empleados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $empleado): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($empleado->nss); ?></td>
                    <td><?php echo e($empleado->nombre); ?> <?php echo e($empleado->apellido_p); ?> <?php echo e($empleado->apellido_m); ?></td>
                    <td><?php echo e($empleado->telefono_movil); ?></td>
                    <td><?php echo e($empleado->correo_nombre); ?></td>
                    <td><?php echo e($empleado->rol); ?></td>
                    <td>$<?php echo e(number_format($empleado->salario, 2)); ?></td>

                    <td>
                        <a href="<?php echo e(route('empleados.edit', $empleado->nss)); ?>"
                           class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil-square"></i>
                        </a>

                        <form action="<?php echo e(route('empleados.destroy', $empleado->nss)); ?>"
                              method="POST" class="d-inline">
                            <?php echo csrf_field(); ?> 
                            <?php echo method_field('DELETE'); ?>
                            <button class="btn btn-danger btn-sm">
                                <i class="bi bi-trash3"></i>
                            </button>
                        </form>
                    </td>
                </tr>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="7" class="text-center text-muted">
                        <i class="bi bi-search"></i> No se encontraron empleados.
                    </td>
                </tr>
                <?php endif; ?>

            </tbody>
        </table>

    </div>

</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Cerea\Documents\Tareas\Septimo Semestre\mineria de datos\ProyectoFinal\Proyecto Mineria\resources\views/empleados/index.blade.php ENDPATH**/ ?>