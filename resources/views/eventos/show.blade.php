
@extends('layouts.app')

@section('title', $evento->titulo . ' — FalaQ')

@section('content')

@php
    $perguntasVotadas = auth()->check()
        ? auth()->user()->perguntasVotadas()
            ->whereIn('perguntas.id', $perguntas->pluck('id'))
            ->pluck('perguntas.id')
            ->toArray()
        : [];
@endphp

<div class="max-w-6xl mx-auto p-6">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-white">
            {{ $evento->titulo }}
        </h1>

        @can('moderate', $evento)
            <a
                href="{{ route('moderacoes.index', $evento) }}"
                class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-md"
            >
                Fila de Moderação
            </a>
        @endcan
    </div>

    <!-- Formulario de perguntas -->
    <div class="bg-gray-800 rounded-lg shadow-lg p-5 mb-6">

        <h2 class="text-xl font-bold text-white mb-4">
            Faça sua Pergunta
        </h2>

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

            <label for="texto" class="block text-gray-300 mb-2">
                Sua pergunta
            </label>

            <textarea
                name="texto"
                id="texto"
                rows="4"
                minlength="10"
                maxlength="255"
                class="w-full p-3 rounded-md bg-gray-900 text-white border-2 border-gray-600 @error('texto') border-red-500 @enderror"
                placeholder="Digite sua pergunta..."
            >{{ old('texto') }}</textarea>

            @error('texto')
                <p class="text-red-500 text-sm mt-2">
                    {{ $message }}
                </p>
            @enderror

            @error('evento_id')
                <p class="text-red-500 text-sm mt-2">
                    {{ $message }}
                </p>
            @enderror

            <button
                type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md mt-4"
            >
                Enviar Pergunta
            </button>
        </form>
    </div>

    <!-- Ranking de perguntas -->
    <h2 class="text-xl font-bold text-white mb-4">
        Ranking de Perguntas
    </h2>

    @forelse($perguntas as $pergunta)

        <div class="bg-gray-800 border-l-4 border-blue-500 rounded-lg shadow-md p-5 mb-4">

            <p class="text-white text-lg mb-3">
                {{ $pergunta->texto }}
            </p>

            <p class="text-gray-400 text-sm mb-3">
                Autor:
                {{ $pergunta->user->name ?? 'Anônimo' }}
            </p>

            <p class="text-blue-400 font-bold mb-3">
                👍 {{ $pergunta->votos_count }} votos
            </p>

            <!-- Sistema de votos -->
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
                        class="{{ $jaVotou ? 'bg-green-600 hover:bg-green-700' : 'bg-blue-600 hover:bg-blue-700' }} text-white px-4 py-2 rounded-md"
                    >
                        {{ $jaVotou ? 'Remover Voto' : 'Votar' }}
                    </button>
                </form>
            @endauth

            <!-- Sprint 07 - Denunciar pergunta -->
            @auth
                <div class="border-t border-gray-700 pt-4 mt-4">

                    <h3 class="text-white font-semibold mb-2">
                        Denunciar Pergunta
                    </h3>

                    <form
                        action="{{ route('moderacoes.store', $pergunta) }}"
                        method="POST"
                    >
                        @csrf

                        <textarea
                            name="motivo"
                            rows="2"
                            minlength="10"
                            maxlength="255"
                            required
                            placeholder="Explique o motivo da denúncia..."
                            class="w-full bg-gray-900 text-white border border-gray-600 rounded-md p-3 mb-2"
                        ></textarea>

                        <button
                            type="submit"
                            class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-md"
                        >
                            Enviar Denúncia
                        </button>
                    </form>
                </div>
            @endauth

            <!-- Exclusao protegida -->
            @can('delete', $pergunta)
                <form
                    action="{{ route('perguntas.destroy', $pergunta) }}"
                    method="POST"
                    class="mt-4"
                    onsubmit="return confirm('Deseja excluir esta pergunta?');"
                >
                    @csrf
                    @method('DELETE')

                    <x-danger-button>
                        Excluir Pergunta
                    </x-danger-button>
                </form>
            @endcan

            <p class="text-gray-400 text-sm mt-4">
                {{ $pergunta->created_at->format('d/m/Y H:i') }}
            </p>

        </div>

    @empty

        <div class="bg-gray-800 rounded-lg p-6 text-center">
            <p class="text-gray-400">
                Nenhuma pergunta publicada ainda.
            </p>
        </div>

    @endforelse

    <!-- Paginacao -->
    <div class="mt-6 flex justify-center">
        {{ $perguntas->links() }}
    </div>

</div>

@endsection
