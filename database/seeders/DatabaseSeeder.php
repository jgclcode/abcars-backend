<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(RoleSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(RolesAndPermissions::class);
        $this->call(SourcesSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(StateSeeder::class);
        $this->call(VehiclebodySeeder::class);
        /* $this->call(PaymentPlatformsTableSeeder::class);
        $this->call(CurrenciesTableSeeder::class); */
        $this->call(CustomerServiceTableSeeder::class); 

        // Brands and Models Seeders
        $this->call(BrandSeeder::class);
        $this->call(CarModelsSeeder::class);
        
        // Services Seeder
        $this->call(ServiceSeeder::class);

        $this->call(BranchSeeder::class);

        $this->call(ShieldSeeder::class);

        $this->call(DocumentSeeder::class);

        $this->call(DamageSeeder::class);

        $this->call(Service_featureSeeder::class);
    }
}
