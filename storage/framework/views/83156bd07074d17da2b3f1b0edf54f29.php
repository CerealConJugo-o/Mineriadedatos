

<?php $__env->startSection('content'); ?>

<div class="container-fluid">

    <h2 class="fw-bold mb-4">
        Editar venta #<?php echo e($venta->folio); ?>

    </h2>

    <?php if($errors->any()): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="card shadow border-0">
        <div class="card-body">

            <form action="<?php echo e(route('ventas.update', $venta->folio)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <div class="row">

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Folio</label>
                        <input type="number"
                               class="form-control"
                               value="<?php echo e($venta->folio); ?>"
                               disabled>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Fecha</label>
                        <input type="date"
                               name="fecha"
                               class="form-control"
                               value="<?php echo e(old('fecha', $venta->fecha)); ?>"
                               required>
                    </div>


                    <div class="col-md-8 mb-3">
                        <label class="form-label">Servicios</label>
                        <input type="text"
                               name="servicios"
                               class="form-control"
                               value="<?php echo e(old('servicios', $venta->servicios)); ?>">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Total</label>
                        <input type="number"
                               step="0.01"
                               name="total"
                               class="form-control"
                               value="<?php echo e(old('total', $venta->total)); ?>"
                               required>
                    </div>

                </div>

                <div class="text-end">
                    <a href="<?php echo e(route('ventas.index')); ?>" class="btn btn-secondary">
                        Cancelar
                    </a>

                    <button class="btn btn-primary">
                        Actualizar venta
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Cerea\Documents\Tareas\Septimo Semestre\mineria de datos\ProyectoFinal\Proyecto Mineria\resources\views/ventas/edit.blade.php ENDPATH**/ ?>