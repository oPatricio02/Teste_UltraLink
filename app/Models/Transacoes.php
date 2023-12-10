<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transacoes extends Model
{
    protected $fillable = [
        'usuario_id',
        'quantia',
        'codigo_autorizacao',
        'tipo',
        'conta_origem',
        'conta_destino',
    ];

    public function user()
    {
        return $this->belongsTo(Usuario::class);
    }

    public function contaDestino()
    {
        return $this->belongsTo(Conta::class, 'numero');
    }
}
