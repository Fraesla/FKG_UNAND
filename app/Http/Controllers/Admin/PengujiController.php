<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PengujiController extends Controller
{
    public function index()
    {
        return view('admin.penguji.index');
    }
}
