<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GuardarTransaccionRequest;
use App\Http\Resources\TransaccionResource;
use App\Models\Transaccion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaccionApiController extends Controller
{
    public function index(Request $request)
    {
        $estado = $request->query('estado');

        $transacciones = Transaccion::whereHas('comercio', fn($q) =>
        $q->where('user_id', $request->user()->id))
            ->when($estado, fn($q) => $q->where('estado', $estado))
            ->latest()
            ->get();

        return TransaccionResource::collection($transacciones);
    }

    private function autorizar(Transaccion $transaccion): void
    {
        abort_unless($transaccion->comercio->user_id === request()->user()->id, 404);
    }

    public function store(GuardarTransaccionRequest $request)
    {
        // creación y el evento en una transacción de base de datos
        $transaccion = DB::transaction(function () use ($request) {
            // 1. Crear la transacción con los datos validados
            $t = Transaccion::create($request->validated());

            // 2. Registrar el evento inicial de creación
            $t->eventos()->create([
                'tipo_evento' => 'creacion',
                'detalle' => 'Transacción iniciada',
            ]);

            return $t;
        });

        // 3. Devolver la respuesta en formato JSON con código 201
        return (new TransaccionResource($transaccion))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Transaccion $transaccion)
    {
        $this->autorizar($transaccion);

        return new TransaccionResource($transaccion);
    }

    public function update(Request $request, Transaccion $transaccion)
    {
        $this->autorizar($transaccion);
        //
    }

    public function destroy(Transaccion $transaccion)
    {
        $this->autorizar($transaccion);

        $transaccion->delete();

        return response()->noContent(); // 204
    }
}
