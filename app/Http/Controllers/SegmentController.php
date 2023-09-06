<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SegmentController extends Controller
{
    public function index() {
        return view('pages.segment.index');
    }

    public function insert() {
        return view('pages.segment.insert');
    }
    
    public function edit($id) {
        return view('pages.segment.edit', ['id' => $id]);
    }
}
