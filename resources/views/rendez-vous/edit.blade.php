@php
    use Carbon\Carbon;
@endphp

@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="text-center mb-5">Modifier le Rendez-vous</h1>

        <form action="{{ route('rendez-vous.update', $rendezVous->id) }}" method="POST" class="shadow p-4 rounded bg-light">
            @csrf
            @method('PUT')

            <!-- Nom du client (non modifiable) -->
            <div class="form-group mb-4">
                <label class="form-label"><i class="fas fa-user"></i> Client</label>
                <input type="text" class="form-control" value="{{ $rendezVous->client->nom }}" disabled>
            </div>

            <!-- Nombre de personnes (non modifiable) -->
            <div class="form-group mb-4">
                <label class="form-label"><i class="fas fa-users"></i> Nombre de personnes</label>
                <input type="text" class="form-control" value="{{ $rendezVous->nombre_personnes }}" disabled>
            </div>

            <!-- Plages horaires disponibles -->
            <div class="form-group mb-4">
                <label for="creneaux" class="form-label"><i class="fas fa-clock"></i> Plages horaires disponibles</label>
                <select name="creneaux" id="creneaux" class="form-select" required>
                    <option value="" disabled>Choisissez une plage horaire</option>

                    @php
                        $reservations = $reservations ?? collect(); // S'assurer que la collection existe
                    @endphp

                    @foreach ($disponibilites as $interval)
                        @php
                            if (!$interval->start || !$interval->end) continue;

                            $creneau = Carbon::parse($interval->start)->format('H:i') . '-' . Carbon::parse($interval->end)->format('H:i');

                            // Vérifier si ce créneau est réservé
                            $estReserve = $reservations->contains(fn($rdv) =>
                                optional($rdv->horaire_debut)->format('H:i') === Carbon::parse($interval->start)->format('H:i') &&
                                optional($rdv->horaire_fin)->format('H:i') === Carbon::parse($interval->end)->format('H:i') &&
                                $rdv->id !== $rendezVous->id // Exclure le rendez-vous en cours d'édition
                            );

                            // Vérifier si le créneau actuel correspond au rendez-vous en cours d'édition
                            $horaireDebut = optional($rendezVous->horaire_debut)->format('H:i');
                            $horaireFin = optional($rendezVous->horaire_fin)->format('H:i');
                            $estSelectionne = ($horaireDebut && $horaireFin && ($horaireDebut . '-' . $horaireFin) == $creneau);
                        @endphp

                        <option value="{{ $creneau }}"
                            {{ $estSelectionne ? 'selected' : '' }}
                            {{ $estReserve && !$estSelectionne ? 'disabled' : '' }}>
                            {{ $creneau }} @if ($estReserve) (Réservé) @endif
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="d-flex justify-content-center">
                <button type="submit" class="btn btn-primary rounded-pill shadow px-5 py-2">
                    <i class="fas fa-save"></i> Enregistrer
                </button>
            </div>
        </form>
    </div>
@endsection
