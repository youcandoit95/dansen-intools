<?php

namespace App\Http\Controllers;

use App\Models\InvoiceAddon;
use Illuminate\Http\Request;

class InvoiceAddonController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'inv_id'   => 'required|exists:invoices,id',
            'nama'     => 'required|string|max:255',
            'qty'      => 'required|integer|min:1',
            'harga'    => 'required|integer|min:0',
            'catatan'  => 'nullable|string',
        ]);

        $validated['total'] = $validated['qty'] * $validated['harga'];
        $validated['created_by'] = session('user_id');

        InvoiceAddon::create($validated);

        return back()->with('success', 'Biaya tambahan berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        $addon = InvoiceAddon::findOrFail($id);

        $addon->delete();

        return back()->with('success', 'Biaya tambahan berhasil dihapus.');
    }
}
