<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CabangController extends Controller
{
    public function index()
    {
        // Data dummy bisa diganti nanti dengan DB
        $activeMenu = 'cabang';

        return view('cabang.cabang-index', compact('activeMenu'));
    }
}
