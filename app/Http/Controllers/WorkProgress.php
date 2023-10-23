<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WorkProgress extends Controller
{
    public function index()
    {
        return view('pages.work-progress.index');
    }

    public function insert()
    {
        return view('pages.work-progress.insert');
    }

    public function edit($id)
    {
        return view('pages.work-progress.edit', ['id' => $id]);
    }
    public function updateImage($id)
    {
        return view('pages.work-progress.update-image', ['id' => $id]);
    }
}
