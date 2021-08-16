<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('m_users')->insert([
            [
                'user_username'=> 'superadmin',
                'user_email' => 'SuperAdmin@admin.com',
                'user_status_verifikasi' => 1,
                'user_status_active' => 1,
                'password' => Hash::make('password'),
                'user_password_str' => 'password',
                'user_role_id'  => 1,
                'user_role_name'  => 'SuperAdmin',
            ],
        ]);
    }
}
