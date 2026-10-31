
@extends('layouts.app')

@section('title', $evento->titulo . ' — FalaQ')

@section('content')

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
                Perguntas do Evento
            </h4>

            <span class="text-gray-400 text-sm">
                Total: {{ $perguntas->total() }}
            </span>
        </div>

        @forelse($perguntas as $pergunta)

            <div class="bg-gray-800 border-l-4 border-blue-500 rounded-lg shadow-md p-4 mb-4">

                <p class="text-white text-lg mb-3">
                    {{ $pergunta->texto }}
                </p>

                <div class="text-gray-400 text-sm mb-3">
                    Autor:
                    <span class="font-semibold text-blue-400">
                        {{ $pergunta->user->name ?? 'Anônimo' }}
                    </span>
                </div>

                <div class="flex justify-between items-center text-gray-400 text-sm">

                    <span>
                        Status: {{ $pergunta->status }}
                    </span>

                    <span>
                        {{ $pergunta->created_at->format('d/m/Y H:i') }}
                    </span>

                </div>
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
