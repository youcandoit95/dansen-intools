<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $activeMenu = 'suppliers';

        $suppliers = Supplier::orderBy('name')->get();
        return view('suppliers.index', compact('suppliers','activeMenu'));
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:100']);
        Supplier::create($request->only('name'));
        return redirect()->route('suppliers.index')->with('success', 'Supplier ditambahkan.');
    }

    public function edit(Supplier $supplier)
    {
        $activeMenu = 'suppliers';
        return view('suppliers.edit', compact('supplier','activeMenu'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $request->validate(['name' => 'required|string|max:100']);
        $supplier->update($request->only('name'));
        return redirect()->route('suppliers.index')->with('success', 'Supplier diperbarui.');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return back()->with('success', 'Supplier dihapus.');
    }
}
