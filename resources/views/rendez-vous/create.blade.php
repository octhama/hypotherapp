@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="text-center mb-5">Créer un Nouveau Rendez-vous</h1>

        <form action="{{ route('rendez-vous.store') }}" method="POST" class="shadow p-4 rounded bg-light" id="rendez-vous-form">
            @csrf

            <!-- Sélectionner un client -->
            <div class="form-group mb-4">
                <label for="client_id" class="form-label"><i class="fas fa-user"></i> Sélectionner un client</label>
                <select name="client_id" id="client_id" class="form-select" required>
                    <option value="" disabled selected>Choisissez un client</option>
                    @foreach ($clients as $client)
                        <option value="{{ $client->id }}" data-nombre-personnes="{{ $client->nombre_personnes }}">
                            {{ $client->nom }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Nombre de personnes -->
            <div class="form-group mb-4">
                <label for="nombre_personnes" class="form-label"><i class="fas fa-users"></i> Nombre de personnes</label>
                <input type="number" class="form-control" id="nombre_personnes" name="nombre_personnes"
                       min="1" max="{{ count($poneys) }}" placeholder="Maximum : {{ count($poneys) }}"
                       required value="{{ old('nombre_personnes') }}" readonly>
            </div>

            <!-- Plages horaires disponibles -->
            <div class="form-group mb-4">
                <label for="creneaux" class="form-label"><i class="fas fa-clock"></i> Choisissez une plage horaire</label>
                <select name="creneaux" id="creneaux" class="form-select" required>
                    <option value="" disabled selected>Choisissez une plage horaire</option>
                    @php
                        $reservations = $reservations ?? collect(); // Définit une collection vide si $reservations n'existe pas
                    @endphp

                    @foreach ($disponibilites as $interval)
                        @php
                            $creneau = $interval->start->format('H:i') . '-' . $interval->end->format('H:i');
                            $estReserve = $reservations->contains(fn($rdv) =>
                                optional($rdv->horaire_debut)->format('H:i') === $interval->start->format('H:i') &&
                                optional($rdv->horaire_fin)->format('H:i') === $interval->end->format('H:i')
                            );
                        @endphp

                        <option value="{{ $creneau }}" {{ $estReserve ? 'disabled' : '' }}>
                            {{ $interval->start->format('H:i') }} - {{ $interval->end->format('H:i') }}
                            @if ($estReserve) (Réservé) @endif
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Assigner des poneys -->
            <div class="form-group mb-4">
                <label for="poneys" class="form-label"><i class="fas fa-horse"></i> Assigner des poneys</label>

                <!-- Afficher une alerte si aucun poney n'est disponible -->
                @if ($aucunPoneyDisponible)
                    <small class="text-danger d-block mb-2">
                        <i class="fas fa-exclamation-circle"></i> Aucun poney disponible. Veuillez libérer des poneys avant de créer un rendez-vous.
                    </small>
                @else
                    <!-- Afficher les champs de sélection dynamiquement -->
                    <div id="poneys-container" class="row row-cols-2 g-3">
                        <!-- Les sélections de poneys seront injectées ici dynamiquement -->
                    </div>
                @endif
            </div>

            <!-- Bouton de validation -->
            <div class="d-flex justify-content-center">
                <button type="submit" class="btn btn-success px-5 py-2">
                    <i class="fas fa-calendar-plus"></i> Créer le rendez-vous
                </button>
            </div>
        </form>
    </div>

    <!-- Script pour gérer la dynamique des poneys et la validation -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const clientSelect = document.getElementById('client_id');
            const nombrePersonnesInput = document.getElementById('nombre_personnes');
            const poneysContainer = document.getElementById('poneys-container');
            const form = document.getElementById('rendez-vous-form');
            const poneysData = @json($poneys);

            // Mettre à jour le nombre de personnes et les sélections de poneys
            clientSelect.addEventListener('change', function () {
                const selectedClient = this.options[this.selectedIndex];
                const nombrePersonnes = selectedClient.dataset.nombrePersonnes;

                // Mettre à jour le champ "Nombre de personnes"
                nombrePersonnesInput.value = nombrePersonnes;

                // Générer les sélections de poneys dynamiquement
                poneysContainer.innerHTML = ''; // Vider le conteneur
                for (let i = 1; i <= nombrePersonnes; i++) {
                    const selectHtml = `
                        <div class="col">
                            <label for="poney-select-${i}" class="form-label">Poney ${i}</label>
                            <select name="poneys[]" id="poney-select-${i}" class="form-select" required>
                                <option value="" disabled selected>Choisissez un poney</option>
                                ${poneysData.map(poney => `
                                    <option value="${poney.id}">
                                        ${poney.nom}
                                    </option>
                                `).join('')}
                            </select>
                        </div>
                    `;
                    poneysContainer.insertAdjacentHTML('beforeend', selectHtml);
                }

                // Appliquer la logique de désactivation des poneys déjà sélectionnés
                updatePoneyOptions();
            });

            // Fonction pour désactiver les poneys déjà sélectionnés
            function updatePoneyOptions() {
                let selectedPoneys = new Set();
                let selects = document.querySelectorAll('select[name="poneys[]"]');

                // Collecter les poneys sélectionnés
                selects.forEach(select => {
                    if (select.value) {
                        selectedPoneys.add(select.value);
                    }
                });

                // Désactiver les poneys déjà sélectionnés dans les autres listes
                selects.forEach(select => {
                    let options = select.querySelectorAll('option');

                    options.forEach(option => {
                        if (option.value) {
                            if (selectedPoneys.has(option.value) && option.value !== select.value) {
                                option.disabled = true;
                                option.textContent = option.textContent.replace(' (Pris)', '') + ' (Pris)';
                            } else {
                                option.disabled = false;
                                option.textContent = option.textContent.replace(' (Pris)', '');
                            }
                        }
                    });
                });
            }

            // Ajouter un événement sur chaque select pour mettre à jour dynamiquement
            poneysContainer.addEventListener('change', function (e) {
                if (e.target.tagName === 'SELECT') {
                    updatePoneyOptions();
                }
            });

            // Validation du formulaire avant soumission
            form.addEventListener('submit', function (e) {
                const nombrePersonnes = parseInt(nombrePersonnesInput.value, 10);
                const selectedPoneys = document.querySelectorAll('select[name="poneys[]"]');
                const selectedPoneysCount = Array.from(selectedPoneys).filter(select => select.value !== '').length;

                // Vérifier si le nombre de poneys sélectionnés correspond au nombre de personnes
                if (selectedPoneysCount !== nombrePersonnes) {
                    e.preventDefault(); // Empêcher la soumission du formulaire
                    alert('Le nombre de poneys sélectionnés ne correspond pas au nombre de personnes. Veuillez sélectionner un poney pour chaque personne.');
                }
            });
        });
    </script>

    <!-- Styles supplémentaires -->
    <style>
        .form-group label {
            font-weight: bold;
        }
        .form-control:focus {
            border-color: #28a745;
            box-shadow: 0 0 5px rgba(40, 167, 69, 0.8);
        }
        .btn-success:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }
        .text-danger {
            font-weight: bold;
            font-size: 1rem;
            color: #dc3545; /* Rouge */
        }
        .text-danger i {
            margin-right: 0.5rem;
        }
    </style>
@endsection
