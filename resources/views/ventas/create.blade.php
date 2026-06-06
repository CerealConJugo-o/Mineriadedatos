@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <h2 class="mb-4"><i class="bi bi-cart-plus"></i> Registrar Nueva Venta</h2>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card p-4 shadow-sm" style="border-radius: 15px;">

        <form action="{{ route('ventas.store') }}" method="POST" id="formVenta">
            @csrf

            <div class="row mb-4">
                <div class="col-md-4">
                    <label class="form-label">Empleado Responsable *</label>
                    <select name="empleado_fk" class="form-control" required>
                        <option value="">Seleccione...</option>
                        @foreach($empleados as $e)
                            <option value="{{ $e->nss }}" {{ old('empleado_fk') == $e->nss ? 'selected' : '' }}>
                                {{ $e->nombre }} {{ $e->apellido_p }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-8">
                    <label class="form-label">Servicios / Notas (opcional)</label>
                    <input type="text" name="servicios" class="form-control" value="{{ old('servicios') }}" placeholder="Ej: Venta de mostrador">
                </div>
            </div>

            <hr>

            <h4 class="mb-3 text-primary">Carrito de Productos</h4>

            <div class="card bg-light p-3 mb-3">
                <div class="row g-2 align-items-end">
                    <div class="col-md-6">
                        <label>Producto</label>
                        <select id="selectProducto" class="form-control">
                            <option value="">Seleccione un producto...</option>
                            @foreach($productos as $p)
                                <option value="{{ $p->cod_producto }}" 
                                        data-precio="{{ $p->precio }}" 
                                        data-nombre="{{ $p->nombre }}"
                                        data-stock="{{ $p->cantidad }}">
                                    {{ $p->nombre }} - ${{ $p->precio }} (Stock: {{ $p->cantidad }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Cantidad</label>
                        <input type="number" id="cantidad" class="form-control" placeholder="0" min="1">
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-success w-100" onclick="agregarProducto()">
                            <i class="bi bi-plus-lg"></i> Agregar
                        </button>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-bordered" id="tablaProductos">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>Producto</th>
                            <th width="10%">Cant.</th>
                            <th width="15%">Precio Unit.</th>
                            <th>Subtotal</th>
                            <th>IVA (16%)</th>
                            <th>Total</th>
                            <th width="5%">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        </tbody>
                </table>
            </div>

            <div class="row mt-4 justify-content-end">
                <div class="col-md-4">
                    <table class="table table-sm">
                        <tr>
                            <td class="text-end"><strong>Subtotal:</strong></td>
                            <td class="text-end">$<span id="subtotalGeneral">0.00</span></td>
                        </tr>
                        <tr>
                            <td class="text-end"><strong>IVA:</strong></td>
                            <td class="text-end">$<span id="ivaGeneral">0.00</span></td>
                        </tr>
                        <tr class="table-primary">
                            <td class="text-end"><h4>Total:</h4></td>
                            <td class="text-end"><h4>$<span id="totalGeneral">0.00</span></h4></td>
                        </tr>
                    </table>
                    
                    <input type="hidden" name="total_general" id="inputTotal">
                </div>
            </div>

            <div id="productosHidden"></div>

            <div class="text-end mt-4">
                <button type="submit" class="btn btn-primary btn-lg" id="btnGuardar" disabled>
                    <i class="bi bi-check-circle"></i> Terminar Venta
                </button>
            </div>

        </form>

    </div>
</div>

@endsection

@section('scripts')
<script>
let lista = [];

function agregarProducto() {
    let select = document.querySelector("#selectProducto");
    let option = select.selectedOptions[0];
    
    // Validaciones simples
    if (!select.value) return alert("Seleccione un producto.");
    
    let id = option.value;
    let nombre = option.dataset.nombre;
    let stock = parseInt(option.dataset.stock);
    let precio = parseFloat(option.dataset.precio);
    let cantidad = parseInt(document.querySelector("#cantidad").value);

    if (!cantidad || cantidad < 1) return alert("Ingrese una cantidad válida.");
    if (cantidad > stock) return alert("No hay suficiente stock. Disponible: " + stock);

    // Verificar si ya existe en la lista para sumar cantidad
    let existe = lista.find(p => p.id === id);
    if (existe) {
        if ((existe.cantidad + cantidad) > stock) {
            return alert("La cantidad total supera el stock disponible.");
        }
        existe.cantidad += cantidad;
        recalcularLinea(existe);
    } else {
        // Calcular valores
        let subtotal = precio * cantidad;
        let iva = subtotal * 0.16; // Asumiendo IVA 16%
        let total = subtotal + iva;

        lista.push({
            id: id,
            nombre: nombre,
            cantidad: cantidad,
            precio: precio,
            subtotal: subtotal,
            iva: iva,
            total: total
        });
    }

    // Limpiar inputs
    select.value = "";
    document.querySelector("#cantidad").value = "";
    
    renderTabla();
}

function recalcularLinea(producto) {
    producto.subtotal = producto.precio * producto.cantidad;
    producto.iva = producto.subtotal * 0.16;
    producto.total = producto.subtotal + producto.iva;
}

function renderTabla() {
    let tbody = document.querySelector("#tablaProductos tbody");
    tbody.innerHTML = "";

    if (lista.length === 0) {
        tbody.innerHTML = '<tr><td colspan="7" class="text-muted">No hay productos agregados</td></tr>';
        document.querySelector("#btnGuardar").disabled = true;
        actualizarTotalesUI(0,0,0);
        document.querySelector("#productosHidden").innerHTML = ""; // Limpiar inputs ocultos
        return;
    }

    document.querySelector("#btnGuardar").disabled = false;
    
    // Variables para el HTML de los inputs ocultos
    let inputsHTML = "";

    lista.forEach((p, index) => {
        // 1. Dibujar Fila Visual
        tbody.innerHTML += `
            <tr>
                <td class="text-start">${p.nombre}</td>
                <td>${p.cantidad}</td>
                <td>$${p.precio.toFixed(2)}</td>
                <td>$${p.subtotal.toFixed(2)}</td>
                <td>$${p.iva.toFixed(2)}</td>
                <td><strong>$${p.total.toFixed(2)}</strong></td>
                <td>
                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="eliminar(${index})">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            </tr>
        `;

        // 2. Generar Inputs Ocultos para Laravel
        inputsHTML += `
            <input type="hidden" name="productos[${index}][id]" value="${p.id}">
            <input type="hidden" name="productos[${index}][cantidad]" value="${p.cantidad}">
            <input type="hidden" name="productos[${index}][precio]" value="${p.precio}">
            <input type="hidden" name="productos[${index}][subtotal]" value="${p.subtotal}">
            <input type="hidden" name="productos[${index}][iva]" value="${p.iva}">
            <input type="hidden" name="productos[${index}][total]" value="${p.total}">
        `;
    });

    // Inyectar inputs ocultos en el DIV especial (sin romper el resto del form)
    document.querySelector("#productosHidden").innerHTML = inputsHTML;

    calcularTotalesGenerales();
}

function eliminar(index) {
    lista.splice(index, 1);
    renderTabla();
}

function calcularTotalesGenerales() {
    let subtotalG = lista.reduce((sum, p) => sum + p.subtotal, 0);
    let ivaG = lista.reduce((sum, p) => sum + p.iva, 0);
    let totalG = lista.reduce((sum, p) => sum + p.total, 0);

    actualizarTotalesUI(subtotalG, ivaG, totalG);
}

function actualizarTotalesUI(subtotal, iva, total) {
    document.querySelector("#subtotalGeneral").textContent = subtotal.toFixed(2);
    document.querySelector("#ivaGeneral").textContent = iva.toFixed(2);
    document.querySelector("#totalGeneral").textContent = total.toFixed(2);
    document.querySelector("#inputTotal").value = total.toFixed(2); // Para enviar al backend
}
</script>
@endsection