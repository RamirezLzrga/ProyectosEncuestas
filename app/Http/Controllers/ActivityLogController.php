<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with('user')->orderBy('created_at', 'desc');

        // Filtro por Período
        if ($request->has('period')) {
            switch ($request->period) {
                case 'today':
                    $query->where('created_at', '>=', now()->startOfDay());
                    break;
                case 'week':
                    $query->where('created_at', '>=', now()->subDays(7));
                    break;
                case 'month':
                    $query->where('created_at', '>=', now()->subMonth());
                    break;
                case 'year': // Para compatibilidad con el diseño que menciona 2025
                    $query->where('created_at', '>=', now()->startOfYear());
                    break;
            }
        }

        // Filtro por Acción/Tipo
        if ($request->has('type') && $request->type != 'all') {
            $query->where('type', $request->type);
        }

        $logs = $query->paginate(20);

        if ($request->ajax()) {
            return view('activity-logs.partials.list', compact('logs'));
        }

        return view('activity-logs.index', compact('logs'));
    }

    public function export(Request $request)
    {
        $query = ActivityLog::orderBy('created_at', 'desc');

        // Filtro por Período
        if ($request->has('period')) {
            switch ($request->period) {
                case 'today':
                    $query->where('created_at', '>=', now()->startOfDay());
                    break;
                case 'week':
                    $query->where('created_at', '>=', now()->subDays(7));
                    break;
                case 'month':
                    $query->where('created_at', '>=', now()->subMonth());
                    break;
                case 'year':
                    $query->where('created_at', '>=', now()->startOfYear());
                    break;
            }
        }

        // Filtro por Acción/Tipo
        if ($request->has('type') && $request->type != 'all') {
            $query->where('type', $request->type);
        }

        $logs = $query->get();

        $filename = "bitacora-" . date('Y-m-d-H-i-s') . ".csv";

        $headers = [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=\"$filename\"",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');
            
            // UTF-8 BOM para Excel
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($file, ['Fecha', 'Usuario', 'Email', 'Acción', 'Descripción', 'Tipo', 'IP']);

            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->created_at->format('Y-m-d H:i:s'),
                    $log->user ? $log->user->name : 'N/A',
                    $log->user_email,
                    $log->action,
                    $log->description,
                    $log->type,
                    $log->ip_address
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
