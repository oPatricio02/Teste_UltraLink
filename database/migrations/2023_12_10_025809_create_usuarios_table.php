<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsuariosTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('nome_completo');
            $table->date('data_nascimento');
            $table->string('cpf')->unique();
            $table->string('email')->unique();
            $table->string('senha'); // Lembre-se de criptografar antes de salvar no banco
            $table->string('cep');
            $table->string('complemento')->nullable();
            $table->string('numero_endereco');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
}
