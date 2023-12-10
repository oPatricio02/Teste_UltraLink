<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransacoesTable extends Migration
{
    public function up()
    {
        Schema::create('transacoes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('usuario_id');
            $table->decimal('quantia', 10, 2);
            $table->string('codigo_autorizacao');
            $table->enum('tipo', ['deposito', 'transferencia']);
            $table->string('conta_origem');
            $table->string('conta_destino')->nullable();
            $table->timestamps();

            $table->foreign('usuario_id')->references('id')->on('usuarios');
            $table->foreign('conta_destino')->references('numero_conta')->on('usuarios')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('transacoes');
    }
}

