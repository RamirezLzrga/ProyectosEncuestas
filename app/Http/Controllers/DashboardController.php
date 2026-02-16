<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // Métricas Generales
        $surveys = Survey::where('user_id', $userId)->get();
        $totalSurveys = $surveys->count();
        $activeSurveys = $surveys->where('is_active', true)->count();
        $inactiveSurveys = $totalSurveys - $activeSurveys;

        // Calcular total de respuestas (Iterando sobre colecciones para compatibilidad MongoDB)
        $totalResponses = 0;
        foreach ($surveys as $survey) {
            $totalResponses += $survey->responses()->count();
        }

        // Tasa de respuesta promedio (Respuestas / Encuestas que tienen al menos 1 respuesta)
        // Como no tenemos "total de envíos esperados", usaremos un cálculo simple o placeholder dinámico
        // Para este dashboard, calcularemos el promedio de respuestas por encuesta
        $avgResponses = $totalSurveys > 0 ? round($totalResponses / $totalSurveys, 1) : 0;

        // Encuestas Recientes
        $recentSurveys = Survey::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        // Datos para Gráfica de Barras (Top 5 encuestas con más respuestas)
        // Procesamiento en memoria para evitar problemas con Mongo aggregation en Eloquent básico
        $surveysWithResponses = $surveys->map(function ($survey) {
            return [
                'title' => $survey->title,
                'responses_count' => $survey->responses()->count()
            ];
        })->sortByDesc('responses_count')->take(5);

        $chartLabels = $surveysWithResponses->pluck('title')->values()->toArray();
        $chartData = $surveysWithResponses->pluck('responses_count')->values()->toArray();

        // Datos para Gráfica de Dona (Estado)
        $doughnutData = [$activeSurveys, $inactiveSurveys];

        return view('dashboard', compact(
            'totalSurveys',
            'activeSurveys',
            'inactiveSurveys',
            'totalResponses',
            'avgResponses',
            'recentSurveys',
            'chartLabels',
            'chartData',
            'doughnutData'
        ));
    }
}
