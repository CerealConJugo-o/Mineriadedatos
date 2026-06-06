

<?php $__env->startSection('content'); ?>

<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>🧠 Redes Neuronales</h2>

        <button class="btn btn-secondary" disabled>
            Ejecutar Algoritmo
        </button>
    </div>

    <div class="row">

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h1><?php echo e($total); ?></h1>
                    <p>Total de Registros</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-success">
                <div class="card-body text-center">
                    <h1><?php echo e($vivieron); ?></h1>
                    <p>Vivieron</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-danger">
                <div class="card-body text-center">
                    <h1><?php echo e($murieron); ?></h1>
                    <p>Murieron</p>
                </div>
            </div>
        </div>

    </div>

    <div class="card mt-4 shadow-sm">

        <div class="card-header">
            Descripción
        </div>

        <div class="card-body">

            <p>
                Este módulo permitirá ejecutar modelos de Redes Neuronales
                para analizar los registros de pacientes y realizar
                predicciones basadas en los datos almacenados.
            </p>

            <p>
                Actualmente el algoritmo aún no ha sido implementado.
            </p>

        </div>

    </div>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Cerea\Documents\Tareas\Septimo Semestre\mineria de datos\ProyectoFinal\Proyecto Mineria\resources\views/algorithms/neural.blade.php ENDPATH**/ ?>