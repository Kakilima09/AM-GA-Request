<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::with('company')->paginate(10);
        return view('master.department.index', compact('departments'));
    }

    public function create()
    {
        $companies = Company::all();
        return view('master.department.create', compact('companies'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:departments,code',
            'description' => 'nullable|string',
        ]);

        Department::create($validated);

        return redirect()->route('departments.index')
            ->with('success', 'Departemen berhasil ditambahkan.');
    }

    public function show(Department $department)
    {
        $department->load('company');
        return view('master.department.show', compact('department'));
    }

    public function edit(Department $department)
    {
        $companies = Company::all();
        return view('master.department.edit', compact('department', 'companies'));
    }

    public function update(Request $request, Department $department)
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'name' => 'required|string|max:255',
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('departments', 'code')->ignore($department->id),
            ],
            'description' => 'nullable|string',
        ]);

        $department->update($validated);

        return redirect()->route('departments.index')
            ->with('success', 'Departemen berhasil diperbarui.');
    }

    public function destroy(Department $department)
    {
        // Cek apakah ada user yang terkait
        if ($department->users()->count() > 0) {
            return back()->withErrors(['error' => 'Departemen tidak dapat dihapus karena masih memiliki user terkait.']);
        }

        $department->delete();
        return redirect()->route('departments.index')
            ->with('success', 'Departemen berhasil dihapus.');
    }
}
