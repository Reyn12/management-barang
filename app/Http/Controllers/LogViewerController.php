<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class LogViewerController extends Controller
{
    public function index(Request $request)
    {
        $logPath = storage_path('logs/laravel.log');

        if (!File::exists($logPath)) {
            return view('log-viewer.index', [
                'logs' => collect(),
                'totalEntries' => 0,
                'logDates' => '',
            ]);
        }

        $content = File::get($logPath);

        $pattern = '/\[(\d{4}-\d{2}-\d{2}[^\]]*)\]\s+(\w+)\.(\w+):\s(.*?)(?=\[\d{4}-\d{2}-\d{2}[^\]]*\]\s+\w+\.\w+:|$)/s';
        preg_match_all($pattern, $content, $matches, PREG_SET_ORDER);

        $logs = collect($matches)->map(function ($match, $index) {
            return [
                'index' => $index + 1,
                'date' => $match[1] ?? '',
                'env' => $match[2] ?? '',
                'level' => strtoupper($match[3] ?? ''),
                'message' => trim($match[4] ?? ''),
            ];
        })->reverse()->values();

        $logDates = '';
        if ($logs->isNotEmpty()) {
            $first = substr($logs->last()['date'], 0, 10);
            $last = substr($logs->first()['date'], 0, 10);
            $logDates = $first === $last ? $first : "{$first} - {$last}";
        }

        if ($request->filled('level')) {
            $logs = $logs->filter(fn($log) => $log['level'] === strtoupper($request->level));
        }

        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $logs = $logs->filter(fn($log) => str_contains(strtolower($log['message']), $search));
        }

        $logs = $logs->take(200)->values();

        return view('log-viewer.index', [
            'logs' => $logs,
            'totalEntries' => $logs->count(),
            'logDates' => $logDates,
        ]);
    }
}
