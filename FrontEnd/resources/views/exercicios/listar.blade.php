@extends('template')

@section('title', 'Todos os Meus Exercícios')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6 text-gray-800">Todos os Meus Exercícios</h1>

    <div class="flex justify-end mb-6 space-x-4">
        <a href="/exercises/create" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
            Adicionar Exercício
        </a>
    </div>

    <div id="exercisesList" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <p>Carregando exercícios...</p>
    </div>
</div>

<script>
async function loadExercises() {
    const token = localStorage.getItem('token');
    const container = document.getElementById('exercisesList');
    container.innerHTML = '';

    try {
        const response = await fetch('http://127.0.0.1:8000/exercises', {
            headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
        });
        const exercises = await response.json();

        if (response.ok) {
            if (exercises.length === 0) {
                container.innerHTML = '<p class="text-gray-600 col-span-full">Você ainda não criou exercícios.</p>';
            } else {
                exercises.forEach(ex => {
                    container.innerHTML += `
                        <a href="/exercises/${ex.id}" class="block bg-white border border-gray-200 rounded-lg shadow-sm p-5 hover:shadow-md">
                            <h2 class="text-xl font-semibold text-gray-900 mb-2">${ex.titulo}</h2>
                            <p class="text-gray-700 mb-2">${ex.descricao}</p>
                            <div class="flex items-center justify-between text-gray-500 text-xs">
                                ${ex.technology ? `<span class="bg-gray-100 text-gray-800 px-2 py-1 rounded-full">${ex.technology.nome}</span>` : ''}
                                <span class="bg-${ex.dificuldade === 'facil' ? 'green' : (ex.dificuldade === 'medio' ? 'yellow' : 'red')}-100 text-${ex.dificuldade === 'facil' ? 'green' : (ex.dificuldade === 'medio' ? 'yellow' : 'red')}-800 px-2 py-1 rounded-full">${ex.dificuldade.charAt(0).toUpperCase() + ex.dificuldade.slice(1)}</span>
                                <span>${new Date(ex.created_at).toLocaleString()}</span>
                            </div>
                        </a>
                    `;
                });
            }
        } else {
            container.innerHTML = `<p class="text-red-600">Erro ao carregar exercícios.</p>`;
        }
    } catch (error) {
        container.innerHTML = `<p class="text-red-600">Erro: ${error}</p>`;
    }
}

window.onload = loadExercises;
</script>
@endsection
