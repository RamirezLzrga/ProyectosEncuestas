@extends('layouts.app')

@section('title', 'Bitácora de Actividades')

@section('content')
    <!-- Header Section -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-3xl font-bold text-gray-800 flex items-center gap-3">
                Bitácora de Actividades
                <span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded-full flex items-center gap-1 animate-pulse">
                    <span class="w-2 h-2 bg-green-500 rounded-full"></span> En vivo
                </span>
            </h2>
            <p class="text-gray-500 mt-1">Registro de todas las acciones en el sistema</p>
        </div>
        <a href="{{ route('activity-logs.export', request()->query()) }}" class="bg-white text-gray-700 border border-gray-300 px-4 py-2 rounded-lg shadow-sm hover:bg-gray-50 transition font-bold flex items-center gap-2">
            <i class="fas fa-file-export text-uaemex"></i> Exportar Bitácora
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex flex-wrap gap-4 items-center mt-6">
        <form method="GET" action="{{ route('activity-logs.index') }}" class="flex flex-wrap gap-4 items-center w-full">
            <div class="flex items-center gap-3 bg-gray-50 px-3 py-2 rounded-lg border border-gray-200">
                <label class="text-sm font-bold text-gray-600">Período:</label>
                <select name="period" class="bg-transparent text-sm font-medium focus:outline-none text-gray-800 cursor-pointer">
                    <option value="today" {{ request('period') == 'today' ? 'selected' : '' }}>Hoy</option>
                    <option value="week" {{ request('period') == 'week' ? 'selected' : '' }}>Última Semana</option>
                    <option value="month" {{ request('period') == 'month' ? 'selected' : '' }}>Último Mes</option>
                    <option value="year" {{ request('period') == 'year' ? 'selected' : '' }}>2025</option>
                    <option value="all" {{ request('period') == 'all' ? 'selected' : '' }}>Todos</option>
                </select>
            </div>
            
            <div class="flex items-center gap-3 bg-gray-50 px-3 py-2 rounded-lg border border-gray-200">
                <label class="text-sm font-bold text-gray-600">Acción:</label>
                <select name="type" class="bg-transparent text-sm font-medium focus:outline-none text-gray-800 cursor-pointer">
                    <option value="all" {{ request('type') == 'all' ? 'selected' : '' }}>Todas</option>
                    <option value="auth" {{ request('type') == 'auth' ? 'selected' : '' }}>Inicios de sesión</option>
                    <option value="survey" {{ request('type') == 'survey' ? 'selected' : '' }}>Encuestas</option>
                    <option value="user" {{ request('type') == 'user' ? 'selected' : '' }}>Usuarios</option>
                </select>
            </div>

            <button type="submit" class="bg-uaemex text-white text-sm font-bold px-6 py-2 rounded-lg hover:bg-green-800 transition shadow-md">
                Aplicar
            </button>
        </form>
    </div>

    <!-- Logs List -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mt-6 overflow-hidden">
        <div id="logs-container" class="divide-y divide-gray-100">
            @if($logs->count() > 0)
                @include('activity-logs.partials.list')
            @else
                <div class="p-12 text-center text-gray-400">
                    <div class="bg-gray-50 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-history text-3xl text-gray-300"></i>
                    </div>
                    <p class="font-medium">No hay registros de actividad para mostrar.</p>
                    <p class="text-sm mt-1">Intenta cambiar los filtros de búsqueda.</p>
                </div>
            @endif
        </div>
    </div>
    
    <!-- Auto-refresh script for "Real-time" feel -->
    <script>
        // Actualizar logs cada 5 segundos usando AJAX
        setInterval(function(){
            // Obtener URL actual con sus parámetros (filtros)
            const url = window.location.href;
            
            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                // Si la respuesta no está vacía, actualizar el contenedor
                if(html.trim() !== '') {
                    const container = document.getElementById('logs-container');
                    // Solo actualizar si hay cambios significativos (opcional, por ahora reemplazamos)
                    // Para una experiencia más suave, podríamos comparar contenido
                    container.innerHTML = html;
                }
            })
            .catch(error => console.error('Error actualizando bitácora:', error));
        }, 5000);
    </script>
@endsection
