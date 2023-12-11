<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Usuário 1
        DB::table('usuarios')->insert([
            'nome_completo' => 'Zeca',
            'data_nascimento' => '1990-01-01',
            'cpf' => '07448185080',
            'email' => 'zeca.doe@example.com',
            'senha' => Hash::make('senha123'),
            'cep' => 12345678,
            'logradouro' => 'Rua 10',
            'bairro' => 'centro',
            'localidade' => 'prudente',
            'uf' => 'sp',
            'complemento' => 'Apartamento 123',
            'numero_endereco' => 456,
        ]);

        // Usuário 2
        DB::table('usuarios')->insert([
            'nome_completo' => 'Doquinha',
            'data_nascimento' => '1990-01-01',
            'cpf' => '89360059013',
            'email' => 'doq.doe@example.com',
            'senha' => Hash::make('senha123'),
            'cep' => 12345678,
            'logradouro' => 'Rua 10',
            'bairro' => 'centro',
            'localidade' => 'prudente',
            'uf' => 'sp',
            'complemento' => 'Apartamento 123',
            'numero_endereco' => 456,
        ]);
    }
}
