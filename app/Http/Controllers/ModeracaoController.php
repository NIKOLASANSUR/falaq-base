
<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreModeracaoRequest;
use App\Models\Evento;
use App\Models\Moderacao;
use App\Models\Pergunta;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class ModeracaoController extends Controller
{
    use AuthorizesRequests;

    // Sprint 07 - Registrar denuncia
    public function store(
        StoreModeracaoRequest $request,
        Pergunta $pergunta
    )
    {
        $pergunta->moderacoes()->create([
            'motivo' => $request->validated('motivo'),
            'status' => 'analisando',
        ]);

        return back()->with(
            'sucesso',
            'Denuncia registrada com sucesso!'
        );
    }

    // Sprint 07 - Fila de moderacao
    public function index(Evento $evento)
    {
        $this->authorize('moderate', $evento);

        $moderacoes = Moderacao::with('pergunta')
            ->whereHas('pergunta', function ($query) use ($evento) {
                $query->where('evento_id', $evento->id);
            })
            ->latest()
            ->paginate(15);

        return view('moderacoes.index', compact(
            'evento',
            'moderacoes'
        ));
    }

    // Sprint 07 - Aprovar ou rejeitar
    public function updateStatus(
        Request $request,
        Moderacao $moderacao
    )
    {
        $this->authorize(
            'moderate',
            $moderacao->pergunta->evento
        );

        $dados = $request->validate([
            'status' => 'required|in:aprovado,rejeitado',
        ]);

        $moderacao->update([
            'status' => $dados['status'],
        ]);

        $moderacao->pergunta->update([
            'is_public' => $dados['status'] === 'aprovado',
        ]);

        return back()->with(
            'sucesso',
            'Status da moderacao atualizado!'
        );
    }
}
