@extends('layouts.app')

@section('title', 'Inicio - OpticsSight')

@section('content')
<div class="row mb-4">
  <div class="col-12">
    <div class="d-flex align-items-center justify-content-between">
      <div>
        <h1 class="h3 mb-0">OpticsSight</h1>
        <p class="text-muted mb-0">Bienvenido, <strong>{{ auth()->user()->name ?? 'Usuario' }}</strong></p>
      </div>
      <div>
        <a href="{{ url('/logout') }}" class="btn btn-outline-secondary">Finalizar Operaciones</a>
      </div>
    </div>
  </div>
</div>

<!-- Cards -->
<div class="row g-3 mb-4">
  <div class="col-md-6">
    <div class="card shadow-sm h-100">
      <div class="card-body">
        <h5 class="card-title">Ventas del día</h5>
        <p class="card-text fs-3">MXN $ <span id="ventasHoy">0.00</span></p>
        <a href="#" class="stretched-link">Ver ventas</a>
      </div>
    </div>
  </div>

  <div class="col-md-6">
    <div class="card shadow-sm h-100">
      <div class="card-body">
        <h5 class="card-title">Exámenes</h5>
        <p class="card-text">Exámenes programados: <strong id="examenesHoy">0</strong></p>
        <a href="#" class="stretched-link">Ver exámenes</a>
      </div>
    </div>
  </div>
</div>

<!-- Empleados Table (mockup) -->
<div class="card shadow-sm">
  <div class="card-header">
    <strong>Gestión de Empleados</strong>
  </div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table mb-0">
        <thead class="table-light">
          <tr>
            <th>Nombre Completo</th>
            <th>Rol</th>
            <th>Salario</th>
            <th>Estado</th>
            <th class="text-end">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <!-- ejemplo estático; luego esto vendrá del controlador -->
          <tr>
            <td>Carlos Hernan Flores Martin</td>
            <td>Gerente General</td>
            <td>$18,500 MXN</td>
            <td>Activo</td>
            <td class="text-end">
              <a href="#" class="btn btn-sm btn-outline-primary">Ver</a>
              <a href="#" class="btn btn-sm btn-outline-secondary">Editar</a>
            </td>
          </tr>

          <tr>
            <td>Aguilar Reyes Jose Carlos</td>
            <td>Vendedor</td>
            <td>$12,000 MXN</td>
            <td>Activo</td>
            <td class="text-end">
              <a href="#" class="btn btn-sm btn-outline-primary">Ver</a>
              <a href="#" class="btn btn-sm btn-outline-secondary">Editar</a>
            </td>
          </tr>

        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
