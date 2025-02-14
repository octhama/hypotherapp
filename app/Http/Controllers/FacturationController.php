<?php

namespace App\Http\Controllers;

use App\Models\Facturation;
use App\Models\Client;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

class FacturationController extends Controller
{
    public function index(): View|Factory|Application
    {
        $facturations = Facturation::with('client')->orderByDesc('mois')->get();
        return view('facturation.index', compact('facturations'));
    }

    public function show($id): View|Factory|Application
    {
        $facturation = Facturation::with('client')->findOrFail($id);
        return view('facturation.show', compact('facturation'));
    }
}

