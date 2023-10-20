<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ModuleComplete extends Controller
{
    public function index()
    {
        return view('pages.module-complete.index');
    }

    public function insert()
    {
        return view('pages.module-complete.insert');
    }

    public function edit($id)
    {
        return view('pages.module-complete.edit', ['id' => $id]);
    }
}
