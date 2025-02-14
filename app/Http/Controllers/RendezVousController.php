<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Poney;
use App\Models\RendezVous;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RendezVousController extends Controller
{
    public function index(): View|Factory|Application
    {
        $rendezVous = RendezVous::with(['client', 'poneys'])->get();
        return view('rendez-vous.index', compact('rendezVous'));
    }


    public function create(): View|Factory|Application
    {
        $clients = Client::all();
        $poneys = (new Poney)->where('disponible', true)->get();
        $disponibilites = $this->getDisponibilites();

        // Récupérer les créneaux déjà réservés
        $reservations = (new RendezVous)->select('horaire_debut', 'horaire_fin')->get()->map(function ($rdv) {
            return (object) [
                'horaire_debut' => $rdv->horaire_debut ? Carbon::parse($rdv->horaire_debut) : null,
                'horaire_fin' => $rdv->horaire_fin ? Carbon::parse($rdv->horaire_fin) : null,
            ];
        });

        // Vérifier si des poneys sont disponibles
        $aucunPoneyDisponible = $poneys->isEmpty();

        return view('rendez-vous.create', compact('clients', 'poneys', 'disponibilites', 'reservations', 'aucunPoneyDisponible'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $validated = $request->validate([
            'creneaux' => 'required|string',
        ]);

        [$horaireDebut, $horaireFin] = explode('-', $validated['creneaux']);
        $horaireDebut = Carbon::createFromFormat('H:i', $horaireDebut);
        $horaireFin = Carbon::createFromFormat('H:i', $horaireFin);

        $rendezVous = (new RendezVous)->findOrFail($id);
        $rendezVous->update([
            'horaire_debut' => $horaireDebut,
            'horaire_fin' => $horaireFin,
        ]);

        return redirect()->route('rendez-vous.index')->with('success', 'Plage horaire mise à jour avec succès.');
    }

    // Supprimer un rendez-vous
    public function destroy($id): RedirectResponse
    {
        DB::transaction(function () use ($id) {
            $rendezVous = (new RendezVous)->findOrFail($id);

            // Réinitialiser la disponibilité des poneys avant de supprimer le rendez-vous
            $rendezVous->poneys()->update(['disponible' => true]);

            // Supprimer le rendez-vous
            $rendezVous->delete();
        });

        return redirect()->route('rendez-vous.index')->with('success', 'Rendez-vous supprimé avec succès.');
    }

    public function getDisponibilites(): array
    {
        $disponibilites = [];
        $horaires = [
            ['start' => '10:00', 'end' => '12:00'],
            ['start' => '14:00', 'end' => '16:30'],
        ];

        foreach ($horaires as $plage) {
            $start = Carbon::createFromFormat('H:i', $plage['start']);
            $end = Carbon::createFromFormat('H:i', $plage['end']);

            while ($start->lt($end)) {
                $intervalEnd = (clone $start)->addMinutes(20);
                if ($intervalEnd->gt($end)) {
                    break;
                }

                $disponibilites[] = (object)[
                    'start' => clone $start,  // ✅ Assure que ce sont des objets Carbon
                    'end' => clone $intervalEnd
                ];

                $start->addMinutes(20);
            }
        }

        return $disponibilites;
    }

    public function edit($id): View|Factory|Application
    {
        $rendezVous = RendezVous::with(['client.rendezVous'])->findOrFail($id);
        $clients = Client::all();
        $poneys = Poney::all();
        $disponibilites = $this->getDisponibilites();

        // Récupérer les créneaux réservés pour la journée en cours
        $reservations = RendezVous::whereDate('horaire_debut', $rendezVous->horaire_debut->toDateString())
            ->where('id', '!=', $rendezVous->id) // Exclure le rendez-vous en cours d'édition
            ->get(['horaire_debut', 'horaire_fin']);

        return view('rendez-vous.edit', compact('rendezVous', 'clients', 'poneys', 'disponibilites', 'reservations'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'nombre_personnes' => 'required|integer|min:1',
            'creneaux' => 'required|string',
            'poneys' => 'array',
            'poneys.*' => 'nullable|exists:poneys,id',
        ]);

        [$horaireDebut, $horaireFin] = explode('-', $validated['creneaux']);
        $horaireDebut = Carbon::createFromFormat('H:i', $horaireDebut);
        $horaireFin = Carbon::createFromFormat('H:i', $horaireFin);

        DB::transaction(function () use ($validated, $horaireDebut, $horaireFin) {
            $rendezVous = (new RendezVous)->create([
                'client_id' => $validated['client_id'],
                'horaire_debut' => $horaireDebut,
                'horaire_fin' => $horaireFin,
                'nombre_personnes' => $validated['nombre_personnes'],
            ]);

            if (!empty($validated['poneys'])) {
                $rendezVous->poneys()->attach($validated['poneys']);
                (new Poney)->whereIn('id', $validated['poneys'])->update(['disponible' => false]);
            }
        });

        return redirect()->route('rendez-vous.index')->with('success', 'Rendez-vous créé avec succès.');
    }
}
