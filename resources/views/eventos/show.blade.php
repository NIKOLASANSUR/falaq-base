
@extends('layouts.app')

@section('title', $evento->titulo . ' — FalaQ')

@section('content')

@php
    // Verifica as perguntas votadas pelo usuario logado
    $perguntasVotadas = auth()->check()
        ? auth()->user()->perguntasVotadas()
            ->whereIn('perguntas.id', $perguntas->pluck('id'))
            ->pluck('perguntas.id')
            ->toArray()
        : [];
@endphp

<div class="row">

    <!-- Formulario de perguntas -->
    <div class="col-md-5 mb-4">
        <div class="bg-gray-800 rounded-lg shadow-lg p-4 mb-4">

            <h4 class="text-xl font-bold text-white mb-4">
                Faça sua Pergunta
            </h4>

            <form
                action="{{ route('eventos.perguntas.store', $evento->id) }}"
                method="POST"
            >
                @csrf

                <input
                    type="hidden"
                    name="evento_id"
                    value="{{ $evento->id }}"
                >

                <div class="mb-4">
                    <label
                        for="texto"
                        class="block text-gray-300 font-medium mb-2"
                    >
                        Texto da Pergunta
                    </label>

                    <textarea
                        name="texto"
                        id="texto"
                        rows="4"
                        minlength="10"
                        maxlength="255"
                        class="w-full p-3 rounded-md bg-gray-900 text-white border-2 border-gray-600 focus:outline-none focus:border-blue-500 @error('texto') border-red-500 @enderror"
                        placeholder="Digite sua pergunta..."
                    >{{ old('texto') }}</textarea>

                    @error('texto')
                        <p class="text-red-500 text-sm mt-2 font-medium">
                            {{ $message }}
                        </p>
                    @enderror

                    @error('evento_id')
                        <p class="text-red-500 text-sm mt-2">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <button
                    type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors w-full font-bold"
                >
                    Enviar Pergunta
                </button>

            </form>
        </div>
    </div>

    <!-- Mural de perguntas -->
    <div class="col-md-7">

        <div class="flex justify-between items-center mb-4">
            <h4 class="text-xl font-bold text-white">
                Ranking de Perguntas
            </h4>

            <span class="text-gray-400 text-sm">
                Total: {{ $perguntas->total() }}
            </span>
        </div>

        @forelse($perguntas as $pergunta)

            <div class="bg-gray-800 border-l-4 border-blue-500 rounded-lg shadow-md p-4 mb-4">

                <!-- Conteudo da pergunta -->
                <p class="text-white text-lg mb-3">
                    {{ $pergunta->texto }}
                </p>

                <!-- Autor da pergunta -->
                <div class="text-gray-400 text-sm mb-3">
                    Autor:
                    <span class="font-semibold text-blue-400">
                        {{ $pergunta->user->name ?? 'Anônimo' }}
                    </span>
                </div>

                <!-- Quantidade de votos -->
                <div class="flex items-center mb-3">
                    <span class="text-blue-400 font-bold text-lg">
                        👍 {{ $pergunta->votos_count }}
                    </span>

                    <span class="text-gray-400 text-sm ml-2">
                        {{ $pergunta->votos_count == 1 ? 'voto' : 'votos' }}
                    </span>
                </div>

                <!-- Botao de votar -->
                @auth

                    @php
                        $jaVotou = in_array(
                            $pergunta->id,
                            $perguntasVotadas
                        );
                    @endphp

                    <form
                        action="{{ route('perguntas.votar', $pergunta) }}"
                        method="POST"
                        class="mb-3"
                    >
                        @csrf

                        <button
                            type="submit"
                            class="{{ $jaVotou
                                ? 'bg-green-600 hover:bg-green-700'
                                : 'bg-blue-600 hover:bg-blue-700'
                            }} text-white px-4 py-2 rounded-md font-semibold transition-colors"
                        >
                            @if($jaVotou)
                                ✓ Remover Voto
                            @else
                                👍 Votar
                            @endif
                        </button>
                    </form>

                @else

                    <p class="text-gray-400 text-sm mb-3">
                        Faça login para votar nesta pergunta.
                    </p>

                @endauth

                <!-- Informacoes da pergunta -->
                <div class="flex justify-between items-center text-gray-400 text-sm mb-3">

                    <span>
                        Status: {{ $pergunta->status }}
                    </span>

                    <span>
                        {{ $pergunta->created_at->format('d/m/Y H:i') }}
                    </span>

                </div>

                <!-- Exclusao protegida - Sprint 05 -->
                @can('delete', $pergunta)

                    <form
                        action="{{ route('perguntas.destroy', $pergunta->id) }}"
                        method="POST"
                        class="mt-3"
                        onsubmit="return confirm('Deseja realmente excluir esta pergunta?');"
                    >
                        @csrf
                        @method('DELETE')

                        <x-danger-button>
                            Excluir Pergunta
                        </x-danger-button>

                    </form>

                @endcan

            </div>

        @empty

            <div class="bg-gray-800 text-gray-300 text-center rounded-lg p-5 mb-4">
                Nenhuma pergunta publicada ainda.
            </div>

        @endforelse

        <!-- Paginacao -->
        <div class="mt-4 flex justify-center">
            {{ $perguntas->links() }}
        </div>

    </div>
</div>

@endsection
