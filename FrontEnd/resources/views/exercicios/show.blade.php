@extends('template')

@section('content')
<div class="flex flex-col items-center justify-center py-12">
    <div class="w-full max-w-3xl px-6 py-8 bg-white rounded-lg shadow-xl">
        <h1 class="text-3xl font-bold text-center text-gray-900 mb-6">Detalhes do Exercício</h1>

        <div id="message" class="mb-4"></div>

        <div id="exerciseDetails" class="mb-8 p-6 border rounded-lg bg-gray-50">
            <p>Carregando detalhes do exercício...</p>
        </div>

        @auth
        <div id="answerSection" class="mb-8 p-6 border rounded-lg bg-gray-50" style="display:none;">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Sua Resposta</h3>
            <div id="userAnswer"></div>
            <form id="answerForm">
                <div class="mb-4">
                    <label for="texto_resposta" class="block text-sm font-medium text-gray-700">Digite sua solução aqui:</label>
                    <textarea name="texto_resposta" id="texto_resposta" rows="10" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Escreva seu código ou sua resposta..."></textarea>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Enviar Solução
                    </button>
                </div>
            </form>
        </div>
        @else
        <div class="p-4 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 rounded" role="alert">
            <p>Faça login para enviar sua solução.</p>
        </div>
        @endauth
    </div>
</div>

<script>
const exerciseId = "{{ $exercicio->id }}"; // ID do exercício passado via Blade

async function loadExercise() {
    const token = localStorage.getItem('token');
    const detailsEl = document.getElementById('exerciseDetails');
    const answerSection = document.getElementById('answerSection');
    const userAnswerEl = document.getElementById('userAnswer');

    try {
        const response = await fetch(`http://127.0.0.1:8000/exercises/${exerciseId}`, {
            headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
        });

        const ex = await response.json();

        if (response.ok) {
            detailsEl.innerHTML = `
                <h2 class="text-xl font-semibold text-gray-800 mb-3">${ex.descricao}</h2>
                <p class="text-sm text-gray-600 mb-2">Dificuldade: <span class="font-medium capitalize">${ex.dificuldade}</span></p>
                <p class="text-sm text-gray-600 mb-4">Tecnologia: <span class="font-medium">${ex.technology?.nome ?? 'N/A'}</span></p>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Conteúdo do Exercício:</h3>
                <div class="bg-white p-4 rounded border text-gray-700 leading-relaxed whitespace-pre-wrap">
                    ${ex.conteudo_exercicio ?? '<p class="text-gray-500 italic">Conteúdo do exercício ainda não gerado ou disponível.</p>'}
                </div>
            `;

            if (@json(auth()->check())) {
                answerSection.style.display = 'block';

                if (ex.user_answer) {
                    userAnswerEl.innerHTML = `
                        <div class="bg-gray-100 p-4 rounded mb-4">
                            <p class="font-medium">Sua última submissão:</p>
                            <p class="whitespace-pre-wrap text-gray-700">${ex.user_answer.texto_resposta}</p>
                            ${ex.user_answer.nota !== null ? `
                                <p class="mt-2">Nota: <span class="font-bold text-blue-600">${ex.user_answer.nota} / 100</span></p>
                                <p class="mt-1">Avaliação: <span class="text-gray-700">${ex.user_answer.avaliacao}</span></p>
                            ` : `<p class="mt-2 text-gray-600 italic">Aguardando avaliação da API.</p>`}
                        </div>
                    `;
                } else {
                    userAnswerEl.innerHTML = '<p class="text-gray-600 italic mb-4">Você ainda não enviou uma resposta para este exercício.</p>';
                }
            }
        } else {
            detailsEl.innerHTML = `<p class="text-red-600">Erro ao carregar exercício: ${ex.message || JSON.stringify(ex)}</p>`;
        }
    } catch (error) {
        detailsEl.innerHTML = `<p class="text-red-600">Erro ao carregar exercício: ${error}</p>`;
    }
}

document.getElementById('answerForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    const token = localStorage.getItem('token');
    const texto_resposta = document.getElementById('texto_resposta').value;
    const messageEl = document.getElementById('message');

    try {
        const response = await fetch(`http://127.0.0.1:8000/exercises/${exerciseId}/answer`, {
            method: 'POST',
            headers: { 'Authorization': `Bearer ${token}`, 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({ texto_resposta })
        });

        const result = await response.json();

        if (response.ok) {
            messageEl.innerHTML = `<p class="text-green-600">${result.message || 'Resposta enviada com sucesso!'}</p>`;
            loadExercise(); // atualiza a resposta do usuário
        } else {
            messageEl.innerHTML = `<p class="text-red-600">${result.message || JSON.stringify(result)}</p>`;
        }
    } catch (error) {
        messageEl.innerHTML = `<p class="text-red-600">Erro ao enviar resposta: ${error}</p>`;
    }
});

window.onload = loadExercise;
</script>
@endsection
