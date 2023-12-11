<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;
use App\Rules\Cpf;
use App\Services\JwtService;
use Illuminate\Http\Response;


class UsuarioController extends Controller
{

    private $jwtService;

    public function __construct(JwtService $jwtService)
    {
        $this->jwtService = $jwtService;
    }

    public function registrar(Request $request)
    {
        //validações
        try {
            $this->validate($request, [
                'nome_completo' => 'required|string',
                'data_nascimento' => 'required|date',
                'cpf' => ['required', new \App\Rules\Cpf, 'unique:usuarios'],
                'email' => 'required|email|unique:usuarios,email',
                'senha' => 'required|min:6',
                'cep' => 'required|numeric',
                'complemento' => 'nullable|string',
                'numero_endereco' => 'required|numeric',
            ]);
        } catch (ValidationException $e) {
            // Trate os erros de validação aqui, se necessário
            return response()->json(['error' => $e->getMessage()], 422);
        }

        $user = new Usuario();
        $user->fill($request->all());
        $user->senha = Hash::make($request->senha);

        $user->numero_conta = $this->gerarNumeroConta();
        $user->saldo = 0;
        $user->status = 'ativa';

        $user->save();


        return response()->json(['message' => 'Usuário cadastrado com sucesso']);
    }

    private function gerarNumeroConta()
    {
        // Gerar gerar número de conta
        return str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
    }


    public function autenticar(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'senha' => 'required|min:6',
        ]);

        $usuario = Usuario::where('email', $request->email)->first();

        if ($usuario && Hash::check($request->senha, $usuario->senha)) {
            // Gerar token JWT
            $token = $this->jwtService->generateToken([
                'id' => $usuario->id,
                'email' => $usuario->email,
            ]);

            return response()->json(['token' => $token]);
        } else {
            return response()->json(['error' => 'Falha na autenticação'], 401);
        }
    }

    public function obterDados(Request $request)
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

        // Consulta o banco de dados para obter mais informações do usuário
        $usuario = Usuario::find($userId);

        if (!$usuario) {
            return response()->json(['error' => 'Usuário não encontrado'], 404);
        }

        // Retorna as informações desejadas
        return response()->json([
            'id' => $usuario->id,
            'nome_completo' => $usuario->nome_completo,
            'email' => $usuario->email,
            'numero_conta' => $usuario->numero_conta,
            'saldo' => $usuario->saldo,
        ]);
    }


}
