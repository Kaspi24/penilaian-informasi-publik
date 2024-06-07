<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WorkUnitController extends Controller
{
    public function index()
    {
        return view('pages.work-unit.index');
    }
}
