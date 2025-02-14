@php
    use Carbon\Carbon;
@endphp

@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="text-center mb-4">Gestion journalière</h1>

        <div class="row">
            <!-- Section Rendez-vous -->
            <div class="col-12 col-md-7 order-md-1 order-2">
                <h3 class="mb-3 text-secondary">{{ ucfirst(Carbon::now()->isoFormat('dddd D MMMM YYYY')) }}</h3>

                <a href="{{ route('rendez-vous.create') }}" class="btn btn-success mb-4 shadow-lg rounded-pill px-4 py-2">
                    <i class="fas fa-plus-circle"></i> Nouveau Rendez-vous
                </a>

                <h4 class="mb-3"><i class="fas fa-calendar-check"></i> Rendez-vous prévus</h4>
                <div class="list-group">
                    @forelse ($rendezVous as $rdv)
                        <div class="list-group-item list-group-item-action rounded shadow-sm border-0 p-2 mb-2">
                            <div class="d-flex justify-content-between align-items-center"
                                 data-bs-toggle="collapse"
                                 data-bs-target="#rdv-{{ $rdv->id }}"
                                 style="cursor: pointer;">
                                <span class="fw-semibold text-dark">
                                    <i class="fas fa-user me-1"></i>
                                    @if (!empty($rdv->client))
                                        {{ $rdv->client->nom }}
                                    @else
                                        <span class="text-danger">Client supprimé</span>
                                    @endif
                                    <span class="text-muted">
                                        (<i class="fas fa-calendar me-1"></i>
                                        {{ $rdv->horaire_debut->format('H:i') }} - {{ $rdv->horaire_fin->format('H:i') }})
                                    </span>
                                </span>
                                <i class="fas fa-chevron-down transition-icon"></i>
                            </div>

                            <div id="rdv-{{ $rdv->id }}" class="collapse mt-2">
                                <p><strong><i class="fas fa-users"></i> Nombre de personnes :</strong> {{ $rdv->nombre_personnes }}</p>
                                <p><strong><i class="fas fa-horse"></i> Poneys assignés :</strong>
                                    @foreach ($rdv->poneys as $poney)
                                        <span class="badge bg-primary">{{ $poney->nom }}</span>
                                    @endforeach
                                </p>

                                <div class="d-flex gap-2">
                                    <form action="{{ route('rendez-vous.destroy', $rdv->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger rounded-circle p-1"
                                                onclick="return confirm('Vous êtes sur le point de valider ce rendez-vous comme terminé. Continuer ?')">
                                            <i class="fas fa-check-circle"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('rendez-vous.edit', $rdv->id) }}" method="GET">
                                        <button type="submit" class="btn btn-outline-primary rounded-circle p-1">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted"><i class="fas fa-info-circle"></i> Aucun rendez-vous prévu pour aujourd'hui.</p>
                    @endforelse
                </div>
            </div>

            <!-- Section Nouveau client -->
            <div class="col-12 col-md-5 order-md-2 order-1 mb-4">
                <div class="card shadow-lg border-0 rounded">
                    <div class="card-header bg-gradient text-white text-center fw-bold" style="background: #6c5ce7;">
                        Enregistrer un nouveau client
                    </div>
                    <div class="card-body">
                        <form action="{{ route('clients.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="nom_client" class="form-label"><i class="fas fa-user"></i> Nom du client</label>
                                <input type="text" class="form-control" id="nom_client" name="nom" required>
                            </div>
                            <div class="mb-3">
                                <label for="nombre_personnes" class="form-label"><i class="fas fa-users"></i> Nombre de personnes</label>
                                <input type="number" class="form-control" id="nombre_personnes" name="nombre_personnes"
                                       min="1" max="20" required>
                            </div>
                            <div class="mb-3">
                                <label for="duree" class="form-label"><i class="fas fa-clock"></i> Durée en minutes</label>
                                <input type="number" class="form-control" id="duree" name="duree" min="10" max="20"
                                       step="1" required>
                                <small class="text-danger d-none" id="alerte-duree"><i
                                        class="fas fa-exclamation-circle"></i> Minimum 10 minutes.</small>
                            </div>
                            <div class="mb-3">
                                <label for="prix_total" class="form-label"><i class="fas fa-euro-sign"></i> Prix total</label>
                                <input type="hidden" id="prix_total_hidden" name="prix_total">
                                <input type="text" class="form-control" id="prix_total" readonly>
                                <small class="text-muted">Calculé automatiquement.</small>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary rounded-pill shadow"><i
                                        class="fas fa-save"></i> Enregistrer
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bootstrap Toast -->
        <div class="toast-container position-fixed bottom-0 end-0 p-3">
            <div id="errorToast" class="toast align-items-center text-bg-danger border-0" role="alert"
                 aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="fas fa-exclamation-triangle"></i> La durée minimale est de 10 minutes !
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto"
                            data-bs-dismiss="toast"></button>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let nombrePersonnesInput = document.getElementById('nombre_personnes');
            let dureeInput = document.getElementById('duree');
            let prixTotalInput = document.getElementById('prix_total');
            let prixTotalHidden = document.getElementById('prix_total_hidden');
            let alerteDuree = document.getElementById('alerte-duree');
            let errorToast = new bootstrap.Toast(document.getElementById('errorToast'));

            function recalculerPrix() {
                let nombrePersonnes = parseInt(nombrePersonnesInput.value) || 0;
                let duree = parseInt(dureeInput.value) || 0;

                if (duree < 10) {
                    alerteDuree.classList.remove('d-none');
                    prixTotalInput.value = "";
                    prixTotalHidden.value = "";
                    errorToast.show();
                    return;
                } else {
                    alerteDuree.classList.add('d-none');
                }

                let prixParPersonne = 50;  // Exemple : 50€ par personne pour 10 min
                let prixTotal = (nombrePersonnes * (duree / 10) * prixParPersonne).toFixed(2);

                prixTotalInput.value = prixTotal + " €";
                prixTotalHidden.value = prixTotal; // Stocker la valeur dans le champ caché
            }

            nombrePersonnesInput.addEventListener('input', recalculerPrix);
            dureeInput.addEventListener('input', recalculerPrix);
        });
    </script>
@endsection
<style>
    body {
        background-color: #f8f9fa;
        font-family: 'Poppins', sans-serif;
    }

    .list-group-item {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }

    .list-group-item:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .transition-icon {
        transition: transform 0.3s ease-in-out;
    }

    [data-bs-toggle="collapse"].collapsed .transition-icon {
        transform: rotate(0deg);
    }

    [data-bs-toggle="collapse"].show .transition-icon {
        transform: rotate(180deg);
    }

    .form-control {
        border-radius: 10px;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }

    .form-control:focus {
        border-color: #6c5ce7;
        box-shadow: 0 0 5px rgba(108, 92, 231, 0.5);
    }

    @media (max-width: 768px) {
        .col-md-7, .col-md-5 {
            width: 100%;
            padding: 0;
        }

        .btn {
            width: 100%;
            margin-bottom: 10px;
        }

        .list-group-item {
            padding: 10px;
        }

        .form-control {
            font-size: 14px;
        }

        h1, h3, h4 {
            font-size: 1.5rem;
        }
    }
</style>
