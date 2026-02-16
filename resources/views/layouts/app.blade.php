<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIEI UAEMex - @yield('title', 'Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .bg-uaemex-dark { background-color: #1b393b; }
        .bg-uaemex { background-color: #0d5c41; }
        .text-uaemex { color: #0d5c41; }
        .btn-uaemex { background-color: #0d5c41; }
        .sidebar-active {
            background-color: rgba(255, 255, 255, 0.1);
            border-left: 4px solid #d4af37;
        }
        .text-gold { color: #d4af37; }
        .bg-gold { background-color: #d4af37; }
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #c1c1c1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #a8a8a8; }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-50 font-sans flex h-screen overflow-hidden">

    <!-- Sidebar -->
    <aside class="w-64 bg-uaemex-dark text-white flex flex-col shadow-2xl z-20">
        <div class="p-6 flex items-center gap-3">
            <div class="bg-gold text-uaemex-dark font-bold p-2 rounded-lg h-10 w-10 flex items-center justify-center text-xl">UA</div>
            <div>
                <h1 class="text-lg font-bold tracking-wide">SIEI UAEMex</h1>
            </div>
        </div>

        <nav class="flex-1 px-4 space-y-4 mt-4 overflow-y-auto">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase mb-3 px-2 tracking-wider">Principal</p>
                <div class="space-y-1">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('dashboard') ? 'sidebar-active' : 'text-gray-400 hover:bg-white/5 hover:text-white border-l-4 border-transparent' }} rounded-r-lg text-white font-medium transition-all duration-200">
                        <i class="fas fa-chart-pie w-5"></i>
                        Dashboard
                    </a>
                    <a href="{{ route('surveys.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('surveys.*') ? 'sidebar-active' : 'text-gray-400 hover:bg-white/5 hover:text-white border-l-4 border-transparent' }} rounded-r-lg transition-all duration-200">
                        <i class="fas fa-clipboard-list w-5"></i>
                        Encuestas
                    </a>
                    <a href="{{ route('statistics.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('statistics.*') ? 'sidebar-active' : 'text-gray-400 hover:bg-white/5 hover:text-white border-l-4 border-transparent' }} rounded-r-lg transition-all duration-200">
                        <i class="fas fa-chart-line w-5"></i>
                        Estadísticas
                    </a>
                </div>
            </div>

            @if(Auth::user()->role === 'admin')
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase mb-3 px-2 tracking-wider">Gestión</p>
                <div class="space-y-1">
                    <a href="{{ route('activity-logs.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('activity-logs.*') ? 'sidebar-active' : 'text-gray-400 hover:bg-white/5 hover:text-white border-l-4 border-transparent' }} rounded-r-lg transition-all duration-200">
                        <i class="fas fa-book w-5"></i>
                        Bitácora
                    </a>
                    <a href="{{ route('users.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('users.*') ? 'sidebar-active' : 'text-gray-400 hover:bg-white/5 hover:text-white border-l-4 border-transparent' }} rounded-r-lg transition-all duration-200">
                        <i class="fas fa-users w-5"></i>
                        Usuarios
                    </a>
                </div>
            </div>

            <div>
                <p class="text-xs font-bold text-gray-400 uppercase mb-3 px-2 tracking-wider">Configuración</p>
                <div class="space-y-1">
                    <a href="#" class="flex items-center gap-3 px-4 py-3 text-gray-400 hover:bg-white/5 hover:text-white rounded-r-lg transition-all duration-200 border-l-4 border-transparent">
                        <i class="fas fa-calendar-alt w-5"></i>
                        Períodos
                    </a>
                    <a href="#" class="flex items-center gap-3 px-4 py-3 text-gray-400 hover:bg-white/5 hover:text-white rounded-r-lg transition-all duration-200 border-l-4 border-transparent">
                        <i class="fas fa-cog w-5"></i>
                        Ajustes
                    </a>
                </div>
            </div>
            @endif
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col h-screen overflow-y-auto bg-gray-50">
        <!-- Top Bar with User Profile -->
        <header class="bg-white border-b border-gray-200 px-8 py-4 flex justify-end items-center sticky top-0 z-30 shadow-sm">
            <div class="relative">
                <button id="userMenuBtn" class="flex items-center justify-center w-12 h-12 rounded-full bg-white border border-gray-200 hover:border-[#d4af37] text-gray-600 hover:text-[#d4af37] transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-[#d4af37]/50 shadow-sm active:scale-95 group">
                    <i class="fas fa-user text-xl group-hover:scale-110 transition-transform"></i>
                </button>

                <!-- Floating Dropdown Bubble -->
                <div id="userMenuDropdown" class="hidden absolute right-0 mt-3 w-72 bg-[#1b393b] rounded-xl shadow-2xl border border-white/10 overflow-hidden transform origin-top-right transition-all duration-200 z-50">
                    <div class="p-5">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-12 h-12 rounded-full bg-[#d4af37] flex items-center justify-center text-white font-bold text-lg shadow-inner">
                                {{ substr(Auth::user()->name, 0, 2) }}
                            </div>
                            <div class="overflow-hidden">
                                <p class="text-white font-bold text-base truncate uppercase tracking-wide">{{ Auth::user()->name }}</p>
                                <span class="inline-block mt-1 px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider
                                    @if(Auth::user()->role === 'admin') bg-purple-500/20 text-purple-200 border border-purple-500/30
                                    @elseif(Auth::user()->role === 'editor') bg-blue-500/20 text-blue-200 border border-blue-500/30
                                    @else bg-gray-500/20 text-gray-200 border border-gray-500/30 @endif">
                                    @switch(Auth::user()->role)
                                        @case('admin') Administrador @break
                                        @case('editor') Editor @break
                                        @default Usuario
                                    @endswitch
                                </span>
                            </div>
                        </div>

                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full flex items-center justify-center gap-2 bg-red-500/10 hover:bg-red-500/20 text-red-300 hover:text-red-200 text-sm font-bold uppercase tracking-wider transition py-3 rounded-lg border border-red-500/20 group">
                                <i class="fas fa-sign-out-alt group-hover:-translate-x-1 transition-transform"></i>
                                Cerrar Sesión
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <div class="p-8 space-y-8 max-w-7xl mx-auto w-full">
            @yield('content')
        </div>
    </main>

    <script>
        // Toggle User Menu Dropdown
        document.addEventListener('DOMContentLoaded', function() {
            const userMenuBtn = document.getElementById('userMenuBtn');
            const userMenuDropdown = document.getElementById('userMenuDropdown');

            if (userMenuBtn && userMenuDropdown) {
                userMenuBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    userMenuDropdown.classList.toggle('hidden');
                });

                // Close dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    if (!userMenuBtn.contains(e.target) && !userMenuDropdown.contains(e.target)) {
                        userMenuDropdown.classList.add('hidden');
                    }
                });
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
