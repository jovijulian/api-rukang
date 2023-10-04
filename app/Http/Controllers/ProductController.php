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

    public function addStatus($id)
    {
        return view('pages.product.add-status', ['id' => $id]);
    }

    public function insert()
    {
        return view('pages.product.insert');
    }

    public function edit($id)
    {
        return view('pages.product.edit', ['id' => $id]);
    }
    
    public function editLocation($id)
    {
        return view('pages.product.update-location', ['id' => $id]);
    }

    public function editStatus(Request $request)
    {
        $idProduct = $request->idProduct;
        $idStatus = $request->idStatus;

        return view('pages.product.edit-status', [
            'idProduct' => $idProduct,
            'idStatus' => $idStatus,
        ]);
    }
}
