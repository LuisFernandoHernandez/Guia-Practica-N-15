@props(['totalTransacciones'])

@if ($totalTransacciones == 0)
    <span class="badge gris">Sin actividad</span>
@else
    <span class="badge verde">Activo</span>
@endif
