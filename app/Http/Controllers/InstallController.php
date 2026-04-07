<?php

namespace App\Http\Controllers;

use App\Services\InstallerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class InstallController extends Controller
{
    protected $installerService;

    public function __construct(InstallerService $installerService)
    {
        $this->installerService = $installerService;
    }

    public function welcome()
    {
        return view('install.welcome');
    }

    public function requirements()
    {
        $requirements = $this->installerService->checkRequirements();
        $satisfied = !in_array(false, array_column($requirements, 'status'));

        return view('install.requirements', compact('requirements', 'satisfied'));
    }

    public function permissions()
    {
        $permissions = $this->installerService->checkPermissions();
        $satisfied = !in_array(false, array_column($permissions, 'status'));

        return view('install.permissions', compact('permissions', 'satisfied'));
    }

    public function environment()
    {
        return view('install.environment');
    }

    public function saveEnvironment(Request $request)
    {
        $request->validate([
            'app_name' => 'required|string|max:255',
            'app_url' => 'required|url',
            'db_host' => 'required|string',
            'db_port' => 'required|numeric',
            'db_name' => 'required|string',
            'db_user' => 'required|string',
            'db_pass' => 'nullable|string',
        ]);

        // Attempt DB connection test
        if (!$this->installerService->testDbConnection($request->only(['db_host', 'db_port', 'db_name', 'db_user', 'db_pass']))) {
            return back()->withErrors(['db_connection' => 'Could not connect to the database. Please check your credentials.'])->withInput();
        }

        // Update .env
        $this->installerService->updateEnv([
            'APP_NAME' => $request->app_name,
            'APP_URL' => $request->app_url,
            'DB_HOST' => $request->db_host,
            'DB_PORT' => $request->db_port,
            'DB_DATABASE' => $request->db_name,
            'DB_USERNAME' => $request->db_user,
            'DB_PASSWORD' => $request->db_pass,
        ]);

        return redirect()->route('install.migration');
    }

    public function migration()
    {
        return view('install.migration');
    }

    public function runMigration()
    {
        try {
            $this->installerService->runInstallation();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Installation Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function admin()
    {
        return view('install.admin');
    }

    public function saveAdmin(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'required|min:8|confirmed',
        ]);

        try {
            // Check if user table exists
            if (!Schema::hasTable('users')) {
                return back()->withErrors(['error' => 'Database tables not found. Please run migrations first.']);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'email_verified_at' => now(),
            ]);

            // Assign Super Admin role if Spatie is used
            if ($user && method_exists($user, 'assignRole')) {
                // You might need to adjust role name based on your seeders
                try {
                    $user->assignRole('Super Admin');
                } catch (\Exception $e) {
                    // Ignore if role doesn't exist yet, it might be in seeders
                }
            }

            return redirect()->route('install.complete');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function complete()
    {
        $this->installerService->finalize();
        return view('install.complete');
    }
}
