<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Facturation;
use App\Models\Poney;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

class RapportController extends Controller
{
    public function index(): View|Factory|Application
    {
        // Données pour le graphique des clients (heures de réservation)
        $clients = Client::all();
        $clientsLabels = $clients->pluck('nom');
        $clientsData = $clients->pluck('minutes');

        // Données pour le graphique des facturations (montant par client)
        $facturations = Facturation::all();
        $factureLabels = $facturations->map(fn($fact) => $fact->client->nom ?? 'Inconnu');
        $factureData = $facturations->pluck('montant');

        // Données pour le graphique des poneys (heures de travail validées)
        $poneys = Poney::all();
        $poneyLabels = $poneys->pluck('nom');
        $poneyData = $poneys->pluck('heures_travail_validee');

        return view('rapports.index', compact('clientsLabels', 'clientsData', 'factureLabels', 'factureData', 'poneyLabels', 'poneyData'));
    }
}
