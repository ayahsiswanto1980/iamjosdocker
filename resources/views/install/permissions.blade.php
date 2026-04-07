@extends('layouts.installer')

@section('content')
<div class="text-center">
    <h2 class="text-xl font-semibold mb-6 text-white">Filesystem Permissions</h2>

    <div class="space-y-3 mb-10 w-full text-left">
        @foreach($permissions as $key => $permission)
            <div class="flex items-center justify-between p-4 bg-slate-800/40 rounded-2xl border {{ $permission['status'] ? 'border-emerald-500/20' : 'border-rose-500/30' }}">
                <div class="flex items-center">
                    <div class="w-2 h-2 rounded-full mr-3 {{ $permission['status'] ? 'bg-emerald-400' : 'bg-rose-400' }}"></div>
                    <span class="font-medium text-slate-300">{{ $permission['name'] }}</span>
                </div>
                <div class="flex items-center">
                    @if($permission['status'])
                        <span class="text-xs text-emerald-400/70 mr-3 px-2 py-1 bg-emerald-500/10 rounded-lg">Writable</span>
                        <svg class="w-5 h-5 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    @else
                        <span class="text-xs text-rose-400/70 mr-3 px-2 py-1 bg-rose-500/10 rounded-lg">Read-only</span>
                        <svg class="w-5 h-5 text-rose-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    @if(!$satisfied)
        <div class="p-4 bg-rose-500/10 border border-rose-500/20 rounded-2xl mb-8 text-left">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-rose-400 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <div class="text-xs text-slate-400 leading-relaxed">
                    <strong class="text-rose-400 block mb-1 uppercase font-bold tracking-wider">Wait!</strong>
                    The directories above must be writable by the web server (e.g., www-data). Please run:
                    <div class="mt-2 bg-slate-900 p-2 rounded-lg font-mono text-[10px] text-emerald-400 overflow-x-auto">
                        sudo chown -R www-data:www-data storage bootstrap/cache .env
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="flex justify-between gap-4">
        <a href="{{ route('install.requirements') }}" class="px-8 py-3 rounded-2xl border border-slate-700 font-medium text-slate-400 hover:bg-slate-800 transition-all text-center">
            Back
        </a>
        
        @if($satisfied)
            <a href="{{ route('install.environment') }}" 
               class="flex-1 px-8 py-3 btn-premium rounded-2xl text-white font-semibold transition-all shadow-xl shadow-blue-500/20 active:scale-95 text-center">
                Next Step
            </a>
        @else
            <button disabled 
                    class="flex-1 px-8 py-3 bg-slate-700 rounded-2xl text-slate-500 font-semibold cursor-not-allowed">
                Permissions Not Met
            </button>
        @endif
    </div>
</div>
@endsection
