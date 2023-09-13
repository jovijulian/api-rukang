<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ShippingController extends Controller
{
    public function index()
    {
        return view('pages.shipping.index');
    }

    public function insert()
    {
        return view('pages.shipping.insert');
    }

    public function edit($id)
    {
        return view('pages.shipping.edit', ['id' => $id]);
    }
}
