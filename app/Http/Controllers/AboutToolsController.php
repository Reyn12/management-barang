<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\File;

class AboutToolsController extends Controller
{
    public function index()
    {
        $tools = [];

        // PHP
        $tools[] = [
            'name' => 'PHP',
            'value' => PHP_VERSION,
            'icon' => 'fab fa-php',
            'color' => 'bg-indigo-500',
        ];

        // Laravel
        $tools[] = [
            'name' => 'Laravel',
            'value' => app()->version(),
            'icon' => 'fab fa-laravel',
            'color' => 'bg-red-500',
        ];

        // Composer
        $composerVersion = $this->getComposerVersion();
        $tools[] = [
            'name' => 'Composer',
            'value' => $composerVersion,
            'icon' => 'fas fa-box',
            'color' => 'bg-amber-600',
        ];

        // Node.js
        $nodeVersion = $this->getNodeVersion();
        $tools[] = [
            'name' => 'Node.js',
            'value' => $nodeVersion,
            'icon' => 'fab fa-node-js',
            'color' => 'bg-green-600',
        ];

        // NPM
        $npmVersion = $this->getNpmVersion();
        $tools[] = [
            'name' => 'NPM',
            'value' => $npmVersion,
            'icon' => 'fab fa-npm',
            'color' => 'bg-red-600',
        ];

        // Vite (dari package.json)
        $viteVersion = $this->getViteVersion();
        $tools[] = [
            'name' => 'Vite',
            'value' => $viteVersion,
            'icon' => 'fas fa-bolt',
            'color' => 'bg-violet-500',
        ];

        // Tailwind (dari package.json)
        $tailwindVersion = $this->getTailwindVersion();
        $tools[] = [
            'name' => 'Tailwind CSS',
            'value' => $tailwindVersion,
            'icon' => 'fas fa-palette',
            'color' => 'bg-cyan-500',
        ];

        // Database driver (dari .env / config)
        $dbDriver = config('database.default');
        $tools[] = [
            'name' => 'Database',
            'value' => strtoupper($dbDriver),
            'icon' => 'fas fa-database',
            'color' => 'bg-blue-600',
        ];

        // Environment
        $tools[] = [
            'name' => 'Environment',
            'value' => app()->environment(),
            'icon' => 'fas fa-server',
            'color' => 'bg-gray-600',
        ];

        return view('about-tools.index', compact('tools'));
    }

    private function getComposerVersion(): string
    {
        try {
            $result = Process::run('composer --version 2>&1');
            if ($result->successful() && preg_match('/Composer version (\S+)/', $result->output(), $m)) {
                return $m[1];
            }
        } catch (\Throwable $e) {
            //
        }
        return class_exists(\Composer\Composer::class) ? '—' : 'Tidak terdeteksi';
    }

    private function getNodeVersion(): string
    {
        try {
            $result = Process::run('node -v 2>&1');
            if ($result->successful()) {
                return trim(str_replace('v', '', $result->output()));
            }
        } catch (\Throwable $e) {
            //
        }
        return 'Tidak terdeteksi';
    }

    private function getNpmVersion(): string
    {
        try {
            $result = Process::run('npm -v 2>&1');
            if ($result->successful()) {
                return trim($result->output());
            }
        } catch (\Throwable $e) {
            //
        }
        return 'Tidak terdeteksi';
    }

    private function getViteVersion(): string
    {
        $path = base_path('package.json');
        if (!File::exists($path)) {
            return '—';
        }
        $json = json_decode(File::get($path), true);
        $dev = $json['devDependencies'] ?? [];
        $deps = $json['dependencies'] ?? [];
        $vite = $dev['vite'] ?? $deps['vite'] ?? null;
        return $vite ? trim($vite, '^~') : '—';
    }

    private function getTailwindVersion(): string
    {
        $path = base_path('package.json');
        if (!File::exists($path)) {
            return '—';
        }
        $json = json_decode(File::get($path), true);
        $dev = $json['devDependencies'] ?? [];
        $tw = $dev['tailwindcss'] ?? null;
        return $tw ? trim($tw, '^~') : '—';
    }
}
