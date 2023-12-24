<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TalentController extends Controller
{
    public function index()
    {
        return view('pages.talent.index');
    }

    public function progress()
    {
        return view('pages.talent.progress');
    }

    public function insert()
    {
        return view('pages.talent.insert');
    }

    public function edit($id)
    {
        return view('pages.talent.edit', ['id' => $id]);
    }

    public function updateImage($id)
    {
        return view('pages.talent.update-image', ['id' => $id]);
    }
}