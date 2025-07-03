<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Domisili;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function __construct()
    {
        view()->share('activeMenu', 'company');
    }

    public function index()
    {
        $companies = Company::latest()->get();
        $deletedCompanies = Company::onlyTrashed()->get();

        return view('company.index', compact('companies', 'deletedCompanies'));
    }

    public function create()
    {
        $domisili = Domisili::orderBy('nama')->get();
        return view('company.create', compact('domisili'));
    }

    public function edit(Company $company)
    {
        $domisili = Domisili::orderBy('nama')->get();
        return view('company.edit', compact('company', 'domisili'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'        => 'required|string|max:255',
            'domisili_id' => 'nullable|exists:domisili,id',
            'telepon'     => 'nullable|string|max:100',
            'email'       => 'nullable|email|max:255',
            'alamat'      => 'nullable|string',
        ]);

        $validated['created_by'] = session('user_id');

        Company::create($validated);

        return redirect()->route('company.index')->with('success', 'Data berhasil ditambahkan.');
    }

    public function update(Request $request, Company $company)
    {
        $validated = $request->validate([
            'nama'        => 'required|string|max:255',
            'domisili_id' => 'nullable|exists:domisili,id',
            'telepon'     => 'nullable|string|max:100',
            'email'       => 'nullable|email|max:255',
            'alamat'      => 'nullable|string',
        ]);

        $validated['updated_by'] = session('user_id');

        $company->update($validated);

        return redirect()->route('company.index')->with('success', 'Data berhasil diupdate.');
    }

    public function destroy(Company $company)
    {
        $company->update(['deleted_by' => session('user_id')]);
        $company->delete();
        return back()->with('success', 'Data berhasil dihapus.');
    }

    public function restore($id)
    {
        $company = Company::onlyTrashed()->findOrFail($id);
        $company->restore();
        return back()->with('success', 'Data berhasil direstore.');
    }

    public function blacklist($id, Request $request)
    {
        $request->validate(['alasan_blacklist' => 'required|string']);
        $company = Company::findOrFail($id);
        $company->update([
            'blacklist' => true,
            'alasan_blacklist' => $request->alasan_blacklist,
        ]);
        return back()->with('success', 'Perusahaan masuk blacklist.');
    }

    public function unblacklist($id)
    {
        $company = Company::findOrFail($id);
        $company->update([
            'blacklist' => false,
            'alasan_blacklist' => null,
        ]);
        return back()->with('success', 'Blacklist dibatalkan.');
    }
}
