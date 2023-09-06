<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DescriptionController extends Controller
{
    public function index()
    {
        return view('pages.description.index');
    }

    public function insert()
    {
        return view('pages.description.insert');
    }

    public function edit($id)
    {
        return view('pages.description.edit', ['id' => $id]);
    }
}
