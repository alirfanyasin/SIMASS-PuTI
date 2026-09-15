<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class PortalController extends Controller
{
    public function index(): View
    {
        return view('pages.portal.index');
    }
}
