@extends('layouts.portal')

@section('title', 'Mes Notes - Pro Contact')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">
        Mes notes partagees
    </h1>
    <p class="text-gray-600 mt-1">{{ $contact->prenom }} {{ $contact->nom }}</p>
</div>

@if($notes->count() > 0)
    @foreach($notes as $rdvId => $rdvNotes)
        @php
            $rdv = $rdvNotes->first()->rendezVous;
        @endphp
        <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
            <div class="bg-gradient-to-r from-purple-600 to-purple-800 px-6 py-3">
                <div class="flex items-center justify-between">
                    <h3 class="text-white font-semibold">
                        <i class="fas fa-calendar mr-2"></i>
                        {{ $rdv->titre }}
                    </h3>
                    <span class="text-purple-200 text-sm">
                        {{ $rdv->date_debut->format('d/m/Y') }}
                    </span>
                </div>
                @if($rdv->activite)
                    <p class="text-purple-200 text-sm mt-1">{{ $rdv->activite->nom }}</p>
                @endif
            </div>

            <div class="p-6 space-y-3">
                @foreach($rdvNotes as $note)
                    <div class="p-4 rounded-lg {{ $note->estDuClient() ? 'bg-blue-50 border-l-4 border-blue-500' : 'bg-gray-50 border-l-4 border-purple-500' }}">
                        <div class="flex items-center justify-between mb-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $note->estDuClient() ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                {{ $note->estDuClient() ? 'Vous' : 'Professionnel' }}
                            </span>
                            <span class="text-xs text-gray-500">
                                {{ $note->created_at->format('d/m/Y H:i') }}
                            </span>
                        </div>
                        <p class="text-gray-700">{{ $note->commentaire }}</p>
                    </div>
                @endforeach
            </div>

            <div class="px-6 pb-4">
                <a href="{{ route('portal.rdv', ['id' => $rdvId, 'token' => $token]) }}"
                   class="text-purple-600 hover:text-purple-800 text-sm font-medium">
                    <i class="fas fa-arrow-right mr-1"></i>
                    Voir ce rendez-vous
                </a>
            </div>
        </div>
    @endforeach
@else
    <div class="bg-white rounded-xl shadow-md p-8 text-center">
        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-sticky-note text-gray-400 text-2xl"></i>
        </div>
        <h2 class="text-xl font-semibold text-gray-700 mb-2">Aucune note</h2>
        <p class="text-gray-500">Aucune note partagee pour le moment.</p>
    </div>
@endif

<!-- Back Link -->
<div class="text-center mt-6">
    <a href="{{ route('portal.index', ['token' => $token]) }}"
       class="inline-flex items-center space-x-2 text-purple-600 hover:text-purple-800 font-medium">
        <i class="fas fa-arrow-left"></i>
        <span>Retour au rendez-vous</span>
    </a>
</div>
@endsection
