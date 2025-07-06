<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\SalesAgent;
use App\Models\Domisili;
use App\Models\Company;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $activeMenu = 'customers';
        $customers = Customer::with(['salesAgent', 'domisiliRef'])->latest()->get();
        return view('customers.index', compact('activeMenu', 'customers'));
    }

    public function create()
    {
        $salesAgents = SalesAgent::all();
        $domisiliList = Domisili::all();
        $companies = Company::whereNull('deleted_at')
            ->where('blacklist', false)
            ->orderBy('nama')
            ->get();

        return view('customers.create', compact('salesAgents', 'domisiliList', 'companies'));
    }

    public function edit(Customer $customer)
    {
        $activeMenu = 'customers';
        $salesAgents = SalesAgent::all();
        $domisiliList = Domisili::all();
        $companies = Company::whereNull('deleted_at')
            ->where('blacklist', false)
            ->orderBy('nama')
            ->get();

        return view('customers.edit', compact('activeMenu', 'customer', 'salesAgents', 'domisiliList', 'companies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string',
            'sales_agent_id' => 'nullable|exists:sales_agents,id',
            'no_tlp' => 'required|string|max:50',
            'domisili' => 'required|exists:domisili,id',
            'alamat_lengkap' => 'required|string',
            'company_id' => 'nullable|exists:companies,id',
        ]);

        Customer::create($request->all());

        return redirect()->route('customers.index')->with('success', 'Customer berhasil ditambahkan.');
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'nama' => 'required|string',
            'sales_agent_id' => 'nullable|exists:sales_agents,id',
            'no_tlp' => 'required|string|max:50',
            'domisili' => 'required|exists:domisili,id',
            'alamat_lengkap' => 'required|string',
            'company_id' => 'nullable|exists:companies,id',
        ]);

        $customer->update($request->all());

        return redirect()->route('customers.index')->with('success', 'Customer berhasil diperbarui.');
    }


    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()->route('customers.index')->with('success', 'Customer berhasil dihapus.');
    }

    public function blacklist(Request $request, Customer $customer)
    {
        $request->validate([
            'alasan_blacklist' => 'required|string|max:255',
        ]);

        $customer->update([
            'is_blacklisted' => true,
            'alasan_blacklist' => $request->alasan_blacklist,
        ]);

        return redirect()->back()->with('success', 'Customer berhasil diblacklist.');
    }

    public function whitelist(Customer $customer)
    {
        $customer->update([
            'is_blacklisted' => false,
            'alasan_blacklist' => null,
        ]);

        return redirect()->route('customers.index')->with('success', 'Customer berhasil di-whitelist.');
    }
}
