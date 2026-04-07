<?php

namespace App\Services;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class InstallerService
{
    /**
     * Check application requirements.
     */
    public function checkRequirements(): array
    {
        $requirements = [
            'php' => [
                'name' => 'PHP >= 8.4',
                'status' => version_compare(PHP_VERSION, '8.4.0', '>='),
                'current' => PHP_VERSION,
            ],
            'openssl' => ['name' => 'OpenSSL Extension', 'status' => extension_loaded('openssl')],
            'pdo' => ['name' => 'PDO Extension', 'status' => extension_loaded('pdo')],
            'mbstring' => ['name' => 'Mbstring Extension', 'status' => extension_loaded('mbstring')],
            'tokenizer' => ['name' => 'Tokenizer Extension', 'status' => extension_loaded('tokenizer')],
            'xml' => ['name' => 'XML Extension', 'status' => extension_loaded('xml')],
            'ctype' => ['name' => 'Ctype Extension', 'status' => extension_loaded('ctype')],
            'json' => ['name' => 'JSON Extension', 'status' => extension_loaded('json')],
            'bcmath' => ['name' => 'BCMath Extension', 'status' => extension_loaded('bcmath')],
            'fileinfo' => ['name' => 'Fileinfo Extension', 'status' => extension_loaded('fileinfo')],
        ];

        return $requirements;
    }

    /**
     * Check folder permissions.
     */
    public function checkPermissions(): array
    {
        $permissions = [
            'storage' => [
                'name' => 'storage/',
                'status' => is_writable(storage_path()),
            ],
            'bootstrap/cache' => [
                'name' => 'bootstrap/cache/',
                'status' => is_writable(bootstrap_path('cache')),
            ],
            'env' => [
                'name' => '.env',
                'status' => is_writable(base_path()) || (file_exists(base_path('.env')) && is_writable(base_path('.env'))),
            ],
        ];

        return $permissions;
    }

    /**
     * Test database connection.
     */
    public function testDbConnection(array $data): bool
    {
        config([
            'database.connections.install_test' => [
                'driver' => 'mysql',
                'host' => $data['db_host'],
                'port' => $data['db_port'],
                'database' => $data['db_name'],
                'username' => $data['db_user'],
                'password' => $data['db_pass'],
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
                'strict' => true,
                'engine' => null,
            ],
        ]);

        try {
            DB::connection('install_test')->getPdo();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Update .env file.
     */
    public function updateEnv(array $data): void
    {
        $envPath = base_path('.env');
        $envExamplePath = base_path('.env.example');

        if (!file_exists($envPath)) {
            copy($envExamplePath, $envPath);
        }

        $content = file_get_contents($envPath);

        foreach ($data as $key => $value) {
            $key = strtoupper($key);
            $pattern = "/^{$key}=.*/m";
            
            if (preg_match($pattern, $content)) {
                $content = preg_replace($pattern, "{$key}=\"{$value}\"", $content);
            } else {
                $content .= "\n{$key}=\"{$value}\"";
            }
        }

        file_put_contents($envPath, $content);
        
        // Refresh config
        Artisan::call('config:clear');
    }

    /**
     * Run migration and seeding.
     */
    public function runInstallation(): void
    {
        Artisan::call('migrate:fresh', ['--force' => true]);
        Artisan::call('db:seed', ['--force' => true]);
        Artisan::call('key:generate', ['--force' => true]);
    }

    /**
     * Finalize installation.
     */
    public function finalize(): void
    {
        File::put(storage_path('installed'), date('Y-m-d H:i:s'));
    }
}
