@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-primary fw-bold">
            <i class="bi bi-receipt-cutoff me-2"></i>Detalle de Venta
        </h2>
        
        <div class="btn-group">
            <button onclick="window.print()" class="btn btn-outline-dark">
                <i class="bi bi-printer-fill me-2"></i> Imprimir
            </button>

            <button onclick="generarPDF()" class="btn btn-danger text-white">
                <i class="bi bi-file-earmark-pdf-fill me-2"></i> Descargar PDF
            </button>

            <a href="{{ route('ventas.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Regresar
            </a>
        </div>
    </div>

    <div id="area-reporte" class="p-4 bg-white rounded">
        
        <div class="row mb-3">
            <div class="col-12 text-center">
                <h3 class="fw-bold text-uppercase">OpticsSight</h3>
                <p class="text-muted">Comprobante de Venta #{{ $venta->folio }}</p>
                <hr>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card shadow-none border h-100">
                    <div class="card-header bg-light py-2">
                        <h6 class="card-title mb-0 fw-bold text-secondary">Información General</h6>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span class="fw-bold">Folio:</span>
                                <span class="badge bg-primary">#{{ $venta->folio }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span class="fw-bold">Fecha:</span>
                                <span>{{ \Carbon\Carbon::parse($venta->fecha_venta)->format('d/m/Y H:i') }}</span>
                            </li>
                            <li class="list-group-item px-0">
                                <div class="fw-bold mb-1">Atendido por:</div>
                                <div>
                                    {{ $venta->empleado->nombre ?? 'N/A' }} {{ $venta->empleado->apellido_p ?? '' }}
                                    <br>
                                    <small class="text-muted">NSS: {{ $venta->empleado->nss ?? '---' }}</small>
                                </div>
                            </li>
                            {{-- Sección Cliente (Opcional si ya arreglaste la relación) --}}
                            @if($venta->cliente)
                            <li class="list-group-item px-0">
                                <div class="fw-bold mb-1">Cliente:</div>
                                <div>{{ $venta->cliente->nombre }} {{ $venta->cliente->apellido ?? '' }}</div>
                            </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-8 mb-4">
                <div class="card shadow-none border">
                    <div class="card-header bg-dark text-white py-2">
                        <h6 class="card-title mb-0">Productos</h6>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">Descripción</th>
                                    <th class="text-center">Cant.</th>
                                    <th class="text-end">Precio</th>
                                    <th class="text-end pe-3">Importe</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($venta->productos as $producto)
                                <tr>
                                    <td class="ps-3">
                                        {{ $producto->nombre }}
                                        <br><small class="text-muted">{{ $producto->cod_producto }}</small>
                                    </td>
                                    <td class="text-center">{{ $producto->pivot->cantidad }}</td>
                                    <td class="text-end">${{ number_format($producto->pivot->precio, 2) }}</td>
                                    <td class="text-end pe-3 fw-bold">
                                        ${{ number_format($producto->pivot->subtotal_p ?? ($producto->pivot->cantidad * $producto->pivot->precio), 2) }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end fw-bold pt-3">Subtotal:</td>
                                    <td class="text-end pe-3 pt-3">${{ number_format($venta->subtotal, 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end fw-bold text-muted">IVA:</td>
                                    <td class="text-end pe-3 text-muted">${{ number_format($venta->iva, 2) }}</td>
                                </tr>
                                <tr class="bg-light">
                                    <td colspan="3" class="text-end fw-bold fs-5 text-primary">TOTAL:</td>
                                    <td class="text-end pe-3 fw-bold fs-5 text-primary">
                                        ${{ number_format($venta->total, 2) }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-12 text-center text-muted small">
                <p>Gracias por su compra en OpticsSight.<br>Este documento es un comprobante de venta válido.</p>
            </div>
        </div>

    </div> </div>

{{-- 3. SCRIPTS NECESARIOS --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<script>
    function generarPDF() {
        // Seleccionamos el elemento exacto que queremos convertir
        const elemento = document.getElementById('area-reporte');

        // Opciones de configuración
        const opciones = {
            margin:       0.5, // Márgenes en pulgadas
            filename:     'Venta_{{ $venta->folio }}.pdf',
            image:        { type: 'jpeg', quality: 0.98 },
            html2canvas:  { scale: 2, useCORS: true }, // Escala 2 mejora la nitidez
            jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait' }
        };

        // Ejecutar la conversión y descarga
        html2pdf().set(opciones).from(elemento).save();
    }
</script>
@endsection