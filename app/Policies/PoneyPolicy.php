<?php

// app/Policies/PoneyPolicy.php
namespace App\Policies;

use App\Models\Poney;
use App\Models\User;

class PoneyPolicy
{
    /**
     * Détermine si l'utilisateur peut voir tous les poneys.
     */
    public function viewAny(User $user): bool
    {
        return true; // Les employés peuvent voir la liste des poneys
    }

    /**
     * Détermine si l'utilisateur peut voir un poney spécifique.
     */
    public function view(User $user, Poney $poney): bool
    {
        return true; // Les employés peuvent voir les détails d'un poney
    }

    /**
     * Détermine si l'utilisateur peut créer un poney.
     */
    public function create(User $user): bool
    {
        return $user->role === 'admin'; // Seul un administrateur peut créer un poney
    }

    /**
     * Détermine si l'utilisateur peut modifier un poney.
     */
    public function update(User $user, Poney $poney): bool
    {
        return $user->role === 'admin'; // Seul un administrateur peut modifier un poney
    }

    /**
     * Détermine si l'utilisateur peut supprimer un poney.
     */
    public function delete(User $user, Poney $poney): bool
    {
        return $user->role === 'admin'; // Seul un administrateur peut supprimer un poney
    }
}
