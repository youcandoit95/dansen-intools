<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\SalesAgent;
use App\Models\Domisili;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $activeMenu = 'customers';
        $customers = Customer::with(['salesAgent', 'domisiliRef'])->latest()->get();
        return view('customers.index', compact('activeMenu','customers'));
    }

    public function create()
    {
        $salesAgents = SalesAgent::all();
        $domisiliList = Domisili::all();
        return view('customers.create', compact('salesAgents', 'domisiliList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string',
            'sales_agent_id' => 'nullable|exists:sales_agents,id',
            'no_tlp' => 'required|string|max:50',
            'domisili' => 'required|exists:domisili,id',
            'alamat_lengkap' => 'required|string',
        ]);

        Customer::create($request->all());

        return redirect()->route('customers.index')->with('success', 'Customer berhasil ditambahkan.');
    }

    public function edit(Customer $customer)
    {
        $activeMenu = 'customers';
        $salesAgents = SalesAgent::all();
        $domisiliList = Domisili::all();
        return view('customers.edit', compact('activeMenu','customer', 'salesAgents', 'domisiliList'));
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'nama' => 'required|string',
            'sales_agent_id' => 'nullable|exists:sales_agents,id',
            'no_tlp' => 'required|string|max:50',
            'domisili' => 'required|exists:domisili,id',
            'alamat_lengkap' => 'required|string',
        ]);

        $customer->update($request->all());

        return redirect()->route('customers.index')->with('success', 'Customer berhasil diperbarui.');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()->route('customers.index')->with('success', 'Customer berhasil dihapus.');
    }
}
