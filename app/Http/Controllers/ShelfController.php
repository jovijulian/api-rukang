<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ShelfController extends Controller
{
    public function index()
    {
        return view('pages.shelf.index');
    }

    public function insert()
    {
        return view('pages.shelf.insert');
    }

    public function edit($id)
    {
        return view('pages.shelf.edit', ['id' => $id]);
    }
}
