<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        return view('pages.category.index');
    }

    public function insert()
    {
        return view('pages.category.insert');
    }

    public function edit($id)
    {
        return view('pages.category.edit', ['id' => $id]);
    }
}
