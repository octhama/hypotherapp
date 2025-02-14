@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="text-center mb-5">Modifier le Poney : <strong>{{ $poney->nom }}</strong></h1>

        <!-- Notification d'erreurs -->
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong><i class="fas fa-exclamation-circle"></i> Erreurs détectées :</strong>
                <ul class="mt-2 mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Formulaire de modification -->
        <div class="card shadow">
            <div class="card-header text-bg-light text-center">
                <h4>Modifier les informations du Poney</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('poneys.update', $poney->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Nom du poney -->
                    <div class="mb-4">
                        <label for="nom" class="form-label"><i class="fas fa-horse"></i> Nom</label>
                        <input type="text" class="form-control" id="nom" name="nom" value="{{ old('nom', $poney->nom) }}" required>
                    </div>

                    <!-- Heures travaillées -->
                    <div class="mb-4">
                        <label for="heures_travail_validee" class="form-label"><i class="fas fa-clock"></i> Heures travaillées</label>
                        <input type="number" class="form-control" id="heures_travail_validee" name="heures_travail_validee"
                               value="{{ old('heures_travail_validee', $poney->heures_travail_validee) }}" min="0" required>
                    </div>

                    <!-- Disponibilité -->
                    <div class="form-check form-switch mb-4">
                        <input class="form-check-input" type="checkbox" id="disponible" name="disponible" value="1" {{ old('disponible', $poney->disponible) ? 'checked' : '' }}>
                        <label class="form-check-label" for="disponible"><i class="fas fa-check-circle"></i> Disponible</label>
                    </div>

                    <!-- Boutons d'action -->
                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Mettre à jour</button>
                        <a href="{{ route('poneys.index') }}" class="btn btn-secondary"><i class="fas fa-undo"></i> Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Styles supplémentaires -->
    <style>
        /* Arrière-plan */
        body {
            background-color: #f8f9fa; /* Blanc cassé */
            font-family: 'Poppins', sans-serif;
            color: #2c3e50; /* Gris foncé */
        }

        .btn-success, .btn-secondary {
            transition: all 0.3s ease;
        }
        .btn-success:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }
        .btn-secondary:hover {
            background-color: #6c757d;
            border-color: #5a6268;
        }
        .form-label {
            font-weight: bold;
        }
        .form-control:focus {
            border-color: #28a745;
            box-shadow: 0 0 5px rgba(40, 167, 69, 0.8);
        }
        /* Alignement centré pour l'en-tête du formulaire */
        .card-header {
            text-align: center;
        }
    </style>
@endsection
