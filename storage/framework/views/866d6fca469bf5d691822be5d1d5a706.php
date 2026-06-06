

<?php $__env->startSection('content'); ?>

<div class="container-fluid">

    <h2 class="fw-bold mb-4">
        Regresión Lineal
    </h2>

    <?php if(session('error')): ?>
        <div class="alert alert-danger">
            <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>

    <div class="row">

        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white">
                    <h4 class="mb-0">Usar tabla de ventas</h4>
                </div>

                <div class="card-body">
                    <p>
                        Ejecuta la regresión lineal utilizando la tabla
                        <strong>ventas</strong> de MariaDB.
                    </p>

                    <p class="text-muted">
                        El sistema agrupa automáticamente las ventas por mes usando
                        las columnas <strong>fecha</strong> y <strong>total</strong>.
                    </p>

                    <form action="<?php echo e(route('linear.ventas')); ?>" method="POST">
                        <?php echo csrf_field(); ?>

                        <button class="btn btn-primary">
                            Ejecutar con tabla ventas
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white">
                    <h4 class="mb-0">Usar CSV cargado</h4>
                </div>

                <div class="card-body">
                    <p>
                        Ejecuta la regresión lineal utilizando un archivo CSV cargado.
                    </p>

                    <p class="text-muted">
                        El CSV debe tener encabezados y las columnas obligatorias:
                        <strong>fecha</strong> y <strong>total</strong>.
                    </p>

                    <form action="<?php echo e(route('linear.csv.run')); ?>" method="POST">
                        <?php echo csrf_field(); ?>

                        <div class="mb-3">
                            <label class="form-label">
                                Dataset CSV
                            </label>

                            <select name="dataset_id" class="form-control" required>
                                <option value="">Seleccione un CSV</option>

                                <?php $__currentLoopData = $datasets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dataset): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($dataset->id); ?>">
                                        <?php echo e($dataset->nombre); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <button class="btn btn-success">
                            Ejecutar con CSV
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Cerea\Documents\Tareas\Septimo Semestre\mineria de datos\ProyectoFinal\Proyecto Mineria\resources\views/analytics/index.blade.php ENDPATH**/ ?>