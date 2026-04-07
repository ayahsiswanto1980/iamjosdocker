@extends('layouts.installer')

@section('content')
<div class="text-center">
    <h2 class="text-xl font-semibold mb-6 text-white">Project Initialization</h2>
    
    <div id="migration-status" class="mb-10 p-8 border border-slate-700/50 bg-slate-800/20 rounded-3xl relative overflow-hidden">
        <div id="progress-indicator" class="relative z-10 flex flex-col items-center">
            <div class="w-16 h-16 bg-blue-500/20 rounded-full flex items-center justify-center mb-6">
                <svg id="status-icon" class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
            </div>
            <p id="status-text" class="text-slate-300 font-medium mb-2">Ready to initialize database</p>
            <p class="text-slate-500 text-xs text-center max-w-xs mx-auto mb-8">
                This will run all database migrations, seed initial data, and generate required security keys.
            </p>

            <button id="run-migration-btn" onclick="runMigration()" 
                    class="px-10 py-4 btn-premium rounded-2xl text-white font-semibold transition-all shadow-xl shadow-blue-500/20 active:scale-95">
                Initialize Application
            </button>
        </div>

        <div id="loading-overlay" class="hidden absolute inset-0 bg-slate-900/40 backdrop-blur-sm z-20 flex flex-col items-center justify-center">
            <div class="loader mb-4"></div>
            <span class="text-blue-400 font-semibold tracking-wider uppercase text-[10px]" id="loading-text">Processing...</span>
        </div>
    </div>

    <div class="flex justify-between items-center text-xs text-slate-500 bg-slate-800/30 p-4 rounded-2xl border border-slate-700/30 mb-8">
        <div class="flex items-center">
            <svg class="w-4 h-4 mr-2 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            Migrations ({{ count(File::files(database_path('migrations'))) }})
        </div>
        <div class="flex items-center">
            <svg class="w-4 h-4 mr-2 text-indigo-500" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            Seeding (Available)
        </div>
    </div>

    <div id="action-buttons" class="hidden flex justify-end">
        <a href="{{ route('install.admin') }}" 
           class="px-10 py-4 btn-premium rounded-2xl text-white font-semibold transition-all shadow-xl shadow-blue-500/20 active:scale-95">
            Continue to Admin Setup
            <svg class="ml-2 w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
            </svg>
        </a>
    </div>
</div>

@push('scripts')
<script>
    function runMigration() {
        const btn = document.getElementById('run-migration-btn');
        const loader = document.getElementById('loading-overlay');
        const statusText = document.getElementById('status-text');
        const statusIcon = document.getElementById('status-icon');
        const actionBtn = document.getElementById('action-buttons');
        const loadingText = document.getElementById('loading-text');

        loader.classList.remove('hidden');
        btn.disabled = true;
        statusText.innerText = "Processing system initialization...";

        fetch("{{ route('install.migration.run') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            loader.classList.add('hidden');
            if (data.success) {
                statusText.innerText = "System successfully initialized!";
                statusText.classList.add('text-emerald-400');
                statusIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>`;
                statusIcon.classList.remove('text-blue-400');
                statusIcon.classList.add('text-emerald-400');
                btn.classList.add('hidden');
                actionBtn.classList.remove('hidden');
            } else {
                statusText.innerText = "Error: " + (data.message || 'Unknown error');
                statusText.classList.add('text-rose-400');
                btn.disabled = false;
                btn.innerText = "Retry Initialization";
            }
        })
        .catch(error => {
            loader.classList.add('hidden');
            statusText.innerText = "Connection error occurred.";
            statusText.classList.add('text-rose-400');
            btn.disabled = false;
        });
    }
</script>
@endpush
@endsection
