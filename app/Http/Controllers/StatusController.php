<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StatusController extends Controller
{
    public function index()
    {
        return view('pages.status.index');
    }

    public function insert()
    {
        return view('pages.status.insert');
    }

    public function edit($id)
    {
        return view('pages.status.edit', ['id' => $id]);
    }
}
