<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class StaffTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('staff')->delete();
        
        \DB::table('staff')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'staff',
                'email' => 'joshcarillo022@gmail.com',
                'password' => '$2y$12$fjdkV5iAYYc3MA25j0CBZex3WyUQi9xrrZkbk9qrJFTXAlSV55BPe',
                'reset_token' => NULL,
                'reset_token_created_at' => NULL,
                'created_at' => '2025-03-31 03:02:12',
                'updated_at' => '2025-04-21 13:37:29',
            ),
            1 => 
            array (
                'id' => 7,
                'name' => 'staff2',
                'email' => 'staff2@example.com',
                'password' => '$2y$12$HCZQy3jmy4BwHR6eym9vYezaNjRsxthz7KWGFb3Ii5vMaYgUhiKDy',
                'reset_token' => NULL,
                'reset_token_created_at' => NULL,
                'created_at' => '2025-04-12 21:41:57',
                'updated_at' => '2025-05-07 10:49:52',
            ),
        ));
        
        
    }
}