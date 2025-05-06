<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show(): string
    {
        //$user = Auth::user();
        //return view('profile.show', compact('user'));
        return "Page des rapports en cours de développement.";

    }
}
