<?php

namespace Database\Seeders;

use App\Models\State;
use Illuminate\Database\Seeder;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        State::create([
            'iso' => 'CMX',
            'name' => 'Ciudad de México',
            'description' => 'Ciudad de México'
        ]);

        State::create([
            'iso' => 'AGU',
            'name' => 'Aguascalientes',
            'description' => 'Aguascalientes'
        ]);

        State::create([
            'iso' => 'BCN',
            'name' => 'Baja California',
            'description' => 'Baja California'
        ]);

        State::create([
            'iso' => 'BCS',
            'name' => 'Baja California Sur',
            'description' => 'Baja California Sur'
        ]);

        State::create([
            'iso' => 'CAM',
            'name' => 'Campeche',
            'description' => 'Campeche'
        ]);

        State::create([
            'iso' => 'COA',
            'name' => 'Coahuila de Zaragoza',
            'description' => 'Coahuila de Zaragoza'
        ]);

        State::create([
            'iso' => 'COL',
            'name' => 'Colima',
            'description' => 'Colima'
        ]);

        State::create([
            'iso' => 'CHP',
            'name' => 'Chiapas',
            'description' => 'Chiapas'
        ]);

        State::create([
            'iso' => 'CHH',
            'name' => 'Chihuahua',
            'description' => 'Chihuahua'
        ]);

        State::create([
            'iso' => 'DUR',
            'name' => 'Durango',
            'description' => 'Durango'
        ]);

        State::create([
            'iso' => 'GUA',
            'name' => 'Guanajuato',
            'description' => 'Guanajuato'
        ]);

        State::create([
            'iso' => 'GRO',
            'name' => 'Guerrero',
            'description' => 'Guerrero'
        ]);

        State::create([
            'iso' => 'HID',
            'name' => 'Hidalgo',
            'description' => 'Hidalgo'
        ]);

        State::create([
            'iso' => 'JAL',
            'name' => 'Jalisco',
            'description' => 'Jalisco'
        ]);

        State::create([
            'iso' => 'MEX',
            'name' => 'México',
            'description' => 'México'
        ]);

        State::create([
            'iso' => 'MIC',
            'name' => 'Michoacán de Ocampo',
            'description' => 'Michoacán de Ocampo'
        ]);

        State::create([
            'iso' => 'MOR',
            'name' => 'Morelos',
            'description' => 'Morelos'
        ]);

        State::create([
            'iso' => 'NAY',
            'name' => 'Nayarit',
            'description' => 'Nayarit'
        ]);

        State::create([
            'iso' => 'NLE',
            'name' => 'Nuevo León',
            'description' => 'Nuevo León'
        ]);

        State::create([
            'iso' => 'OAX',
            'name' => 'Oaxaca',
            'description' => 'Oaxaca'
        ]);

        State::create([
            'iso' => 'PUE',
            'name' => 'Puebla',
            'description' => 'Puebla'
        ]);

        State::create([
            'iso' => 'QUE',
            'name' => 'Querétaro',
            'description' => 'Querétaro'
        ]);

        State::create([
            'iso' => 'ROO',
            'name' => 'Quintana Roo',
            'description' => 'Quintana Roo'
        ]);

        State::create([
            'iso' => 'SLP',
            'name' => 'San Luis Potosí',
            'description' => 'San Luis Potosí'
        ]);

        State::create([
            'iso' => 'SIN',
            'name' => 'Sinaloa',
            'description' => 'Sinaloa'
        ]);

        State::create([
            'iso' => 'SON',
            'name' => 'Sonora',
            'description' => 'Sonora'
        ]);

        State::create([
            'iso' => 'TAB',
            'name' => 'Tabasco',
            'description' => 'Tabasco'
        ]);

        State::create([
            'iso' => 'TAM',
            'name' => 'Tamaulipas',
            'description' => 'Tamaulipas'
        ]);

        State::create([
            'iso' => 'TLA',
            'name' => 'Tlaxcala',
            'description' => 'Tlaxcala'
        ]);

        State::create([
            'iso' => 'VER',
            'name' => 'Veracruz de Ignacio de la Llave Veracruz',
            'description' => 'Veracruz de Ignacio de la Llave Veracruz'
        ]);

        State::create([
            'iso' => 'YUC',
            'name' => 'Yucatán',
            'description' => 'Yucatán'
        ]);

        State::create([
            'iso' => 'ZAC',
            'name' => 'Zacatecas',
            'description' => 'Zacatecas'
        ]);
    }
}
