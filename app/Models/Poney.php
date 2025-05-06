<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Poney extends Model
{
    use HasFactory;

    protected $table = 'poneys';

    // Champs modifiables via formulaire
    protected $fillable = ['nom', 'heures_travail_validee', 'disponible'];

}






