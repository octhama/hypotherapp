@extends('layouts.app')

@section('content')
    <div class="container">
        <!-- Afficher les messages d'alerte -->
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <h1 class="mb-5 text-center">Liste des Clients</h1>

        <div class="text-center mb-4">
            <a href="{{ route('rendez-vous.create') }}" class="btn btn-success btn-lg shadow-lg rounded-pill px-4 py-2">
                <i class="fas fa-plus-circle"></i> Nouveau Rendez-vous
            </a>
        </div>

        <div class="d-block d-md-none">
            @forelse ($clients as $client)
                <div class="card mb-3 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">{{ $client->nom }}</h5>
                        <p class="card-text">
                            <strong>Nombre de personnes:</strong> {{ $client->nombre_personnes }}<br>
                            <strong>Minutes:</strong> {{ $client->minutes }}<br>
                            <strong>Prix total:</strong> {{ number_format($client->prix_total, 2, ',', ' ') }} €
                        </p>
                        <div class="btn-group" role="group">
                            <a href="{{ route('clients.show', $client->id) }}" class="btn btn-primary btn-sm" title="Voir" data-bs-toggle="tooltip">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-secondary btn-sm" title="Modifier" data-bs-toggle="tooltip">
                                <i class="fas fa-edit"></i>
                            </a>
                            @if (!empty($client->rendezVous) && $client->rendezVous->isNotEmpty())
                                <button class="btn btn-danger btn-sm" title="Supprimer" disabled data-bs-toggle="tooltip">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            @else
                                <form action="{{ route('clients.destroy', $client->id) }}" method="POST" style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm delete-btn" title="Supprimer" data-bs-toggle="tooltip">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center text-muted py-4">
                    <i class="fas fa-info-circle fa-2x"></i><br>
                    Aucun client enregistré.
                </div>
            @endforelse
        </div>

        <div class="d-none d-md-block">
            <div class="table-responsive">
                <table class="table table-striped table-hover shadow-sm">
                    <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Nom</th>
                        <th>Nombre de personnes</th>
                        <th>Minutes</th>
                        <th>Prix total (€)</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($clients as $client)
                        <tr>
                            <td>{{ $client->id }}</td>
                            <td>{{ $client->nom }}</td>
                            <td>{{ $client->nombre_personnes }}</td>
                            <td>{{ $client->minutes }}</td>
                            <td>{{ number_format($client->prix_total, 2, ',', ' ') }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                <form action="{{ route('clients.show', $client->id) }}" method="GET" class="d-inline">
                                    <button type="submit" class="btn btn-primary btn-sm" title="Voir" data-bs-toggle="tooltip">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </form>
                                <form action="{{ route('clients.edit', $client->id) }}" method="GET" class="d-inline">
                                    <button type="submit" class="btn btn-secondary btn-sm" title="Modifier" data-bs-toggle="tooltip">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </form>
                                @if (!empty($client->rendezVous) && $client->rendezVous->isNotEmpty())
                                    <form action="{{ route('clients.destroy', $client->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Supprimer" disabled data-bs-toggle="tooltip">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('clients.destroy', $client->id) }}" method="POST" style="display: inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm delete-btn" title="Supprimer" data-bs-toggle="tooltip">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="fas fa-info-circle fa-2x"></i><br>
                                Aucun client enregistré.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination Bootstrap -->
        <div class="d-flex justify-content-center mt-4">
            {{ $clients->links('vendor.pagination.custom') }}
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const deleteButtons = document.querySelectorAll('.delete-btn');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function (e) {
                    if (!confirm('Voulez-vous vraiment supprimer ce client ?')) {
                        e.preventDefault();
                    }
                });
            });

            // Initialisation des tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>

    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Poppins', sans-serif;
            color: #2c3e50;
        }
        .table-hover tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.1);
            transition: background-color 0.3s ease;
        }
        .card {
            margin-bottom: 1rem;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .btn-group .btn {
            margin-right: 0.5rem;
            transition: transform 0.2s ease;
        }
        .btn-group .btn:hover {
            transform: scale(1.1);
        }
        .alert {
            transition: opacity 0.5s ease;
        }
        .alert-dismissible .btn-close {
            padding: 0.75rem 1.25rem;
        }
    </style>
@endsection
