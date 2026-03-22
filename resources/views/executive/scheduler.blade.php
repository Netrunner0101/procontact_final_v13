@extends('layouts.sidebar')

@section('title', 'Appointment Scheduler | ProContact CRM')

@section('top-nav-links')
<div class="ml-8 flex space-x-6">
<a class="text-sm font-medium text-stone-600 hover:text-primary transition-all" href="{{ route('executive.dashboard') }}">Daily Brief</a>
<a class="text-sm font-medium text-primary font-semibold" href="{{ route('executive.scheduler') }}">Scheduler</a>
</div>
@endsection

@section('content')
<div class="p-12 space-y-8">
<!-- Header Section -->
<div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
<div>
<nav class="flex text-[10px] uppercase tracking-widest text-stone-500 mb-2 font-bold">
<span>CRM</span> <span class="mx-2">/</span> <span class="text-primary">Scheduler</span>
</nav>
<h1 class="text-4xl font-extrabold tracking-tighter text-on-surface">Appointment Scheduler</h1>
<p class="text-stone-500 mt-2 max-w-lg font-medium">Manage client interactions and site visits with precision. All times are synced to GMT+1.</p>
</div>
<div class="flex items-center bg-surface-container-low p-1.5 rounded-full shadow-inner border border-stone-200/30">
<button class="px-6 py-2 rounded-full text-sm font-bold bg-white text-on-surface shadow-sm transition-all">List View</button>
<button class="px-6 py-2 rounded-full text-sm font-medium text-stone-500 hover:text-on-surface transition-all">Calendar View</button>
</div>
</div>
<!-- Bento Filter Bar -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 bg-surface-container p-4 rounded-xl">
<div class="bg-surface-container-lowest p-3 rounded-lg border border-stone-200/20 shadow-sm flex flex-col justify-between">
<span class="text-[10px] uppercase font-bold text-stone-400 tracking-wider">Time Range</span>
<div class="flex items-center justify-between mt-2">
<span class="text-sm font-bold">This Week</span>
<span class="material-symbols-outlined text-stone-400 text-lg">calendar_month</span>
</div>
</div>
<div class="bg-surface-container-lowest p-3 rounded-lg border border-stone-200/20 shadow-sm flex flex-col justify-between">
<span class="text-[10px] uppercase font-bold text-stone-400 tracking-wider">Filter Status</span>
<div class="flex items-center justify-between mt-2">
<span class="text-sm font-bold">All Appointments</span>
<span class="material-symbols-outlined text-stone-400 text-lg">tune</span>
</div>
</div>
<div class="bg-surface-container-lowest p-3 rounded-lg border border-stone-200/20 shadow-sm flex flex-col justify-between">
<span class="text-[10px] uppercase font-bold text-stone-400 tracking-wider">Quick Actions</span>
<div class="flex gap-2 mt-2 overflow-x-auto pb-1">
<span class="px-3 py-1 bg-tertiary-fixed text-on-tertiary-fixed text-[10px] font-bold rounded-full uppercase">Priority</span>
<span class="px-3 py-1 bg-secondary-container text-on-secondary-container text-[10px] font-bold rounded-full uppercase">New Only</span>
</div>
</div>
<div class="flex items-center justify-end">
<button class="h-full px-8 bg-primary text-white rounded-lg font-bold hover:brightness-110 transition-all flex items-center gap-2">
<span class="material-symbols-outlined">add_circle</span>
<span>Schedule Appointment</span>
</button>
</div>
</div>
<!-- List View Table -->
<div class="bg-surface-container-lowest rounded-xl shadow-sm border border-stone-100 overflow-hidden">
<!-- Table Header -->
<div class="grid grid-cols-12 px-6 py-4 bg-surface-container-high border-b border-stone-200/50">
<div class="col-span-3 text-[11px] font-extrabold uppercase tracking-widest text-stone-500">Contact</div>
<div class="col-span-3 text-[11px] font-extrabold uppercase tracking-widest text-stone-500">Activity</div>
<div class="col-span-2 text-[11px] font-extrabold uppercase tracking-widest text-stone-500">Start Date</div>
<div class="col-span-2 text-[11px] font-extrabold uppercase tracking-widest text-stone-500">End Date</div>
<div class="col-span-2 text-right text-[11px] font-extrabold uppercase tracking-widest text-stone-500">Status</div>
</div>
<!-- Table Rows -->
<div class="divide-y divide-stone-100">
<!-- Row 1 -->
<div class="grid grid-cols-12 px-6 py-5 hover:bg-surface-container-low transition-colors items-center group">
<div class="col-span-3 flex items-center space-x-3">
<div class="w-10 h-10 rounded-full bg-primary-fixed flex items-center justify-center text-primary font-bold shadow-sm">MB</div>
<div>
<p class="text-sm font-bold text-on-surface">Marcus Blackwell</p>
<p class="text-xs text-stone-500">Blackwell Architects Co.</p>
</div>
</div>
<div class="col-span-3">
<div class="flex items-center space-x-2">
<span class="material-symbols-outlined text-tertiary text-lg" style="font-variation-settings: 'FILL' 1;">handshake</span>
<span class="text-sm font-semibold">Project Scoping Review</span>
</div>
</div>
<div class="col-span-2">
<p class="text-sm font-medium">Oct 24, 09:00 AM</p>
</div>
<div class="col-span-2">
<p class="text-sm font-medium text-stone-400">Oct 24, 10:30 AM</p>
</div>
<div class="col-span-2 flex flex-col items-end gap-2">
<span class="px-3 py-1 bg-tertiary-fixed text-on-tertiary-fixed text-[10px] font-bold rounded-full uppercase tracking-tighter">Confirm&eacute;</span>
<div class="flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
<button class="p-1 hover:bg-stone-200 rounded text-stone-400 hover:text-primary transition-all" title="Reschedule"><span class="material-symbols-outlined text-sm">schedule_send</span></button>
<button class="p-1 hover:bg-stone-200 rounded text-stone-400 hover:text-error transition-all" title="Cancel"><span class="material-symbols-outlined text-sm">cancel</span></button>
</div>
</div>
</div>
<!-- Row 2 -->
<div class="grid grid-cols-12 px-6 py-5 hover:bg-surface-container-low transition-colors items-center group">
<div class="col-span-3 flex items-center space-x-3">
<div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold shadow-sm">ES</div>
<div>
<p class="text-sm font-bold text-on-surface">Elena Sterling</p>
<p class="text-xs text-stone-500">Private Client</p>
</div>
</div>
<div class="col-span-3">
<div class="flex items-center space-x-2">
<span class="material-symbols-outlined text-primary text-lg" style="font-variation-settings: 'FILL' 1;">location_on</span>
<span class="text-sm font-semibold">Site Inspection: Villa Oria</span>
</div>
</div>
<div class="col-span-2">
<p class="text-sm font-medium">Oct 24, 02:30 PM</p>
</div>
<div class="col-span-2">
<p class="text-sm font-medium text-stone-400">Oct 24, 04:00 PM</p>
</div>
<div class="col-span-2 flex flex-col items-end gap-2">
<span class="px-3 py-1 bg-secondary-container text-on-secondary-container text-[10px] font-bold rounded-full uppercase tracking-tighter">En cours</span>
<div class="flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
<button class="p-1 hover:bg-stone-200 rounded text-stone-400 hover:text-primary transition-all"><span class="material-symbols-outlined text-sm">check_circle</span></button>
</div>
</div>
</div>
<!-- Row 3 -->
<div class="grid grid-cols-12 px-6 py-5 hover:bg-surface-container-low transition-colors items-center group">
<div class="col-span-3 flex items-center space-x-3">
<div class="w-10 h-10 rounded-full bg-stone-200 flex items-center justify-center text-stone-500 font-bold shadow-sm">JD</div>
<div>
<p class="text-sm font-bold text-on-surface">Jonathan DuPont</p>
<p class="text-xs text-stone-500">Luxe Materials Ltd.</p>
</div>
</div>
<div class="col-span-3">
<div class="flex items-center space-x-2">
<span class="material-symbols-outlined text-stone-400 text-lg">call</span>
<span class="text-sm font-semibold">Quarterly Materials Bid</span>
</div>
</div>
<div class="col-span-2">
<p class="text-sm font-medium">Oct 25, 11:15 AM</p>
</div>
<div class="col-span-2">
<p class="text-sm font-medium text-stone-400">Oct 25, 12:00 PM</p>
</div>
<div class="col-span-2 flex flex-col items-end gap-2">
<span class="px-3 py-1 bg-surface-container-high text-stone-500 text-[10px] font-bold rounded-full uppercase tracking-tighter">Programm&eacute;</span>
<div class="flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
<button class="p-1 hover:bg-stone-200 rounded text-stone-400 hover:text-primary transition-all"><span class="material-symbols-outlined text-sm">edit_calendar</span></button>
</div>
</div>
</div>
<!-- Row 4 -->
<div class="grid grid-cols-12 px-6 py-5 hover:bg-surface-container-low transition-colors items-center group">
<div class="col-span-3 flex items-center space-x-3">
<div class="w-10 h-10 rounded-full bg-primary-fixed-dim flex items-center justify-center text-primary font-bold shadow-sm">SP</div>
<div>
<p class="text-sm font-bold text-on-surface">Sarah Pellegrini</p>
<p class="text-xs text-stone-500">Urban Design Collective</p>
</div>
</div>
<div class="col-span-3">
<div class="flex items-center space-x-2">
<span class="material-symbols-outlined text-secondary text-lg" style="font-variation-settings: 'FILL' 1;">check_circle</span>
<span class="text-sm font-semibold">Contract Signing</span>
</div>
</div>
<div class="col-span-2">
<p class="text-sm font-medium">Oct 23, 10:00 AM</p>
</div>
<div class="col-span-2">
<p class="text-sm font-medium text-stone-400">Oct 23, 11:00 AM</p>
</div>
<div class="col-span-2 flex flex-col items-end gap-2">
<span class="px-3 py-1 bg-surface-container-highest text-on-surface-variant text-[10px] font-bold rounded-full uppercase tracking-tighter">Termin&eacute;</span>
</div>
</div>
<!-- Row 5 -->
<div class="grid grid-cols-12 px-6 py-5 hover:bg-surface-container-low transition-colors items-center group">
<div class="col-span-3 flex items-center space-x-3">
<div class="w-10 h-10 rounded-full bg-error-container flex items-center justify-center text-error font-bold shadow-sm">RW</div>
<div>
<p class="text-sm font-bold text-on-surface">Robert Wagner</p>
<p class="text-xs text-stone-500">Wagner &amp; Sons</p>
</div>
</div>
<div class="col-span-3">
<div class="flex items-center space-x-2">
<span class="material-symbols-outlined text-error text-lg" style="font-variation-settings: 'FILL' 1;">priority_high</span>
<span class="text-sm font-semibold">Emergency Structural Audit</span>
</div>
</div>
<div class="col-span-2">
<p class="text-sm font-medium">Oct 23, 01:00 PM</p>
</div>
<div class="col-span-2">
<p class="text-sm font-medium text-stone-400">Oct 23, 03:00 PM</p>
</div>
<div class="col-span-2 flex flex-col items-end gap-2">
<span class="px-3 py-1 bg-error-container text-on-error-container text-[10px] font-bold rounded-full uppercase tracking-tighter">Annul&eacute;</span>
<div class="flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
<button class="p-1 hover:bg-stone-200 rounded text-stone-400 hover:text-primary transition-all"><span class="material-symbols-outlined text-sm">refresh</span></button>
</div>
</div>
</div>
</div>
</div>
<!-- Footer Stats / Insights (Asymmetric Bento) -->
<div class="grid grid-cols-12 gap-6">
<div class="col-span-12 md:col-span-7 bg-surface-container-low rounded-xl p-8 flex justify-between items-center overflow-hidden relative border border-stone-200/40">
<div class="z-10">
<h3 class="text-2xl font-extrabold tracking-tighter mb-1">Weekly Efficiency</h3>
<p class="text-stone-500 text-sm mb-4">You have 12 confirmed site visits this week.</p>
<div class="flex gap-8">
<div>
<p class="text-4xl font-extrabold text-primary">84%</p>
<p class="text-[10px] uppercase font-bold text-stone-400 tracking-wider">Completion Rate</p>
</div>
<div class="border-l border-stone-200 pl-8">
<p class="text-4xl font-extrabold text-on-surface">03</p>
<p class="text-[10px] uppercase font-bold text-stone-400 tracking-wider">Reported Conflicts</p>
</div>
</div>
</div>
<div class="absolute -right-12 -bottom-12 w-64 h-64 bg-primary-fixed/30 rounded-full blur-3xl"></div>
</div>
<div class="col-span-12 md:col-span-5 bg-tertiary-fixed rounded-xl p-8 border border-tertiary/10 flex flex-col justify-between">
<div>
<span class="inline-block px-3 py-1 bg-white text-on-tertiary-fixed text-[10px] font-extrabold rounded-full uppercase tracking-widest mb-4">Golden Lead Alert</span>
<h3 class="text-xl font-bold tracking-tight text-on-tertiary-fixed">Vantage Peak Resorts</h3>
<p class="text-on-tertiary-fixed/80 text-sm mt-1">Requested a follow-up visit for next Tuesday at 10 AM. Recommended: Send Portfolio.</p>
</div>
<button class="w-fit mt-6 flex items-center gap-2 text-sm font-bold border-b-2 border-on-tertiary-fixed pb-0.5 hover:gap-4 transition-all">
<span>Action Request</span>
<span class="material-symbols-outlined text-sm">arrow_forward</span>
</button>
</div>
</div>
</div>

<!-- Floating Action Button -->
<button class="fixed bottom-8 right-8 w-16 h-16 bg-primary text-white rounded-full shadow-2xl flex items-center justify-center hover:scale-105 active:scale-90 transition-all z-50 group">
<span class="material-symbols-outlined text-3xl group-hover:rotate-90 transition-transform duration-300">add</span>
</button>
@endsection
