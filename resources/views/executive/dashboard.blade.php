@extends('layouts.sidebar')

@section('title', 'ProContact CRM | Executive Dashboard')

@section('content')
<div class="pt-24 pb-12 px-8 max-w-7xl mx-auto">
<!-- Header & Greeting -->
<div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
<div>
<h1 class="text-4xl font-extrabold tracking-tighter text-on-surface mb-2">Executive Summary</h1>
<p class="text-stone-500 font-medium">Welcome back. Here is your portfolio performance for <span class="text-primary font-bold">{{ now()->format('F Y') }}</span>.</p>
</div>
<div class="flex gap-3">
<button class="px-5 py-2.5 bg-surface-container-lowest border border-outline-variant/20 rounded-lg text-sm font-semibold hover:bg-surface-container transition-colors shadow-sm">
    Export PDF
</button>
<button class="px-5 py-2.5 bg-primary text-on-primary rounded-lg text-sm font-semibold hover:opacity-95 transition-all shadow-md">
    Generate Insight
</button>
</div>
</div>
<!-- Stats Bento Grid -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
<!-- Total Contacts Card -->
<div class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant/10 shadow-sm relative overflow-hidden group">
<div class="flex justify-between items-start mb-4">
<div class="p-2 bg-primary/5 rounded-lg">
<span class="material-symbols-outlined text-primary">person</span>
</div>
<span class="text-[10px] font-bold uppercase tracking-widest text-emerald-600 bg-emerald-50 px-2 py-1 rounded">+12% vs LY</span>
</div>
<h3 class="text-stone-500 text-xs font-bold uppercase tracking-widest mb-1">Total Contacts</h3>
<p class="text-3xl font-extrabold tracking-tighter">1,284</p>
<div class="mt-4 h-1 bg-surface-container rounded-full overflow-hidden">
<div class="h-full bg-primary w-[75%] rounded-full"></div>
</div>
</div>
<!-- Activities Card -->
<div class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant/10 shadow-sm relative overflow-hidden">
<div class="flex justify-between items-start mb-4">
<div class="p-2 bg-tertiary/5 rounded-lg">
<span class="material-symbols-outlined text-tertiary">bolt</span>
</div>
<span class="text-[10px] font-bold uppercase tracking-widest text-amber-600 bg-amber-50 px-2 py-1 rounded">Target 85%</span>
</div>
<h3 class="text-stone-500 text-xs font-bold uppercase tracking-widest mb-1">Weekly Activities</h3>
<p class="text-3xl font-extrabold tracking-tighter">492</p>
<div class="mt-4 flex gap-1">
<div class="h-1 flex-1 bg-tertiary rounded-full"></div>
<div class="h-1 flex-1 bg-tertiary rounded-full"></div>
<div class="h-1 flex-1 bg-tertiary rounded-full"></div>
<div class="h-1 flex-1 bg-stone-200 rounded-full"></div>
</div>
</div>
<!-- Appointments Card -->
<div class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant/10 shadow-sm relative overflow-hidden">
<div class="flex justify-between items-start mb-4">
<div class="p-2 bg-[#A24E3D]/10 rounded-lg">
<span class="material-symbols-outlined text-[#A24E3D]">calendar_today</span>
</div>
<span class="text-[10px] font-bold uppercase tracking-widest text-blue-600 bg-blue-50 px-2 py-1 rounded">12 Pending</span>
</div>
<h3 class="text-stone-500 text-xs font-bold uppercase tracking-widest mb-1">Appointments</h3>
<p class="text-3xl font-extrabold tracking-tighter">86</p>
<p class="mt-4 text-[11px] text-stone-400 font-medium italic">Highest activity in Q4 to date</p>
</div>
</div>
<!-- Main Grid Layout (Trend + Reminders) -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
<!-- Trend Analysis Chart Area -->
<div class="lg:col-span-2 space-y-8">
<div class="bg-surface-container-low rounded-xl p-8 border border-outline-variant/5">
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
<div>
<h2 class="text-xl font-bold tracking-tight">12-Month Performance Trend</h2>
<p class="text-xs text-stone-500 uppercase tracking-widest font-semibold mt-1">Growth Index Analysis</p>
</div>
<div class="flex items-center gap-4 bg-white/50 p-1 rounded-lg">
<button class="px-3 py-1 text-xs font-bold text-primary bg-white shadow-sm rounded">Contacts</button>
<button class="px-3 py-1 text-xs font-bold text-stone-400 hover:text-stone-600">Appointments</button>
</div>
</div>
<div class="h-64 flex items-end justify-between gap-2">
<div class="w-full flex items-end justify-between px-2 gap-3 h-full">
@php $months = ['JAN','FEB','MAR','APR','MAY','JUN','JUL','AUG','SEP','OCT','NOV','DEC']; $heights = [30,45,40,60,55,75,65,80,70,85,95,20]; @endphp
@foreach($months as $i => $month)
<div class="group relative flex-1 flex flex-col items-center justify-end h-full">
<div class="w-full rounded-t-sm h-[{{ $heights[$i] }}%] transition-all {{ $month === 'NOV' ? 'bg-primary shadow-lg shadow-primary/20' : ($month === 'AUG' ? 'bg-primary/20 group-hover:bg-primary' : ($month === 'DEC' ? 'bg-stone-100 group-hover:bg-primary/40' : 'bg-stone-200 group-hover:bg-primary/40')) }}"></div>
<span class="text-[9px] font-bold mt-2 {{ $month === 'NOV' ? 'text-primary' : 'text-stone-400' }}">{{ $month }}</span>
</div>
@endforeach
</div>
</div>
</div>
<!-- Recent Leads / Accounts Asymmetric Card -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
<div class="bg-surface-container-highest/40 p-6 rounded-xl border border-white">
<h3 class="text-sm font-bold mb-4 flex items-center gap-2">
<span class="material-symbols-outlined text-[18px] text-stone-400">new_releases</span>
    New High-Value Leads
</h3>
<div class="space-y-4">
<div class="flex items-center justify-between p-3 bg-white rounded-lg shadow-sm border border-stone-100">
<div class="flex items-center gap-3">
<div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold text-xs">AS</div>
<div>
<p class="text-xs font-bold">Arthur Sterling</p>
<p class="text-[10px] text-stone-500">Wealth Management</p>
</div>
</div>
<span class="material-symbols-outlined text-[16px] text-tertiary" style="font-variation-settings: 'FILL' 1;">star</span>
</div>
<div class="flex items-center justify-between p-3 bg-white rounded-lg shadow-sm border border-stone-100">
<div class="flex items-center gap-3">
<div class="w-8 h-8 rounded-full bg-stone-100 flex items-center justify-center text-stone-400 font-bold text-xs">MK</div>
<div>
<p class="text-xs font-bold">Mila Kovic</p>
<p class="text-[10px] text-stone-500">Estate Planning</p>
</div>
</div>
</div>
</div>
</div>
<div class="bg-surface-container-low p-6 rounded-xl relative overflow-hidden">
<div class="absolute top-0 right-0 w-32 h-32 bg-primary/5 rounded-full -translate-y-1/2 translate-x-1/2"></div>
<h3 class="text-sm font-bold mb-1">CRM Optimization</h3>
<p class="text-xs text-stone-500 mb-4 italic">3 recommendations available</p>
<button class="text-primary text-[10px] font-bold uppercase tracking-widest flex items-center gap-1 hover:gap-2 transition-all">
    Analyze Data <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
</button>
</div>
</div>
</div>
<!-- Reminders Widget -->
<div class="lg:col-span-1">
<div class="bg-surface-container-lowest rounded-xl shadow-sm border border-outline-variant/15 flex flex-col h-full overflow-hidden">
<div class="p-6 bg-surface-container-high border-b border-outline-variant/5">
<h2 class="text-lg font-bold tracking-tight">Upcoming Reminders</h2>
<div class="flex gap-2 mt-4">
<span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-error-container text-on-error-container">Urgente</span>
<span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-primary-container/20 text-on-primary-fixed-variant">Haute</span>
<span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-stone-100 text-stone-500">Normale</span>
</div>
</div>
<div class="p-6 space-y-6 flex-1 overflow-auto">
<!-- Reminder Item: Urgente -->
<div class="relative pl-6 border-l-2 border-error">
<div class="flex justify-between items-start mb-1">
<h4 class="text-xs font-bold text-on-surface">Contract Renewal - H. Miller</h4>
<span class="text-[10px] font-medium text-error">In 2 hours</span>
</div>
<p class="text-xs text-stone-500 line-clamp-2">Final proposal review required before executive meeting at 4PM.</p>
<div class="flex items-center gap-2 mt-2">
<span class="material-symbols-outlined text-[14px] text-stone-400">schedule</span>
<span class="text-[10px] text-stone-400">16:00 today</span>
</div>
</div>
<!-- Reminder Item: Haute -->
<div class="relative pl-6 border-l-2 border-primary">
<div class="flex justify-between items-start mb-1">
<h4 class="text-xs font-bold text-on-surface">Client Onboarding Call</h4>
<span class="text-[10px] font-medium text-stone-500">Tomorrow</span>
</div>
<p class="text-xs text-stone-500 line-clamp-2">Welcome call with the new development team from Paris.</p>
<div class="flex items-center gap-2 mt-2">
<span class="material-symbols-outlined text-[14px] text-stone-400">person</span>
<span class="text-[10px] text-stone-400">Sarah Jenkins</span>
</div>
</div>
<!-- Reminder Item: Normale -->
<div class="relative pl-6 border-l-2 border-stone-200">
<div class="flex justify-between items-start mb-1">
<h4 class="text-xs font-bold text-on-surface text-stone-400 line-through">Draft Quarterly Report</h4>
<span class="material-symbols-outlined text-[16px] text-emerald-500">check_circle</span>
</div>
<p class="text-xs text-stone-400">Completed 3 hours ago</p>
</div>
<!-- Reminder Item: Basse -->
<div class="relative pl-6 border-l-2 border-stone-100">
<div class="flex justify-between items-start mb-1">
<h4 class="text-xs font-bold text-on-surface">Archive Old Leads</h4>
<span class="text-[10px] font-medium text-stone-500">Nov 28</span>
</div>
<p class="text-xs text-stone-500 line-clamp-2">Routine maintenance of the CRM database to improve performance.</p>
</div>
</div>
<button class="m-6 p-3 rounded-lg border border-dashed border-stone-300 text-stone-400 text-xs font-bold hover:border-primary hover:text-primary transition-all flex items-center justify-center gap-2">
<span class="material-symbols-outlined text-[16px]">add</span>
    Add New Reminder
</button>
</div>
</div>
</div>
</div>
<!-- Background Accents -->
<div class="fixed bottom-0 right-0 w-1/4 h-1/4 bg-primary/5 -z-10 blur-[120px] rounded-full pointer-events-none"></div>
<div class="fixed top-20 left-64 w-40 h-40 border-l border-t border-primary/5 -z-10 pointer-events-none"></div>
@endsection
