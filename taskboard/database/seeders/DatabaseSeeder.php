<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $ana = User::create([
            'name' => 'Ana López',
            'email' => 'ana@taskboard.test',
            'password' => 'password123',
        ]);

        $carlos = User::create([
            'name' => 'Carlos Pérez',
            'email' => 'carlos@taskboard.test',
            'password' => 'password123',
        ]);

        $cafe = $ana->comercios()->create([
            'nombre_comercio' => 'Café Amanecer',
            'rubro' => 'Restaurante',
            'telefono' => '2222-1234',
        ]);

        $cafe->transacciones()->createMany([
            ['monto' => 45.00, 'cliente_nombre' => 'María López', 'estado' => 'Completada'],
            ['monto' => 12.50, 'cliente_nombre' => 'Juan Pérez', 'estado' => 'Iniciada'],
        ]);

        $pupuseria = $ana->comercios()->create([
            'nombre_comercio' => 'Pupusería Doña Marta',
            'rubro' => 'Restaurante',
            'telefono' => '2222-9012',
        ]);

        $pupuseria->transacciones()->create([
            'monto' => 8.00,
            'cliente_nombre' => 'Carlos Ramírez',
            'estado' => 'Fallida',
        ]);

        $ferreteria = $carlos->comercios()->create([
            'nombre_comercio' => 'Ferretería El Tornillo',
            'rubro' => 'Ferretería',
            'telefono' => '2222-5678',
        ]);

        $ferreteria->transacciones()->create([
            'monto' => 20.00,
            'cliente_nombre' => 'Pedro Gómez',
            'estado' => 'Iniciada',
        ]);
    }
}
