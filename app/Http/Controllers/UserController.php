<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function userNotVerify() {
        return view('pages.user.index');
    }
}
