@extends('layouts.client-sidebar')

@section('title', $rendezVous->titre . ' | ProContact')

@section('content')
<!-- Breadcrumb -->
<div class="flex items-center gap-2 mb-8 text-[10px] uppercase tracking-widest font-bold text-stone-400">
<a href="{{ route('client.portal.dashboard') }}" class="hover:text-primary transition-colors">Dashboard</a>
<span>/</span>
<a href="{{ route('client.portal.appointments') }}" class="hover:text-primary transition-colors">Appointments</a>
<span>/</span>
<span class="text-primary">{{ Str::limit($rendezVous->titre, 30) }}</span>
</div>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
<!-- Main Column -->
<div class="lg:col-span-8 space-y-10">
<!-- Appointment Details Card -->
<div class="bg-white rounded-md border border-stone-200/60 shadow-xl shadow-stone-200/30 overflow-hidden">
<div class="px-8 py-6 border-b border-stone-100 bg-stone-50/50 flex justify-between items-start">
<div>
<h2 class="font-manrope text-2xl font-extrabold tracking-tight text-on-surface">{{ $rendezVous->titre }}</h2>
<div class="flex flex-wrap items-center gap-4 mt-3 text-sm text-secondary">
<span class="flex items-center gap-1.5"><span class="material-symbols-outlined text-sm">calendar_today</span> {{ $rendezVous->date_debut->format('l, F j, Y') }}</span>
<span class="flex items-center gap-1.5"><span class="material-symbols-outlined text-sm">schedule</span> {{ $rendezVous->heure_debut->format('H:i') }} - {{ $rendezVous->heure_fin->format('H:i') }}</span>
</div>
</div>
<div>
@if($rendezVous->date_debut->isPast())
<span class="px-3 py-1.5 bg-stone-100 text-stone-600 text-[10px] font-bold rounded uppercase tracking-wider">Completed</span>
@elseif($rendezVous->date_debut->isToday())
<span class="px-3 py-1.5 bg-primary/10 text-primary text-[10px] font-bold rounded uppercase tracking-wider border border-primary/20">Today</span>
@else
<span class="px-3 py-1.5 bg-green-50 text-green-700 text-[10px] font-bold rounded uppercase tracking-wider border border-green-200">Upcoming</span>
@endif
</div>
</div>
<div class="p-8 space-y-8">
@if($rendezVous->description)
<div>
<h4 class="text-[10px] font-bold uppercase tracking-widest text-stone-400 mb-3">Description</h4>
<p class="text-sm text-secondary leading-relaxed">{{ $rendezVous->description }}</p>
</div>
@endif

<div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
@if($rendezVous->lieu)
<div class="flex items-start gap-3">
<div class="w-10 h-10 bg-stone-100 rounded flex items-center justify-center shrink-0">
<span class="material-symbols-outlined text-stone-500">location_on</span>
</div>
<div>
<p class="text-[10px] font-bold uppercase tracking-widest text-stone-400 mb-1">Location</p>
<p class="text-sm font-semibold text-on-surface">{{ $rendezVous->lieu }}</p>
</div>
</div>
@endif
@if($rendezVous->activite)
<div class="flex items-start gap-3">
<div class="w-10 h-10 bg-stone-100 rounded flex items-center justify-center shrink-0">
<span class="material-symbols-outlined text-stone-500">work</span>
</div>
<div>
<p class="text-[10px] font-bold uppercase tracking-widest text-stone-400 mb-1">Activity</p>
<p class="text-sm font-semibold text-on-surface">{{ $rendezVous->activite->nom }}</p>
</div>
</div>
@endif
</div>
</div>
</div>

<!-- Notes Section -->
<div class="bg-white rounded-md border border-stone-200/60 shadow-xl shadow-stone-200/30 overflow-hidden">
<div class="px-8 py-6 border-b border-stone-100 bg-stone-50/50 flex justify-between items-center">
<div>
<h3 class="font-manrope text-xl font-bold tracking-tight">Notes</h3>
<p class="text-xs text-secondary mt-0.5">View shared notes and add your own</p>
</div>
<button onclick="document.getElementById('new-note-form').classList.toggle('hidden')" class="px-4 py-2 bg-stone-900 text-white text-xs font-bold rounded shadow-md hover:bg-stone-800 transition-all uppercase tracking-widest flex items-center gap-2">
<span class="material-symbols-outlined text-sm">add</span> New Note
</button>
</div>

<!-- New Note Form (hidden by default) -->
<div id="new-note-form" class="hidden border-b border-stone-100 p-8 bg-stone-50/30">
<form action="{{ route('client.portal.notes.store', $rendezVous) }}" method="POST">
@csrf
<div class="space-y-4">
<div>
<label class="text-[10px] font-bold uppercase tracking-widest text-stone-400 mb-2 block">Title</label>
<input type="text" name="titre" required class="w-full bg-white border border-stone-200 rounded-lg px-4 py-3 text-sm focus:ring-1 focus:ring-primary/20 focus:border-primary/30" placeholder="Note title..." value="{{ old('titre') }}"/>
@error('titre') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
</div>
<div>
<label class="text-[10px] font-bold uppercase tracking-widest text-stone-400 mb-2 block">Content</label>
<textarea name="commentaire" required rows="4" class="w-full bg-white border border-stone-200 rounded-lg px-4 py-3 text-sm focus:ring-1 focus:ring-primary/20 focus:border-primary/30" placeholder="Write your note...">{{ old('commentaire') }}</textarea>
@error('commentaire') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
</div>
<div class="flex gap-3 justify-end">
<button type="button" onclick="document.getElementById('new-note-form').classList.add('hidden')" class="px-6 py-2 bg-white text-stone-600 text-xs font-bold rounded border border-stone-300 hover:bg-stone-50 transition-all uppercase tracking-widest">Cancel</button>
<button type="submit" class="px-6 py-2 bg-primary text-white text-xs font-bold rounded shadow-md hover:opacity-90 transition-all uppercase tracking-widest">Save Note</button>
</div>
</div>
</form>
</div>

<!-- Notes List -->
<div class="divide-y divide-stone-100">
@php
$sharedNotes = $rendezVous->notes->where('is_shared_with_client', true);
$clientNotes = $rendezVous->notes->where('user_id', Auth::id());
$allNotes = $sharedNotes->merge($clientNotes)->unique('id')->sortByDesc('created_at');
@endphp

@forelse($allNotes as $note)
<div class="p-8 hover:bg-stone-50/50 transition-colors group">
<div class="flex justify-between items-start mb-3">
<div class="flex items-center gap-3">
<div class="w-8 h-8 rounded-full {{ $note->user_id === Auth::id() ? 'bg-primary/10 text-primary' : 'bg-stone-100 text-stone-500' }} flex items-center justify-center">
<span class="material-symbols-outlined text-sm">{{ $note->user_id === Auth::id() ? 'edit_note' : 'description' }}</span>
</div>
<div>
<h4 class="text-sm font-bold text-on-surface">{{ $note->titre }}</h4>
<p class="text-[10px] text-stone-400 mt-0.5">
    {{ $note->user_id === Auth::id() ? 'Your note' : 'From your advisor' }}
    &middot; {{ $note->created_at->format('M d, Y \a\t H:i') }}
</p>
</div>
</div>
@if($note->user_id === Auth::id())
<div class="flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
<button onclick="document.getElementById('edit-note-{{ $note->id }}').classList.toggle('hidden'); this.closest('.group').querySelector('.note-content-{{ $note->id }}').classList.toggle('hidden');" class="p-1.5 hover:bg-stone-200 rounded text-stone-400 hover:text-primary transition-all" title="Edit">
<span class="material-symbols-outlined text-sm">edit</span>
</button>
<form action="{{ route('client.portal.notes.destroy', [$rendezVous, $note]) }}" method="POST" onsubmit="return confirm('Delete this note?')">
@csrf
@method('DELETE')
<button type="submit" class="p-1.5 hover:bg-red-50 rounded text-stone-400 hover:text-red-500 transition-all" title="Delete">
<span class="material-symbols-outlined text-sm">delete</span>
</button>
</form>
</div>
@endif
</div>
<div class="note-content-{{ $note->id }} pl-11">
<p class="text-sm text-secondary leading-relaxed">{{ $note->commentaire }}</p>
</div>
<!-- Inline Edit Form (hidden) -->
@if($note->user_id === Auth::id())
<div id="edit-note-{{ $note->id }}" class="hidden pl-11 mt-4">
<form action="{{ route('client.portal.notes.update', [$rendezVous, $note]) }}" method="POST">
@csrf
@method('PUT')
<div class="space-y-3">
<input type="text" name="titre" value="{{ $note->titre }}" required class="w-full bg-white border border-stone-200 rounded-lg px-4 py-2 text-sm focus:ring-1 focus:ring-primary/20"/>
<textarea name="commentaire" required rows="3" class="w-full bg-white border border-stone-200 rounded-lg px-4 py-2 text-sm focus:ring-1 focus:ring-primary/20">{{ $note->commentaire }}</textarea>
<div class="flex gap-2 justify-end">
<button type="button" onclick="document.getElementById('edit-note-{{ $note->id }}').classList.add('hidden'); this.closest('.group').querySelector('.note-content-{{ $note->id }}').classList.remove('hidden');" class="px-4 py-1.5 text-xs font-bold text-stone-500 border border-stone-200 rounded hover:bg-stone-50">Cancel</button>
<button type="submit" class="px-4 py-1.5 text-xs font-bold text-white bg-primary rounded hover:opacity-90">Update</button>
</div>
</div>
</form>
</div>
@endif
</div>
@empty
<div class="p-12 text-center">
<span class="material-symbols-outlined text-4xl text-stone-300 mb-4">note</span>
<p class="text-stone-500 font-medium">No notes yet</p>
<p class="text-xs text-stone-400 mt-1">Add a note using the button above.</p>
</div>
@endforelse
</div>
</div>
</div>

<!-- Sidebar Column -->
<div class="lg:col-span-4 space-y-10">
<!-- Appointment Status Card -->
<div class="bg-white rounded-md p-8 border border-stone-200/60 shadow-lg shadow-stone-200/30">
<h3 class="font-manrope text-lg font-bold mb-6 flex items-center gap-2">
<span class="h-4 w-1 bg-primary rounded-full"></span>
    Status
</h3>
<div class="space-y-6">
<div class="text-center p-6 rounded-lg {{ $rendezVous->date_debut->isPast() ? 'bg-stone-50' : ($rendezVous->date_debut->isToday() ? 'bg-primary/5' : 'bg-green-50') }}">
<span class="material-symbols-outlined text-4xl mb-2 {{ $rendezVous->date_debut->isPast() ? 'text-stone-400' : ($rendezVous->date_debut->isToday() ? 'text-primary' : 'text-green-600') }}" style="font-variation-settings: 'FILL' 1;">
    {{ $rendezVous->date_debut->isPast() ? 'check_circle' : ($rendezVous->date_debut->isToday() ? 'pending' : 'event_upcoming') }}
</span>
<p class="text-lg font-bold {{ $rendezVous->date_debut->isPast() ? 'text-stone-600' : ($rendezVous->date_debut->isToday() ? 'text-primary' : 'text-green-700') }}">
    {{ $rendezVous->date_debut->isPast() ? 'Completed' : ($rendezVous->date_debut->isToday() ? 'Today' : 'Upcoming') }}
</p>
@if(!$rendezVous->date_debut->isPast())
<p class="text-xs text-stone-500 mt-1">{{ $rendezVous->date_debut->diffForHumans() }}</p>
@endif
</div>

<div class="space-y-4">
<div>
<p class="text-[10px] font-bold uppercase tracking-widest text-stone-400 mb-1">Date</p>
<p class="text-sm font-semibold text-on-surface">{{ $rendezVous->date_debut->format('l, F j, Y') }}</p>
</div>
<div>
<p class="text-[10px] font-bold uppercase tracking-widest text-stone-400 mb-1">Time</p>
<p class="text-sm font-semibold text-on-surface">{{ $rendezVous->heure_debut->format('H:i') }} - {{ $rendezVous->heure_fin->format('H:i') }}</p>
</div>
@if($rendezVous->lieu)
<div>
<p class="text-[10px] font-bold uppercase tracking-widest text-stone-400 mb-1">Location</p>
<p class="text-sm font-semibold text-on-surface">{{ $rendezVous->lieu }}</p>
</div>
@endif
</div>
</div>
</div>

<!-- Reminders -->
@if($rendezVous->rappels && $rendezVous->rappels->count() > 0)
<div class="bg-white rounded-md p-8 border border-stone-200/60 shadow-lg shadow-stone-200/30">
<h3 class="font-manrope text-lg font-bold mb-6 flex items-center gap-2">
<span class="h-4 w-1 bg-primary rounded-full"></span>
    Reminders
</h3>
<div class="space-y-4">
@foreach($rendezVous->rappels as $rappel)
<div class="flex items-start gap-3 p-3 bg-stone-50 rounded-lg">
<span class="material-symbols-outlined text-amber-500 text-sm mt-0.5">notifications_active</span>
<div>
<p class="text-xs font-bold text-on-surface">{{ $rappel->date_rappel->format('M d, Y \a\t H:i') }}</p>
<p class="text-[10px] text-stone-500">{{ $rappel->frequence }}</p>
</div>
</div>
@endforeach
</div>
</div>
@endif

<!-- Back Navigation -->
<a href="{{ route('client.portal.appointments') }}" class="flex items-center gap-2 text-sm font-bold text-stone-500 hover:text-primary transition-colors">
<span class="material-symbols-outlined text-sm">arrow_back</span>
    Back to all appointments
</a>
</div>
</div>
@endsection
