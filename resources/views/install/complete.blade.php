@extends('layouts.installer')

@section('content')
<div class="text-center">
    <div class="w-24 h-24 bg-emerald-500/20 rounded-full flex items-center justify-center mx-auto mb-8 relative">
        <svg class="w-12 h-12 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
        <div class="absolute inset-0 bg-emerald-500/20 rounded-full animate-ping opacity-20"></div>
    </div>

    <h2 class="text-3xl font-bold mb-4 text-white">Installation Successful!</h2>
    <p class="text-slate-400 mb-10 leading-relaxed max-w-sm mx-auto">
        Your application is now fully configured and ready for production use. The installer has been securely locked.
    </p>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-10">
        <div class="p-4 bg-slate-800/40 rounded-2xl border border-slate-700/50">
            <div class="text-emerald-400 font-bold text-lg mb-1">.env</div>
            <div class="text-[10px] text-slate-500 uppercase tracking-tighter">Generated</div>
        </div>
        <div class="p-4 bg-slate-800/40 rounded-2xl border border-slate-700/50">
            <div class="text-blue-400 font-bold text-lg mb-1">DB</div>
            <div class="text-[10px] text-slate-500 uppercase tracking-tighter">Migrated</div>
        </div>
        <div class="p-4 bg-slate-800/40 rounded-2xl border border-slate-700/50">
            <div class="text-indigo-400 font-bold text-lg mb-1">Lock</div>
            <div class="text-[10px] text-slate-500 uppercase tracking-tighter">Secured</div>
        </div>
    </div>

    <div class="p-6 bg-slate-900/50 rounded-3xl border border-slate-700/50 mb-10 text-left">
        <h4 class="text-xs font-bold text-white uppercase tracking-widest mb-4 flex items-center">
            <span class="w-1.5 h-1.5 bg-amber-500 rounded-full mr-2"></span>
            Post-Installation Security
        </h4>
        <ul class="space-y-3">
            <li class="flex items-start text-xs text-slate-400">
                <svg class="w-4 h-4 mr-3 text-emerald-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                The <code>storage/installed</code> file has been created.
            </li>
            <li class="flex items-start text-xs text-slate-400">
                <svg class="w-4 h-4 mr-3 text-emerald-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Installer routes are now inaccessible.
            </li>
            <li class="flex items-start text-xs text-slate-400">
                <svg class="w-4 h-4 mr-3 text-amber-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                It is recommended to set <code>APP_DEBUG=false</code> in your <code>.env</code> file for production.
            </li>
        </ul>
    </div>

    <a href="{{ url('/') }}" 
       class="inline-flex items-center justify-center px-12 py-4 btn-premium rounded-2xl text-white font-semibold transition-all shadow-xl shadow-blue-500/20 active:scale-95 leading-none">
        Go to Dashboard
        <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
        </svg>
    </a>
</div>
@endsection
