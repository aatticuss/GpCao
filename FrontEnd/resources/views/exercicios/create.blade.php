@extends('template')

@section('content')
<div class="flex flex-col items-center justify-center min-h-screen py-12">
    <div class="w-full max-w-2xl px-6 py-8 bg-white rounded-lg shadow-xl">
        <h1 class="text-3xl font-bold text-center text-gray-900 mb-6">Criar Novo Exercício</h1>

        <div id="message" class="mb-4"></div>

        <form id="exerciseForm">
            <div class="mb-4">
                <label for="titulo" class="block text-sm font-medium text-gray-700">Título do Exercício</label>
                <input type="text" id="titulo" name="titulo" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Ex: Trabalho final de semestre" required>
            </div>

            <div class="mb-4">
                <label for="descricao" class="block text-sm font-medium text-gray-700">Descrição</label>
                <input type="text" id="descricao" name="descricao" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Ex: Projeto de Lógica de Programação" required>
            </div>

            <div class="mb-4">
                <label for="dificuldade" class="block text-sm font-medium text-gray-700">Dificuldade</label>
                <select id="dificuldade" name="dificuldade" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <option value="facil">Fácil</option>
                    <option value="medio">Médio</option>
                    <option value="dificil">Difícil</option>
                </select>
            </div>

            <div class="mb-6">
                <label for="tecnologia_id" class="block text-sm font-medium text-gray-700">Tecnologia</label>
                <select id="tecnologia_id" name="tecnologia_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></select>
            </div>

            <div class="flex items-center justify-end">
                <button type="submit" class="w-full flex justify-center py-2 px-4 bg-blue-600 text-white rounded-md hover:bg-blue-700">Gerar Exercício</button>
            </div>
        </form>
    </div>
</div>

<script>
async function loadTechnologies() {
    const token = localStorage.getItem('token');
    const select = document.getElementById('tecnologia_id');

    try {
        const response = await fetch('http://127.0.0.1:8000/user/profile', {
            headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
        });
        const user = await response.json();
        if (response.ok && user.technologies) {
            user.technologies.forEach(tech => {
                const option = document.createElement('option');
                option.value = tech.id;
                option.textContent = tech.nome;
                select.appendChild(option);
            });
        } else {
            select.innerHTML = '<option disabled>Nenhuma tecnologia disponível</option>';
        }
    } catch (error) {
        select.innerHTML = '<option disabled>Erro ao carregar tecnologias</option>';
    }
}

document.getElementById('exerciseForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const token = localStorage.getItem('token');
    const data = {
        titulo: document.getElementById('titulo').value,
        descricao: document.getElementById('descricao').value,
        dificuldade: document.getElementById('dificuldade').value,
        tecnologia_id: document.getElementById('tecnologia_id').value
    };

    const messageEl = document.getElementById('message');

    try {
        const response = await fetch('http://127.0.0.1:8000/exercises', {
            method: 'POST',
            headers: { 'Authorization': `Bearer ${token}`, 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify(data)
        });
        const result = await response.json();

        if (response.ok) {
            messageEl.innerHTML = `<p class="text-green-600">${result.message || 'Exercício criado com sucesso!'}</p>`;
            setTimeout(() => window.location.href = '/exercises', 1000);
        } else {
            messageEl.innerHTML = `<p class="text-red-600">${result.message || JSON.stringify(result)}</p>`;
        }
    } catch (error) {
        messageEl.innerHTML = `<p class="text-red-600">Erro ao criar exercício: ${error}</p>`;
    }
});

window.onload = loadTechnologies;
</script>
@endsection
