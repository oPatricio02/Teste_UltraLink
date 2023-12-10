<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transacoes;
use App\Models\Usuario;

class TransacaoController extends Controller
{
    protected $transacaoService;

    public function __construct(TransacaoService $transacaoService)
    {
        $this->transacaoService = $transacaoService;
    }

    public function deposito(Request $request)
    {
        // Validação dos dados de deposito
        $this->transacaoService->validarDeposito($request->all());

        // Lógica para gerar o código de autorização DEP0000
        $codigo_autorizacao = 'DEP' . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);

        // Salvar a transação no banco
        Transacoes::create([
            'usuario_id' => auth()->user()->id,
            'quantia' => $request->quantia,
            'codigo_autorizacao' => $codigo_autorizacao,
            'tipo' => 'deposito',
            'conta_destino' => $request->conta_destino,
        ]);

        return response()->json(['message' => 'Depósito realizado com sucesso']);
    }

    public function transferencia(Request $request)
    {
        // Validação dos dados de transferencia
        $this->transacaoService->validarTransferencia($request->all());

        // Lógica para gerar o código de autorização TRANSF0000
        $codigo_autorizacao = 'TRANSF' . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);

        // Salvar a transação no banco
        Transacoes::create([
            'usuario_id' => auth()->user()->id,
            'quantia' => $request->quantia,
            'codigo_autorizacao' => $codigo_autorizacao,
            'tipo' => 'transferencia',
            'conta_origem'=>auth()->user()->numero_conta,
            'conta_destino' => $request->conta_destino,
        ]);

        //criar regra para incrementar valor na conta que recebeu a transferencia.

        return response()->json(['message' => 'Transferência realizada com sucesso']);
    }
}
