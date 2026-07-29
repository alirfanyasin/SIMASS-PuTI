<?php

namespace App\Http\Controllers\Ticketing;

use App\Http\Controllers\Controller;

class TicketingController extends Controller
{
    public function index()
    {
        return view('pages.ticketing.index');
    }

    public function create()
    {
        return view('pages.ticketing.create');
    }

    public function myTickets()
    {
        return view('pages.ticketing.my-tickets');
    }

    public function tasks()
    {
        return view('pages.ticketing.tasks');
    }

    public function history()
    {
        return view('pages.ticketing.history');
    }

    public function luna()
    {
        return view('pages.ticketing.luna');
    }
}
