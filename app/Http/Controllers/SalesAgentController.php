<?php

namespace App\Http\Controllers;

use App\Models\SalesAgent;
use App\Models\Domisili;
use Illuminate\Http\Request;

class SalesAgentController extends Controller
{
    public function index()
    {
        $activeMenu = 'sales-agent';
        $salesAgents = SalesAgent::with('domisiliRef')->latest()->get();

        return view('sales_agent.sa-index', compact('salesAgents', 'activeMenu'));
    }

    public function create()
    {
        $activeMenu = 'sales-agent';
        $domisiliList = Domisili::orderBy('nama')->pluck('nama', 'id');

        return view('sales_agent.sa-create', compact('domisiliList', 'activeMenu'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'     => 'required|string|max:100',
            'telepon'  => 'required|string|max:20',
            'email'    => 'nullable|email',
            'domisili' => 'required|exists:domisili,id',
        ]);

        SalesAgent::create($request->only(['nama', 'telepon', 'email', 'domisili']));

        return redirect()->route('sales-agent.index')
            ->with('success', 'Sales Agent berhasil ditambahkan.');
    }

    public function edit(SalesAgent $salesAgent)
    {
        $activeMenu = 'sales-agent';
        $domisiliList = Domisili::orderBy('nama')->pluck('nama', 'id');

        return view('sales_agent.sa-edit', compact('salesAgent', 'domisiliList', 'activeMenu'));
    }

    public function update(Request $request, SalesAgent $salesAgent)
    {
        $request->validate([
            'nama'     => 'required|string|max:100',
            'telepon'  => 'required|string|max:20',
            'email'    => 'nullable|email|unique:sales_agents,email,' . $salesAgent->id,
            'domisili' => 'required|exists:domisili,id',
        ]);

        $salesAgent->update($request->only(['nama', 'telepon', 'email', 'domisili']));

        return redirect()->route('sales-agent.edit', $salesAgent->id)
            ->with('success', 'Sales Agent berhasil diperbarui.');
    }

    public function destroy(SalesAgent $salesAgent)
    {
        $salesAgent->delete();

        return redirect()->route('sales-agent.index')
            ->with('success', 'Sales Agent berhasil dihapus.');
    }
}
