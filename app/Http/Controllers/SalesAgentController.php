<?php

namespace App\Http\Controllers;

use App\Models\SalesAgent;
use App\Models\Domisili;
use Illuminate\Http\Request;

class SalesAgentController extends Controller
{
    /**
     * Tampilkan daftar sales agent.
     */
    public function index()
    {
        $salesAgents = SalesAgent::with('domisiliRef')->latest()->get();

        return view('sales_agent.sa-index', compact('salesAgents'));
    }


    /**
     * Form tambah sales agent.
     */
    public function create()
    {
        $domisiliList = Domisili::orderBy('nama')->pluck('nama', 'id');

        return view('sales_agent.sa-create', compact('domisiliList'));
    }

    /**
     * Simpan sales agent baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama'     => 'required|string|max:100',
            'telepon'  => 'required|string|max:20',
            'email'    => 'nullable|email',
            'domisili' => 'required|exists:domisili,id',
        ]);

        SalesAgent::create([
            'nama'     => $request->nama,
            'telepon'  => $request->telepon,
            'email'    => $request->email,
            'domisili' => $request->domisili,
        ]);

        return redirect()->route('sales-agent.index')
            ->with('success', 'Sales Agent berhasil ditambahkan.');
    }

    /**
     * Form edit sales agent.
     */
    public function edit(SalesAgent $salesAgent)
    {
        $domisiliList = Domisili::orderBy('nama')->pluck('nama', 'id');

        return view('sales_agent.sa-edit', compact('salesAgent', 'domisiliList'));
    }

    /**
     * Update data sales agent.
     */
    public function update(Request $request, SalesAgent $salesAgent)
    {
        $request->validate([
            'nama'     => 'required|string|max:100',
            'telepon'  => 'required|string|max:20',
            'email'    => 'nullable|email|unique:sales_agents,email,' . $salesAgent->id,
            'domisili' => 'required|exists:domisili,id',
        ]);

        $salesAgent->update([
            'nama'     => $request->nama,
            'telepon'  => $request->telepon,
            'email'    => $request->email,
            'domisili' => $request->domisili,
        ]);

        return redirect()->route('sales-agent.edit', $salesAgent->id)
            ->with('success', 'Sales Agent berhasil diperbarui.');
    }

    /**
     * Hapus data sales agent.
     */
    public function destroy(SalesAgent $salesAgent)
    {
        $salesAgent->delete();

        return redirect()->route('sales-agent.index')
            ->with('success', 'Sales Agent berhasil dihapus.');
    }
}
