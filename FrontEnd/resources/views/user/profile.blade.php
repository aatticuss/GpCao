@extends('template')

@section('title', 'Meu Perfil')

@section('content')
<div class="flex flex-col min-h-screen bg-gray-100">
    <div class="bg-gradient-to-r from-blue-500 to-blue-700 h-48 w-full relative"></div>

    <div class="container mx-auto px-4 -mt-24"> 
        <div class="bg-white p-8 rounded-lg shadow-xl relative z-10">
            <div id="profileContent">
                <p>Carregando informações...</p>
            </div>
        </div>
    </div>
</div>

<script>
async function loadProfile() {
    const token = localStorage.getItem('token');
    const container = document.getElementById('profileContent');

    try {
        const response = await fetch('http://127.0.0.1:8000/user/profile', {
            method: 'GET',
            headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
        });
        const user = await response.json();

        if (response.ok) {
            container.innerHTML = `
                <div class="flex flex-col md:flex-row items-center md:items-start space-y-4 md:space-y-0 md:space-x-8">
                    <div class="w-32 h-32 rounded-full bg-gray-300 flex items-center justify-center overflow-hidden border-4 border-white shadow-lg">
                        ${user.foto_perfil ? `<img src="data:image/jpeg;base64,${user.foto_perfil}" class="w-full h-full object-cover">` :
                        `<svg class="w-20 h-20 text-gray-500" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 20.993V24H0v-2.996a14.977 14.977 0 0112.004-5.004 14.977 14.977 0 0111.996 5.004zM22.002 12A10 10 0 102 12a10 10 0 0020.002 0zM12 4a4 4 0 100 8 4 4 0 000-8z" />
                        </svg>`}
                    </div>
                    <div class="flex-grow flex flex-col items-center md:items-start text-center md:text-left">
                        <div class="flex items-center w-full justify-between mb-2">
                            <div>
                                <h2 class="text-3xl font-bold text-gray-900">${user.nome}</h2>
                                <p class="text-gray-600 text-lg">${user.nome_usuario}</p>
                            </div>
                            <a href="/profile/edit" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded-full text-sm">
                                Editar Perfil
                            </a>
                        </div>
                        <div class="w-full mt-4 p-4 border border-gray-200 rounded-lg bg-gray-50">
                            <h3 class="text-gray-700 font-semibold mb-2 text-sm">BIOGRAFIA</h3>
                            <p class="text-gray-800 text-base">${user.biografia || 'Adicione uma biografia!'}</p>
                        </div>
                    </div>
                </div>
            `;
        } else {
            container.innerHTML = `<p class="text-red-600">Erro ao carregar perfil</p>`;
        }
    } catch (error) {
        container.innerHTML = `<p class="text-red-600">Erro: ${error}</p>`;
    }
}

window.onload = loadProfile;
</script>
@endsection
