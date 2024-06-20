<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        // $user = User::with(['work_unit','score'])->find(5);
        // dd($user);
        return view('pages.user.index');
    }
}
