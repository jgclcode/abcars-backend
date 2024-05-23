<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Document;

class DocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $document = Document::create(['name' => 'Factura']);
        $document = Document::create(['name' => 'REPUVE']);
        $document = Document::create(['name' => 'Tarjeta de circulación/baja']);
        $document = Document::create(['name' => 'Tenencia']);
        $document = Document::create(['name' => 'Verificación']);
        $document = Document::create(['name' => 'Constancia de cambio de propietario']);
        $document = Document::create(['name' => 'Identificación oficial']);
        $document = Document::create(['name' => 'Curp']);
        
    }
}
