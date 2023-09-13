<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function index()
    {
        return view('pages.group.index');
    }

    public function insert()
    {
        return view('pages.group.insert');
    }

    public function edit($id)
    {
        return view('pages.group.edit', ['id' => $id]);
    }
}
