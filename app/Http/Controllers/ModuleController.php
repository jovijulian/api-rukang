<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ModuleController extends Controller
{
    public function index()
    {
        return view('pages.module.index');
    }

    public function insert()
    {
        return view('pages.module.insert');
    }

    public function edit($id)
    {
        return view('pages.module.edit', ['id' => $id]);
    }
}
