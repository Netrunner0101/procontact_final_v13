@extends('layouts.app')

@section('title', $contact->prenom . ' ' . $contact->nom . ' - Pro Contact')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-start mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $contact->prenom }} {{ $contact->nom }}</h1>
                @if($contact->status)
                    <span class="inline-block mt-2 px-3 py-1 text-sm font-medium rounded-full bg-blue-100 text-blue-800">
                        {{ $contact->status->nom }}
                    </span>
                @endif
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('contacts.edit', $contact) }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200">
                    Modifier
                </a>
                <a href="{{ route('contacts.index') }}"
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200">
                    Retour
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Contact Details -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Informations</h3>
                    <div class="grid grid-cols-2 gap-4">
                        @if($contact->emails->count() > 0)
                            <div>
                                <label class="text-sm font-medium text-gray-500">Email(s)</label>
                                @foreach($contact->emails as $email)
                                    <p class="text-gray-900">{{ $email->email }}</p>
                                @endforeach
                            </div>
                        @endif
                        @if($contact->numeroTelephones->count() > 0)
                            <div>
                                <label class="text-sm font-medium text-gray-500">Telephone(s)</label>
                                @foreach($contact->numeroTelephones as $phone)
                                    <p class="text-gray-900">{{ $phone->numero_telephone }}</p>
                                @endforeach
                            </div>
                        @endif
                        @if($contact->ville)
                            <div>
                                <label class="text-sm font-medium text-gray-500">Ville</label>
                                <p class="text-gray-900">{{ $contact->ville }}</p>
                            </div>
                        @endif
                        @if($contact->pays)
                            <div>
                                <label class="text-sm font-medium text-gray-500">Pays</label>
                                <p class="text-gray-900">{{ $contact->pays }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Appointments -->
                @if($contact->rendezVous->count() > 0)
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Rendez-vous</h3>
                    <div class="space-y-3">
                        @foreach($contact->rendezVous as $rdv)
                            <a href="{{ route('rendez-vous.show', $rdv) }}"
                               class="block p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $rdv->titre }}</p>
                                        <p class="text-sm text-gray-500">
                                            {{ $rdv->date_debut->format('d/m/Y') }}
                                            {{ $rdv->heure_debut ? $rdv->heure_debut->format('H:i') : '' }}
                                        </p>
                                    </div>
                                    <i class="fas fa-chevron-right text-gray-400"></i>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Portal Access Status -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Acces Portail Client</h3>
                    @if($contact->accessToken)
                        <div class="flex items-center space-x-2 mb-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-link mr-1"></i> Actif
                            </span>
                        </div>
                        <p class="text-sm text-gray-600 mb-3">
                            Le client peut acceder a son espace via le lien envoye par email.
                        </p>
                        <form method="POST" action="{{ route('contacts.revoquer-acces', $contact) }}">
                            @csrf
                            <button type="submit"
                                    onclick="return confirm('Revoquer l\'acces client ?')"
                                    class="w-full bg-red-100 hover:bg-red-200 text-red-700 px-4 py-2 rounded-lg transition duration-200 text-sm font-medium">
                                <i class="fas fa-ban mr-1"></i>
                                Revoquer l'acces
                            </button>
                        </form>
                    @else
                        <div class="flex items-center space-x-2 mb-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                <i class="fas fa-link mr-1"></i> Inactif
                            </span>
                        </div>
                        <p class="text-sm text-gray-500">
                            Sera active au prochain envoi d'email de rendez-vous.
                        </p>
                    @endif
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions</h3>
                    <div class="space-y-3">
                        <a href="{{ route('rendez-vous.create', ['contact_id' => $contact->id]) }}"
                           class="block w-full bg-purple-600 hover:bg-purple-700 text-white text-center px-4 py-2 rounded transition duration-200">
                            Nouveau rendez-vous
                        </a>
                        <form action="{{ route('contacts.destroy', $contact) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    onclick="return confirm('Supprimer ce contact ?')"
                                    class="block w-full bg-red-600 hover:bg-red-700 text-white text-center px-4 py-2 rounded transition duration-200">
                                Supprimer le contact
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
