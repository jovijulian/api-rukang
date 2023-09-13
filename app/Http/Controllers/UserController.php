<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return view('pages.user.index');
    }

    public function insert()
    {
        return view('pages.user.insert');
    }

    public function inactiveUser() 
    {
        return view('pages.user.inactive-user');
    }
}
