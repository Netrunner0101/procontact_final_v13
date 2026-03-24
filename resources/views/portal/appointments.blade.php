@extends('layouts.client-sidebar')

@section('title', 'My Appointments | ProContact')

@section('content')
<!-- Header -->
<div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10">
<div>
<div class="flex items-center gap-2 mb-2">
<span class="h-px w-8 bg-primary"></span>
<span class="text-[10px] font-bold uppercase tracking-[0.3em] text-primary">Client Portal</span>
</div>
<h2 class="font-headline text-4xl font-extrabold tracking-tight text-on-surface">My Appointments</h2>
<p class="text-secondary font-medium mt-2">View and manage your scheduled consultations.</p>
</div>
<div class="flex items-center bg-surface-container-low p-1.5 rounded-full shadow-inner border border-stone-200/30">
<a href="{{ route('client.portal.appointments') }}" class="px-6 py-2 rounded-full text-sm font-bold {{ !request('status') ? 'bg-white text-on-surface shadow-sm' : 'text-stone-500 hover:text-on-surface' }} transition-all">All</a>
<a href="{{ route('client.portal.appointments', ['status' => 'upcoming']) }}" class="px-6 py-2 rounded-full text-sm font-bold {{ request('status') === 'upcoming' ? 'bg-white text-on-surface shadow-sm' : 'text-stone-500 hover:text-on-surface' }} transition-all">Upcoming</a>
<a href="{{ route('client.portal.appointments', ['status' => 'past']) }}" class="px-6 py-2 rounded-full text-sm font-bold {{ request('status') === 'past' ? 'bg-white text-on-surface shadow-sm' : 'text-stone-500 hover:text-on-surface' }} transition-all">Past</a>
</div>
</div>

<!-- Appointments List -->
<div class="bg-white rounded-md border border-stone-200/60 shadow-xl shadow-stone-200/30 overflow-hidden">
<div class="divide-y divide-stone-100">
@forelse($appointments as $rdv)
<div class="p-8 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6 hover:bg-stone-50/80 transition-colors group">
<div class="flex gap-6">
<div class="flex flex-col items-center justify-center w-14 h-14 rounded shadow-md
    @if($rdv->date_debut->isPast()) bg-stone-100 text-stone-400
    @elseif($rdv->date_debut->isToday()) bg-primary text-white
    @else bg-stone-900 text-white @endif">
<span class="text-[9px] font-bold uppercase tracking-widest">{{ $rdv->date_debut->format('M') }}</span>
<span class="text-xl font-black">{{ $rdv->date_debut->format('d') }}</span>
</div>
<div>
<h4 class="text-lg font-bold text-on-surface group-hover:text-primary transition-colors font-manrope">{{ $rdv->titre }}</h4>
<div class="flex flex-wrap items-center gap-4 mt-2 text-sm text-secondary">
<span class="flex items-center gap-1.5"><span class="material-symbols-outlined text-xs">schedule</span> {{ $rdv->heure_debut->format('H:i') }} - {{ $rdv->heure_fin->format('H:i') }}</span>
@if($rdv->lieu)
<span class="flex items-center gap-1.5"><span class="material-symbols-outlined text-xs">location_on</span> {{ $rdv->lieu }}</span>
@endif
@if($rdv->activite)
<span class="flex items-center gap-1.5"><span class="material-symbols-outlined text-xs">work</span> {{ $rdv->activite->nom }}</span>
@endif
</div>
<div class="flex gap-2 mt-4">
@if($rdv->date_debut->isPast())
<span class="px-2.5 py-0.5 bg-stone-100 text-stone-500 text-[9px] font-bold rounded uppercase">Completed</span>
@elseif($rdv->date_debut->isToday())
<span class="px-2.5 py-0.5 bg-[#A24E3D]/10 text-primary text-[9px] font-bold rounded uppercase border border-primary/20">Today</span>
@else
<span class="px-2.5 py-0.5 bg-green-50 text-green-700 text-[9px] font-bold rounded uppercase border border-green-200">Upcoming</span>
@endif
@php $noteCount = $rdv->notes->where('is_shared_with_client', true)->count(); @endphp
@if($noteCount > 0)
<span class="px-2.5 py-0.5 bg-blue-50 text-blue-600 text-[9px] font-bold rounded uppercase">{{ $noteCount }} {{ Str::plural('Note', $noteCount) }}</span>
@endif
</div>
</div>
</div>
<div class="flex items-center gap-3 w-full sm:w-auto">
<a href="{{ route('client.portal.appointment', $rdv) }}" class="flex-1 sm:flex-none px-6 py-2 bg-white text-stone-900 text-xs font-bold rounded border border-stone-300 hover:bg-stone-50 transition-all uppercase tracking-widest text-center">View Details</a>
</div>
</div>
@empty
<div class="p-16 text-center">
<span class="material-symbols-outlined text-5xl text-stone-300 mb-4">event_busy</span>
<h3 class="text-lg font-bold text-stone-500 mb-2">No appointments found</h3>
<p class="text-sm text-stone-400">There are no appointments matching your criteria.</p>
</div>
@endforelse
</div>

@if($appointments->hasPages())
<div class="px-8 py-4 border-t border-stone-100 bg-stone-50/30 flex justify-between items-center">
<p class="text-xs text-stone-500">Showing {{ $appointments->firstItem() }}-{{ $appointments->lastItem() }} of {{ $appointments->total() }}</p>
<div class="flex gap-2">
@if($appointments->onFirstPage())
<span class="px-3 py-1 text-xs font-bold text-stone-400 cursor-not-allowed">Previous</span>
@else
<a href="{{ $appointments->previousPageUrl() }}" class="px-3 py-1 text-xs font-bold text-primary hover:bg-primary/5 rounded transition-all">Previous</a>
@endif
@if($appointments->hasMorePages())
<a href="{{ $appointments->nextPageUrl() }}" class="px-3 py-1 text-xs font-bold text-primary hover:bg-primary/5 rounded transition-all">Next</a>
@else
<span class="px-3 py-1 text-xs font-bold text-stone-400 cursor-not-allowed">Next</span>
@endif
</div>
</div>
@endif
</div>
@endsection
