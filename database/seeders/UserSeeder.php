<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Client;
use App\Models\Valuator;
use App\Models\Technician;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {        
        // Developer
        $developer = User::create([
            'name' => 'Developer',
            'surname' => 'ABCars',
            'email' => 'dev@abcars.mx',
            'password' => hash('sha256', 'Hola2022@@'),
            'gender' => 'm'
        ]);
        $developer->assignRole('developer');

        $cliente = Client::create([
            'phone1' => 1234567890,
            'phone2' => 1234567890,
            'curp' => 'AABC000101HPLNHS01',
            'points' => 0,
            'rewards' => 'ABCarsDeveloper',
            'user_id' => 1,
            'source_id' => 1
        ]);

        // Marketing   
        $marketing = User::create([
            'name' => 'Marketing',
            'surname' => 'ABCars',
            'email' => 'marketing@abcars.mx',
            'password' => hash('sha256', 'Hola2022@@'),
            'gender' => 'm'
        ]);
        $marketing->assignRole('marketing');

        $cliente = Client::create([
            'phone1' => 1234567890,
            'phone2' => 1234567890,
            'curp' => 'AABC000101HPLNHS01',
            'points' => 0,
            'rewards' => 'ABCarsMarketing',
            'user_id' => 2,
            'source_id' => 1
        ]);        

        // Owner
        $owner = User::create([
            'name' => 'Owner',
            'surname' => 'ABCars',
            'email' => 'pro@abcars.mx',
            'password' => hash('sha256', 'Hola2022@@'),
            'gender' => 'm'
        ]);
        $owner->assignRole('valuator');

        $cliente = Client::create([
            'phone1' => 1234567890,
            'phone2' => 1234567890,
            'curp' => 'AABC000101HPLNHS01',
            'points' => 0,
            'rewards' => 'ABCarsOwner',
            'user_id' => 3,
            'source_id' => 1
        ]);

        // Technical
        $technical = User::create([
            'name' => 'Technical',
            'surname' => 'ABCars',
            'email' => 'tec@abcars.mx',
            'password' => hash('sha256', 'Hola2022@@'),
            'gender' => 'm'
        ]);
        $technical->assignRole('appraiser');

        $cliente = Client::create([
            'phone1' => 1234567890,
            'phone2' => 1234567890,
            'curp' => 'AABC000101HPLNHS01',
            'points' => 0,
            'rewards' => 'ABCarsTechnical',
            'user_id' => 4,
            'source_id' => 1
        ]);

        // Sales
        $sales = User::create([
            'name' => 'Sales',
            'surname' => 'ABCars',
            'email' => 'sales@abcars.mx',
            'password' => hash('sha256', 'Hola2022@@'),
            'gender' => 'm'
        ]);
        $sales->assignRole('sales');

        $cliente = Client::create([
            'phone1' => 1234567890,
            'phone2' => 1234567890,
            'curp' => 'AABC000101HPLNHS01',
            'points' => 0,
            'rewards' => 'ABCarsSales',
            'user_id' => 5,
            'source_id' => 1
        ]);

        // Aftersales
        $aftersales = User::create([
            'name' => 'Aftersales',
            'surname' => 'ABCars',
            'email' => 'aftersales@abcars.mx',
            'password' => hash('sha256', 'Hola2022@@'),
            'gender' => 'm'
        ]);
        $aftersales->assignRole('aftersales');

        $cliente = Client::create([
            'phone1' => 1234567890,
            'phone2' => 1234567890,
            'curp' => 'AABC000101HPLNHS01',
            'points' => 0,
            'rewards' => 'ABCarsAftersales',
            'user_id' => 6,
            'source_id' => 1
        ]);

        // Manager
        $manager = User::create([
            'name' => 'Manager',
            'surname' => 'ABCars',
            'email' => 'manager@abcars.mx',
            'password' => hash('sha256', 'Hola2022@@'),
            'gender' => 'm'
        ]);
        $manager->assignRole('gestor');

        $cliente = Client::create([
            'phone1' => 1234567890,
            'phone2' => 1234567890,
            'curp' => 'AABC000101HPLNHS01',
            'points' => 0,
            'rewards' => 'ABCarsManager',
            'user_id' => 7,
            'source_id' => 1
        ]);

        // Appraiser Technician
        $appraiser_technician = User::create([
            'name' => 'Appraiser Technician',
            'surname' => 'ABCars',
            'email' => 'tecval@abcars.mx',
            'password' => hash('sha256', 'Hola2022@@'),
            'gender' => 'm'
        ]);
        $appraiser_technician->assignRole('appraiser_technician');

        $cliente = Client::create([
            'phone1' => 1234567890,
            'phone2' => 1234567890,
            'curp' => 'AABC000101HPLNHS01',
            'points' => 0,
            'rewards' => 'ABCarsAppraiserTechnician',
            'user_id' => 8,
            'source_id' => 1
        ]);

        // Spare Parts
        $spare_parts = User::create([
            'name' => 'Spare Parts',
            'surname' => 'ABCars',
            'email' => 'parts@abcars.mx',
            'password' => hash('sha256', 'Hola2022@@'),
            'gender' => 'm'
        ]);
        $spare_parts->assignRole('spare_parts');

        $cliente = Client::create([
            'phone1' => 1234567890,
            'phone2' => 1234567890,
            'curp' => 'AABC000101HPLNHS01',
            'points' => 0,
            'rewards' => 'ABCarsSpareParts',
            'user_id' => 9,
            'source_id' => 1
        ]);

        // Spare Parts Manager
        $spare_parts_manager = User::create([
            'name' => 'Spare Parts Manager',
            'surname' => 'ABCars',
            'email' => 'pmanager@abcars.mx',
            'password' => hash('sha256', 'Hola2022@@'),
            'gender' => 'm'
        ]);
        $spare_parts_manager->assignRole('spare_parts_manager');

        $cliente = Client::create([
            'phone1' => 1234567890,
            'phone2' => 1234567890,
            'curp' => 'AABC000101HPLNHS01',
            'points' => 0,
            'rewards' => 'ABCarsSparePartsManager',
            'user_id' => 10,
            'source_id' => 1
        ]);

        // Contact
        $contact = User::create([
            'name' => 'Contact',
            'surname' => 'ABCars',
            'email' => 'contact@abcars.mx',
            'password' => hash('sha256', 'Hola2022@@'),
            'gender' => 'm'
        ]);
        $contact->assignRole('contact');

        $cliente = Client::create([
            'phone1' => 1234567890,
            'phone2' => 1234567890,
            'curp' => 'AABC000101HPLNHS01',
            'points' => 0,
            'rewards' => 'ABCarsContact',
            'user_id' => 11,
            'source_id' => 1
        ]);

        // Intelimotors
        $contact = User::create([
            'name' => 'Intelimotors',
            'surname' => 'Intelimotors',
            'email' => 'intelimotors@abcars.mx',
            'password' => hash('sha256', 'Intelimotors2022@@'),
            'gender' => 'm'
        ]);
        $contact->assignRole('contact');

        $cliente = Client::create([
            'phone1' => 1234567890,
            'phone2' => 1234567890,
            'curp' => 'AABC000101HPLNHS01',
            'points' => 0,
            'rewards' => '',
            'user_id' => 12,
            'source_id' => 1
        ]);

        // Valuadores

        // valuador 1
        $valuator_user = User::create([
            'name' => 'Juan',
            'surname' => 'Ballesteros',
            'email' => 'jballesteros@abcars.mx',
            'password' => hash('sha256', 'Hola2022@@'),
            'gender' => 'm'
        ]);
        $valuator_user->assignRole('valuator');

        $cliente = Client::create([
            'phone1' => 1234567890,
            'phone2' => 1234567890,
            'curp' => 'AABC000101HPLNHS01',
            'points' => 0,
            'rewards' => '',
            'user_id' => 13,
            'source_id' => 1
        ]);

        $valuator = Valuator::create([
            'status' => 'active',
            'user_id' => 13
        ]);

        // valuador 2
        $valuator_user = User::create([
            'name' => 'Fabian',
            'surname' => 'Tapia',
            'email' => 'ftapia@abcars.mx',
            'password' => hash('sha256', 'Hola2022@@'),
            'gender' => 'm'
        ]);
        $valuator_user->assignRole('valuator');

        $cliente = Client::create([
            'phone1' => 1234567890,
            'phone2' => 1234567890,
            'curp' => 'AABC000101HPLNHS01',
            'points' => 0,
            'rewards' => '',
            'user_id' => 14,
            'source_id' => 1
        ]);

        $valuator = Valuator::create([
            'status' => 'active',
            'user_id' => 14
        ]);

        // valuador 3
        $valuator_user = User::create([
            'name' => 'Edgar',
            'surname' => 'Barranco',
            'email' => 'ebarranco@abcars.mx',
            'password' => hash('sha256', 'Hola2022@@'),
            'gender' => 'm'
        ]);
        $valuator_user->assignRole('valuator');

        $cliente = Client::create([
            'phone1' => 1234567890,
            'phone2' => 1234567890,
            'curp' => 'AABC000101HPLNHS01',
            'points' => 0,
            'rewards' => '',
            'user_id' => 15,
            'source_id' => 1
        ]);

        $valuator = Valuator::create([
            'status' => 'active',
            'user_id' => 15
        ]);

        // Tecnicos

        // Tecnico 1
        $technician_user = User::create([
            'name' => 'technician1',
            'surname' => 'technician',
            'email' => 'technician1@abcars.mx',
            'password' => hash('sha256', 'Hola2022@@'),
            'gender' => 'm'
        ]);
        $technician_user->assignRole('technician');

        $cliente = Client::create([
            'phone1' => 1234567890,
            'phone2' => 1234567890,
            'curp' => 'AABC000101HPLNHS01',
            'points' => 0,
            'rewards' => '',
            'user_id' => 16,
            'source_id' => 1
        ]);

        $technician = Technician::create([            
            'user_id' => 16
        ]);

        // Tecnico 2
        $technician_user = User::create([
            'name' => 'technician2',
            'surname' => 'technician',
            'email' => 'technician2@abcars.mx',
            'password' => hash('sha256', 'Hola2022@@'),
            'gender' => 'm'
        ]);
        $technician_user->assignRole('technician');

        $cliente = Client::create([
            'phone1' => 1234567890,
            'phone2' => 1234567890,
            'curp' => 'AABC000101HPLNHS01',
            'points' => 0,
            'rewards' => '',
            'user_id' => 17,
            'source_id' => 1
        ]);

        $technician = Technician::create([            
            'user_id' => 17
        ]);

        // Tecnico 3
        $technician_user = User::create([
            'name' => 'technician3',
            'surname' => 'technician',
            'email' => 'technician3@abcars.mx',
            'password' => hash('sha256', 'Hola2022@@'),
            'gender' => 'm'
        ]);
        $technician_user->assignRole('technician');

        $cliente = Client::create([
            'phone1' => 1234567890,
            'phone2' => 1234567890,
            'curp' => 'AABC000101HPLNHS01',
            'points' => 0,
            'rewards' => '',
            'user_id' => 18,
            'source_id' => 1
        ]);

        $technician = Technician::create([            
            'user_id' => 18
        ]);

        // accountant
        $accountant = User::create([
            'name' => 'accountant',
            'surname' => 'accountant',
            'email' => 'accountant@abcars.mx',
            'password' => hash('sha256', 'Hola2022@@'),
            'gender' => 'm'
        ]);
        $accountant->assignRole('accountant');

        $cliente = Client::create([
            'phone1' => 8888887360,
            'phone2' => 8888887360,
            'curp' => 'MDM000101HPLNHS01',
            'points' => 0,
            'rewards' => '',
            'user_id' => 19,
            'source_id' => 1
        ]);

        // valuador 4
        $valuator_user = User::create([
            'name' => 'Cesar',
            'surname' => 'Fuentes',
            'email' => 'cfuentes@abcars.mx',
            'password' => hash('sha256', 'Hola2022@@'),
            'gender' => 'm'
        ]);
        $valuator_user->assignRole('valuator');

        $cliente = Client::create([
            'phone1' => 1234567890,
            'phone2' => 1234567890,
            'curp' => 'AABC000101HPLNHS01',
            'points' => 0,
            'rewards' => '',
            'user_id' => 20,
            'source_id' => 1
        ]);

        $valuator = Valuator::create([
            'status' => 'active',
            'user_id' => 20
        ]);

        // valuador 5
        $valuator_user = User::create([
            'name' => 'Javier',
            'surname' => 'Zavala',
            'email' => 'jzavala@abcars.mx',
            'password' => hash('sha256', 'Hola2022@@'),
            'gender' => 'm'
        ]);
        $valuator_user->assignRole('valuator');

        $cliente = Client::create([
            'phone1' => 1234567890,
            'phone2' => 1234567890,
            'curp' => 'AABC000101HPLNHS01',
            'points' => 0,
            'rewards' => '',
            'user_id' => 21,
            'source_id' => 1
        ]);

        $valuator = Valuator::create([
            'status' => 'active',
            'user_id' => 21
        ]);

        // valuador 6
        $valuator_user = User::create([
            'name' => 'Martha',
            'surname' => 'Condado',
            'email' => 'mcondado@abcars.mx',
            'password' => hash('sha256', 'Hola2022@@'),
            'gender' => 'f'
        ]);
        $valuator_user->assignRole('valuator');

        $cliente = Client::create([
            'phone1' => 1234567890,
            'phone2' => 1234567890,
            'curp' => 'AABC000101HPLNHS01',
            'points' => 0,
            'rewards' => '',
            'user_id' => 22,
            'source_id' => 1
        ]);

        $valuator = Valuator::create([
            'status' => 'active',
            'user_id' => 22
        ]);

        // valuador 7
        $valuator_user = User::create([
            'name' => 'Patricia',
            'surname' => 'Sanchez',
            'email' => 'psanchez@abcars.mx',
            'password' => hash('sha256', 'Hola2022@@'),
            'gender' => 'f'
        ]);
        $valuator_user->assignRole('valuator');

        $cliente = Client::create([
            'phone1' => 1234567890,
            'phone2' => 1234567890,
            'curp' => 'AABC000101HPLNHS01',
            'points' => 0,
            'rewards' => '',
            'user_id' => 23,
            'source_id' => 1
        ]);

        $valuator = Valuator::create([
            'status' => 'active',
            'user_id' => 23
        ]);


    }
}
