@extends('layouts.installer')

@section('content')
<div class="text-center">
    <div class="mb-8">
        <h2 class="text-2xl font-semibold mb-3 text-white">Welcome</h2>
        <p class="text-slate-400 leading-relaxed">
            Before we begin, please make sure you have:
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-10 text-left">
        <div class="p-4 bg-slate-800/40 rounded-2xl border border-slate-700/50">
            <h3 class="font-semibold text-blue-400 mb-1">Database Credentials</h3>
            <p class="text-xs text-slate-500">Host, name, username, and password.</p>
        </div>
        <div class="p-4 bg-slate-800/40 rounded-2xl border border-slate-700/50">
            <h3 class="font-semibold text-indigo-400 mb-1">PHP Configuration</h3>
            <p class="text-xs text-slate-500">Required extensions and version.</p>
        </div>
        <div class="p-4 bg-slate-800/40 rounded-2xl border border-slate-700/50 text-left">
            <h3 class="font-semibold text-emerald-400 mb-1">Write Permissions</h3>
            <p class="text-xs text-slate-500">Storage and cache folder access.</p>
        </div>
        <div class="p-4 bg-slate-800/40 rounded-2xl border border-slate-700/50 text-left">
            <h3 class="font-semibold text-amber-400 mb-1">Environment File</h3>
            <p class="text-xs text-slate-500">Dynamic .env generation.</p>
        </div>
    </div>

    <a href="{{ route('install.requirements') }}" 
       class="inline-flex items-center justify-center px-10 py-4 btn-premium rounded-2xl text-white font-semibold transition-all shadow-xl shadow-blue-500/20 active:scale-95">
        Let's Get Started
        <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
        </svg>
    </a>
</div>
@endsection
