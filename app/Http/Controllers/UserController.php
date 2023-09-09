<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function inactiveUser() {
        return view('pages.user.index');
    }
}
