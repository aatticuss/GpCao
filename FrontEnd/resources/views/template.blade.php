<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minha Aplicação</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style type="text/tailwindcss">
        @layer base {
            :root {
                --sidebar-bg: #1A1A1A;
                --sidebar-text: #E0E0E0;
                --sidebar-hover-bg: rgba(255, 255, 255, 0.08);
                --main-bg-color: #F0F2F5;
                --primary-blue: #007BFF;
            }
        }

        @layer utilities {
            .bg-sidebar-bg { background-color: var(--sidebar-bg); }
            .text-sidebar-text { color: var(--sidebar-text); }
            .hover\:bg-sidebar-hover-bg:hover { background-color: var(--sidebar-hover-bg); }
            .bg-main-bg-color { background-color: var(--main-bg-color); }
            .text-primary-blue { color: var(--primary-blue); }
            .border-l-primary-blue { border-left-color: var(--primary-blue); }
        }

        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            display: flex; 
            min-height: 100vh;
            background-color: var(--main-bg-color);
            color: #333333;
        }

        .sidebar-link.active { border-left: 3px solid var(--primary-blue); }
        .icon-fill-current { fill: currentColor; }
    </style>
</head>
<body class="flex min-h-screen bg-main-bg-color text-gray-800">

    <!-- Sidebar será renderizada via JS -->
    <div id="sidebarContainer"></div>

    <!-- Main content -->
    <main id="mainContent" class="flex-grow p-6">
        @yield('content')
    </main>

    <script>
        const token = localStorage.getItem('token');

        if (token) {
            document.getElementById('sidebarContainer').innerHTML = `
                <aside class="fixed inset-y-0 left-0 w-64 p-5 flex flex-col shadow-lg z-10 bg-sidebar-bg">
                    <div class="text-lg font-semibold mb-6 text-sidebar-text">GPCão</div>
                    <nav class="flex-grow">
                        <ul class="space-y-1">
                            <li>
                                <a href="/perfil" class="sidebar-link flex items-center p-3 rounded-lg text-sm text-sidebar-text hover:bg-sidebar-hover-bg hover:text-white">
                                    <svg class="w-5 h-5 mr-4 icon-fill-current" viewBox="0 0 24 24">
                                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                    </svg>
                                    Perfil
                                </a>
                            </li>
                            <li>
                                <a href="/exercicios" class="sidebar-link flex items-center p-3 rounded-lg text-sm text-sidebar-text hover:bg-sidebar-hover-bg hover:text-white">
                                    <svg class="w-5 h-5 mr-4 icon-fill-current" viewBox="0 0 24 24">
                                        <path d="M13 14h-2V9h2v5zm-2 2h2v2h-2zm-1-9H8V5h2zm-2 2H6v2h2zm-2 2H4v2h2zm10 0h2v2h-2zm-2 2h2v2h-2zm2 0h2v2h-2zm-2 2h2v2h-2zm-2 0h2v2h-2zm-2 2h2v2h-2zM4 22h16c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2H4c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2zm0-18h16v16H4V4z"/>
                                    </svg>
                                    Exercícios
                                </a>
                            </li>
                        </ul>
                    </nav>

                    <div class="pt-5 border-t border-gray-700">
                        <button id="logoutButton" class="sidebar-link flex items-center w-full p-3 rounded-lg text-sm text-red-500 hover:bg-red-700 hover:text-white">
                            <svg class="w-5 h-5 mr-4 icon-fill-current" viewBox="0 0 24 24">
                                <path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"/>
                            </svg>
                            Sair
                        </button>
                    </div>
                </aside>
            `;

            document.getElementById('mainContent').classList.add('ml-64');

            document.getElementById('logoutButton').addEventListener('click', async function () {
                try {
                    await fetch('http://127.0.0.1:8000/auth/logout', {
                        method: "POST",
                        headers: {
                            "Authorization": `Bearer ${token}`,
                            "Accept": "application/json",
                        },
                    });

                    localStorage.removeItem('token');
                    window.location.href = "/login";
                } catch (err) {
                    console.error("Erro ao fazer logout:", err);
                }
            });
        }
    </script>

</body>
</html>
