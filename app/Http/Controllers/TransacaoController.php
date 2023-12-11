<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transacoes;
use App\Models\Usuario;
use App\Services\TransacaoService;
use App\Services\JwtService;

class TransacaoController extends Controller
{
    protected $transacaoService;
    protected $jwtService;

    public function __construct(TransacaoService $transacaoService, JwtService $jwtService)
    {
        $this->transacaoService = $transacaoService;
        $this->jwtService = $jwtService;
    }

    public function deposito(Request $request)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['error' => 'Token não fornecido'], 401);
        }

        $dadosUsuario = $this->jwtService->verifyToken($token);

        if (!$dadosUsuario) {
            return response()->json(['error' => 'Token inválido'], 401);
        }

        // Obtém o ID do usuário
        $userId = $dadosUsuario->data->id;

        // Validação dos dados de deposito
        $this->transacaoService->validarDeposito($request->all());

        // Lógica para gerar o código de autorização DEP0000
        $codigo_autorizacao = 'DEP' . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);

        // Salvar a transação no banco
        Transacoes::create([
            'usuario_id' => $userId,
            'quantia' => $request->quantia,
            'codigo_autorizacao' => $codigo_autorizacao,
            'tipo' => 'deposito',
            'conta_destino' => $request->conta_destino,
        ]);

        // Incremento no campo saldo na tabela usuarios
        $usuarioDestino = Usuario::where('numero_conta', $request->conta_destino)->first();

        if ($usuarioDestino) {
            $usuarioDestino->saldo += $request->quantia;
            $usuarioDestino->save();
        }


        return response()->json(['message' => 'Depósito realizado com sucesso']);
    }



    public function transferencia(Request $request)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['error' => 'Token não fornecido'], 401);
        }

        $dadosUsuario = $this->jwtService->verifyToken($token);

        if (!$dadosUsuario) {
            return response()->json(['error' => 'Token inválido'], 401);
        }

        // Obtém o ID do usuário
        $userId = $dadosUsuario->data->id;

        // Validação dos dados de transferencia
        $this->transacaoService->validarTransferencia($request->all(),$userId);

        // Lógica para gerar o código de autorização TRANSF0000
        $codigo_autorizacao = 'TRANSF' . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);


        //Conta do usuário que está fazendo a transação
        $usuarioOrigem = Usuario::where('id', $userId)->first();

        if($usuarioOrigem->numero_conta == $request->conta_destino)
        {
            return response()->json(['error' => ' Conta de destino não pode ser igual á origem'], 401);
        }

        // Salvar a transação no banco
        Transacoes::create([
            'usuario_id' => $userId,
            'quantia' => $request->quantia,
            'codigo_autorizacao' => $codigo_autorizacao,
            'tipo' => 'transferencia',
            'conta_origem'=>$usuarioOrigem->numero_conta,
            'conta_destino' => $request->conta_destino,
        ]);

        /// Incremento no campo saldo na tabela usuarios
        $usuarioDestino = Usuario::where('numero_conta', $request->conta_destino)->first();

        if ($usuarioDestino) {
            $usuarioDestino->saldo += $request->quantia;
            $usuarioDestino->save();

            $usuarioOrigem->saldo -= $request->quantia;
            $usuarioOrigem->save();
        }

        return response()->json(['message' => 'Transferência realizada com sucesso']);
    }
}
