@extends('layouts.client-sidebar')

@section('title', 'ProContact | Client Portal')

@section('content')
<!-- Welcome Section -->
<section class="flex flex-col lg:flex-row lg:items-center justify-between gap-10 mb-16">
<div class="max-w-3xl">
<div class="flex items-center gap-2 mb-4">
<span class="h-px w-8 bg-primary"></span>
<span class="text-[10px] font-bold uppercase tracking-[0.3em] text-primary">Secure Dashboard</span>
</div>
<h2 class="font-headline text-4xl sm:text-5xl font-extrabold tracking-tight text-on-surface mb-6 leading-[1.1]">
    Welcome back, <br/><span class="text-primary italic">{{ Auth::user()->prenom }} {{ Auth::user()->nom }}.</span>
</h2>
<p class="font-body text-lg text-secondary leading-relaxed max-w-xl">
    Your appointments are managed through our secure portal. Review your upcoming consultations and manage notes below.
</p>
</div>
<div class="flex-shrink-0">
<div class="bg-white p-6 rounded-md shadow-lg shadow-stone-200/50 border border-stone-100 flex flex-col items-center text-center w-64">
<p class="text-[10px] font-bold uppercase tracking-[0.2em] text-stone-400 mb-4">Your Account</p>
<div class="h-20 w-20 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold text-2xl mb-4 ring-4 ring-stone-50">
    {{ strtoupper(substr(Auth::user()->prenom, 0, 1)) }}{{ strtoupper(substr(Auth::user()->nom, 0, 1)) }}
</div>
<h4 class="font-bold text-on-surface text-base">{{ Auth::user()->prenom }} {{ Auth::user()->nom }}</h4>
<p class="text-xs text-secondary mb-6 italic">Client Portal Access</p>
<a href="{{ route('client.portal.appointments') }}" class="w-full py-2.5 bg-stone-900 text-white text-xs font-bold rounded hover:bg-stone-800 transition-all uppercase tracking-widest flex items-center justify-center gap-2">
<span class="material-symbols-outlined text-sm">event</span>
    View Appointments
</a>
</div>
</div>
</section>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
<!-- Main Column -->
<div class="lg:col-span-8 space-y-10">
<!-- Upcoming Appointments Card -->
<div class="bg-white rounded-md border border-stone-200/60 shadow-xl shadow-stone-200/30 overflow-hidden">
<div class="px-8 py-6 border-b border-stone-100 flex justify-between items-center bg-stone-50/50">
<div>
<h3 class="font-manrope text-xl font-bold tracking-tight">Active Appointments</h3>
<p class="text-xs text-secondary mt-0.5">Your upcoming professional consultations</p>
</div>
<div class="flex p-1 bg-stone-100 rounded-md">
<a href="{{ route('client.portal.appointments', ['status' => 'upcoming']) }}" class="px-4 py-1.5 bg-white text-[10px] font-bold rounded shadow-sm uppercase tracking-tighter">Upcoming</a>
<a href="{{ route('client.portal.appointments', ['status' => 'past']) }}" class="px-4 py-1.5 bg-transparent text-[10px] font-bold rounded uppercase tracking-tighter text-stone-400">Past</a>
</div>
</div>
<div class="divide-y divide-stone-100">
@forelse($upcomingAppointments as $rdv)
<div class="p-8 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6 hover:bg-stone-50/80 transition-colors group">
<div class="flex gap-6">
<div class="flex flex-col items-center justify-center w-14 h-14 {{ $loop->first ? 'bg-primary text-white' : 'bg-stone-100 text-stone-400' }} rounded shadow-md">
<span class="text-[9px] font-bold uppercase tracking-widest {{ $loop->first ? 'opacity-80' : '' }}">{{ $rdv->date_debut->format('M') }}</span>
<span class="text-xl font-black">{{ $rdv->date_debut->format('d') }}</span>
</div>
<div>
<h4 class="text-lg font-bold text-on-surface group-hover:text-primary transition-colors font-manrope">{{ $rdv->titre }}</h4>
<div class="flex flex-wrap items-center gap-4 mt-2 text-sm text-secondary">
<span class="flex items-center gap-1.5"><span class="material-symbols-outlined text-xs">schedule</span> {{ $rdv->heure_debut->format('H:i') }} - {{ $rdv->heure_fin->format('H:i') }}</span>
@if($rdv->lieu)
<span class="flex items-center gap-1.5"><span class="material-symbols-outlined text-xs">location_on</span> {{ $rdv->lieu }}</span>
@endif
</div>
<div class="flex gap-2 mt-4">
@if($rdv->activite)
<span class="px-2.5 py-0.5 bg-stone-100 text-stone-500 text-[9px] font-bold rounded uppercase border border-stone-200">{{ $rdv->activite->nom }}</span>
@endif
@if($rdv->date_debut->isToday())
<span class="px-2.5 py-0.5 bg-[#A24E3D]/10 text-primary text-[9px] font-bold rounded uppercase border border-primary/20">Today</span>
@endif
</div>
</div>
</div>
<div class="flex items-center gap-3 w-full sm:w-auto">
<a href="{{ route('client.portal.appointment', $rdv) }}" class="flex-1 sm:flex-none px-6 py-2 bg-stone-900 text-white text-xs font-bold rounded shadow-md hover:bg-stone-800 transition-all uppercase tracking-widest text-center">View Details</a>
</div>
</div>
@empty
<div class="p-12 text-center">
<span class="material-symbols-outlined text-4xl text-stone-300 mb-4">event_busy</span>
<p class="text-stone-500 font-medium">No upcoming appointments</p>
<p class="text-xs text-stone-400 mt-1">Your upcoming consultations will appear here.</p>
</div>
@endforelse
</div>
</div>

<!-- Privacy Section -->
<div class="p-8 rounded-md bg-stone-900 text-white relative overflow-hidden shadow-2xl">
<div class="absolute top-0 right-0 w-64 h-64 bg-primary/20 rounded-full blur-3xl -mr-32 -mt-32"></div>
<div class="relative z-10 flex flex-col md:flex-row gap-8 items-start">
<div class="p-3 bg-primary/20 rounded border border-primary/30 text-primary">
<span class="material-symbols-outlined text-3xl" style="font-variation-settings: 'FILL' 1;">encrypted</span>
</div>
<div>
<h4 class="font-manrope text-xl font-bold mb-3">Enterprise-Grade Data Isolation</h4>
<p class="text-stone-400 text-sm leading-relaxed mb-6 max-w-xl">
    ProContact utilizes hardware-level isolation to ensure your project intelligence remains strictly confidential. Every document, schedule, and transcript is encrypted with AES-256 standards.
</p>
<div class="flex flex-wrap gap-6 items-center">
<a class="text-[10px] font-bold text-primary uppercase tracking-[0.2em] flex items-center gap-2 hover:underline" href="#">
<span class="material-symbols-outlined text-sm">security</span>
    Protocol Details
</a>
<span class="h-1 w-1 bg-stone-700 rounded-full"></span>
<span class="text-[10px] uppercase font-bold text-stone-500 tracking-widest">ISO 27001 Certified</span>
</div>
</div>
</div>
</div>
</div>

<!-- Sidebar Column -->
<div class="lg:col-span-4 space-y-10">
<!-- Stats Summary -->
<div class="bg-white rounded-md p-8 border border-stone-200/60 shadow-lg shadow-stone-200/30">
<h3 class="font-manrope text-lg font-bold mb-8 flex items-center gap-2">
<span class="h-4 w-1 bg-primary rounded-full"></span>
    Overview
</h3>
<div class="space-y-6">
<div class="flex items-center justify-between p-4 bg-stone-50 rounded-lg">
<div class="flex items-center gap-3">
<div class="w-10 h-10 bg-primary/10 rounded-lg flex items-center justify-center">
<span class="material-symbols-outlined text-primary">calendar_today</span>
</div>
<div>
<p class="text-xs font-bold text-stone-500 uppercase tracking-widest">Total</p>
<p class="text-xl font-extrabold text-on-surface">{{ $totalAppointmentsCount }}</p>
</div>
</div>
</div>
<div class="flex items-center justify-between p-4 bg-stone-50 rounded-lg">
<div class="flex items-center gap-3">
<div class="w-10 h-10 bg-green-50 rounded-lg flex items-center justify-center">
<span class="material-symbols-outlined text-green-600">upcoming</span>
</div>
<div>
<p class="text-xs font-bold text-stone-500 uppercase tracking-widest">Upcoming</p>
<p class="text-xl font-extrabold text-on-surface">{{ $upcomingAppointments->count() }}</p>
</div>
</div>
</div>
<div class="flex items-center justify-between p-4 bg-stone-50 rounded-lg">
<div class="flex items-center gap-3">
<div class="w-10 h-10 bg-stone-100 rounded-lg flex items-center justify-center">
<span class="material-symbols-outlined text-stone-500">history</span>
</div>
<div>
<p class="text-xs font-bold text-stone-500 uppercase tracking-widest">Completed</p>
<p class="text-xl font-extrabold text-on-surface">{{ $pastAppointmentsCount }}</p>
</div>
</div>
</div>
</div>
</div>

<!-- Support Directory -->
<div class="bg-white rounded-md p-8 border border-stone-200/60 shadow-lg shadow-stone-200/30">
<h3 class="font-manrope text-lg font-bold mb-8 flex items-center gap-2">
<span class="h-4 w-1 bg-primary rounded-full"></span>
    Support Directory
</h3>
<div class="space-y-8">
<div class="flex items-start gap-4">
<div class="h-10 w-10 bg-stone-100 rounded flex items-center justify-center text-primary-container shrink-0">
<span class="material-symbols-outlined">headset_mic</span>
</div>
<div>
<p class="text-[10px] font-bold text-secondary uppercase tracking-widest mb-1">Technical Support</p>
<p class="text-sm font-semibold text-on-surface">+1 (800) PC-SUPPORT</p>
<p class="text-xs text-stone-400 mt-1">Mon-Fri, 9am - 6pm EST</p>
</div>
</div>
<div class="flex items-start gap-4">
<div class="h-10 w-10 bg-stone-100 rounded flex items-center justify-center text-primary-container shrink-0">
<span class="material-symbols-outlined">contact_mail</span>
</div>
<div>
<p class="text-[10px] font-bold text-secondary uppercase tracking-widest mb-1">Account Executive</p>
<p class="text-sm font-semibold text-on-surface">admin@procontact.crm</p>
<p class="text-xs text-stone-400 mt-1">Direct Billing &amp; Scaling</p>
</div>
</div>
</div>
<div class="mt-10 pt-8 border-t border-stone-100">
<button class="w-full py-3.5 bg-stone-100 text-stone-900 text-[10px] font-black rounded uppercase tracking-[0.2em] border border-stone-200 hover:bg-stone-200 transition-all">Submit Support Ticket</button>
</div>
</div>
</div>
</div>
@endsection
