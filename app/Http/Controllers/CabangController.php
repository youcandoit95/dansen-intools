<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cabang;

class CabangController extends Controller
{
    public function index()
    {
        $activeMenu = 'cabang';

        // Ambil semua data cabang yang belum di-soft delete
        $cabangs = Cabang::latest()->get();

        return view('cabang.cabang-index', compact('activeMenu', 'cabangs'));
    }

    public function create()
    {
        $activeMenu = 'cabang';

        return view('cabang.create', compact('activeMenu'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_cabang' => 'required|string|max:100',
            'alamat'      => 'required|string',
            'telepon'     => 'required|string|max:20',
            'nama_pic'    => 'required|string|max:100',
            'status'      => 'required|boolean',
        ]);

        Cabang::create($request->all());

        return redirect()->route('cabang.index')->with('success', 'Cabang berhasil ditambahkan.');
    }
}
