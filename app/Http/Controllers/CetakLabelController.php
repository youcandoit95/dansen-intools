<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CetakLabelController extends Controller
{
    public function show(Request $request)
    {
        $nama = $request->query('nama', 'PRODUK');
        $barcode = $request->query('barcode', '-');

        return view('cetak.label', compact('nama', 'barcode'));
    }
}
