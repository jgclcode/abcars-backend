<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Shield;

class ShieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Shield::create([
            'name' => 'Factura Original',
            'path' => 'invocie.png'
        ]);

        Shield::create([
            'name' => 'Bajo KM',
            'path' => 'km.png'
        ]);

        Shield::create([
            'name' => 'Garantizado',
            'path' => 'bonded.png'
        ]);

        Shield::create([
            'name' => 'Certificado de Planta',
            'path' => 'plant.png'
        ]);
    }
}
