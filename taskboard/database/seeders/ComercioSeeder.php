<?php

namespace Database\Seeders;

use App\Models\Comercio;
use Illuminate\Database\Seeder;

class ComercioSeeder extends Seeder
{
    public function run(): void
    {
        $cafeAmanecer = Comercio::create([
            'nombre_comercio' => 'Café Amanecer',
            'rubro' => 'Restaurante',
            'telefono' => '2222-1234',
        ]);

        $cafeAmanecer->transacciones()->createMany([
            [
                'monto' => 45.00,
                'cliente_nombre' => 'María López',
                'estado' => 'Completada',
            ],
            [
                'monto' => 12.50,
                'cliente_nombre' => 'Juan Pérez',
                'estado' => 'Iniciada',
            ],
        ]);

        Comercio::create([
            'nombre_comercio' => 'Ferretería El Tornillo',
            'rubro' => 'Ferretería',
            'telefono' => '2222-5678',
        ]);

        $pupuseria = Comercio::create([
            'nombre_comercio' => 'Pupusería Doña Marta',
            'rubro' => 'Restaurante',
            'telefono' => '2222-9012',
        ]);

        $pupuseria->transacciones()->create([
            'monto' => 8.00,
            'cliente_nombre' => 'Carlos Ramírez',
            'estado' => 'Fallida',
        ]);
    }
}
