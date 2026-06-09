

<?php $__env->startSection('content'); ?>

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">
            Ventas
        </h2>

        <a href="<?php echo e(route('ventas.create')); ?>" class="btn btn-success">
            Agregar venta
        </a>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <div class="card shadow border-0">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Folio</th>
                            <th>Fecha</th>
                            <th>Servicios</th>
                            <th>Total</th>
                            <th width="180">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $ventas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $venta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($venta->folio); ?></td>
                                <td><?php echo e($venta->fecha); ?></td>
                                <td><?php echo e($venta->servicios ?? 'Sin servicio'); ?></td>
                                <td>$<?php echo e(number_format($venta->total, 2)); ?></td>
                                <td>
                                    <div class="d-flex gap-2">

                                        <a href="<?php echo e(route('ventas.edit', $venta->folio)); ?>"
                                           class="btn btn-primary btn-sm">
                                            Editar
                                        </a>

                                        <form action="<?php echo e(route('ventas.destroy', $venta->folio)); ?>"
                                              method="POST"
                                              onsubmit="return confirm('¿Seguro que deseas eliminar esta venta?')">

                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>

                                            <button class="btn btn-danger btn-sm">
                                                Borrar
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted">
                                    No hay ventas registradas.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php echo e($ventas->links()); ?>


        </div>
    </div>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Cerea\Documents\Tareas\Septimo Semestre\mineria de datos\ProyectoFinal\Proyecto Mineria\resources\views/ventas/index.blade.php ENDPATH**/ ?>