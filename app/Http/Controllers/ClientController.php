<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Facturation;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    use AuthorizesRequests;
    public function index(): View|Factory|Application
    {
        $clients = (new Client)->paginate(5); // Récupère les clients avec pagination
        return view('clients.index', compact('clients')); // Retourne une vue avec les clients
    }

    public function show($id): View|Factory|Application
    {
        $client = (new Client)->findOrFail($id); // Récupère le client ou renvoie une erreur 404
        return view('clients.show', compact('client'));
    }

    public function edit($id)
    {
        $client = Client::findOrFail($id); // Récupère le client ou retourne une erreur 404
        return view('clients.edit', compact('client'));
    }

    public function store(Request $request): RedirectResponse
    {
        // dd($request->all()); Afficher les données soumises

        // Nettoyer le prix_total en supprimant le symbole € et en convertissant en nombre
        $request->merge([
            'prix_total' => (float) str_replace('€', '', $request->prix_total),
        ]);

        // Validation des données
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'nombre_personnes' => 'required|integer|min:1',
            'duree' => 'required|integer|min:10|max:120', // Durée en minutes
            'prix_total' => 'required|numeric|min:0',
        ]);

        // Création du client avec les données soumises
        $client = Client::create([
            'nom' => $validated['nom'],
            'nombre_personnes' => $validated['nombre_personnes'],
            'minutes' => $validated['duree'], // Durée en minutes
            'prix_total' => $validated['prix_total'],
        ]);

        // dd($client);   Afficher l'objet client créé

        return redirect()->route('clients.index')->with('success', 'Client créé avec succès.');
    }

    public function update(Request $request, $id): RedirectResponse
    {
        // Nettoyer le prix_total en supprimant le symbole € et en convertissant en nombre
        $request->merge([
            'prix_total' => (float) str_replace('€', '', $request->prix_total),
        ]);

        // Validation des données
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'nombre_personnes' => 'required|integer|min:1',
            'minutes' => 'required|integer|min:10|max:120', // Durée en minutes
            'prix_total' => 'required|numeric|min:0',
        ]);

        // Mettre à jour le client
        $client = (new Client)->findOrFail($id);
        $client->update([
            'nom' => $validated['nom'],
            'nombre_personnes' => $validated['nombre_personnes'],
            'minutes' => $validated['minutes'],
            'prix_total' => $validated['prix_total'],
        ]);

        return redirect()->route('clients.index')->with('success', 'Client mis à jour avec succès.');
    }

    /**
     * @throws AuthorizationException
     */
    public function generateInvoice($id): Response
    {
        $client = Client::findOrFail($id);
        $this->authorize('generateInvoice', $client); // Vérifie l'autorisation

        $pdf = Pdf::loadView('clients.invoice', compact('client'));
        return $pdf->download('facture_' . $client->nom . '.pdf');
    }

    public function destroy(Client $client): RedirectResponse
    {
        if (Auth::user()->role === 'employee') {
            return redirect()->route('clients.index')->with('error', '⛔ Vous n\'êtes pas autorisé à supprimer des clients. Veuillez contacter l\'administrateur.');
        }

        $client->delete();
        return redirect()->route('clients.index')->with('success', 'Client supprimé avec succès.');
    }
}
