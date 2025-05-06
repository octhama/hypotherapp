<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RendezVousPoney extends Model
{
    protected $table = 'rendez_vous_poneys';
    public $timestamps = false;
    protected $fillable = ['rendez_vous_id', 'poney_id'];

}
