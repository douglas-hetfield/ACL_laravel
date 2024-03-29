<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            'id' => 1,
            'name' => 'administrador',
            'label' => 'todas as permissões genericas',
        ]);

        DB::table('roles')->insert([
            'id' => 2,
            'name' => 'anunciante',
            'label' => 'possui apenas permissão para visualização emanipulação de suas próprias demandas.',
        ]);

    }
}
