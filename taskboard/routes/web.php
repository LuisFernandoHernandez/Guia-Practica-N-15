<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ComercioController;
use App\Http\Controllers\TransaccionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// ===== Guía N.º 1 (públicas, sin datos reales) =====

Route::get('/taskboard', function () {
    return 'Bienvenido a TaskBoard, tu pasarela de pagos.';
});

Route::get('/acerca-de', function () {
    return 'TaskBoard es una pasarela de pagos que permite a comercios afiliados recibir pagos de sus clientes y dar seguimiento a cada transacción hasta su liquidación.';
});

Route::get('/contacto', function () {
    return 'Luis Hernandez - correo: luis.hernandez63901@uped.edu.sv';
});

Route::get('/estados', function () {
    return ['Iniciada', 'Procesando', 'Aprobada', 'Rechazada', 'Liquidada'];
});

Route::get('/transaccion/demo', function () {
    return [
        'id'       => 1,
        'comercio' => 'Café Amanecer',
        'monto'    => 25.50,
        'moneda'   => 'USD',
        'estado'   => 'Aprobada',
    ];
});

Route::get('/comercios-categoria/{categoria?}', function ($categoria = 'todos') {
    return "Mostrando comercios de la categoría: $categoria";
});

// ===== Laboratorio de formularios (Semana 9) =====

Route::get('/practica/formulario-demo', function () {
    return view('practica.formulario_demo');
});

Route::post('/practica/enviar', function () {
    return 'Formulario recibido correctamente.';
});

// ===== Semana 12: solo para quien NO tiene sesión =====

Route::middleware('guest')->group(function () {
    Route::get('/registro', [AuthController::class, 'mostrarRegistro'])->name('registro');
    Route::post('/registro', [AuthController::class, 'registrar'])->name('registro.store');
    Route::get('/login', [AuthController::class, 'mostrarLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:5,1')
        ->name('login.attempt');
});

// ===== Semana 12: solo para quien SÍ tiene sesión =====

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::prefix('comercios')->name('comercios.')->group(function () {
        Route::get('/', [ComercioController::class, 'index'])->name('index');
        Route::get('/{comercio}', [ComercioController::class, 'show'])->name('show');
    });

    Route::get('/comercios/{comercio}/transacciones/nueva',
        [TransaccionController::class, 'create']
    )->name('transacciones.create');

    Route::post('/transacciones',
        [TransaccionController::class, 'store']
    )->name('transacciones.store');

    Route::get('/transacciones/{transaccion}/editar',
        [TransaccionController::class, 'edit']
    )->name('transacciones.edit');

    Route::put('/transacciones/{transaccion}',
        [TransaccionController::class, 'update']
    )->name('transacciones.update');

    Route::delete('/transacciones/{transaccion}',
        [TransaccionController::class, 'destroy']
    )->name('transacciones.destroy');

    Route::patch('/transacciones/{transaccion}/mover/{estado}',
        [TransaccionController::class, 'moverEstado']
    )->name('transacciones.mover');
});
