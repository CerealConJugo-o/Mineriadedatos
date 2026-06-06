

<?php $__env->startSection('content'); ?>

<div class="container-fluid">

    <h3 class="mb-4">
        Dataset: <?php echo e($dataset->nombre); ?>

    </h3>

    <div class="card">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-striped">

                    <?php $__currentLoopData = $filas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fila): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                        <tr>

                            <?php $__currentLoopData = $fila; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $celda): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                <td>
                                    <?php echo e($celda); ?>

                                </td>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        </tr>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                </table>

            </div>

        </div>

    </div>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Cerea\Documents\Tareas\Septimo Semestre\mineria de datos\ProyectoFinal\opticsSight\resources\views/datos/show.blade.php ENDPATH**/ ?>