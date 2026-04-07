@extends('layouts.installer')

@section('content')
<div>
    <h2 class="text-xl font-semibold mb-6 text-white text-center">Environment Configuration</h2>

    <form action="{{ route('install.environment.save') }}" method="POST" class="space-y-4">
        @csrf
        
        @if($errors->any())
            <div class="p-4 bg-rose-500/10 border border-rose-500/20 rounded-2xl mb-6">
                <ul class="text-xs text-rose-400 list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="space-y-2">
                <label class="text-xs font-semibold text-slate-400 uppercase tracking-wider">App Name</label>
                <input type="text" name="app_name" value="{{ old('app_name', config('app.name')) }}" 
                       class="w-full bg-slate-800/50 border border-slate-700 rounded-xl px-4 py-3 text-slate-200 focus:outline-none focus:border-blue-500 transition-all"
                       placeholder="My Awesome App">
            </div>
            <div class="space-y-2">
                <label class="text-xs font-semibold text-slate-400 uppercase tracking-wider">App URL</label>
                <input type="text" name="app_url" value="{{ old('app_url', url('/')) }}" 
                       class="w-full bg-slate-800/50 border border-slate-700 rounded-xl px-4 py-3 text-slate-200 focus:outline-none focus:border-blue-500 transition-all"
                       placeholder="https://example.com">
            </div>
        </div>

        <div class="pt-4 mt-4 border-t border-slate-700/50">
            <h3 class="text-sm font-semibold text-slate-400 mb-4 flex items-center">
                <svg class="w-4 h-4 mr-2 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path>
                </svg>
                Database Connection
            </h3>
            
            <div class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-2 space-y-2">
                        <label class="text-[10px] font-semibold text-slate-500 uppercase">DB Host</label>
                        <input type="text" name="db_host" value="{{ old('db_host', '127.0.0.1') }}" 
                               class="w-full bg-slate-800/50 border border-slate-700 rounded-xl px-4 py-3 text-slate-200 focus:outline-none focus:border-blue-500 transition-all">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-semibold text-slate-500 uppercase">Port</label>
                        <input type="text" name="db_port" value="{{ old('db_port', '3306') }}" 
                               class="w-full bg-slate-800/50 border border-slate-700 rounded-xl px-4 py-3 text-slate-200 focus:outline-none focus:border-blue-500 transition-all">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-semibold text-slate-500 uppercase">Database Name</label>
                    <input type="text" name="db_name" value="{{ old('db_name') }}" 
                           class="w-full bg-slate-800/50 border border-slate-700 rounded-xl px-4 py-3 text-slate-200 focus:outline-none focus:border-blue-500 transition-all"
                           placeholder="e.g. database_prod">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-[10px] font-semibold text-slate-500 uppercase">Username</label>
                        <input type="text" name="db_user" value="{{ old('db_user', 'root') }}" 
                               class="w-full bg-slate-800/50 border border-slate-700 rounded-xl px-4 py-3 text-slate-200 focus:outline-none focus:border-blue-500 transition-all">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-semibold text-slate-500 uppercase">Password</label>
                        <input type="password" name="db_pass" value="{{ old('db_pass') }}" 
                               class="w-full bg-slate-800/50 border border-slate-700 rounded-xl px-4 py-3 text-slate-200 focus:outline-none focus:border-blue-500 transition-all"
                               placeholder="********">
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-between gap-4 pt-6">
            <a href="{{ route('install.permissions') }}" class="px-8 py-3 rounded-2xl border border-slate-700 font-medium text-slate-400 hover:bg-slate-800 transition-all text-center">
                Back
            </a>
            <button type="submit" 
                    class="flex-1 px-8 py-3 btn-premium rounded-2xl text-white font-semibold transition-all shadow-xl shadow-blue-500/20 active:scale-95 text-center">
                Test & Save
            </button>
        </div>
    </form>
</div>
@endsection
