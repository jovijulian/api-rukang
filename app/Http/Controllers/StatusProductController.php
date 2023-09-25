<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StatusProductController extends Controller
{
    public function index()
    {
        return view('pages.status-product.index');
    }

    public function insert()
    {
        return view('pages.status-product.insert');
    }

    public function edit($id)
    {
        return view('pages.status-product.edit', ['id' => $id]);
    }
}
