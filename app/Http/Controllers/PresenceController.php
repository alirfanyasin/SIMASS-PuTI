<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PresenceController extends Controller
{
    public function presence()
    {
        return view('pages.app.presence');
    }


    public function presenceList()
    {
        return view('pages.app.presence-list');
    }

    public function presenceHistory()
    {
        return view('pages.app.presence-history');
    }
}
