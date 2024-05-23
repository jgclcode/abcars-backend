<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Branch;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Branch::create([
            'name' => 'Sin asignar',
            'state_id' => 1
        ]);

        Branch::create([
            'name' => 'Carcentral Puebla',
            'state_id' => 21
        ]);

        Branch::create([
            'name' => 'Carcentral Tlaxcala',
            'state_id' => 29
        ]);

        Branch::create([
            'name' => 'Carcentral Hidalgo',
            'state_id' => 13
        ]);

        Branch::create([
            'name' => 'Carcentral Veracruz',
            'state_id' => 30
        ]);

        Branch::create([
            'name' => 'Carcentral Oaxaca',
            'state_id' => 20
        ]);

        Branch::create([
            'name' => 'Carcentral Coahuila',
            'state_id' => 6
        ]);
    }
}
