<?php

// Modèle RendezVous
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\HigherOrderCollectionProxy;

class RendezVous extends Model
{
    use HasFactory;

    /**
     * @var HigherOrderCollectionProxy|mixed
     */
    protected $table = 'rendez_vous';

    protected $fillable = [
        'client_id',
        'horaire_debut',
        'horaire_fin',
        'nombre_personnes',
        'confirmed',
    ];

    protected $casts = [
        'horaire_debut' => 'datetime',
        'horaire_fin' => 'datetime',
    ];
    // Relation avec le modèle Client
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
    // Relation avec les poneys
    public function poneys(): BelongsToMany
    {
        return $this->belongsToMany(Poney::class, 'rendez_vous_poneys', 'rendez_vous_id', 'poney_id');
    }
}
