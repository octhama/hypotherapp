<?php

// app/Policies/ClientPolicy.php
namespace App\Policies;

use App\Models\Client;
use App\Models\User;

class ClientPolicy
{
    /**
     * Détermine si l'utilisateur peut voir tous les clients.
     */
    public function viewAny(User $user): bool
    {
        return true; // Les employés peuvent voir la liste des clients
    }

    /**
     * Détermine si l'utilisateur peut voir un client spécifique.
     */
    public function view(User $user, Client $client): bool
    {
        return true; // Les employés peuvent voir les détails d'un client
    }

    /**
     * Détermine si l'utilisateur peut créer un client.
     */
    public function create(User $user): bool
    {
        return $user->role === 'admin'; // Seul un administrateur peut créer un client
    }

    /**
     * Détermine si l'utilisateur peut modifier un client.
     */
    public function update(User $user, Client $client): bool
    {
        return $user->role === 'admin'; // Seul un administrateur peut modifier un client
    }

    /**
     * Détermine si l'utilisateur peut supprimer un client.
     */
    public function delete(User $user, Client $client): bool
    {
        return $user->role === 'admin'; // Seul un administrateur peut supprimer un client
    }

    /**
     * Détermine si l'utilisateur peut générer une facture.
     */
    public function generateInvoice(User $user, Client $client): bool
    {
        return in_array($user->role, ['admin', 'employee']); // Admin et employés peuvent générer des factures
    }
}
