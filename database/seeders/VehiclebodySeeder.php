<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vehiclebody;

class VehiclebodySeeder extends Seeder
{
    public function run()
    {
        Vehiclebody::create([
            'name' => 'convertible'
        ]);

        Vehiclebody::create([
            'name' => 'coupé'
        ]);

        Vehiclebody::create([
            'name' => 'crossover'
        ]);

        Vehiclebody::create([
            'name' => 'hatchback'
        ]);

        Vehiclebody::create([
            'name' => 'miniván'
        ]);

        Vehiclebody::create([
            'name' => 'auto pequeño'
        ]);

        Vehiclebody::create([
            'name' => 'camión'
        ]);

        Vehiclebody::create([
            'name' => 'suv'
        ]);

        Vehiclebody::create([
            'name' => 'sedán'
        ]);

        Vehiclebody::create([
            'name' => 'van'
        ]);

        Vehiclebody::create([
            'name' => 'familiar'
        ]);

        Vehiclebody::create([
            'name' => 'otro'
        ]);  
        
        Vehiclebody::create([
            'name' => 'ninguno'
        ]);          

    }
}