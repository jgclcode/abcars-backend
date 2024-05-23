<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Damage;

class DamageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $damage = Damage::create(['name' => 'frontal', 'status' => 'exterior']);
        $damage = Damage::create(['name' => 'trasera', 'status' => 'exterior']);
        $damage = Damage::create(['name' => 'izquierda', 'status' => 'exterior']);
        $damage = Damage::create(['name' => 'derecha', 'status' => 'exterior']);
        $damage = Damage::create(['name' => 'debajo', 'status' => 'exterior']);
        $damage = Damage::create(['name' => 'cofre', 'status' => 'exterior']);
        $damage = Damage::create(['name' => 'parabrisas', 'status' => 'exterior']);
        $damage = Damage::create(['name' => 'techo', 'status' => 'exterior']);
        $damage = Damage::create(['name' => 'limpia parabrisas', 'status' => 'exterior']);
        $damage = Damage::create(['name' => 'capó', 'status' => 'exterior']);
        $damage = Damage::create(['name' => 'parrilla', 'status' => 'exterior']);        
        $damage = Damage::create(['name' => 'retrovisores', 'status' => 'exterior']);
        $damage = Damage::create(['name' => 'luz delantera', 'status' => 'exterior']);
        $damage = Damage::create(['name' => 'parachoques', 'status' => 'exterior']);
        $damage = Damage::create(['name' => 'defensa', 'status' => 'exterior']);
        $damage = Damage::create(['name' => 'maletero', 'status' => 'exterior']);
        $damage = Damage::create(['name' => 'luz trasera', 'status' => 'exterior']);
        $damage = Damage::create(['name' => 'ventanillas', 'status' => 'exterior']);
        $damage = Damage::create(['name' => 'manillas de las puestas', 'status' => 'exterior']);
        $damage = Damage::create(['name' => 'puertas', 'status' => 'exterior']);
        $damage = Damage::create(['name' => 'ruedas', 'status' => 'exterior']);
        $damage = Damage::create(['name' => 'otro', 'status' => 'exterior']);
        
        $damage = Damage::create(['name' => 'consola', 'status' => 'inside']);
        $damage = Damage::create(['name' => 'asientos traseros', 'status' => 'inside']);
        $damage = Damage::create(['name' => 'panel de instrumento', 'status' => 'inside']);
        $damage = Damage::create(['name' => 'luces interior', 'status' => 'inside']);
        $damage = Damage::create(['name' => 'toma corriente', 'status' => 'inside']);
        $damage = Damage::create(['name' => 'computadora de viaje', 'status' => 'inside']);
        $damage = Damage::create(['name' => 'reloj/termómetro', 'status' => 'inside']);
        $damage = Damage::create(['name' => 'quemacocos', 'status' => 'inside']);
        $damage = Damage::create(['name' => 'cinturones', 'status' => 'inside']);
        $damage = Damage::create(['name' => 'hoja de servicios', 'status' => 'inside']);        
        
        $damage = Damage::create(['name' => 'Otro', 'status' => 'inside']);
    }
}
