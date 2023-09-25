<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ToolController extends Controller
{
    public function index()
    {
        return view('pages.tool.index');
    }

    public function detail($id)
    {
        return view('pages.tool.detail', ['id' => $id]);
    }

    public function updateStatus($id)
    {
        return view('pages.tool.update-status', ['id' => $id]);
    }

    public function insert()
    {
        return view('pages.tool.insert');
    }

    public function edit($id)
    {
        return view('pages.tool.edit', ['id' => $id]);
    }

    public function editLocation($id)
    {
        return view('pages.tool.update-location', ['id' => $id]);
    }

    public function editStatus(Request $request)
    {
        $idProduct = $request->idProduct;
        $idStatus = $request->idStatus;

        return view('pages.tool.edit-status', [
            'idProduct' => $idProduct,
            'idStatus' => $idStatus,
        ]);
    }
}
