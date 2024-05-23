<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{   
    public function run()
    {
        $role = Role::create(['name' => 'developer']);        
        $role = Role::create(['name' => 'marketing']);
        $role = Role::create(['name' => 'client']);
        $role = Role::create(['name' => 'appraiser']);
        $role = Role::create(['name' => 'valuator']);
        $role = Role::create(['name' => 'appraiser_technician']);
        $role = Role::create(['name' => 'sales']);
        $role = Role::create(['name' => 'aftersales']);
        $role = Role::create(['name' => 'gestor']);
        $role = Role::create(['name' => 'spare_parts']);
        $role = Role::create(['name' => 'spare_parts_manager']);
        $role = Role::create(['name' => 'contact']);
        $role = Role::create(['name' => 'technician']);       
        $role = Role::create(['name' => 'accountant']);        
    }
}
