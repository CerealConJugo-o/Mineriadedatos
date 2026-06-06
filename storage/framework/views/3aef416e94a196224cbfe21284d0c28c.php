

<?php $__env->startSection('content'); ?>

<div class="container-fluid">

    <h2>
        Resultados Random Forest
    </h2>

    <div class="card">

        <div class="card-body">

            <table class="table table-striped">

                <thead>

                    <tr>

                        <th>ID</th>
                        <th>Mortalidad</th>

                    </tr>

                </thead>

                <tbody>

                <?php $__currentLoopData = $datos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fila): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                    <tr>

                        <td>
                            <?php echo e($fila->nuevos); ?>

                        </td>

                        <td>

                            <?php if($fila->mortalidad == 1): ?>

                                <span class="badge bg-danger">
                                    Detectado
                                </span>

                            <?php else: ?>

                                <span class="badge bg-success">
                                    No Detectado
                                </span>

                            <?php endif; ?>

                        </td>

                    </tr>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                </tbody>

            </table>

            <?php echo e($datos->links()); ?>


        </div>

    </div>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Cerea\Documents\Tareas\Septimo Semestre\mineria de datos\ProyectoFinal\Proyecto Mineria\resources\views/algorithms/rf_results.blade.php ENDPATH**/ ?>