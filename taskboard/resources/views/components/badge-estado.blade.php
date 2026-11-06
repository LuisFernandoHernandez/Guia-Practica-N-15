@props(['estado'])

@if ($estado === 'Completada')
    <span class="badge verde">✔ Completada</span>
@elseif ($estado === 'Fallida')
    <span class="badge rojo">✗ Fallida</span>
@else
    <span class="badge amarillo">⏳ {{ $estado }}</span>
@endif
