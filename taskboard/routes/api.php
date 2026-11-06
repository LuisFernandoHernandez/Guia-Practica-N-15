<?php

use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\TransaccionApiController;
use App\Http\Resources\ComercioResource;
use App\Models\Comercio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthApiController::class, 'login'])
    ->middleware('throttle:5,1');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/yo', [AuthApiController::class, 'yo']);
    Route::post('/logout', [AuthApiController::class, 'logout']);

    Route::get('/comercios', function (Request $request) {
        return ComercioResource::collection(
            $request->user()->comercios()->orderBy('nombre_comercio')->get()
        );
    });

    Route::get('/comercios/{comercio}', function (Request $request, Comercio $comercio) {
        abort_unless($comercio->user_id === $request->user()->id, 404);

        return new ComercioResource($comercio->load('transacciones'));
    });

    Route::apiResource('transacciones', TransaccionApiController::class)
        ->parameters(['transacciones' => 'transaccion'])
        ->names('api.transacciones');

    // Reto
    Route::get('/tokens', [AuthApiController::class, 'tokens']);
    Route::delete('/tokens/{id}', [AuthApiController::class, 'cerrarDispositivo']);
});
