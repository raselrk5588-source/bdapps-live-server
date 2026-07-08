<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->updateOrInsert(
            ['id' => 1],
            ['name' => 'Admin']
        );

        DB::table('roles')->updateOrInsert(
            ['id' => 2],
            ['name' => 'User']
        );
    }
}
