@extends('template')

@section('title', 'Meu Perfil')

@section('content')
<div class="flex flex-col min-h-screen bg-gray-100">
    <div class="bg-gradient-to-r from-blue-500 to-blue-700 h-48 w-full relative"></div>

    <div class="container mx-auto px-4 -mt-24"> 
        <div class="bg-white p-8 rounded-lg shadow-xl relative z-10">
            <!-- Perfil e abas -->
            <div id="profileContent">
                <p>Carregando informações...</p>
            </div>
        </div>
    </div>
</div>

<script>
async function loadProfileData() {
    const token = localStorage.getItem('token');
    const container = document.getElementById('profileContent');

    try {
        const response = await fetch('http://127.0.0.1:8000/api/user/profile', {
            method: 'GET',
            headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
        });

        const data = await response.json();

        if (!response.ok) {
            container.innerHTML = `<p class="text-red-600">Erro ao carregar perfil</p>`;
            return;
        }

        const u = data.user;
        const exercises = data.latest_exercises;
        const technologies = data.all_technologies;
        const userTech = data.user_technologies;

        container.innerHTML = `
            <div class="flex flex-col md:flex-row items-center md:items-start space-y-4 md:space-y-0 md:space-x-8">
                <div class="w-32 h-32 rounded-full bg-gray-300 flex items-center justify-center overflow-hidden border-4 border-white shadow-lg">
                    ${u.foto_perfil ? `<img src="http://127.0.0.1:8000/storage/${u.foto_perfil}" class="w-full h-full object-cover">` :
                    `<svg class="w-20 h-20 text-gray-500" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M24 20.993V24H0v-2.996a14.977 14.977 0 0112.004-5.004 14.977 14.977 0 0111.996 5.004zM22.002 12A10 10 0 102 12a10 10 0 0020.002 0zM12 4a4 4 0 100 8 4 4 0 000-8z"/>
                    </svg>`}
                </div>

                <div class="flex-grow flex flex-col items-center md:items-start text-center md:text-left">
                    <div class="flex items-center w-full justify-between mb-2">
                        <div>
                            <h2 class="text-3xl font-bold text-gray-900">${u.nome}</h2>
                            <p class="text-gray-600 text-lg">${u.nome_usuario}</p>
                        </div>
                        <a href="/profile/edit" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded-full text-sm">
                            Editar Perfil
                        </a>
                    </div>

                    <div class="w-full mt-4 p-4 border border-gray-200 rounded-lg bg-gray-50">
                        <h3 class="text-gray-700 font-semibold mb-2 text-sm">BIOGRAFIA</h3>
                        <p class="text-gray-800 text-base">${u.biografia || 'Adicione uma biografia!'}</p>
                    </div>
                </div>
            </div>

            <!-- abas -->
            <div class="mt-8 border-b border-gray-200">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                    <a href="#" id="exercises-tab" onclick="showTab('exercises')" class="border-b-2 border-primary-blue text-primary-blue whitespace-nowrap py-4 px-1 font-medium text-base">Exercícios</a>
                    <a href="#" id="technologies-tab" onclick="showTab('technologies')" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 font-medium text-base">Tecnologias</a>
                </nav>
            </div>

            <!-- conteúdo -->
            <div id="exercises-content" class="mt-8">
                <div class="flex justify-end mb-6 space-x-4">
                    <a href="/exercises" class="inline-flex items-center px-4 py-2 border border-blue-600 text-sm font-medium rounded-md shadow-sm text-blue-600 bg-white hover:bg-blue-50">
                        Ver Todos os Exercícios
                    </a>
                    <a href="/exercises/create" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700">
                        Adicionar Exercício
                    </a>
                </div>

                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
                    ${exercises.length ? exercises.map(ex => `
                        <a href="/exercises/${ex.id}" class="block bg-white border border-gray-200 rounded-lg shadow-sm p-5 transition-shadow hover:shadow-md cursor-pointer">
                            <h2 class="text-xl font-semibold text-gray-900 mb-2">${ex.titulo}</h2>
                            <p class="text-gray-900 mb-2">${ex.descricao}</p>
                            <div class="flex items-center justify-between text-gray-500 text-xs">
                                ${ex.technology ? `<span class="bg-gray-100 text-gray-800 px-2 py-1 rounded-full">${ex.technology.nome}</span>` : ''}
                                <span class="bg-${ex.dificuldade === 'facil' ? 'green' : ex.dificuldade === 'medio' ? 'yellow' : 'red'}-100 text-${ex.dificuldade === 'facil' ? 'green' : ex.dificuldade === 'medio' ? 'yellow' : 'red'}-800 px-2 py-1 rounded-full">${ex.dificuldade.charAt(0).toUpperCase() + ex.dificuldade.slice(1)}</span>
                                <span>${new Date(ex.created_at).toLocaleString()}</span>
                            </div>
                        </a>
                    `).join('') : '<p class="text-gray-600 col-span-full">Nenhum exercício encontrado.</p>'}
                </div>
            </div>

            <div id="technologies-content" class="mt-8 hidden">
                <div id="associated-technologies">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-800">Minhas Tecnologias</h3>
                        <button id="add-tech-button" onclick="toggleAddForm()" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700">
                            Adicionar
                        </button>
                    </div>
                    <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        ${userTech.length ? userTech.map(t => `
                            <div class="flex items-center space-x-4 bg-white border border-gray-200 rounded-lg shadow-sm p-4">
                                <img src="${t.icone}" alt="${t.nome} ícone" class="w-8 h-8">
                                <span class="text-gray-800 text-lg font-semibold">${t.nome}</span>
                            </div>
                        `).join('') : '<p class="text-gray-600 col-span-full">Nenhuma tecnologia associada.</p>'}
                    </div>
                </div>

                <div id="add-technologies-form" class="mt-8 p-6 bg-gray-50 rounded-lg shadow-inner hidden">
                    <form id="addTechForm">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Adicionar Novas Tecnologias</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                            ${technologies.map(t => `
                                <label class="flex items-center space-x-3 bg-white p-3 rounded-md border border-gray-200 hover:bg-blue-50 cursor-pointer transition-colors duration-200">
                                    <input type="checkbox" name="technologies[]" value="${t.id}" class="h-5 w-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                        ${userTech.some(ut => ut.id === t.id) ? 'checked' : ''}>
                                    <img src="${t.icone}" alt="${t.nome} ícone" class="w-6 h-6">
                                    <span class="text-gray-800 font-medium">${t.nome}</span>
                                </label>
                            `).join('')}
                        </div>
                        <div class="mt-6 flex justify-end space-x-4">
                            <button type="button" onclick="toggleAddForm()" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">Cancelar</button>
                            <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">Salvar Alterações</button>
                        </div>
                    </form>
                </div>
            </div>
        `;

        showTab('exercises');

        document.getElementById('addTechForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const selected = Array.from(document.querySelectorAll('input[name="technologies[]"]:checked'))
                                  .map(tc => parseInt(tc.value));
            await updateUserTechnologies(selected);
        });

    } catch (error) {
        container.innerHTML = `<p class="text-red-600">Erro: ${error}</p>`;
    }
}

function showTab(tabName) {
    document.querySelectorAll('[id$="-content"]').forEach(el => el.classList.add('hidden'));
    document.querySelectorAll('[id$="-tab"]').forEach(el => {
        el.classList.remove('border-primary-blue', 'text-primary-blue');
        el.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
    });

    const content = document.getElementById(tabName + '-content');
    if (content) content.classList.remove('hidden');

    const activeTab = document.getElementById(tabName + '-tab');
    if (activeTab) {
        activeTab.classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
        activeTab.classList.add('border-primary-blue', 'text-primary-blue');
    }

    const addForm = document.getElementById('add-technologies-form');
    if (addForm) addForm.classList.add('hidden');
}

function toggleAddForm() {
    const addForm = document.getElementById('add-technologies-form');
    const associatedTechs = document.getElementById('associated-technologies');
    const addButton = document.getElementById('add-tech-button');

    if (addForm.classList.contains('hidden')) {
        addForm.classList.remove('hidden');
        associatedTechs.classList.add('hidden');
        if (addButton) addButton.classList.add('hidden');
    } else {
        addForm.classList.add('hidden');
        associatedTechs.classList.remove('hidden');
        if (addButton) addButton.classList.remove('hidden');
    }
}

async function updateUserTechnologies(selectedTechIds) {
    const token = localStorage.getItem('token');
    try {
        const response = await fetch('http://127.0.0.1:8000/api/user/technologies', {
            method: 'PUT',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ technologies: selectedTechIds })
        });
        const data = await response.json();
        if (response.ok) {
            alert('Tecnologias atualizadas com sucesso!');
            loadProfileData();
        } else {
            alert('Erro ao atualizar tecnologias: ' + JSON.stringify(data));
        }
    } catch (error) {
        alert('Erro: ' + error);
    }
}

window.onload = loadProfileData;
</script>
@endsection
