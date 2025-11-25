@extends('template')

@section('title', 'Editar Perfil')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6 text-gray-800">Editar Perfil</h1>

    <div id="message" class="mb-4"></div>

    <form id="editProfileForm" enctype="multipart/form-data" class="bg-white p-6 rounded shadow-md w-full max-w-lg mx-auto">
        <div class="mb-4">
            <label for="nome" class="block text-sm font-medium text-gray-700">Nome</label>
            <input type="text" id="nome" name="nome" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
        </div>

        <div class="mb-4">
            <label for="biografia" class="block text-sm font-medium text-gray-700">Biografia</label>
            <textarea id="biografia" name="biografia" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
        </div>

        <div class="mb-4">
            <label for="foto_perfil" class="block text-sm font-medium text-gray-700">Foto de Perfil</label>
            <input type="file" id="foto_perfil" name="foto_perfil" class="mt-1 block w-full text-sm text-gray-600">
        </div>

        <div class="flex justify-end space-x-4">
            <a href="/profile" class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">Cancelar</a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Salvar</button>
        </div>
    </form>
</div>

<script>
async function loadProfile() {
    const token = localStorage.getItem('token');
    try {
        const response = await fetch('http://127.0.0.1:8000/api/user/profile', {
            method: 'GET',
            headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
        });
        const user = await response.json();
        if (response.ok) {
            document.getElementById('nome').value = user.nome || '';
            document.getElementById('biografia').value = user.biografia || '';
        }
    } catch (error) {
        document.getElementById('message').innerHTML = `<p class="text-red-600">Erro ao carregar perfil: ${error}</p>`;
    }
}

document.getElementById('editProfileForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const token = localStorage.getItem('token');
    const formData = new FormData(this);
    const messageEl = document.getElementById('message');

    try {
        const response = await fetch('http://127.0.0.1:8000/api/user/profile', {
            method: 'PUT',
            headers: { 'Authorization': `Bearer ${token}` },
            body: formData
        });
        const result = await response.json();

        if (response.ok) {
            messageEl.innerHTML = `<p class="text-green-600">${result.message || 'Perfil atualizado com sucesso!'}</p>`;
            setTimeout(() => window.location.href = '/profile', 1000);
        } else {
            messageEl.innerHTML = `<p class="text-red-600">${result.message || JSON.stringify(result)}</p>`;
        }
    } catch (error) {
        messageEl.innerHTML = `<p class="text-red-600">Erro ao atualizar perfil: ${error}</p>`;
    }
});

window.onload = loadProfile;
</script>
@endsection
