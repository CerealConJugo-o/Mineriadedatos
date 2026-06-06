

<?php $__env->startSection('content'); ?>

<div class="container-fluid">

    <h2 class="fw-bold mb-4">
        Regresión Lineal con CSV
    </h2>

    <?php if(session('error')): ?>
        <div class="alert alert-danger">
            <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>

    <div class="card shadow-sm">

        <div class="card-header bg-white">
            <h4 class="mb-0">
                Seleccionar columnas
            </h4>
        </div>

        <div class="card-body">

            <p>
                Dataset seleccionado:
                <strong><?php echo e($dataset->nombre); ?></strong>
            </p>

            <form action="<?php echo e(route('linear.csv.run')); ?>" method="POST">
                <?php echo csrf_field(); ?>

                <input type="hidden" name="dataset_id" value="<?php echo e($dataset->id); ?>">

                <div class="row">

                    <div class="col-md-6">
                        <label class="form-label">
                            Variable X
                        </label>

                        <select name="columna_x" class="form-control" required>
                            <option value="">Seleccione columna X</option>

                            <?php $__currentLoopData = $columnas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $columna): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($columna); ?>">
                                    <?php echo e($columna); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>

                        <small class="text-muted">
                            Ejemplo: mes, inversión, cantidad, periodo.
                        </small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            Variable Y
                        </label>

                        <select name="columna_y" class="form-control" required>
                            <option value="">Seleccione columna Y</option>

                            <?php $__currentLoopData = $columnas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $columna): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($columna); ?>">
                                    <?php echo e($columna); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>

                        <small class="text-muted">
                            Ejemplo: total, ventas, ingresos.
                        </small>
                    </div>

                </div>

                <button class="btn btn-primary mt-4">
                    Ejecutar Regresión Lineal
                </button>

                <a href="<?php echo e(route('linear.index')); ?>" class="btn btn-secondary mt-4">
                    Volver
                </a>

            </form>

        </div>

    </div>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Cerea\Documents\Tareas\Septimo Semestre\mineria de datos\ProyectoFinal\Proyecto Mineria\resources\views/analytics/csv.blade.php ENDPATH**/ ?>