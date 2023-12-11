<?php
namespace App\Services;

use App\Models\Usuario;
use Illuminate\Validation\ValidationException;

class TransacaoService
{
    public function validarDeposito(array $data)
    {
        // Validar que a quantia não seja 0 ou negativa
        if ($data['quantia'] <= 0) {
            throw ValidationException::withMessages(['quantia' => 'A quantia deve ser maior que 0.']);
        }

        // Validar que a conta de destino (usuário) existe
        $usuarioExiste = Usuario::where('numero_conta', $data['conta_destino'])->exists();
        if (!$usuarioExiste) {
            throw ValidationException::withMessages(['conta_destino' => 'A conta de destino não existe.']);
        }
    }

    public function validarTransferencia(array $data,$userId)
    {
        // Validar que a quantia não seja 0 ou negativa
        if ($data['quantia'] <= 0) {
            throw ValidationException::withMessages(['quantia' => 'A quantia deve ser maior que 0.']);
        }

        // Validar que a conta de destino (usuário) existe
        $usuarioExiste = Usuario::where('numero_conta', $data['conta_destino'])->exists();
        if (!$usuarioExiste) {
            throw ValidationException::withMessages(['conta_destino' => 'A conta de destino não existe.']);
        }

        // Validar se a quantia é menor ou igual ao saldo do usuário
        $usuarioOrigem = Usuario::where('id', $userId)->first();

        if ($data['quantia'] > $usuarioOrigem->saldo) {
            throw ValidationException::withMessages(['quantia' => 'A quantia é maior que o saldo disponível na conta.']);
        }
    }
}
