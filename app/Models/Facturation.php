<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Facturation extends Model {

    use HasFactory;

    protected $fillable = ['client_id', 'nombre_minutes', 'montant'];

    /**
     * Relation avec le client. Une facturation appartient à un client (1-N)
     * @return BelongsTo
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

}
