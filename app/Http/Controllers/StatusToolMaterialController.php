<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StatusToolMaterialController extends Controller
{
    public function index()
    {
        return view('pages.status-tool-material.index');
    }

    public function insert()
    {
        return view('pages.status-tool-material.insert');
    }

    public function edit($id)
    {
        return view('pages.status-tool-material.edit', ['id' => $id]);
    }
}
