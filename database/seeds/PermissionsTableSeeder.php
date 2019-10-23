<?php

use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permissions')->insert([
            'name' => 'read',
            'label' => 'Permissão de leitura generica',
        ]);

        DB::table('permissions')->insert([
            'name' => 'write',
            'label' => 'Permissão de escrita generica',
        ]);

    }
}
