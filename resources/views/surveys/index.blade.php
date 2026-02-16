@extends('layouts.app')

@section('title', 'Encuestas')

@section('content')
    <!-- Header Section -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-3xl font-bold text-gray-800">Encuestas</h2>
            <p class="text-gray-500 mt-1">Gestiona todas tus encuestas</p>
        </div>
        <a href="{{ route('surveys.create') }}" class="bg-uaemex text-white px-6 py-3 rounded-lg shadow-lg shadow-green-900/20 hover:bg-green-800 transition font-bold flex items-center gap-2">
            <i class="fas fa-plus"></i> Nueva Encuesta
        </a>
    </div>

    <!-- Filters & Search -->
    <form action="{{ route('surveys.index') }}" method="GET" id="filtersForm" class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex flex-wrap gap-4 items-center">
        
        <!-- Filtro Fecha Desde (Flatpickr) -->
        <div class="flex items-center gap-3 bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-200">
            <label class="text-sm font-bold text-gray-600">Desde:</label>
            <input type="text" id="datepicker" name="start_date" value="{{ request('start_date') }}" class="bg-transparent text-sm font-medium focus:outline-none text-gray-800 w-28 cursor-pointer" placeholder="Seleccionar...">
        </div>

        <!-- Filtro Estado -->
        <div class="flex items-center gap-3 bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-200">
            <label class="text-sm font-bold text-gray-600">Estado:</label>
            <select name="status" onchange="this.form.submit()" class="bg-transparent text-sm font-medium focus:outline-none text-gray-800 cursor-pointer">
                <option value="Todas" {{ request('status') == 'Todas' ? 'selected' : '' }}>Todas</option>
                <option value="Activas" {{ request('status') == 'Activas' ? 'selected' : '' }}>Activas</option>
                <option value="Inactivas" {{ request('status') == 'Inactivas' ? 'selected' : '' }}>Inactivas</option>
            </select>
        </div>
        
        <!-- Buscador -->
        <div class="flex items-center gap-3 bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-200 flex-1 min-w-[200px]">
            <label class="text-sm font-bold text-gray-600">Buscar:</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar encuesta..." class="bg-transparent text-sm font-medium focus:outline-none text-gray-800 w-full">
            <button type="submit" class="hidden">Buscar</button>
        </div>

        <a href="{{ route('surveys.index') }}" class="text-uaemex text-sm font-bold border border-uaemex px-4 py-1.5 rounded-lg hover:bg-uaemex hover:text-white transition flex items-center gap-2">
            <i class="fas fa-sync-alt"></i> Resetear
        </a>
    </form>

    <!-- Surveys List (Table) -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        @if($surveys->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100 text-xs uppercase text-gray-500 font-bold tracking-wider">
                            <th class="px-6 py-4">Título</th>
                            <th class="px-6 py-4">Autor</th>
                            <th class="px-6 py-4">Creación</th>
                            <th class="px-6 py-4 text-center">Respuestas</th>
                            <th class="px-6 py-4 text-center">Preguntas</th>
                            <th class="px-6 py-4 text-center">Estado</th>
                            <th class="px-6 py-4 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($surveys as $survey)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-gray-800 text-sm md:text-base">{{ $survey->title }}</span>
                                        <span class="text-xs text-gray-500 line-clamp-1 max-w-xs">{{ $survey->description }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <div class="h-6 w-6 rounded-full bg-gray-200 flex items-center justify-center text-xs font-bold text-gray-600">
                                            {{ substr($survey->user->name ?? '?', 0, 1) }}
                                        </div>
                                        <span class="text-sm font-medium text-gray-700">{{ $survey->user->name ?? 'Desconocido' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm text-gray-600 flex items-center gap-2">
                                        <i class="far fa-clock text-gray-400"></i>
                                        {{ $survey->created_at ? $survey->created_at->format('d/m/Y h:i A') : 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="bg-gray-100 text-gray-700 text-xs font-bold px-2 py-1 rounded-full">
                                        {{ $survey->responses()->count() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="text-sm text-gray-600 font-medium">{{ count($survey->questions ?? []) }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="{{ $survey->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }} text-xs font-bold px-2 py-1 rounded-full inline-block min-w-[80px]">
                                        {{ $survey->is_active ? 'Activa' : 'Inactiva' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('surveys.public', $survey->id) }}" target="_blank" class="text-gray-400 hover:text-purple-600 transition p-2 rounded-lg hover:bg-purple-50" title="Responder / Enlace Público">
                                            <i class="fas fa-external-link-alt"></i>
                                        </a>
                                        <a href="{{ route('surveys.show', $survey->id) }}" class="text-gray-400 hover:text-uaemex transition p-2 rounded-lg hover:bg-green-50" title="Ver">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('surveys.edit', $survey->id) }}" class="text-gray-400 hover:text-blue-500 transition p-2 rounded-lg hover:bg-blue-50" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        
                                        <form action="{{ route('surveys.toggle-status', $survey->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-gray-400 {{ $survey->is_active ? 'hover:text-red-500 hover:bg-red-50' : 'hover:text-green-500 hover:bg-green-50' }} transition p-2 rounded-lg" title="{{ $survey->is_active ? 'Inhabilitar' : 'Habilitar' }}">
                                                <i class="fas {{ $survey->is_active ? 'fa-ban' : 'fa-check-circle' }}"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-16">
                <div class="text-gray-200 mb-4 text-6xl">
                    <i class="fas fa-search"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-500">No se encontraron encuestas</h3>
                <p class="text-gray-400 mt-2">Intenta ajustar los filtros de búsqueda</p>
                @if(request()->has('start_date') || request()->has('status') || request()->has('search'))
                    <a href="{{ route('surveys.index') }}" class="mt-6 inline-block text-uaemex font-bold hover:underline">
                        Limpiar filtros
                    </a>
                @else
                    <a href="{{ route('surveys.create') }}" class="mt-6 inline-block bg-uaemex text-white px-6 py-2 rounded-lg font-bold hover:bg-green-800 transition">
                        Crear Encuesta
                    </a>
                @endif
            </div>
        @endif
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        /* Personalización de Flatpickr */
        .flatpickr-day.has-survey {
            background: #e6fffa;
            border-color: transparent;
            position: relative;
        }
        .flatpickr-day.has-survey::after {
            content: '';
            position: absolute;
            bottom: 4px;
            left: 50%;
            transform: translateX(-50%);
            width: 4px;
            height: 4px;
            background-color: #3b5f39; /* Color UAEMex */
            border-radius: 50%;
        }
        .flatpickr-day.selected.has-survey {
            background: #3b5f39;
            border-color: #3b5f39;
            color: #fff;
        }
        .flatpickr-day.selected.has-survey::after {
            background-color: #fff;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/es.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Fechas con encuestas pasadas desde el controlador
            const surveyDates = @json($surveyDates ?? []);

            flatpickr("#datepicker", {
                locale: "es",
                dateFormat: "Y-m-d",
                altInput: true,
                altFormat: "d/m/Y",
                allowInput: true,
                onDayCreate: function(dObj, dStr, fp, dayElem) {
                    // Formatear la fecha del día actual en el loop
                    const date = dayElem.dateObj.toISOString().slice(0, 10);
                    
                    // Si la fecha está en nuestra lista, agregar clase
                    if (surveyDates.includes(date)) {
                        dayElem.classList.add('has-survey');
                        dayElem.title = "Hay encuestas este día";
                    }
                },
                onChange: function(selectedDates, dateStr, instance) {
                    // Enviar el formulario al seleccionar fecha
                    document.getElementById('filtersForm').submit();
                }
            });
        });
    </script>
@endpush