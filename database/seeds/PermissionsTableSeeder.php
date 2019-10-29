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
            'id' => 1,
            'name' => 'read',
            'label' => 'Permissão de leitura generica',
        ]);

        DB::table('permissions')->insert([
            'id' => 2,
            'name' => 'write',
            'label' => 'Permissão de escrita generica',
        ]);

        DB::table('permissions')->insert([
            'id' => 3,
            'name' => 'edit',
            'label' => 'Permissão de editar generica',
        ]);

        DB::table('permissions')->insert([
            'id' => 4,
            'name' => 'finalize',
            'label' => 'permissão para alterar o status da demanda',
        ]);

        DB::table('permissions')->insert([
            'id' => 5,
            'name' => 'adm',
            'label' => 'Full permission',
        ]);

    }
}
