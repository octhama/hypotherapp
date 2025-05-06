<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'nombre_personnes',
        'minutes', // Durée en minutes
        'prix_total',
    ];

    /**
     * Relation avec la facturation du client. Un client a une facturation (1-1)
     * @return HasOne
     */
    public function facturation(): HasOne
    {
        return $this->hasOne(Facturation::class, 'client_id');
    }

    /**
     * Relation avec les rendez-vous du client. Un client a plusieurs rendez-vous (1-N)
     * @return HasMany
     */
    public function rendezVous(): HasMany
    {
        return $this->hasMany(RendezVous::class, 'client_id');
    }

    protected static function boot(): void
    {
        parent::boot();

        // Quand un client est créé, créer une facturation associée avec les mêmes données initiales (pas de fuite de données)
        static::created(function ($client) {
            (new Facturation)->create([
                'client_id' => $client->id,
                'nombre_minutes' => $client->minutes, // Garder les minutes, pas d'erreur de conversion
                'montant' => $client->prix_total,
            ]);
        });

        // Quand un client est mis à jour, mettre à jour la facturation associée s'il y en a une (pas de fuite de données)
        static::updated(function ($client) {
            $facturation = (new Facturation)->where('client_id', $client->id)->first();
            if ($facturation) {
                $facturation->update([
                    'nombre_minutes' => $client->minutes, // Toujours en minutes pour éviter les erreurs de conversion
                    'montant' => $client->prix_total,
                ]);
            }
        });

        // Quand un client est supprimé, supprimer la facturation associée s'il y en a une (pas de fuite de données)
        static::deleted(function ($client) {
            $facturation = (new Facturation)->where('client_id', $client->id)->first();
            $facturation?->delete();
        });
    }
}
