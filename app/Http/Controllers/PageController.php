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
}
