<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::paginate(10);
        return view('master.company.index', compact('companies'));
    }

    public function create()
    {
        return view('master.company.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:companies,code',
        ]);

        Company::create($validated);

        return redirect()->route('companies.index')
            ->with('success', 'Perusahaan berhasil ditambahkan.');
    }

    public function show(Company $company)
    {
        return view('master.company.show', compact('company'));
    }

    public function edit(Company $company)
    {
        return view('master.company.edit', compact('company'));
    }

    public function update(Request $request, Company $company)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('companies', 'code')->ignore($company->id),
            ],
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
        ]);

        $company->update($validated);

        return redirect()->route('companies.index')
            ->with('success', 'Perusahaan berhasil diperbarui.');
    }

    public function destroy(Company $company)
    {
        // Cek apakah ada departemen atau user yang terkait
        if ($company->departments()->count() > 0 || $company->users()->count() > 0) {
            return back()->withErrors(['error' => 'Perusahaan tidak dapat dihapus karena masih memiliki data terkait.']);
        }

        $company->delete();
        return redirect()->route('companies.index')
            ->with('success', 'Perusahaan berhasil dihapus.');
    }
}
