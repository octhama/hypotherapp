<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
     * Relation avec les rendez-vous du client.
     *
     * @return HasMany
     */
    public function rendezVous(): HasMany
    {
        return $this->hasMany(RendezVous::class, 'client_id');
    }

    protected static function boot(): void
    {
        parent::boot();

        // Quand un client est créé, créer une facturation associée avec les mêmes données initiales
        static::created(function ($client) {
            (new Facturation)->create([
                'client_id' => $client->id,
                'nombre_minutes' => $client->minutes, // Garder les minutes, pas d'erreur de conversion
                'montant' => $client->prix_total,
            ]);
        });

        // Quand un client est mis à jour, mettre à jour la facturation associée
        static::updated(function ($client) {
            $facturation = (new Facturation)->where('client_id', $client->id)->first();
            if ($facturation) {
                $facturation->update([
                    'nombre_minutes' => $client->minutes, // Toujours en minutes
                    'montant' => $client->prix_total,
                ]);
            }
        });

        // Quand un client est supprimé, supprimer la facturation associée
        static::deleted(function ($client) {
            $facturation = (new Facturation)->where('client_id', $client->id)->first();
            if ($facturation) {
                $facturation->delete();
            }
        });
    }
}
