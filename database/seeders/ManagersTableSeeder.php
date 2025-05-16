<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ManagersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('managers')->delete();
        
        \DB::table('managers')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Capacity Planning',
                'email' => 'jelocarillo22@gmail.com',
                'password' => '$2y$12$E4un1lsHIWnXpBBMJgW9deaWB1BXXgx3XQ9V233/KSAoPMSCWWi56',
                'reset_token' => '$2y$12$fRfK0SrxxJXVUP8HN94UpOSjo5BE1Psljke9PKJdWpmERjna0/eKW',
                'reset_token_created_at' => '2025-04-22 06:39:56',
                'manager_number' => 1,
                'created_at' => '2025-03-31 03:23:26',
                'updated_at' => '2025-04-22 06:39:56',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Production Chief',
                'email' => '2@gmail.com',
                'password' => '$2y$12$E1VYNlVg7Vb79/3XdpMqyeXSuiPwOTsfEMeW5N93h3DRNUrIdVd.6',
                'reset_token' => NULL,
                'reset_token_created_at' => NULL,
                'manager_number' => 2,
                'created_at' => '2025-03-31 03:24:30',
                'updated_at' => '2025-04-20 01:39:45',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Process Engineer',
                'email' => '3@gmail.com',
                'password' => '$2y$12$QB2CbZCgIr0O5jXDDGO52ONjC9iA9R7qCgi.jUCNgAY42CjhNFy0C',
                'reset_token' => NULL,
                'reset_token_created_at' => NULL,
                'manager_number' => 3,
                'created_at' => '2025-03-31 03:25:05',
                'updated_at' => '2025-03-31 16:01:49',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'QA Engineer',
                'email' => '4@gmail.com',
                'password' => '$2y$12$pkcFrP6CVTCoPcMv5D8b6.rbfFrbjwpjqqo0/OMsQCuZ6HBDu0YZW',
                'reset_token' => NULL,
                'reset_token_created_at' => NULL,
                'manager_number' => 4,
                'created_at' => '2025-03-31 03:25:05',
                'updated_at' => '2025-03-31 16:03:51',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Planning Manager',
                'email' => '5@gmail.com',
                'password' => '$2y$12$Z8LdAmhsKK7Bm0eK.TahW.eWHEfR9bxz8gwfjVBVcDuWF0WZ9Ort.',
                'reset_token' => NULL,
                'reset_token_created_at' => NULL,
                'manager_number' => 5,
                'created_at' => '2025-04-02 09:46:40',
                'updated_at' => '2025-04-02 09:49:36',
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'Production Manager',
                'email' => '6@gmail.com',
                'password' => '$2y$12$E/xbisov/KRdyvgq2iePA.YltVZ1lK8e77WNjFHY4c6okqKr6m.wu',
                'reset_token' => NULL,
                'reset_token_created_at' => NULL,
                'manager_number' => 6,
                'created_at' => '2025-04-02 09:46:40',
                'updated_at' => '2025-04-02 13:33:45',
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'EE Head',
                'email' => '7@gmail.com',
                'password' => '$2y$12$Uwn10QKs5YRF0hH2p.PN.eSVRmNcOap1gCRAIzQwun7yacrxps5vS',
                'reset_token' => NULL,
                'reset_token_created_at' => NULL,
                'manager_number' => 7,
                'created_at' => '2025-04-02 09:47:56',
                'updated_at' => '2025-04-02 13:36:13',
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'QAE Manager',
                'email' => '8@gmail.com',
                'password' => '$2y$12$E.0Ce6XbvObql7kUdoORseTuaw82XpOlq4jf0huwMUQ3OtQdYu5CK',
                'reset_token' => NULL,
                'reset_token_created_at' => NULL,
                'manager_number' => 8,
                'created_at' => '2025-04-02 09:47:56',
                'updated_at' => '2025-04-09 01:05:30',
            ),
            8 => 
            array (
                'id' => 9,
                'name' => 'General Manager',
                'email' => '9@gmail.com',
                'password' => '$2y$12$bAHLEuoCP6MhI0.3UTblJOBtnFv.jQOxOssc1VpgNicSF7FdkU0de',
                'reset_token' => NULL,
                'reset_token_created_at' => NULL,
                'manager_number' => 9,
                'created_at' => '2025-04-02 09:48:50',
                'updated_at' => '2025-04-09 21:46:21',
            ),
        ));
        
        
    }
}