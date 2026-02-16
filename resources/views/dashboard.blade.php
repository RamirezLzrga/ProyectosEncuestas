@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <!-- Header Section -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-3xl font-bold text-gray-800">Dashboard</h2>
            <p class="text-gray-500 mt-1">Resumen general • <span class="font-semibold text-gray-700">{{ $activeSurveys }} activas, {{ $inactiveSurveys }} inactivas</span></p>
        </div>
        <a href="{{ route('surveys.create') }}" class="bg-uaemex text-white px-6 py-3 rounded-lg shadow-lg shadow-green-900/20 hover:bg-green-800 transition font-bold flex items-center gap-2">
            <i class="fas fa-plus"></i> Nueva Encuesta
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
        <!-- Card 1 -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition duration-300">
            <div class="flex justify-between items-start mb-6">
                <div class="p-3 bg-orange-50 rounded-xl text-orange-400 shadow-sm">
                    <i class="fas fa-clipboard text-xl"></i>
                </div>
            </div>
            <h3 class="text-4xl font-bold text-gray-800 mb-2">{{ $totalSurveys }}</h3>
            <p class="text-gray-500 text-sm font-medium">Total Encuestas</p>
            <div class="mt-4 flex gap-2">
                <span class="bg-green-50 text-green-700 text-xs px-2 py-1 rounded border border-green-100 font-bold">{{ $activeSurveys }} activas</span>
                <span class="bg-red-50 text-red-700 text-xs px-2 py-1 rounded border border-red-100 font-bold">{{ $inactiveSurveys }} inactivas</span>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition duration-300">
            <div class="flex justify-between items-start mb-6">
                <div class="p-3 bg-blue-50 rounded-xl text-blue-400 shadow-sm">
                    <i class="fas fa-chart-bar text-xl"></i>
                </div>
            </div>
            <h3 class="text-4xl font-bold text-gray-800 mb-2">{{ $totalResponses }}</h3>
            <p class="text-gray-500 text-sm font-medium">Respuestas recibidas</p>
            <p class="text-xs text-gray-400 mt-1">Promedio: {{ $avgResponses }} por encuesta</p>
        </div>

        <!-- Card 3 -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition duration-300">
            <div class="flex justify-between items-start mb-6">
                <div class="p-3 bg-pink-50 rounded-xl text-pink-400 shadow-sm">
                    <i class="fas fa-bullseye text-xl"></i>
                </div>
            </div>
            <h3 class="text-4xl font-bold text-gray-800 mb-2">{{ $activeSurveys > 0 ? round(($activeSurveys / ($totalSurveys > 0 ? $totalSurveys : 1)) * 100) : 0 }}%</h3>
            <p class="text-gray-500 text-sm font-medium">Encuestas Activas</p>
            <div class="w-full bg-gray-100 rounded-full h-2 mt-5">
                <div class="bg-gradient-to-r from-uaemex to-green-400 h-2 rounded-full" style="width: {{ $activeSurveys > 0 ? round(($activeSurveys / ($totalSurveys > 0 ? $totalSurveys : 1)) * 100) : 0 }}%"></div>
            </div>
            <p class="text-xs text-gray-400 mt-2 text-right">Porcentaje de actividad</p>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-8">
        <!-- Chart 1 -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-bold text-gray-800">Top Encuestas (Respuestas)</h3>
            </div>
            <div class="h-64 relative">
                <canvas id="barChart"></canvas>
            </div>
        </div>

        <!-- Chart 2 -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-bold text-gray-800">Estado de Encuestas</h3>
            </div>
            <div class="h-64 relative flex justify-center">
                <canvas id="doughnutChart"></canvas>
            </div>
            <div class="flex justify-center gap-6 mt-4">
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full" style="background-color: #0d5c41"></div>
                    <span class="text-xs text-gray-600 font-bold">Activas</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full" style="background-color: #d4af37"></div>
                    <span class="text-xs text-gray-600 font-bold">Inactivas</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Surveys List -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mb-8 mt-8">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-bold text-xl text-gray-800">Encuestas Recientes</h3>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @forelse($recentSurveys as $survey)
                <div class="border border-gray-200 rounded-xl p-4 hover:border-uaemex transition cursor-pointer group relative">
                     <a href="{{ route('surveys.show', $survey->id) }}" class="absolute inset-0 z-10"></a>
                    <div class="flex justify-between items-start">
                        <div>
                            <h4 class="font-bold text-gray-800 group-hover:text-uaemex transition">{{ $survey->title }}</h4>
                            <p class="text-xs text-gray-500 mt-1 line-clamp-1">{{ $survey->description }}</p>
                        </div>
                        <span class="{{ $survey->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }} text-xs font-bold px-2 py-1 rounded">
                            {{ $survey->is_active ? 'Activa' : 'Inactiva' }}
                        </span>
                    </div>
                    <div class="mt-4 flex items-center gap-4 text-xs text-gray-500">
                        <div class="flex items-center gap-1 bg-gray-50 px-2 py-1 rounded">
                            <i class="far fa-calendar text-gray-400"></i> {{ \Carbon\Carbon::parse($survey->created_at)->format('d/m/Y') }}
                        </div>
                        @if(isset($survey->settings['anonymous']) && $survey->settings['anonymous'])
                        <div class="flex items-center gap-1 bg-blue-50 text-blue-600 px-2 py-1 rounded">
                            <i class="fas fa-lock"></i> Anónima
                        </div>
                        @endif
                        <div class="flex items-center gap-1 bg-gray-100 text-gray-600 px-2 py-1 rounded ml-auto">
                            <i class="fas fa-comment-dots"></i> {{ $survey->responses()->count() }}
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-2 text-center py-8 text-gray-400">
                    <p>No has creado encuestas todavía.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Bar Chart
    const ctxBar = document.getElementById('barChart').getContext('2d');
    const chartLabels = @json($chartLabels);
    const chartData = @json($chartData);

    new Chart(ctxBar, {
        type: 'bar',
        data: {
            labels: chartLabels.length > 0 ? chartLabels : ['Sin datos'],
            datasets: [{
                label: 'Respuestas',
                data: chartData.length > 0 ? chartData : [0],
                backgroundColor: '#4ade80',
                borderRadius: 6,
                barThickness: 40
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true, grid: { borderDash: [2, 2] } },
                x: { grid: { display: false } }
            }
        }
    });

    // Doughnut Chart
    const ctxDoughnut = document.getElementById('doughnutChart').getContext('2d');
    const doughnutData = @json($doughnutData); // [Active, Inactive]

    new Chart(ctxDoughnut, {
        type: 'doughnut',
        data: {
            labels: ['Activas', 'Inactivas'],
            datasets: [{
                data: doughnutData[0] + doughnutData[1] > 0 ? doughnutData : [1, 0], // Fallback visual if no data
                backgroundColor: ['#0d5c41', '#d4af37'],
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '75%',
            plugins: {
                legend: { display: false }
            }
        }
    });
</script>
@endpush