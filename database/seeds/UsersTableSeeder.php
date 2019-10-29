<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'id' => 1,
            'name' => 'ADM',
            'email' => 'admin@email.com',
            'password' => bcrypt('123456789'),
        ]);

        DB::table('users')->insert([
            'id' => 2,
            'name' => 'Anunciante',
            'email' => 'anunciante@email.com',
            'password' => bcrypt('123456789'),
        ]);
    }
}
