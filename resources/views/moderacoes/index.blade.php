
@extends('layouts.app')

@section('title', 'Moderação de Perguntas')

@section('content')

<div class="max-w-5xl mx-auto p-6">

    <h1 class="text-2xl font-bold text-white mb-4">
        Fila de Moderação
    </h1>

    <p class="text-gray-400 mb-6">
        Evento: {{ $evento->titulo }}
    </p>

    @if(session('sucesso'))
        <div class="bg-green-600 text-white p-4 rounded-md mb-4">
            {{ session('sucesso') }}
        </div>
    @endif

    @forelse($moderacoes as $moderacao)

        <div class="bg-gray-800 rounded-lg shadow-md p-5 mb-4">

            <h2 class="text-lg font-semibold text-white mb-3">
                Pergunta denunciada
            </h2>

            <p class="text-gray-200 mb-4">
                {{ $moderacao->pergunta->texto ?? 'Pergunta indisponível' }}
            </p>

            <div class="mb-3">
                <span class="font-semibold text-gray-300">
                    Motivo:
                </span>

                <p class="text-gray-400">
                    {{ $moderacao->motivo }}
                </p>
            </div>

            <p class="text-gray-400 mb-4">
                Status:
                <span class="font-bold text-yellow-400">
                    {{ $moderacao->status }}
                </span>
            </p>

            <div class="flex flex-wrap gap-3">

                <form
                    action="{{ route('moderacoes.updateStatus', $moderacao) }}"
                    method="POST"
                >
                    @csrf
                    @method('PATCH')

                    <input
                        type="hidden"
                        name="status"
                        value="aprovado"
                    >

                    <button
                        type="submit"
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md font-semibold"
                    >
                        Aprovar
                    </button>
                </form>

                <form
                    action="{{ route('moderacoes.updateStatus', $moderacao) }}"
                    method="POST"
                >
                    @csrf
                    @method('PATCH')

                    <input
                        type="hidden"
                        name="status"
                        value="rejeitado"
                    >

                    <button
                        type="submit"
                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md font-semibold"
                    >
                        Rejeitar
                    </button>
                </form>

            </div>
        </div>

    @empty

        <div class="bg-gray-800 rounded-lg p-6 text-center">
            <p class="text-gray-400">
                Nenhuma denúncia encontrada para este evento.
            </p>
        </div>

    @endforelse

    <div class="mt-6">
        {{ $moderacoes->links() }}
    </div>

    <a
        href="{{ route('eventos.show', $evento->id) }}"
        class="inline-block mt-6 text-blue-400 hover:text-blue-300"
    >
        Voltar para o evento
    </a>

</div>

@endsection

