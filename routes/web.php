<?php

use Illuminate\Support\Facades\Route;

// Controladores  Algoritmos
use App\Http\Controllers\RandomForestController;
use App\Http\Controllers\LinearRegressionController;
use App\Http\Controllers\NeuralNetworkController;
use App\Http\Controllers\KDDController;

use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\VentaProductoController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\ExamenController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\DatosController;
/*
|--------------------------------------------------------------------------
| RUTAS DE AUTENTICACIÓN
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| RUTAS PROTEGIDAS (SISTEMA)
|--------------------------------------------------------------------------
*/




    // Redirección y Dashboard
    Route::get('/', function () { return redirect()->route('dashboard'); });
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

    // =============================================================
    // 1. ÁREA ADMINISTRATIVA (RRHH y NÓMINA)
    // =============================================================
    Route::middleware(['role:gerente,dba'])->group(function () {
        Route::resource('pagos', PagoController::class);
        Route::resource('empleados', EmpleadoController::class);
    });

// Datos
Route::get('/datos', [DatosController::class, 'index'])
    ->name('datos.index');

Route::post('/datos/upload', [DatosController::class, 'upload'])
    ->name('datos.upload');

Route::get('/datos/{dataset}', [DatosController::class, 'show'])
    ->name('datos.show');

    //RUTAS ALGORITMOS
    //RANDOM FOREST

    Route::get('/random-forest',[RandomForestController::class, 'index'])->name('randomforest.index');
    Route::post('/random-forest/run',[RandomForestController::class, 'run'])->name('randomforest.run');
    Route::get('/randomforest/results',[RandomForestController::class,'results'])->name('randomforest.results');
    
    //REGRESION LINEAL
    Route::get('/linear-regression',[AnalyticsController::class, 'index'])->name('linear.index');    Route::get('/neural-network',[NeuralNetworkController::class, 'index'])->name('neural.index');
    Route::get('/kdd',[KDDController::class, 'index'])->name('kdd.index');
    
    // =============================================================
    // 2. CLIENTES
    // =============================================================
    Route::middleware(['role:vendedor,gerente,dba'])->group(function () {
        
        // Crear y Guardar (Solo Vendedor y DBA)
        Route::middleware(['role:vendedor,dba'])->group(function(){
            Route::get('/clientes/create', [ClienteController::class, 'create'])->name('clientes.create');
            Route::post('/clientes', [ClienteController::class, 'store'])->name('clientes.store');
        });

        // Lista (Index)
        Route::get('/clientes', [ClienteController::class, 'index'])->name('clientes.index');

        // Ver Detalle
        Route::get('/clientes/{cliente}', [ClienteController::class, 'show'])->name('clientes.show');
        
        // Editar y Borrar (Solo Vendedor y DBA)
        Route::middleware(['role:vendedor,dba'])->group(function(){
            Route::get('/clientes/{cliente}/edit', [ClienteController::class, 'edit'])->name('clientes.edit');
            Route::put('/clientes/{cliente}', [ClienteController::class, 'update'])->name('clientes.update');
            Route::delete('/clientes/{cliente}', [ClienteController::class, 'destroy'])->name('clientes.destroy');
        });
    });

    // =============================================================
    // 3. VENTAS
    // =============================================================
    
    // Crear Venta (Primero)
    Route::middleware(['role:vendedor,dba'])->group(function () {
        Route::get('/ventas/create', [VentaController::class, 'create'])->name('ventas.create');
        Route::post('/ventas', [VentaController::class, 'store'])->name('ventas.store');
        Route::resource('venta-productos', VentaProductoController::class);
    });

    // Ver Ventas
    Route::middleware(['role:gerente,vendedor,dba'])->group(function () {
        Route::get('/ventas', [VentaController::class, 'index'])->name('ventas.index');
        Route::get('/ventas/{venta}', [VentaController::class, 'show'])->name('ventas.show');
    });

    // =============================================================
    // 4. ÁREA INVENTARIO (Productos y Proveedores)
    // =============================================================

    // --- PRODUCTOS ---
    Route::middleware(['role:almacenista,dba'])->group(function () {
        Route::get('/productos/create', [ProductoController::class, 'create'])->name('productos.create');
        Route::post('/productos', [ProductoController::class, 'store'])->name('productos.store');
        Route::get('/productos/{producto}/edit', [ProductoController::class, 'edit'])->name('productos.edit');
        Route::put('/productos/{producto}', [ProductoController::class, 'update'])->name('productos.update');
        Route::delete('/productos/{producto}', [ProductoController::class, 'destroy'])->name('productos.destroy');
    });

    Route::middleware(['role:gerente,almacenista,dba'])->group(function () {
        Route::get('/productos', [ProductoController::class, 'index'])->name('productos.index');
        Route::get('/productos/{producto}', [ProductoController::class, 'show'])->name('productos.show');
    });

    // --- PROVEEDORES ---
    Route::middleware(['role:almacenista,gerente,dba'])->group(function () {
        Route::get('/proveedores/create', [ProveedorController::class, 'create'])->name('proveedores.create');
        Route::post('/proveedores', [ProveedorController::class, 'store'])->name('proveedores.store');
        Route::get('/proveedores/{proveedor}/edit', [ProveedorController::class, 'edit'])->name('proveedores.edit');
        Route::put('/proveedores/{proveedor}', [ProveedorController::class, 'update'])->name('proveedores.update');
        Route::delete('/proveedores/{proveedor}', [ProveedorController::class, 'destroy'])->name('proveedores.destroy');
    });

    Route::middleware(['role:gerente,almacenista,dba'])->group(function () {
        Route::get('/proveedores', [ProveedorController::class, 'index'])->name('proveedores.index');
        Route::get('/proveedores/{proveedor}', [ProveedorController::class, 'show'])->name('proveedores.show');
    });

    // =============================================================
    // 5. ÁREA CLÍNICA
    // =============================================================

    // Escritura
    Route::middleware(['role:optometrista,dba'])->group(function () {
        Route::get('/examenes/create', [ExamenController::class, 'create'])->name('examenes.create');
        Route::post('/examenes', [ExamenController::class, 'store'])->name('examenes.store');
        Route::get('/examenes/{fecha}/edit', [ExamenController::class, 'edit'])->name('examenes.edit');
        Route::put('/examenes/{fecha}', [ExamenController::class, 'update'])->name('examenes.update');
        Route::delete('/examenes/{fecha}', [ExamenController::class, 'destroy'])->name('examenes.destroy');
    });

    // Lectura
    Route::middleware(['role:gerente,optometrista,dba'])->group(function () {
        Route::get('/examenes', [ExamenController::class, 'index'])->name('examenes.index');
        Route::get('/examenes/{fecha}', [ExamenController::class, 'show'])->name('examenes.show');
    });

    // =============================================================
    // 6. PEDIDOS (Corregido y Unificado)
    // =============================================================

    // Escritura (Vendedor, Almacenista, Optometrista, DBA)
    Route::middleware(['role:vendedor,almacenista,optometrista,dba'])->group(function () {
        Route::get('/pedidos/create', [PedidoController::class, 'create'])->name('pedidos.create');
        Route::post('/pedidos', [PedidoController::class, 'store'])->name('pedidos.store');
        Route::get('/pedidos/{pedido}/edit', [PedidoController::class, 'edit'])->name('pedidos.edit');
        Route::put('/pedidos/{pedido}', [PedidoController::class, 'update'])->name('pedidos.update');
        Route::delete('/pedidos/{pedido}', [PedidoController::class, 'destroy'])->name('pedidos.destroy');
    });

    // Lectura (Todos los anteriores + Gerente)
    Route::middleware(['role:gerente,vendedor,almacenista,optometrista,dba'])->group(function () {
        Route::get('/pedidos', [PedidoController::class, 'index'])->name('pedidos.index');
        Route::get('/pedidos/{pedido}', [PedidoController::class, 'show'])->name('pedidos.show');
    });

Route::get('/regresion-lineal', [AnalyticsController::class, 'index'])->name('linear.index');

Route::post('/regresion-lineal/ventas', [AnalyticsController::class, 'prediccionVentas'])->name('linear.ventas');

Route::post('/regresion-lineal/csv', [AnalyticsController::class, 'prediccionCsv'])->name('linear.csv.run');
    //Route::get('/analytics/prediccion', [AnalyticsController::class, 'prediccion'])->name('analytics.prediccion');
    
    // Fin del grupo AUTH


