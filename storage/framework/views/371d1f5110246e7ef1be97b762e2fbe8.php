

<?php $__env->startSection('content'); ?>

<div class="container">

    <h2>Regresión Lineal</h2>

    <div class="row mt-4">

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h4><?php echo e($total); ?></h4>
                    <p>Total de Registros</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-success">
                <div class="card-body">
                    <h4><?php echo e($vivieron); ?></h4>
                    <p>Vivieron</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-danger">
                <div class="card-body">
                    <h4><?php echo e($murieron); ?></h4>
                    <p>Murieron</p>
                </div>
            </div>
        </div>

    </div>

    <div class="mt-4">

        <button class="btn btn-primary" disabled>
            Ejecutar Regresión Lineal
        </button>

        <p class="mt-3 text-muted">
            Algoritmo aún no implementado.
        </p>

    </div>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Cerea\Documents\Tareas\Septimo Semestre\mineria de datos\ProyectoFinal\Proyecto Mineria\resources\views/algorithms/linear.blade.php ENDPATH**/ ?>