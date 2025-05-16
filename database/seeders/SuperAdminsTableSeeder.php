<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SuperAdminsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('super_admins')->delete();
        
        \DB::table('super_admins')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'admin',
                'email' => 'superadmin@gmail.com',
                'password' => '$2y$12$8yemJ3HxxHGEMahiT1YBGuOieDVR98cISbFcrUSadzmMk5PdEtG0C',
                'remember_token' => NULL,
                'created_at' => '2025-04-14 13:04:05',
                'updated_at' => '2025-04-14 13:05:07',
            ),
        ));
        
        
    }
}