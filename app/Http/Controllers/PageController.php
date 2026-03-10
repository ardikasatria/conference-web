<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

/**
 * Handles rendering of public-facing Inertia pages.
 * Single Responsibility: only renders public pages.
 */
class PageController extends Controller
{
    public function home(): Response
    {
        return Inertia::render('Home');
    }

    public function committee(): Response
    {
        return Inertia::render('Committee');
    }

    public function schedule(): Response
    {
        return Inertia::render('Schedule');
    }

    public function information(): Response
    {
        return Inertia::render('FaqPage');
    }

    public function program(): Response
    {
        return Inertia::render('Program');
    }

    public function author(): Response
    {
        return Inertia::render('Author');
    }
}
