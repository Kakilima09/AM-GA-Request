<?php

namespace App\Http\Controllers;

use App\Models\Bast;
use App\Models\BastItemFa;
use App\Models\BastItemJasa;
use App\Models\Company;
use App\Models\Department;
use App\Http\Requests\BastRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class BastController extends Controller
{
    /**
     * Display a listing of BAST.
     */
    public function index()
    {
        $basts = Bast::with(['user', 'company', 'department'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('bast.index', compact('basts'));
    }

    /**
     * Show the form for creating a new BAST.
     */
    public function create()
    {
        $companies = Company::all();
        $departments = Department::all();
        return view('bast.create', compact('companies', 'departments'));
    }

    /**
     * Store a newly created BAST in storage.
     */
    public function store(BastRequest $request)
    {
        $validated = $request->validated();
        $validated['user_id'] = Auth::id();

        // Simpan BAST
        $bast = Bast::create($validated);

        // Simpan item FA jika ada
        if ($request->has('items_fa') && is_array($request->items_fa)) {
            foreach ($request->items_fa as $item) {
                $bast->itemsFa()->create($item);
            }
        }

        // Simpan item Jasa jika ada
        if ($request->has('items_jasa') && is_array($request->items_jasa)) {
            foreach ($request->items_jasa as $item) {
                $bast->itemsJasa()->create($item);
            }
        }

        return redirect()->route('bast.show', $bast)
            ->with('success', 'BAST berhasil dibuat.');
    }

    /**
     * Display the specified BAST.
     */
    public function show(Bast $bast)
    {
        $bast->load(['itemsFa', 'itemsJasa', 'company', 'department', 'user']);
        return view('bast.show', compact('bast'));
    }

    /**
     * Show the form for editing the specified BAST.
     */
    public function edit(Bast $bast)
    {
        $companies = Company::all();
        $departments = Department::all();
        $bast->load(['itemsFa', 'itemsJasa']);
        return view('bast.edit', compact('bast', 'companies', 'departments'));
    }

    /**
     * Update the specified BAST in storage.
     */
    public function update(BastRequest $request, Bast $bast)
    {
        $validated = $request->validated();
        $bast->update($validated);

        // Hapus item lama
        $bast->itemsFa()->delete();
        $bast->itemsJasa()->delete();

        // Simpan item baru
        if ($request->has('items_fa') && is_array($request->items_fa)) {
            foreach ($request->items_fa as $item) {
                $bast->itemsFa()->create($item);
            }
        }
        if ($request->has('items_jasa') && is_array($request->items_jasa)) {
            foreach ($request->items_jasa as $item) {
                $bast->itemsJasa()->create($item);
            }
        }

        return redirect()->route('bast.show', $bast)
            ->with('success', 'BAST berhasil diperbarui.');
    }

    /**
     * Remove the specified BAST from storage.
     */
    public function destroy(Bast $bast)
    {
        $bast->itemsFa()->delete();
        $bast->itemsJasa()->delete();
        $bast->delete();

        return redirect()->route('bast.index')
            ->with('success', 'BAST berhasil dihapus.');
    }

    /**
     * Save digital signature from canvas.
     */
    public function saveSignature(Request $request, Bast $bast)
    {
        $request->validate([
            'field' => 'required|in:ttd_diserahkan,ttd_diterima,ttd_mengetahui,ttd_menyetujui',
            'signature' => 'required|string', // base64 image
        ]);

        $bast->update([$request->field => $request->signature]);

        return response()->json(['success' => true]);
    }

    /**
     * Export BAST to PDF.
     */
    public function exportPdf(Bast $bast)
    {
        $bast->load(['itemsFa', 'itemsJasa', 'company', 'department', 'user']);

        if (! class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            Log::warning('dompdf tidak terpasang (barryvdh/laravel-dompdf).');
            return back()->with('error', 'Fitur ekspor PDF belum tersedia. Jalankan `composer require barryvdh/laravel-dompdf`.');
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('bast.pdf', compact('bast'));
        return $pdf->download('BAST-'.$bast->id.'.pdf');
    }
}
