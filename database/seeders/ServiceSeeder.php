<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Service::create([
            'name' => '1er Mantenimiento de Póliza',
            'description' => '6,000 km o 4 meses de uso',
            'amount' => 1,
            'points' => 0,
            'interchange' => 0
        ]);

        Service::create([
            'name' => '2do Mantenimiento de Póliza',
            'description' => '12,000 km o 8 meses de uso',
            'amount' => 1,
            'points' => 0,
            'interchange' => 0
        ]);

        Service::create([
            'name' => '3er Mantenimiento de Póliza',
            'description' => '18,000 km o 12 meses de uso',
            'amount' => 1,
            'points' => 0,
            'interchange' => 0
        ]);

        Service::create([
            'name' => 'Mantenimiento Preventivo fuera de Garantía',
            'description' => '28,000 km o 18 meses de uso',
            'amount' => 1,
            'points' => 0,
            'interchange' => 0
        ]);

        Service::create([
            'name' => 'Mantenimiento Preventivo fuera de Garantía',
            'description' => '38,000 km o 24 meses de uso',
            'amount' => 1,
            'points' => 0,
            'interchange' => 0
        ]);

        Service::create([
            'name' => 'Mantenimiento Preventivo fuera de Garantía',
            'description' => '48,000 km o 30 meses de uso',
            'amount' => 1,
            'points' => 0,
            'interchange' => 0
        ]);

        Service::create([
            'name' => 'Mantenimiento Preventivo fuera de Garantía',
            'description' => '58,000 km o 36 meses de uso',
            'amount' => 1,
            'points' => 0,
            'interchange' => 0
        ]);
    }
}
