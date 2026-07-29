<?php

namespace App\Http\Controllers\Presence;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return view('pages.presence.dashboard');
    }
}
