<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $table = 'usuarios';
    protected $fillable = ['nome_completo', 'data_nascimento', 'cpf', 'email', 'senha', 'cep', 'complemento', 'numero_endereco',
    'numero_conta','saldo','status',];

    protected $hidden = [
        'senha',
    ];
}
