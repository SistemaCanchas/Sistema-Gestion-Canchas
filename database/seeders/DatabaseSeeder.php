<?php

namespace Database\Seeders;

use App\Models\Cancha;
use App\Models\Equipamiento;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Recepcionista del Lobby
        User::create([
            'name' => 'Admin Balonazo',
            'email' => 'admin@balonazo.com',
            'telefono' => '7000-0001',
            'rol' => 'admin',
            'password' => Hash::make('admin123'),
        ]);

        // 2. Jugador de Prueba
        User::create([
            'name' => 'Jugador Prueba',
            'email' => 'jugador@balonazo.com',
            'telefono' => '7000-0002',
            'rol' => 'cliente',
            'password' => Hash::make('cliente123'),
        ]);

        // 3. Las 6 Canchas de Balonazo
        Cancha::create(['nombre' => 'Cancha Normal 1', 'tipo' => 'normal', 'precio_hora' => 15.00, 'incluye_balon_ac' => false, 'descripcion' => 'Cancha estándar ideal para partidos rápidos.']);
        Cancha::create(['nombre' => 'Cancha Normal 2', 'tipo' => 'normal', 'precio_hora' => 15.00, 'incluye_balon_ac' => false, 'descripcion' => 'Cancha estándar con césped sintético.']);
        
        Cancha::create(['nombre' => 'Cancha Pro 1', 'tipo' => 'pro', 'precio_hora' => 20.00, 'incluye_balon_ac' => false, 'descripcion' => 'Cancha profesional con mejor iluminación y césped de alto rendimiento.']);
        Cancha::create(['nombre' => 'Cancha Pro 2', 'tipo' => 'pro', 'precio_hora' => 20.00, 'incluye_balon_ac' => false, 'descripcion' => 'Cancha profesional con graderías laterales.']);
        Cancha::create(['nombre' => 'Cancha Pro 3', 'tipo' => 'pro', 'precio_hora' => 20.00, 'incluye_balon_ac' => false, 'descripcion' => 'Cancha profesional techada parcialmente.']);

        Cancha::create(['nombre' => 'Cancha Premium', 'tipo' => 'premium', 'precio_hora' => 30.00, 'incluye_balon_ac' => true, 'descripcion' => 'Cancha VIP con césped FIFA Quality. Incluye 1 Balón de Alta Calidad gratis.']);

        // 4. Inventario de Equipamiento
        Equipamiento::create(['nombre' => 'Balón Normal', 'categoria' => 'balon_normal', 'talla' => null, 'precio_alquiler' => 3.00, 'stock_total' => 10]);
        Equipamiento::create(['nombre' => 'Balón Alta Calidad (AC)', 'categoria' => 'balon_ac', 'talla' => null, 'precio_alquiler' => 5.00, 'stock_total' => 10]);

        Equipamiento::create(['nombre' => 'Tacos Talla 7', 'categoria' => 'tacos', 'talla' => '7', 'precio_alquiler' => 5.00, 'stock_total' => 10]);
        Equipamiento::create(['nombre' => 'Tacos Talla 8', 'categoria' => 'tacos', 'talla' => '8', 'precio_alquiler' => 5.00, 'stock_total' => 10]);
        Equipamiento::create(['nombre' => 'Tacos Talla 9', 'categoria' => 'tacos', 'talla' => '9', 'precio_alquiler' => 5.00, 'stock_total' => 10]);

        Equipamiento::create(['nombre' => 'Guantes de Portero', 'categoria' => 'guantes', 'talla' => null, 'precio_alquiler' => 5.00, 'stock_total' => 15]);
    }
}
