@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <h2 class="mb-4"><i class="bi bi-cart-check"></i> Crear Pedido</h2>

    <div class="card p-4 shadow-sm" style="border-radius: 15px;">
@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

        <form action="{{ route('pedidos.store') }}" method="POST" id="formPedido">
            @csrf

            <!-- Proveedor -->
            <div class="mb-4">
                <label class="form-label">Proveedor *</label>
                <select name="proveedores_fk" class="form-control" required>
                    <option value="">Seleccione...</option>
                    @foreach($proveedores as $p)
                        <option value="{{ $p->telefono }}">{{ $p->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Fecha entrega -->
            <div class="mb-4">
                <label class="form-label">Fecha de Entrega</label>
                <input type="date" name="fecha_entrega" class="form-control">
            </div>

            <hr>

            <h4 class="mb-3">Agregar Productos</h4>

            <div class="input-group mb-3">

                <select id="selectProducto" class="form-control">
                    <option value="">Seleccione un producto...</option>
                    @foreach($productos as $p)
                    <option value="{{ $p->cod_producto }}">{{ $p->nombre }}</option>
                    @endforeach
                </select>

                <input type="number" id="cantidad" class="form-control" placeholder="Cantidad" min="1">

                <input type="text" id="especif" class="form-control" placeholder="Especificaciones">


            </div>

                            <button type="button" class="btn btn-success" onclick="agregarProducto()">
                    <i class="bi bi-plus-lg"></i> Agregar Pedido
                    
                </button>

                <br></br>

            <!-- Tabla productos -->
            <table class="table table-hover" id="tablaProductos">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Cantidad</th>
                        <th>Especificación</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody></tbody>
            </table>

            <div class="text-end mt-4">
                <button class="btn btn-primary"><i class="bi bi-check-circle"></i> Guardar Pedido</button>
            </div>

        </form>

    </div>

</div>

@endsection

@section('scripts')

<script>
let lista = [];

function agregarProducto() {
    let id = document.querySelector("#selectProducto").value;
    let cantidad = document.querySelector("#cantidad").value;
    let especif = document.querySelector("#especif").value;

    if (!id || !cantidad) {
        return alert("Seleccione producto y cantidad.");
    }

    lista.push({ id, cantidad, especif });

    renderTabla();
}

function renderTabla()
{
    let tbody = document.querySelector("#tablaProductos tbody");
    tbody.innerHTML = "";

    lista.forEach((p, index) => {
        tbody.innerHTML += `
            <tr>
                <td>${p.id}</td>
                <td>${p.cantidad}</td>
                <td>${p.especif}</td>
                <td>
                    <button class="btn btn-danger btn-sm" onclick="eliminar(${index})">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            </tr>
        `;
    });

    // borrar inputs anteriores
    document.querySelectorAll(".inputTemp").forEach(e => e.remove());

    // agregar inputs ocultos (FIX AQUÍ)
    let form = document.querySelector("#formPedido");

    lista.forEach((p, i) => {
        form.insertAdjacentHTML('beforeend', `
            <input type="hidden" class="inputTemp" name="productos[${i}][id]" value="${p.id}">
            <input type="hidden" class="inputTemp" name="productos[${i}][cantidad]" value="${p.cantidad}">
            <input type="hidden" class="inputTemp" name="productos[${i}][especif]" value="${p.especif}">
        `);
    });
}


function eliminar(i) {
    lista.splice(i, 1);
    renderTabla();
}
</script>

<script>
document.getElementById('formPedido').addEventListener('submit', function(e) {
    if (lista.length === 0) {
        e.preventDefault();
        alert('Debes agregar al menos un producto.');
    }
});
</script>


@endsection
