{{-- transacciones/edit.blade.php --}}
@extends('layouts.app')

@section('titulo', 'Editar transacción')

@section('contenido')
    <h1>Editar Transacción</h1>
    <p class="meta">Comercio: {{ $transaccion->comercio->nombre_comercio }}</p>

    <form action="{{ route('transacciones.update', $transaccion) }}" method="POST">
        @csrf
        @method('PUT')
        <input type="hidden" name="comercio_id" value="{{ $transaccion->comercio_id }}">

        <label for="cliente">Cliente</label>
        <input id="cliente" name="cliente_nombre" type="text" value="{{ old('cliente_nombre', $transaccion->cliente_nombre) }}"><br>
        @error('cliente_nombre')
            <span class="error">{{ $message }}</span>
        @enderror

        <label for="monto">Monto</label>
        <input id="monto" name="monto" type="number" step="0.01" value="{{ old('monto', $transaccion->monto) }}"><br>
        @error('monto')
            <span class="error">{{ $message }}</span>
        @enderror

        <button type="submit">Guardar cambios</button>
    </form>
@endsection
