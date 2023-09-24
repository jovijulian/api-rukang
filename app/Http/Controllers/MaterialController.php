<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MaterialController extends Controller
{
    public function index()
    {
        return view('pages.material.index');
    }

    public function detail($id)
    {
        return view('pages.material.detail', ['id' => $id]);
    }

    public function updateStatus($id)
    {
        return view('pages.material.update-status', ['id' => $id]);
    }

    public function insert()
    {
        return view('pages.material.insert');
    }

    public function edit($id)
    {
        return view('pages.material.edit', ['id' => $id]);
    }
    public function editLocation($id)
    {
        return view('pages.material.update-location', ['id' => $id]);
    }
}
