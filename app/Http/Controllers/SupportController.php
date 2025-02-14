<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;

class SupportController extends Controller
{
    public function index(): View|Factory|Application
    {
        return view('support.index'); // Créez une vue resources/views/support/index.blade.php
    }

}
