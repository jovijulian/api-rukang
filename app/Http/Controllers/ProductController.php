<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return view('pages.product.index');
    }

    public function insert()
    {
        return view('pages.product.insert');
    }

    public function edit($id)
    {
        return view('pages.product.edit', ['id' => $id]);
    }
}
