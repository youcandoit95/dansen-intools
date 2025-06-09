<?php

namespace App\Http\Controllers;

use App\Models\Inbound;
use Illuminate\Http\Request;

class InboundController extends Controller
{
    public function index()
    {
        $data = Inbound::with('product')->latest()->get();
        return view('inbound.index', compact('data'));
    }

    public function create()
    {
        return view('inbound.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required',
            'tipe' => 'required|in:1,2,3,4',
            'qty' => 'nullable|integer',
            'berat' => 'nullable|numeric',
            'catatan' => 'nullable|string'
        ]);

        $inbound = Inbound::create($validated);
        $inbound->qr_code = '' . $inbound->product_id . '-' . $inbound->id;
        $inbound->save();

        return redirect()->route('inbound.index')->with('success', 'Inbound berhasil ditambahkan.');
    }

    public function show(Inbound $inbound)
    {
        return view('inbound.show', compact('inbound'));
    }

    public function edit(Inbound $inbound)
    {
        return view('inbound.edit', compact('inbound'));
    }

    public function update(Request $request, Inbound $inbound)
    {
        $validated = $request->validate([
            'tipe' => 'required|in:1,2,3,4',
            'qty' => 'nullable|integer',
            'berat' => 'nullable|numeric',
            'catatan' => 'nullable|string',
        ]);

        $inbound->update($validated);
        return redirect()->route('inbound.index')->with('success', 'Inbound berhasil diupdate.');
    }

    public function destroy(Inbound $inbound)
    {
        $inbound->update([
            'destroy' => true,
            'destroyed_by' => session('user_id'),
            'destroyed_at' => now(),
        ]);

        return back()->with('success', 'Barang inbound ditandai sebagai dihancurkan.');
    }
}
