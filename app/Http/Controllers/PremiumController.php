<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class PremiumController extends Controller
{
    //
    public function index()
    {
        return Inertia::render('music/achat/Premium',);
    }
}
