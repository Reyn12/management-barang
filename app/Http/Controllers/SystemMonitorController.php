<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class SystemMonitorController extends Controller
{
    public function index()
    {
        $startTime = microtime(true);

        // Performance metrics
        $memoryUsed = memory_get_usage(true);
        $peakMemory = memory_get_peak_usage(true);

        // Database size
        $dbInfo = $this->getDatabaseInfo();

        // Storage used (storage/app)
        $storageUsed = $this->getDirectorySize(storage_path('app'));

        // Record counts per table
        $tables = [
            ['name' => 'Produks', 'icon' => 'fa-box', 'color' => 'text-blue-500', 'count' => DB::table('produks')->count()],
            ['name' => 'Suppliers', 'icon' => 'fa-truck', 'color' => 'text-purple-500', 'count' => DB::table('suppliers')->count()],
            ['name' => 'Transaksis', 'icon' => 'fa-exchange-alt', 'color' => 'text-orange-500', 'count' => DB::table('transaksis')->count()],
            ['name' => 'Users', 'icon' => 'fa-users', 'color' => 'text-green-500', 'count' => DB::table('users')->count()],
        ];

        // PHP config
        $phpConfig = [
            ['label' => 'PHP Version', 'value' => PHP_VERSION, 'color' => 'text-indigo-600'],
            ['label' => 'Laravel Version', 'value' => app()->version(), 'color' => 'text-red-500'],
            ['label' => 'Memory Limit', 'value' => ini_get('memory_limit'), 'color' => 'text-amber-500'],
            ['label' => 'Max Execution Time', 'value' => ini_get('max_execution_time') . 's', 'color' => 'text-rose-500'],
            ['label' => 'Upload Max Filesize', 'value' => ini_get('upload_max_filesize'), 'color' => 'text-cyan-500'],
            ['label' => 'Post Max Size', 'value' => ini_get('post_max_size'), 'color' => 'text-violet-500'],
        ];

        // Server info
        $serverInfo = [
            ['label' => 'Server Software', 'value' => $_SERVER['SERVER_SOFTWARE'] ?? 'CLI'],
            ['label' => 'OS', 'value' => PHP_OS],
            ['label' => 'Server Protocol', 'value' => $_SERVER['SERVER_PROTOCOL'] ?? '-'],
            ['label' => 'Document Root', 'value' => $_SERVER['DOCUMENT_ROOT'] ?? base_path()],
            ['label' => 'PHP SAPI', 'value' => php_sapi_name()],
        ];

        $responseTime = round((microtime(true) - $startTime) * 1000, 2);

        return view('system-monitor.index', compact(
            'responseTime',
            'memoryUsed',
            'peakMemory',
            'dbInfo',
            'storageUsed',
            'tables',
            'phpConfig',
            'serverInfo'
        ));
    }

    private function getDatabaseInfo(): array
    {
        $driver = config('database.default');
        $size = 0;

        if ($driver === 'sqlite') {
            $path = config('database.connections.sqlite.database');
            if (File::exists($path)) {
                $size = File::size($path);
            }
        } elseif ($driver === 'mysql') {
            $dbName = config('database.connections.mysql.database');
            $result = DB::select("SELECT SUM(data_length + index_length) as size FROM information_schema.tables WHERE table_schema = ?", [$dbName]);
            $size = $result[0]->size ?? 0;
        }

        return [
            'driver' => strtoupper($driver),
            'size' => $size,
        ];
    }

    private function getDirectorySize(string $path): int
    {
        if (!File::isDirectory($path)) {
            return 0;
        }

        $size = 0;
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($path, \RecursiveDirectoryIterator::SKIP_DOTS)
        );

        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $size += $file->getSize();
            }
        }

        return $size;
    }
}
