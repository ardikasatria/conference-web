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

    public function committee(Request $request)
    {
        // Serve Committee page
        return Inertia::render('Committee');
    }

    public function schedule(Request $request)
    {
        // Serve Schedule page
        return Inertia::render('Schedule');
    }

    public function information(Request $request)
    {
        // Serve Information/FAQ page
        return Inertia::render('FaqPage');
    }

    public function program(Request $request)
    {
        // Serve Program page
        return Inertia::render('Program');
    }

    public function author(Request $request)
    {
        // Serve Author page
        return Inertia::render('Author');
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