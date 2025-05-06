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
    /**
     * Afficher la liste des rendez-vous
     * @return View|Factory|Application
     */
    public function index(): View|Factory|Application
    {
        // Récupérer les rendez-vous avec les clients et les poneys associés à chaque rendez-vous pour l'affichage
        $rendezVous = RendezVous::with(['client', 'poneys'])->get();
        return view('rendez-vous.index', compact('rendezVous'));
    }


    /**
     * Afficher le formulaire de création d'un rendez-vous
     * @return View|Factory|Application
     */

    public function create(): View|Factory|Application
    {
        // Récupérer les clients et les poneys disponibles pour la demande de rendez-vous
        $clients = Client::all();
        $poneys = (new Poney)->where('disponible', true)->get();
        $disponibilites = $this->getDisponibilites();

        // Récupérer les créneaux déjà réservés pour la journée en cours (hors le rendez-vous en cours de création)
        $reservations = (new RendezVous)->select('horaire_debut', 'horaire_fin')->get()->map(function ($rdv) {
            return (object) [
                'horaire_debut' => $rdv->horaire_debut ? Carbon::parse($rdv->horaire_debut) : null,
                'horaire_fin' => $rdv->horaire_fin ? Carbon::parse($rdv->horaire_fin) : null,
            ];
        });

        // Vérifier si des poneys sont disponibles pour la demande de rendez-vous
        $aucunPoneyDisponible = $poneys->isEmpty();

        return view('rendez-vous.create', compact('clients', 'poneys', 'disponibilites', 'reservations', 'aucunPoneyDisponible'));
    }

    /**
     * Mettre à jour le rendez-vous
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function update(Request $request, $id): RedirectResponse
    {
        // Valider les données soumises par le formulaire de mise à jour de rendez-vous (créneaux horaires)
        $validated = $request->validate([
            'creneaux' => 'required|string',
        ]);

        // Mettre à jour la plage horaire du rendez-vous sélectionné par le client (créneau horaire)
        [$horaireDebut, $horaireFin] = explode('-', $validated['creneaux']);
        $horaireDebut = Carbon::createFromFormat('H:i', $horaireDebut);
        $horaireFin = Carbon::createFromFormat('H:i', $horaireFin);

        // Mettre à jour la plage horaire du rendez-vous sélectionné par le client (créneau horaire) dans la base de données
        $rendezVous = (new RendezVous)->findOrFail($id);
        $rendezVous->update([
            'horaire_debut' => $horaireDebut,
            'horaire_fin' => $horaireFin,
        ]);

        return redirect()->route('rendez-vous.index')->with('success', 'Plage horaire mise à jour avec succès.');
    }

    /**
     * Supprimer un rendez-vous
     * @param $id
     * @return RedirectResponse
     */
    public function destroy($id): RedirectResponse
    {
        // Supprimer un rendez-vous et réinitialiser la disponibilité des poneys associés à ce rendez-vous avant de le supprimer
        DB::transaction(function () use ($id) {
            $rendezVous = (new RendezVous)->findOrFail($id);

            // Réinitialiser la disponibilité des poneys avant de supprimer le rendez-vous
            $rendezVous->poneys()->update(['disponible' => true]);

            // Supprimer le rendez-vous
            $rendezVous->delete();
        });

        return redirect()->route('rendez-vous.index')->with('success', 'Rendez-vous supprimé avec succès.');
    }

    /**
     * Récupérer les créneaux disponibles pour la démande de rendez-vous et les poneys
     * @return array
     */
    public function getDisponibilites(): array
    {
        // Créer des créneaux de 20 minutes pour la journée en cours (10h00 - 12h00 et 14h00 - 16h30) pour les rendez-vous
        $disponibilites = [];
        $horaires = [
            ['start' => '10:00', 'end' => '12:00'],
            ['start' => '14:00', 'end' => '16:30'],
        ];

        // Créer des créneaux de 20 minutes pour chaque plage horaire définie ci-dessus (10h00 - 12h00 et 14h00 - 16h30)
        foreach ($horaires as $plage) {
            $start = Carbon::createFromFormat('H:i', $plage['start']);
            $end = Carbon::createFromFormat('H:i', $plage['end']);

            // Créer des créneaux de 20 minutes pour chaque plage horaire définie ci-dessus (10h00 - 12h00 et 14h00 - 16h30)
            while ($start->lt($end)) {
                $intervalEnd = (clone $start)->addMinutes(20);
                if ($intervalEnd->gt($end)) {
                    break;
                }

                $disponibilites[] = (object)[
                    'start' => clone $start,  // ✅ Assure que ce sont des objets Carbon
                    'end' => clone $intervalEnd // ✅ Assure que ce sont des objets Carbon
                ];

                $start->addMinutes(20); // Passer au créneau suivant de 20 minutes (10h00 - 10h20, 10h20 - 10h40, etc.)
            }
        }

        return $disponibilites;
    }

    /**
     * Afficher le formulaire d'édition d'un rendez-vous
     * @param $id
     * @return View|Factory|Application
     */
    public function edit($id): View|Factory|Application
    {
        // Récupérer le rendez-vous à éditer avec les clients et les poneys associés à ce rendez-vous
        $rendezVous = RendezVous::with(['client.rendezVous'])->findOrFail($id);
        $clients = Client::all();
        $poneys = Poney::all();
        $disponibilites = $this->getDisponibilites();

        // Récupérer les créneaux réservés pour la journée en cours (hors le rendez-vous en cours d'édition)
        $reservations = RendezVous::whereDate('horaire_debut', $rendezVous->horaire_debut->toDateString())
            ->where('id', '!=', $rendezVous->id) // Exclure le rendez-vous en cours d'édition
            ->get(['horaire_debut', 'horaire_fin']);

        return view('rendez-vous.edit', compact('rendezVous', 'clients', 'poneys', 'disponibilites', 'reservations'));
    }

    /**
     * Créer un rendez-vous
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        // Valider les données soumises par le formulaire de création de rendez-vous
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'nombre_personnes' => 'required|integer|min:1',
            'creneaux' => 'required|string',
            'poneys' => 'array',
            'poneys.*' => 'nullable|exists:poneys,id',
        ]);

        // Extraire les horaires de début et de fin du créneau sélectionné par le client
        [$horaireDebut, $horaireFin] = explode('-', $validated['creneaux']);
        $horaireDebut = Carbon::createFromFormat('H:i', $horaireDebut);
        $horaireFin = Carbon::createFromFormat('H:i', $horaireFin);

        // Créer un rendez-vous et attacher les poneys sélectionnés par le client s'il y en a une ou plusieurs sélectionnés parmi les poneys
        DB::transaction(function () use ($validated, $horaireDebut, $horaireFin) {
            $rendezVous = (new RendezVous)->create([
                'client_id' => $validated['client_id'],
                'horaire_debut' => $horaireDebut,
                'horaire_fin' => $horaireFin,
                'nombre_personnes' => $validated['nombre_personnes'],
            ]);

            // Mettre à jour la disponibilité des poneys sélectionnés par le client s'il y en a une ou plusieurs(selectionnées) parmi les poneys
            if (!empty($validated['poneys'])) {
                $rendezVous->poneys()->attach($validated['poneys']);
                (new Poney)->whereIn('id', $validated['poneys'])->update(['disponible' => false]);
            }
        });

        return redirect()->route('rendez-vous.index')->with('success', 'Rendez-vous créé avec succès.');
    }
}
