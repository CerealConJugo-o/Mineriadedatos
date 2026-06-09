

<?php $__env->startSection('content'); ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    body {
        background: #F8FAFC;
    }

    .dashboard-title {
        color: #0F172A;
        font-weight: 800;
    }

    .dashboard-subtitle {
        color: #64748B;
        font-size: 14px;
    }

    .stat-card {
        border-radius: 18px;
        overflow: hidden;
    }

    .stat-card h6 {
        opacity: 0.85;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-card h2 {
        font-weight: 800;
    }

    .custom-card {
        border-radius: 18px;
        overflow: hidden;
    }

    .custom-header-dark {
        background: #0F172A;
        color: white;
        font-weight: 600;
    }

    .custom-header-accent {
        background: #14B8A6;
        color: white;
        font-weight: 600;
    }

    .accent-badge {
        background: #14B8A6;
        color: white;
        border-radius: 12px;
        padding: 6px 10px;
    }

    .dark-badge {
        background: #0F172A;
        color: white;
        border-radius: 12px;
        padding: 6px 10px;
    }

    .soft-text {
        color: #64748B;
    }
</style>

<div class="container-fluid">

    <div class="mb-4">
        <h2 class="dashboard-title mb-1">
            Panel de control - Minería de Datos
        </h2>
        <p class="dashboard-subtitle">
            Monitoreo general de registros, ventas, datasets y algoritmos del sistema.
        </p>
    </div>

    <div class="row mb-4">

        <div class="col-md-3 mb-3">
            <div class="card shadow border-0 text-white stat-card"
                 style="background:#0F172A;">
                <div class="card-body">
                    <h6>Registros COVID</h6>
                    <h2><?php echo e(number_format($totalRegistros)); ?></h2>
                    <small>Tabla registro_a</small>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card shadow border-0 text-white stat-card"
                 style="background:#14B8A6;">
                <div class="card-body">
                    <h6>Ventas registradas</h6>
                    <h2><?php echo e(number_format($ventas)); ?></h2>
                    <small>Total: $<?php echo e(number_format($totalVentas, 2)); ?></small>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card shadow border-0 text-white stat-card"
                 style="background:#0F172A;">
                <div class="card-body">
                    <h6>Fallecidos detectados</h6>
                    <h2><?php echo e(number_format($fallecidos)); ?></h2>
                    <small>Mortalidad = 1</small>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card shadow border-0 text-white stat-card"
                 style="background:#14B8A6;">
                <div class="card-body">
                    <h6>Datasets cargados</h6>
                    <h2><?php echo e(number_format($datasets)); ?></h2>
                    <small>Archivos CSV disponibles</small>
                </div>
            </div>
        </div>

    </div>

    <div class="row mb-4">

        <div class="col-md-8 mb-3">
            <div class="card shadow border-0 custom-card">
                <div class="card-header custom-header-dark">
                    Rendimiento mensual de ventas
                </div>

                <div class="card-body">
                    <canvas id="ventasChart" height="120"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card shadow border-0 custom-card">
                <div class="card-header custom-header-accent">
                    Procesos del sistema
                </div>

                <div class="card-body">
                    <?php $__currentLoopData = $procesos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $proceso): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                            <span><?php echo e($proceso['nombre']); ?></span>

                            <?php if($proceso['estado'] === 'Listo' || $proceso['estado'] === 'Ejecutado'): ?>
                                <span class="accent-badge">
                                    <?php echo e($proceso['estado']); ?>

                                </span>
                            <?php else: ?>
                                <span class="dark-badge">
                                    <?php echo e($proceso['estado']); ?>

                                </span>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>

    </div>

    <div class="row">

        <div class="col-md-6 mb-3">
            <div class="card shadow border-0 custom-card">
                <div class="card-header custom-header-accent">
                    Random Forest - Mortalidad
                </div>

                <div class="card-body">
                    <canvas id="mortalidadChart" height="120"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <div class="card shadow border-0 custom-card">
                <div class="card-header custom-header-dark">
                    Estado general
                </div>

                <div class="card-body">
                    <p>
                        Laravel en Render:
                        <span class="accent-badge">Activo</span>
                    </p>

                    <p>
                        PostgreSQL:
                        <span class="accent-badge">Conectado</span>
                    </p>

                    <p>
                        Python:
                        <span class="accent-badge">Disponible</span>
                    </p>

                    <p>
                        Minería de datos:
                        <span class="dark-badge">Operativa</span>
                    </p>

                    <p class="soft-text mt-3 mb-0">
                        El sistema se encuentra desplegado en la nube y conectado a una base de datos PostgreSQL.
                    </p>
                </div>
            </div>
        </div>

    </div>

</div>

<script>
    const labelsVentas = <?php echo json_encode($labelsVentas); ?>;
    const dataVentas = <?php echo json_encode($dataVentas); ?>;

    new Chart(document.getElementById('ventasChart'), {
        type: 'line',
        data: {
            labels: labelsVentas,
            datasets: [{
                label: 'Ventas por mes',
                data: dataVentas,
                fill: true,
                tension: 0.3,
                borderColor: '#14B8A6',
                backgroundColor: 'rgba(20,184,166,0.15)',
                borderWidth: 3,
                pointBackgroundColor: '#0F172A',
                pointBorderColor: '#14B8A6',
                pointRadius: 4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    labels: {
                        color: '#0F172A'
                    }
                }
            },
            scales: {
                x: {
                    ticks: {
                        color: '#64748B'
                    }
                },
                y: {
                    ticks: {
                        color: '#64748B'
                    }
                }
            }
        }
    });

    new Chart(document.getElementById('mortalidadChart'), {
        type: 'doughnut',
        data: {
            labels: ['Falleció', 'Vivió'],
            datasets: [{
                data: [
                    <?php echo e($fallecidos); ?>,
                    <?php echo e($vivos); ?>

                ],
                backgroundColor: [
                    '#0F172A',
                    '#14B8A6'
                ],
                borderColor: '#ffffff',
                borderWidth: 3
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    labels: {
                        color: '#0F172A'
                    }
                }
            }
        }
    });
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Cerea\Documents\Tareas\Septimo Semestre\mineria de datos\ProyectoFinal\Proyecto Mineria\resources\views/dashboard.blade.php ENDPATH**/ ?>