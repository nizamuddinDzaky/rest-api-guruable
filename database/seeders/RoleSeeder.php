<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('m_roles')->insert([
            [
                'role_name'=> 'SuperAdmin',
            ],
            [
                'role_name'=> 'Admin',
            ],
            [
                'role_name'=> 'Teacher',
            ],
            [
                'role_name'=> 'Student',
            ],
        ]);
    }
}
