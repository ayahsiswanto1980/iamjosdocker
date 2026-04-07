@extends('layouts.installer')

@section('content')
<div>
    <h2 class="text-xl font-semibold mb-6 text-white text-center">Setup Administrator Account</h2>

    <form action="{{ route('install.admin.save') }}" method="POST" class="space-y-4">
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

        <div class="space-y-2">
            <label class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Full Name</label>
            <div class="relative">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </span>
                <input type="text" name="name" value="{{ old('name') }}" 
                       class="w-full bg-slate-800/50 border border-slate-700 rounded-xl pl-12 pr-4 py-3 text-slate-200 focus:outline-none focus:border-blue-500 transition-all font-medium"
                       placeholder="Super Admin">
            </div>
        </div>

        <div class="space-y-2">
            <label class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Email Address</label>
            <div class="relative">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </span>
                <input type="email" name="email" value="{{ old('email') }}" 
                       class="w-full bg-slate-800/50 border border-slate-700 rounded-xl pl-12 pr-4 py-3 text-slate-200 focus:outline-none focus:border-blue-500 transition-all font-medium"
                       placeholder="admin@example.com">
            </div>
            <p class="text-[10px] text-slate-500 mt-1 italic">This email will be used for logins and system notifications.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
            <div class="space-y-2">
                <label class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Password</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zM10 6V4a2 2 0 114 0v2m-6 0h12"></path>
                        </svg>
                    </span>
                    <input type="password" name="password" 
                           class="w-full bg-slate-800/50 border border-slate-700 rounded-xl pl-12 pr-4 py-3 text-slate-200 focus:outline-none focus:border-blue-500 transition-all">
                </div>
            </div>
            <div class="space-y-2">
                <label class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Confirm</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </span>
                    <input type="password" name="password_confirmation" 
                           class="w-full bg-slate-800/50 border border-slate-700 rounded-xl pl-12 pr-4 py-3 text-slate-200 focus:outline-none focus:border-blue-500 transition-all">
                </div>
            </div>
        </div>

        <div class="flex justify-end pt-8">
            <button type="submit" 
                    class="w-full md:w-auto px-12 py-4 btn-premium rounded-2xl text-white font-semibold transition-all shadow-xl shadow-blue-500/20 active:scale-95 text-center">
                Finish Setup
                <svg class="ml-2 w-5 h-5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </button>
        </div>
    </form>
</div>
@endsection
