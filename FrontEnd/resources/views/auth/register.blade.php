@extends('template')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-main-bg-color">
    <div class="bg-white p-8 rounded-lg shadow-xl w-full max-w-md">
        <h2 class="text-2xl font-bold text-gray-900 mb-6 text-center">Criar Conta</h2>

        <div id="message" class="mb-4"></div>

        <form id="registerForm">
            <div class="mb-4">
                <label for="nome" class="block text-gray-700 text-sm font-medium mb-2">Nome Completo</label>
                <input type="text" id="nome" name="nome" class="shadow-sm border rounded w-full py-2 px-3 text-gray-700" required>
            </div>

            <div class="mb-4">
                <label for="nome_usuario" class="block text-gray-700 text-sm font-medium mb-2">Nome de Usuário</label>
                <input type="text" id="nome_usuario" name="nome_usuario" class="shadow-sm border rounded w-full py-2 px-3 text-gray-700" required>
            </div>

            <div class="mb-4">
                <label for="email" class="block text-gray-700 text-sm font-medium mb-2">Email</label>
                <input type="email" id="email" name="email" class="shadow-sm border rounded w-full py-2 px-3 text-gray-700" required>
            </div>

            <div class="mb-4">
                <label for="senha" class="block text-gray-700 text-sm font-medium mb-2">Senha</label>
                <input type="password" id="senha" name="senha" class="shadow-sm border rounded w-full py-2 px-3 text-gray-700" required>
            </div>

            <div class="mb-6">
                <label for="senha_confirmation" class="block text-gray-700 text-sm font-medium mb-2">Confirmar Senha</label>
                <input type="password" id="senha_confirmation" name="senha_confirmation" class="shadow-sm border rounded w-full py-2 px-3 text-gray-700" required>
            </div>

            <div class="flex items-center justify-between">
                <a href="/login" class="inline-block align-baseline font-bold text-sm text-gray-600 hover:text-primary-blue">
                    Já tem uma conta? Faça Login
                </a>
                <button type="submit" class="bg-primary-blue hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Registrar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('registerForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const data = {
        nome: document.getElementById('nome').value,
        nome_usuario: document.getElementById('nome_usuario').value,
        email: document.getElementById('email').value,
        senha: document.getElementById('senha').value,
        senha_confirmation: document.getElementById('senha_confirmation').value
    };

    const messageEl = document.getElementById('message');

    try {
        const response = await fetch('http://127.0.0.1:8000/api/auth/register', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify(data)
        });

        const result = await response.json();

        if (response.ok) {
            messageEl.innerHTML = `<p class="text-green-600">${result.message || 'Conta criada com sucesso!'}</p>`;
            setTimeout(() => window.location.href = '/login', 1500);
        } else {
            messageEl.innerHTML = `<p class="text-red-600">${result.message || JSON.stringify(result)}</p>`;
        }
    } catch (error) {
        messageEl.innerHTML = `<p class="text-red-600">Erro ao cadastrar: ${error}</p>`;
    }
});
</script>
@endsection
