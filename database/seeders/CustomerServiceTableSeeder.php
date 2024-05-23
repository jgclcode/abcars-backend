<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\CustomerService;

class CustomerServiceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        CustomerService::create([
            'key' => 'AP',
            'name' => 'Apartados',
            'monto' => 3000
        ]);
        CustomerService::create([
            'key' => 'AV',
            'name' => 'Auxilio vial',
            'monto' => 250
        ]);
    }
}
