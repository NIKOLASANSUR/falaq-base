
<?php

namespace App\Http\Controllers;

use App\Models\Pergunta;
use Illuminate\Http\RedirectResponse;

class PerguntaController extends Controller
{
    /**
     * Sprint 06 - Adiciona ou remove o voto
     * do usuario autenticado.
     */
    public function votar(Pergunta $pergunta): RedirectResponse
    {
        $pergunta->votos()->toggle(auth()->id());

        return back()->with(
            'sucesso',
            'Seu voto foi atualizado com sucesso!'
        );
    }
}

