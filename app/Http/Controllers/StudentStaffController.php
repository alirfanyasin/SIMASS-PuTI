<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StudentStaffController extends Controller
{
    public function index()
    {
        return view('pages.app.student-staff');
    }
}
