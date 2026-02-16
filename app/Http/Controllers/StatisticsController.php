<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\SurveyResponse;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;

class StatisticsController extends Controller
{
    public function index(Request $request)
    {
        // Obtener todas las encuestas para el selector
        $surveys = Survey::orderBy('created_at', 'desc')->get();

        // Obtener la encuesta seleccionada o la última por defecto
        $selectedSurveyId = $request->input('survey_id');
        $selectedSurvey = null;

        if ($selectedSurveyId) {
            $selectedSurvey = $surveys->find($selectedSurveyId);
        } else {
            $selectedSurvey = $surveys->first();
        }

        // Inicializar stats
        $stats = [
            'total_responses' => 0,
            'completion_rate' => 0,
            'responses_growth' => 0,
            'completion_growth' => 0,
            'responses_per_day' => ['labels' => [], 'data' => []],
            'responses_distribution' => ['labels' => [], 'data' => []],
            'recent_responses' => []
        ];

        if ($selectedSurvey) {
            // =========================================================
            // LÓGICA DE DATOS REALES
            // =========================================================

            // 1. Total de Respuestas
            $totalResponses = $selectedSurvey->responses()->count();
            $stats['total_responses'] = $totalResponses;

            // 2. Tasa de Completado (Para MVP asumimos 100% si se guardó)
            // En un sistema real se compararía contra "vistas" o "asignaciones"
            $stats['completion_rate'] = $totalResponses > 0 ? 100 : 0; 

            // Crecimiento vs mes anterior (Simple comparativa)
            $lastMonthResponses = $selectedSurvey->responses()
                ->where('created_at', '>=', Carbon::now()->subMonth())
                ->count();
            $prevMonthResponses = $selectedSurvey->responses()
                ->whereBetween('created_at', [Carbon::now()->subMonths(2), Carbon::now()->subMonth()])
                ->count();
            
            if ($prevMonthResponses > 0) {
                $growth = (($lastMonthResponses - $prevMonthResponses) / $prevMonthResponses) * 100;
                $stats['responses_growth'] = round($growth, 1);
            } else {
                $stats['responses_growth'] = $lastMonthResponses > 0 ? 100 : 0;
            }

            // 3. Gráfica de Evolución (Por día, últimos 7 días con actividad o rango fijo)
            // Para MongoDB y evitar problemas con selectRaw/SQL functions, hacemos el procesamiento en memoria
            // Esto es seguro para volúmenes moderados. Para millones de registros, usar Agregaciones Nativas de Mongo.
            
            $evolutionStartDate = Carbon::now()->subDays(30);
            
            // Traemos solo fecha de creación de los últimos 30 días
            $rawResponses = $selectedSurvey->responses()
                ->where('created_at', '>=', $evolutionStartDate)
                ->get(['created_at']); // Solo traemos created_at para optimizar
            
            // Agrupamos en memoria
            $groupedByDate = $rawResponses->groupBy(function($item) {
                return $item->created_at->format('Y-m-d');
            })->map(function($group) {
                return $group->count();
            });

            // Generar últimos 7 días para la gráfica
            $labelsEvolution = [];
            $dataEvolution = [];
            
            for ($i = 6; $i >= 0; $i--) {
                $dateObj = Carbon::now()->subDays($i);
                $dateKey = $dateObj->format('Y-m-d');
                $labelsEvolution[] = $dateObj->format('d M');
                
                $dataEvolution[] = $groupedByDate->get($dateKey, 0);
            }

            $stats['responses_per_day'] = [
                'labels' => $labelsEvolution,
                'data' => $dataEvolution
            ];

            // 4. Gráfica de Distribución
            // Analizar la PRIMERA pregunta de opción múltiple/checkboxes para la gráfica
            $targetQuestion = null;
            $questions = $selectedSurvey->questions ?? [];
            
            foreach ($questions as $q) {
                if (in_array($q['type'], ['multiple_choice', 'checkboxes', 'dropdown'])) {
                    $targetQuestion = $q;
                    break;
                }
            }

            if ($targetQuestion) {
                $qText = $targetQuestion['text'];
                $options = $targetQuestion['options'] ?? [];
                $counts = array_fill_keys($options, 0);

                // Obtener todas las respuestas para contar
                // Nota: Esto puede ser pesado con miles de respuestas, idealmente usar agregación de Mongo
                $allResponses = $selectedSurvey->responses()->get();
                
                foreach ($allResponses as $resp) {
                    $answers = $resp->answers ?? [];
                    // Buscar la respuesta a esta pregunta
                    // La clave en $answers es el texto de la pregunta (según show.blade.php)
                    if (isset($answers[$qText])) {
                        $val = $answers[$qText];
                        if (is_array($val)) { // Checkboxes
                            foreach ($val as $v) {
                                if (isset($counts[$v])) $counts[$v]++;
                            }
                        } else { // Radio/Select
                            if (isset($counts[$val])) $counts[$val]++;
                        }
                    }
                }

                $stats['responses_distribution'] = [
                    'labels' => array_keys($counts),
                    'data' => array_values($counts)
                ];
            } else {
                // Si no hay preguntas cerradas, mostrar mensaje vacío o contar total
                 $stats['responses_distribution'] = [
                    'labels' => ['Sin preguntas cerradas'],
                    'data' => [0]
                ];
            }

            // 5. Tabla de Respuestas Recientes
            $recent = $selectedSurvey->responses()->orderBy('created_at', 'desc')->take(10)->get();
            $formattedRecent = [];
            
            foreach ($recent as $r) {
                // Generar resumen (primeras 3 respuestas)
                $summaryParts = [];
                $answers = $r->answers ?? [];
                $i = 0;
                foreach ($answers as $q => $a) {
                    if ($i >= 3) break;
                    $val = is_array($a) ? implode(', ', $a) : $a;
                    $summaryParts[] = Str::limit($val, 20);
                    $i++;
                }
                
                $formattedRecent[] = [
                    'date' => $r->created_at->format('d/m/Y, h:i a'),
                    'summary' => implode(' | ', $summaryParts)
                ];
            }
            $stats['recent_responses'] = $formattedRecent;
        }

        return view('statistics.index', compact('surveys', 'selectedSurvey', 'stats'));
    }
}
