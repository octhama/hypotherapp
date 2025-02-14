@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4">Modifier le client : <strong>{{ $client->nom }}</strong></h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li><i class="fas fa-exclamation-circle"></i> {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('clients.update', $client->id) }}" method="POST" class="p-4 shadow-sm bg-light rounded">
            @csrf
            @method('PUT')

            <div class="form-group mb-3">
                <label for="nom" class="form-label">Nom du client</label>
                <input type="text" class="form-control" id="nom" name="nom" value="{{ old('nom', $client->nom) }}" required>
            </div>

            <div class="form-group mb-3">
                <label for="nombre_personnes" class="form-label">Nombre de personnes</label>
                <input type="number" class="form-control" id="nombre_personnes" name="nombre_personnes"
                       value="{{ old('nombre_personnes', $client->nombre_personnes) }}" min="1" required>
            </div>

            <div class="form-group mb-3">
                <label for="minutes" class="form-label">Durée (minutes)</label>
                <input type="number" class="form-control" id="minutes" name="minutes"
                       value="{{ old('minutes', $client->minutes) }}" min="10" max="120" required>
                <small class="text-danger d-none" id="alerte-duree"><i class="fas fa-exclamation-triangle"></i> Minimum 10 minutes.</small>
            </div>

            <div class="form-group mb-4">
                <label for="prix_total" class="form-label">Prix total (€)</label>
                <input type="text" class="form-control" id="prix_total" name="prix_total"
                       value="{{ old('prix_total', number_format($client->prix_total, 2)) }}" readonly>
            </div>

            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Mettre à jour</button>
                <a href="{{ route('clients.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Annuler</a>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let nombrePersonnesInput = document.getElementById('nombre_personnes');
            let dureeInput = document.getElementById('minutes');
            let prixTotalInput = document.getElementById('prix_total');
            let alerteDuree = document.getElementById('alerte-duree');

            function recalculerPrix() {
                let nombrePersonnes = parseInt(nombrePersonnesInput.value) || 0;
                let duree = parseInt(dureeInput.value) || 0;

                if (duree < 10) {
                    alerteDuree.classList.remove('d-none');
                    prixTotalInput.value = "";
                    return;
                } else {
                    alerteDuree.classList.add('d-none');
                }

                let prixParPersonne = 50;  // Exemple : 50€ par personne pour 10 min
                let prixTotal = (nombrePersonnes * (duree / 10) * prixParPersonne).toFixed(2);

                prixTotalInput.value = prixTotal + " €";
            }

            nombrePersonnesInput.addEventListener('input', recalculerPrix);
            dureeInput.addEventListener('input', recalculerPrix);
        });
    </script>
@endsection
