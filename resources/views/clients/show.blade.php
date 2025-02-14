@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4 text-center mobile-title">Détails du Client : <strong>{{ $client->nom }}</strong></h1>

        <!-- Carte avec détails du client -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-user"></i> Informations générales</h5>
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <strong><i class="fas fa-users"></i> Nombre de personnes :</strong> {{ $client->nombre_personnes }}
                    </li>
                    <li class="mb-2">
                        <strong><i class="fas fa-clock"></i> Minutes :</strong> {{ $client->minutes }}
                    </li>
                    <li>
                        <strong><i class="fas fa-euro-sign"></i> Prix total :</strong> {{ $client->prix_total }} €
                    </li>
                </ul>
            </div>
        </div>

        <div class="text-center mobile-buttons">
            <a href="{{ route('clients.invoice', $client->id) }}" class="btn btn-success mb-2">
                <i class="fas fa-file-pdf"></i> Générer la Facture
            </a>
            <a href="{{ route('clients.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour à la liste des clients
            </a>
        </div>
    </div>

    <!-- Styles -->
    <style>
        /* Arrière-plan */
        body {
            background-color: #f8f9fa; /* Blanc cassé */
            font-family: 'Poppins', sans-serif;
            color: #2c3e50; /* Gris foncé */
        }

        /* Titres */
        h1, h2, h3, h4 {
            color: #2c3e50; /* Gris foncé */
            font-weight: 600;
        }
        .card-title {
            font-weight: bold;
            color: #495057;
        }
        .btn-secondary:hover {
            background-color: #6c757d;
            border-color: #5a6268;
        }

        /* Styles spécifiques pour les écrans mobiles */
        @media (max-width: 767.98px) {
            .mobile-title {
                font-size: 1.5rem; /* Réduire la taille du titre */
                margin-top: 1rem; /* Ajouter de la marge en haut */
            }

            .card-body {
                padding: 1rem; /* Ajouter du padding pour éviter que le contenu soit trop serré */
            }

            .card-title {
                font-size: 1.2rem; /* Réduire la taille du titre de la carte */
            }

            .list-unstyled li {
                font-size: 0.9rem; /* Réduire la taille de la police des éléments de la liste */
            }

            .mobile-buttons .btn {
                width: 100%; /* Boutons prennent toute la largeur */
                margin-bottom: 0.5rem; /* Ajouter de la marge entre les boutons */
            }
        }
    </style>
@endsection
