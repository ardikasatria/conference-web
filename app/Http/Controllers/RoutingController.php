<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RoutingController extends Controller
{

    public function index(Request $request)
    {
        // Serve Inertia.js Home page (public landing page)
        return Inertia::render('Home');
    }

    public function root(Request $request, $first)
    {
        return Inertia::render('Home');
    }

    public function secondLevel(Request $request, $first, $second)
    {
        return Inertia::render('Home');
    }

    public function thirdLevel(Request $request, $first, $second, $third)
    {
        return Inertia::render('Home');
    }
}