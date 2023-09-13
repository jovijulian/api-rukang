<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return view('pages.product.index');
    }

    public function detail($id)
    {
        return view('pages.product.detail', ['id' => $id]);
    }

    public function updateStatus($id)
    {
        return view('pages.product.update-status', ['id' => $id]);
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
