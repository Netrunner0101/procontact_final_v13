@extends('layouts.portal')

@section('title', 'Lien invalide - Pro Contact')

@section('content')
<div class="flex items-center justify-center min-h-[50vh]">
    <div class="text-center max-w-md">
        <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-link-slash text-red-500 text-3xl"></i>
        </div>
        <h1 class="text-2xl font-bold text-gray-800 mb-4">Lien invalide</h1>
        <p class="text-gray-600">
            Ce lien est invalide ou a ete desactive.
        </p>
        <p class="text-gray-500 text-sm mt-4">
            Veuillez contacter votre professionnel pour obtenir un nouveau lien d'acces.
        </p>
    </div>
</div>
@endsection
