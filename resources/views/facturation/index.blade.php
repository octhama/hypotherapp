@php
    use Carbon\Carbon;
@endphp

@extends('layouts.app')

@section('content')
    <div class="container">
        <h3 class="mb-4">{{ ucfirst(Carbon::now()->isoFormat('dddd D MMMM YYYY')) }}</h3>

        <h2 class="text-center mb-4">Facturation</h2>

        <!-- Conteneur pour les deux colonnes -->
        <div class="row">
            <!-- Colonne Historique des facturations -->
            <div class="col-md-4 mb-4">
                <h4 class="mb-3">Historique</h4>
                <ol class="list-group shadow-sm">
                    @forelse ($facturations->groupBy(fn($facture) => Carbon::parse($facture->created_at)->format('Y-m')) as $mois => $factures)
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <a class="text-decoration-none text-dark fw-semibold d-flex align-items-center toggle-link"
                                   data-bs-toggle="collapse" href="#facture-{{ $loop->index }}" role="button"
                                   aria-expanded="false" aria-controls="facture-{{ $loop->index }}">
                                    {{ ucfirst(Carbon::createFromFormat('Y-m', $mois)->translatedFormat('F Y')) }}
                                </a>
                                <small class="text-muted">Total : {{ count($factures) }} factures</small>
                            </div>
                            <span class="badge text-bg-primary rounded-pill fs-6">
                                {{ number_format($factures->sum('montant'), 2, ',', ' ') }} €
                            </span>
                        </li>
                    @empty
                        <li class="list-group-item text-center text-muted">Aucune facturation disponible</li>
                    @endforelse
                </ol>
            </div>

            <!-- Colonne Détails de facturation -->
            <div class="col-md-8 mb-4">
                <h4 class="mb-3">Détails</h4>

                <!-- Message d'instruction quand aucun historique n'est sélectionné -->
                <div id="message-instruction" class="text-center text-muted p-4">
                    <i class="fas fa-info-circle fa-2x mb-2"></i>
                    <p>Cliquez sur une facture dans l'historique pour afficher les détails.</p>
                </div>

                <div>
                    @forelse ($facturations->groupBy(fn($facture) => Carbon::parse($facture->created_at)->format('Y-m')) as $mois => $factures)
                        <div class="collapse fade mt-2 bg-white p-3 rounded shadow-sm animated-collapse"
                             id="facture-{{ $loop->index }}" data-bs-parent=".col-md-8">
                            <h5 class="fw-bold text-primary">{{ ucfirst(Carbon::createFromFormat('Y-m', $mois)->translatedFormat('F Y')) }}</h5>
                            @if ($factures->isNotEmpty())
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="bg-primary text-white">
                                        <tr>
                                            <th>Client</th>
                                            <th>Minutes</th>
                                            <th>Montant (€)</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($factures as $facture)
                                            <tr class="align-middle">
                                                <td>{{ $facture->client ? $facture->client->nom : 'Client inconnu' }}</td>
                                                <td>{{ $facture->nombre_minutes }}</td>
                                                <td class="fw-bold">{{ number_format($facture->montant, 2, ',', ' ') }}€</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <button class="btn btn-primary mb-4 shadow-lg rounded-pill px-4 py-2 d-block mx-auto">
                                    <i class="fas fa-paper-plane"></i> Envoyer les factures
                                </button>
                            @else
                                <p class="text-center text-muted"><i class="fas fa-info-circle"></i> Aucune facture pour ce mois</p>
                            @endif
                        </div>
                    @empty
                        <p class="text-muted"><i class="fas fa-info-circle"></i> Cliquez sur une facture dans l'historique pour afficher les détails.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Icônes animées */
        .transition-icon {
            transition: transform 0.3s ease-in-out;
        }

        /* Rotation de l'icône au clic */
        .collapsed .transition-icon {
            transform: rotate(0deg);
        }

        .show .transition-icon {
            transform: rotate(90deg);
        }

        /* Animation des détails */
        .animated-collapse {
            transform: translateY(-10px);
            opacity: 0;
            transition: opacity 0.5s ease, transform 0.5s ease;
        }

        .collapse.show {
            transform: translateY(0);
            opacity: 1;
        }

        /* Animation sur la liste */
        .list-group-item:hover {
            background: #f8f9fa;
            transition: background 0.3s ease-in-out;
        }

        /* Ombre sur tableau */
        .table-hover tbody tr:hover {
            background: rgba(0, 123, 255, 0.1);
        }

        /* Effet de survol bouton */
        .btn-primary:hover {
            background: #0056b3;
            transform: translateY(-2px);
            transition: all 0.2s ease-in-out;
        }

        /* Adaptation pour mobile */
        @media (max-width: 768px) {
            .container {
                padding: 0 10px;
            }

            h2, h3, h4 {
                font-size: 1.5rem;
            }

            .list-group-item {
                padding: 10px;
            }

            .btn-primary {
                font-size: 1rem;
                padding: 10px;
            }

            .table-responsive {
                overflow-x: auto;
            }

            /* Empiler les colonnes sur mobile */
            .col-md-4, .col-md-8 {
                width: 100%;
                max-width: 100%;
            }
        }
    </style>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let messageInstruction = document.getElementById("message-instruction");

            document.querySelectorAll(".toggle-link").forEach(link => {
                link.addEventListener("click", function () {
                    // Masquer le message d'instruction au premier clic sur une facture
                    if (messageInstruction) {
                        messageInstruction.style.display = "none";
                    }
                });
            });
        });
    </script>
@endsection
