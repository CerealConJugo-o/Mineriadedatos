

<?php $__env->startSection('content'); ?>
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 text-dark fw-bold">
            Dashboard de Minería de Datos
        </h2>
    </div>

    <div class="row g-4">

        <!-- DATOS CARGADOS -->
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-database-fill me-2"></i>
                        Datos cargados
                    </h5>
                </div>
                <div class="card-body">

                    <h1 class="display-4 fw-bold text-primary">0</h1>

                    <p class="text-muted mb-0">
                        Registros actualmente disponibles para análisis.
                    </p>

                    <hr>

                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            Dataset principal: Sin cargar
                        </li>
                        <li class="list-group-item">
                            Última actualización: --
                        </li>
                    </ul>

                </div>
            </div>
        </div>

        <!-- ÚLTIMOS PROCESOS -->
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-cpu-fill me-2"></i>
                        Últimos procesos realizados
                    </h5>
                </div>
                <div class="card-body">

                    <div class="alert alert-light border">
                        No se han ejecutado procesos.
                    </div>

                    <ul class="list-group">
                        <li class="list-group-item">
                            Regresión Lineal
                            <span class="badge bg-secondary float-end">
                                Pendiente
                            </span>
                        </li>

                        <li class="list-group-item">
                            KDD
                            <span class="badge bg-secondary float-end">
                                Pendiente
                            </span>
                        </li>

                        <li class="list-group-item">
                            Random Forest
                            <span class="badge bg-secondary float-end">
                                Pendiente
                            </span>
                        </li>
                    </ul>

                </div>
            </div>
        </div>

        <!-- RENDIMIENTO -->
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-graph-up-arrow me-2"></i>
                        Rendimiento de los modelos
                    </h5>
                </div>

                <div class="card-body">

                    <div class="row text-center">

                        <div class="col-md-3">
                            <h6>Regresión Lineal</h6>
                            <div class="display-6 text-primary">
                                --
                            </div>
                            <small class="text-muted">
                                Precisión
                            </small>
                        </div>

                        <div class="col-md-3">
                            <h6>Random Forest</h6>
                            <div class="display-6 text-success">
                                --
                            </div>
                            <small class="text-muted">
                                Precisión
                            </small>
                        </div>

                        <div class="col-md-3">
                            <h6>K-Means</h6>
                            <div class="display-6 text-warning">
                                --
                            </div>
                            <small class="text-muted">
                                Clusters
                            </small>
                        </div>

                        <div class="col-md-3">
                            <h6>Red Neuronal</h6>
                            <div class="display-6 text-danger">
                                --
                            </div>
                            <small class="text-muted">
                                Precisión
                            </small>
                        </div>

                    </div>

                    <hr>

                    <div class="text-center text-muted py-5">
                        <i class="bi bi-bar-chart-line"
                           style="font-size: 5rem;"></i>

                        <p class="mt-3">
                            Aquí se mostrará la gráfica comparativa de los modelos.
                        </p>
                    </div>

                </div>
            </div>
        </div>

    </div>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Cerea\Documents\Tareas\Septimo Semestre\mineria de datos\ProyectoFinal\Proyecto Mineria\resources\views/dashboard.blade.php ENDPATH**/ ?>