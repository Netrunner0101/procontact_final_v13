@extends('layouts.portal')

@section('title', 'Mon Rendez-vous - Pro Contact')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">
        Bonjour {{ $contact->prenom }} {{ $contact->nom }}
    </h1>
    <p class="text-gray-600 mt-1">Voici les details de votre rendez-vous</p>
</div>

@if($rdv)
    <!-- RDV Details Card -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8">
        <div class="bg-gradient-to-r from-purple-600 to-purple-800 px-6 py-4">
            <h2 class="text-xl font-semibold text-white">{{ $rdv->titre }}</h2>
            @if($rdv->description)
                <p class="text-purple-200 mt-1">{{ $rdv->description }}</p>
            @endif
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-calendar text-purple-600"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Date</p>
                        <p class="font-medium text-gray-800">
                            {{ $rdv->date_debut->format('d/m/Y') }}
                            @if($rdv->date_fin && $rdv->date_debut->format('Y-m-d') !== $rdv->date_fin->format('Y-m-d'))
                                au {{ $rdv->date_fin->format('d/m/Y') }}
                            @endif
                        </p>
                    </div>
                </div>

                <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-clock text-blue-600"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Heure</p>
                        <p class="font-medium text-gray-800">
                            {{ $rdv->heure_debut->format('H:i') }} - {{ $rdv->heure_fin->format('H:i') }}
                        </p>
                    </div>
                </div>

                @if($rdv->activite)
                <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg md:col-span-2">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-briefcase text-green-600"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Activite</p>
                        <p class="font-medium text-gray-800">{{ $rdv->activite->nom }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Shared Notes Section -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-sticky-note text-purple-600 mr-2"></i>
                Notes partagees
            </h3>
        </div>

        <div class="p-6">
            @if($rdv->notesPartagees && $rdv->notesPartagees->count() > 0)
                <div class="space-y-4">
                    @foreach($rdv->notesPartagees as $note)
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
            @else
                <p class="text-gray-500 text-center py-4">Aucune note partagee pour ce rendez-vous.</p>
            @endif
        </div>
    </div>

    <!-- Add Note Form -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-pen text-purple-600 mr-2"></i>
                Ajouter une note
            </h3>
        </div>

        <div class="p-6">
            <form method="POST" action="{{ route('portal.note.store') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="rendez_vous_id" value="{{ $rdv->id }}">

                <div class="mb-4">
                    <textarea
                        name="contenu"
                        rows="4"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent resize-none"
                        placeholder="Ecrivez votre note ici..."
                        required
                        maxlength="2000"
                    >{{ old('contenu') }}</textarea>
                    @error('contenu')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                            class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-lg transition duration-200 flex items-center space-x-2">
                        <i class="fas fa-paper-plane"></i>
                        <span>Envoyer</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
@else
    <div class="bg-white rounded-xl shadow-md p-8 text-center">
        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-calendar-times text-gray-400 text-2xl"></i>
        </div>
        <h2 class="text-xl font-semibold text-gray-700 mb-2">Aucun rendez-vous</h2>
        <p class="text-gray-500">Aucun rendez-vous n'a encore ete programme.</p>
    </div>
@endif

<!-- Navigation -->
<div class="text-center">
    <a href="{{ route('portal.notes', ['token' => $token]) }}"
       class="inline-flex items-center space-x-2 text-purple-600 hover:text-purple-800 font-medium">
        <i class="fas fa-list"></i>
        <span>Voir toutes mes notes</span>
    </a>
</div>
@endsection
