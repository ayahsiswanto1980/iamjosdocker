<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Laravel Setup Wizard' }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: radial-gradient(circle at top left, #1e293b, #0f172a);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #f1f5f9;
            overflow-x: hidden;
        }

        .glass {
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
        }

        .step-active {
            color: #38bdf8;
            border-bottom: 2px solid #38bdf8;
        }

        .btn-premium {
            background: linear-gradient(135deg, #0ea5e9 0%, #2563eb 100%);
            transition: all 0.3s ease;
        }

        .btn-premium:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(14, 165, 233, 0.4);
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-fade-in {
            animation: fadeIn 0.5s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        }

        .loader {
            border: 3px solid rgba(255, 255, 255, 0.1);
            border-top: 3px solid #38bdf8;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="container max-w-2xl px-4 py-8 relative">
        <!-- Abstract background blobs -->
        <div class="absolute -top-24 -left-24 w-64 h-64 bg-blue-500/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-24 -right-24 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl"></div>

        <div class="glass rounded-3xl p-8 md:p-12 animate-fade-in relative z-10">
            <div class="text-center mb-10">
                <div class="inline-flex items-center justify-center p-3 bg-blue-500/20 rounded-2xl mb-4">
                    <svg class="w-10 h-10 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold tracking-tight text-white mb-2">
                    {{ config('app.name', 'Laravel') }} Setup
                </h1>
                <p class="text-slate-400">Step-by-step application configuration</p>
            </div>

            <!-- Progress Indicator -->
            <div class="flex justify-between mb-12 relative">
                <div class="absolute top-1/2 left-0 w-full h-0.5 bg-slate-700 -translate-y-1/2 -z-10"></div>
                @php
                    $steps = [
                        'welcome' => 'Start',
                        'requirements' => 'Check',
                        'permissions' => 'Write',
                        'environment' => 'Config',
                        'migration' => 'Build',
                        'admin' => 'Admin'
                    ];
                    $currentRoute = Route::currentRouteName();
                    $activeFound = false;
                @endphp

                @foreach($steps as $route => $label)
                    @php
                        $isCurrent = "install.$route" === $currentRoute;
                        if ($isCurrent) $activeFound = true;
                    @endphp
                    <div class="flex flex-col items-center">
                        <div class="w-4 h-4 rounded-full {{ $isCurrent ? 'bg-blue-400 shadow-[0_0_10px_rgba(56,189,248,0.5)]' : ($activeFound ? 'bg-slate-700' : 'bg-blue-500') }} transition-colors"></div>
                        <span class="text-[10px] mt-2 font-semibold uppercase tracking-wider {{ $isCurrent ? 'text-blue-400' : 'text-slate-500' }}">
                            {{ $label }}
                        </span>
                    </div>
                @endforeach
            </div>

            <main>
                @yield('content')
            </main>

            <footer class="mt-12 pt-8 border-t border-slate-700/50 flex justify-between items-center text-sm text-slate-500">
                <span>&copy; {{ date('Y') }} Engineering Excellence</span>
                <span class="px-2 py-1 bg-slate-800 rounded font-mono text-[10px]">v1.0.0</span>
            </footer>
        </div>
    </div>
    
    @stack('scripts')
</body>
</html>
