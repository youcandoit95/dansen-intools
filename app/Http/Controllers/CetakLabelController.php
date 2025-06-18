<?php

namespace App\Http\Controllers;

use App\Models\Stok;
use Illuminate\Http\Request;

class CetakLabelController extends Controller
{
    public function show(Request $request)
    {
        $nama = $request->query('nama', 'PRODUK');
        $barcode = $request->query('barcode', '-');

        return view('cetak.label', compact('nama', 'barcode'));
    }

    public function markAsPrinted(Request $request)
    {
        $stok = Stok::where('barcode_stok', $request->barcode)->first();

        if ($stok && !$stok->barcode_printed) {
            $stok->barcode_printed = true;
            $stok->save();
        }

        return response()->json(['status' => 'success']);
    }
}
