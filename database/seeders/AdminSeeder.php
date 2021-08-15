<?php

namespace Database\Seeders;
use DB;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('m_admins')->insert([
            [
                'admin_name'=> 'Super Admin',
                'admin_telpn' => '983264932420347',
                'admin_birth_place' => 'Surabaya',
                'admin_birth_date' => '1996-10-08',
                'admin_user_id' => '1',
                'admin_address'=> 'Gresik Sono'
            ],
        ]);
    }
}
