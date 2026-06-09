

<?php $__env->startSection('content'); ?>

<?php
    $colorClase = $colorEstado === 'red'
        ? 'border-red-500'
        : ($colorEstado === 'yellow' ? 'border-yellow-500' : 'border-green-500');

    $badgeClase = $colorEstado === 'red'
        ? 'bg-red-100'
        : ($colorEstado === 'yellow' ? 'bg-yellow-100' : 'bg-green-100');
?>

<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Regresión Lineal - Predicción de Ventas</h2>

        <button onclick="generarPDF()" class="btn btn-danger">
            Descargar PDF
        </button>
    </div>

    <div id="contenido-pdf" class="bg-gray-100 p-4 rounded">

        <div class="mb-8 text-center">
            <h2 class="text-3xl font-bold text-gray-800">
                Análisis Integral de Ventas
            </h2>

            <p class="text-gray-600">
                Reporte generado con Regresión Lineal en Python.
            </p>

            <p class="text-xs text-gray-400 mt-1">
                Generado el: <?php echo e(date('d/m/Y H:i')); ?>

            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

            <div class="bg-white rounded-xl shadow-lg p-6 border-t-4 border-blue-500">
                <h3 class="text-xl font-bold text-gray-800 mb-2">
                    1. Analítica Descriptiva
                </h3>

                <p class="text-gray-600 text-sm mb-4">
                    Comportamiento real de las ventas agrupadas por mes.
                </p>

                <div class="grid grid-cols-2 gap-4 text-center">

                    <div class="bg-gray-50 p-3 rounded">
                        <p class="text-gray-500 text-xs uppercase">
                            Promedio Mensual
                        </p>

                        <p class="text-xl font-bold text-gray-700">
                            $<?php echo e(number_format($promedioVentas, 2)); ?>

                        </p>
                    </div>

                    <div class="bg-gray-50 p-3 rounded">
                        <p class="text-gray-500 text-xs uppercase">
                            Último Dato Real
                        </p>

                        <p class="text-xl font-bold text-gray-700">
                            $<?php echo e(number_format($data[count($data) - 2], 2)); ?>

                        </p>
                    </div>

                </div>

                <div class="mt-4 h-40">
                    <canvas id="descriptiveChart"></canvas>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 border-t-4 border-purple-500">
                <h3 class="text-xl font-bold text-gray-800 mb-2">
                    2. Analítica Diagnóstica
                </h3>

                <p class="text-gray-600 text-sm mb-4">
                    Interpretación del comportamiento de ventas.
                </p>

                <div class="bg-purple-50 p-4 rounded-lg border border-purple-100">
                    <p class="italic text-purple-900">
                        "<?php echo e($diagnostico); ?>"
                    </p>
                </div>

                <div class="mt-4">
                    <p class="text-xs text-gray-500 uppercase font-bold mb-2">
                        Indicadores Clave:
                    </p>

                    <ul class="list-disc list-inside text-sm text-gray-700 space-y-1">
                        <li>
                            Tendencia detectada:
                            <strong><?php echo e($prediccion['tendencia']); ?></strong>
                        </li>

                        <li>
                            Estabilidad de datos:
                            <strong><?php echo e(count($labels) > 6 ? 'Alta' : 'Baja'); ?></strong>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 border-t-4 border-indigo-500">
                <h3 class="text-xl font-bold text-gray-800 mb-4">
                    3. Analítica Predictiva
                </h3>

                <div class="text-center py-6 bg-indigo-50 rounded-lg mb-4">
                    <p class="text-sm text-indigo-600 uppercase tracking-wide">
                        Proyección Mes <?php echo e($prediccion['mes_predicho']); ?>

                    </p>

                    <p class="text-4xl font-extrabold text-indigo-900 mt-2">
                        $<?php echo e(number_format($prediccion['venta_estimada'], 2)); ?>

                    </p>
                </div>

                <p class="text-xs text-gray-500 text-center">
                    Modelo: Regresión Lineal con Python y scikit-learn
                </p>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 border-t-4 <?php echo e($colorClase); ?>">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-xl font-bold text-gray-800">
                        4. Analítica Prescriptiva
                    </h3>

                    <span class="<?php echo e($badgeClase); ?> text-gray-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                        Recomendación IA
                    </span>
                </div>

                <p class="text-gray-600 text-sm mb-4">
                    Plan de acción sugerido.
                </p>

                <div class="space-y-3">
                    <?php $__currentLoopData = $prescripcion; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tipo => $accion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex items-start">
                            <div class="flex-shrink-0 h-6 w-6 rounded-full bg-gray-200 flex items-center justify-center text-xs font-bold text-gray-600 mr-3">
                                <?php echo e(substr($tipo, 0, 1)); ?>

                            </div>

                            <div>
                                <p class="text-xs font-bold text-gray-500 uppercase">
                                    <?php echo e($tipo); ?>

                                </p>

                                <p class="text-sm text-gray-800">
                                    <?php echo e($accion); ?>

                                </p>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

        </div>

        <div class="mt-8 bg-white p-6 rounded-xl shadow-lg">
            <h3 class="text-lg font-bold text-gray-800 mb-4">
                Visualización Global
            </h3>

            <div class="h-64 w-full">
                <canvas id="mainChart"></canvas>
            </div>
        </div>

    </div>

</div>

<script>
    function generarPDF() {
        const graficas = document.querySelectorAll('canvas');

        graficas.forEach(canvas => {
            const ctx = canvas.getContext('2d');

            ctx.save();
            ctx.globalCompositeOperation = 'destination-over';
            ctx.fillStyle = '#ffffff';
            ctx.fillRect(0, 0, canvas.width, canvas.height);
            ctx.restore();

            const imagen = document.createElement('img');
            imagen.src = canvas.toDataURL('image/png');
            imagen.style.width = '100%';
            imagen.className = 'mx-auto';

            canvas.style.display = 'none';
            canvas.parentNode.appendChild(imagen);
        });

        const elemento = document.getElementById('contenido-pdf');

        const opciones = {
            margin: 0.3,
            filename: 'Reporte_Regresion_Lineal.pdf',
            image: {
                type: 'jpeg',
                quality: 0.98
            },
            html2canvas: {
                scale: 2,
                useCORS: true,
                backgroundColor: '#ffffff'
            },
            jsPDF: {
                unit: 'in',
                format: 'letter',
                orientation: 'portrait'
            }
        };

        html2pdf().set(opciones).from(elemento).save().then(() => {
            setTimeout(() => {
                window.location.href = "<?php echo e(route('linear.index')); ?>";
            }, 1000);
        });
    }

    document.addEventListener("DOMContentLoaded", function() {
        const etiquetas = <?php echo json_encode($labels); ?>;
        const datos = <?php echo json_encode($data); ?>;

        const ctxMain = document.getElementById('mainChart');

        const bgColors = datos.map((valor, indice) =>
            indice === datos.length - 1
                ? 'rgba(239, 68, 68, 0.6)'
                : 'rgba(59, 130, 246, 0.6)'
        );

        new Chart(ctxMain, {
            type: 'bar',
            data: {
                labels: etiquetas,
                datasets: [{
                    label: 'Ventas ($)',
                    data: datos,
                    backgroundColor: bgColors,
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: false
            }
        });

        const ctxDesc = document.getElementById('descriptiveChart');

        const datosHist = datos.slice(0, -1);
        const labelsHist = etiquetas.slice(0, -1);

        new Chart(ctxDesc, {
            type: 'line',
            data: {
                labels: labelsHist,
                datasets: [{
                    label: 'Histórico',
                    data: datosHist,
                    borderColor: 'rgba(59, 130, 246, 1)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    fill: true,
                    tension: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        display: false
                    },
                    y: {
                        display: false
                    }
                }
            }
        });
    });
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Cerea\Documents\Tareas\Septimo Semestre\mineria de datos\ProyectoFinal\Proyecto Mineria\resources\views/analytics/prediccion.blade.php ENDPATH**/ ?>