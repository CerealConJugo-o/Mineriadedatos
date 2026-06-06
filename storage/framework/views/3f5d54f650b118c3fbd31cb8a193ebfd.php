

<?php $__env->startSection('content'); ?>

<div class="container-fluid">

    <div class="card">

        <div class="card-header">
            <h4>Cargar Dataset</h4>
        </div>

        <div class="card-body">

            <form action="<?php echo e(route('datos.upload')); ?>"
                  method="POST"
                  enctype="multipart/form-data">

                <?php echo csrf_field(); ?>

                <div class="mb-3">

                    <label class="form-label">
                        Seleccione un archivo CSV
                    </label>

                    <input type="file"
                           class="form-control"
                           name="archivo"
                           accept=".csv">

                </div>

                <button type="submit"
                        class="btn btn-primary">
                    Cargar Archivo
                </button>

            </form>

            <hr>

            <h4>Datasets cargados</h4>

            <table class="table table-striped">

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Archivo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>

                <?php $__empty_1 = true; $__currentLoopData = $datasets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dataset): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                    <tr>

                        <td><?php echo e($dataset->id); ?></td>

                        <td><?php echo e($dataset->nombre); ?></td>

                        <td><?php echo e($dataset->archivo); ?></td>

                        <td>

                            <a href="<?php echo e(route('datos.show', $dataset->id)); ?>"
                               class="btn btn-primary btn-sm">

                                Ver Datos

                            </a>

                        </td>

                    </tr>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                    <tr>
                        <td colspan="4" class="text-center">
                            No hay datasets cargados.
                        </td>
                    </tr>

                <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Cerea\Documents\Tareas\Septimo Semestre\mineria de datos\ProyectoFinal\opticsSight\resources\views/datos/index.blade.php ENDPATH**/ ?>