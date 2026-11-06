<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GuardarTransaccionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'comercio_id' => ['required', Rule::exists('comercios', 'id')->where('user_id', $this->user()->id)],
            'cliente_nombre' => ['required', 'string', 'min:3', 'max:255'],
            'monto' => ['required', 'numeric', 'min:0.01', 'max:99999999.99', 'decimal:0,2'], // Corrección E-01 y E-02
            'estado' => ['prohibited'], // Corrección E-03: prohíbe enviar estado al crear
        ];
    }

    public function messages(): array
    {
        return [
            'cliente_nombre.required' => 'Debes indicar el nombre del cliente.',
            'cliente_nombre.min' => 'El nombre del cliente es demasiado corto.',
            'monto.required' => 'Debes indicar un monto.',
            'monto.numeric' => 'El monto debe ser un número.',
            'monto.min' => 'El monto debe ser mayor a cero.',
            'monto.max' => 'El monto no puede superar 99,999,999.99.',
            'monto.decimal' => 'El monto admite como máximo dos decimales.',
            'estado.prohibited' => 'El estado no se envía al crear: toda transacción nace Iniciada.',
        ];
    }

    public function attributes(): array
    {
        return [
            'cliente_nombre' => 'nombre del cliente',
            'monto' => 'monto de la transacción',
            'comercio_id' => 'comercio',
        ];
    }
}
