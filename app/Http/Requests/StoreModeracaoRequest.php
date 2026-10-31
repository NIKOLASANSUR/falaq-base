
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreModeracaoRequest extends FormRequest
{
    /**
     * Autoriza o envio da requisicao.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Regras de validacao do motivo da denuncia.
     */
    public function rules(): array
    {
        return [
            'motivo' => 'required|string|min:10|max:255',
        ];
    }
}
