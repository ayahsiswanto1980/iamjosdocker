@extends('layouts.installer')

@section('content')
<div class="text-center">
    <h2 class="text-xl font-semibold mb-6 text-white">Application Requirements</h2>

    <div class="space-y-3 mb-10 w-full text-left">
        @foreach($requirements as $key => $requirement)
            <div class="flex items-center justify-between p-4 bg-slate-800/40 rounded-2xl border {{ $requirement['status'] ? 'border-emerald-500/20' : 'border-rose-500/30' }}">
                <div class="flex items-center">
                    <div class="w-2 h-2 rounded-full mr-3 {{ $requirement['status'] ? 'bg-emerald-400' : 'bg-rose-400' }}"></div>
                    <span class="font-medium text-slate-300">{{ $requirement['name'] }}</span>
                </div>
                <div class="flex items-center">
                    @if(isset($requirement['current']))
                        <span class="text-xs text-slate-500 mr-3">({{ $requirement['current'] }})</span>
                    @endif
                    @if($requirement['status'])
                        <svg class="w-5 h-5 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    @else
                        <svg class="w-5 h-5 text-rose-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <div class="flex justify-between gap-4">
        <a href="{{ route('install.welcome') }}" class="px-8 py-3 rounded-2xl border border-slate-700 font-medium text-slate-400 hover:bg-slate-800 transition-all">
            Back
        </a>
        
        @if($satisfied)
            <a href="{{ route('install.permissions') }}" 
               class="flex-1 px-8 py-3 btn-premium rounded-2xl text-white font-semibold transition-all shadow-xl shadow-blue-500/20 active:scale-95 text-center">
                Next Step
            </a>
        @else
            <button disabled 
                    class="flex-1 px-8 py-3 bg-slate-700 rounded-2xl text-slate-500 font-semibold cursor-not-allowed">
                Requirements Not Met
            </button>
        @endif
    </div>
</div>
@endsection
