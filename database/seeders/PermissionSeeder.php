<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $permission = Permission::create([
            'name' => 'create user',
            'guard_name' => 'web'          
        ]);

        $permission = Permission::create([
            'name' => 'read-users',  
            'guard_name' => 'web'          
        ]);

        $permission = Permission::create([
            'name' => 'update-user',   
            'guard_name' => 'web'           
        ]);

        $permission = Permission::create([
            'name' => 'delete-user',      
            'guard_name' => 'web'    
        ]);
    }
}
