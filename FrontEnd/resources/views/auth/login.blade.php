@extends('template')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-main-bg-color">
    <div class="bg-white p-8 rounded-lg shadow-xl w-full max-w-md">
        <h2 class="text-2xl font-bold text-gray-900 mb-6 text-center">Entrar na Conta</h2>

        <div id="message" class="mb-4"></div>

        <form id="loginForm">
            <div class="mb-4">
                <label for="login" class="block text-gray-700 text-sm font-medium mb-2">Nome de Usuário ou Email</label>
                <input type="text" id="login" name="login" class="shadow-sm border rounded w-full py-2 px-3 text-gray-700" required>
            </div>

            <div class="mb-6">
                <label for="senha" class="block text-gray-700 text-sm font-medium mb-2">Senha</label>
                <input type="password" id="senha" name="senha" class="shadow-sm border rounded w-full py-2 px-3 text-gray-700" required>
            </div>

            <div class="flex items-center justify-between">
                <a href="/register" class="inline-block align-baseline font-bold text-sm text-primary-blue hover:text-blue-800">
                    Não tem uma conta? Cadastre-se
                </a>
                <button type="submit" class="bg-primary-blue hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Entrar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('loginForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const data = {
        login: document.getElementById('login').value,
        senha: document.getElementById('senha').value
    };

    const messageEl = document.getElementById('message');

    try {
        
        const response = await fetch('http://127.0.0.1:8000/auth/login', {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json', 
                'Accept': 'application/json' 
            },
            body: JSON.stringify(data)
        });

        const result = await response.json();

        if (response.ok && result.token) {
            localStorage.setItem('token', result.token);
            messageEl.innerHTML = `<p class="text-green-600">Login realizado com sucesso!</p>`;
            setTimeout(() => window.location.href = '/profile', 1000);
        } else {
            messageEl.innerHTML = `<p class="text-red-600">${result.message || JSON.stringify(result)}</p>`;
        }
    } catch (error) {
        messageEl.innerHTML = `<p class="text-red-600">Erro ao logar: ${error}</p>`;
    }
});
</script>
@endsection
