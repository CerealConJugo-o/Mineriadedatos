

<?php $__env->startSection('content'); ?>


<div class="container-fluid">

    <h2 class="mb-4">
        Random Forest
    </h2>

    <?php if(session('success')): ?>
    <div class="alert alert-success">
        <?php echo e(session('success')); ?>

    </div>
<?php endif; ?>

<?php if(session('error')): ?>
    <div class="alert alert-danger">
        <?php echo e(session('error')); ?>

    </div>
<?php endif; ?>

    <div class="row">

        <div class="col-md-4">

            <div class="card shadow">

                <div class="card-body">

                    <h5>
                        Registros Totales
                    </h5>

                    <h2>
                        <?php echo e(number_format($total)); ?>

                    </h2>

                </div>

            </div>

        </div>

        <div class="col-md-4">

            <div class="card shadow">

                <div class="card-body">

                    <h5>
                        Fallecio = 1
                    </h5>

                    <h2>
                        <?php echo e(number_format($positivos)); ?>

                    </h2>

                </div>

            </div>

        </div>

        <div class="col-md-4">

            <div class="card shadow">

                <div class="card-body">

                    <h5>
                        Vivio = 0
                    </h5>

                    <h2>
                        <?php echo e(number_format($negativos)); ?>

                    </h2>

                </div>

            </div>

        </div>

    </div>

    <div class="card mt-4 shadow">

        <div class="card-body">

            <h4>
                Ejecutar Modelo
            </h4>

            <p>
                Analiza la columna resclin y
                actualiza mortalidad.
            </p>

            <form
                action="<?php echo e(route('randomforest.run')); ?>"
                method="POST">

                <?php echo csrf_field(); ?>

                <button
                    class="btn btn-success">

                    Ejecutar Random Forest

                </button>

            </form>

            <a
                href="<?php echo e(route('randomforest.results')); ?>"
                class="btn btn-primary mt-3">

                Ver Resultados

            </a>

        </div>

    </div>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Cerea\Documents\Tareas\Septimo Semestre\mineria de datos\ProyectoFinal\Proyecto Mineria\resources\views/algorithms/randomforest.blade.php ENDPATH**/ ?>