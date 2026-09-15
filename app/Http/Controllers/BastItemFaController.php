<?php

namespace App\Http\Controllers;

use App\Models\BastItemFa;
use App\Models\Bast;
use Illuminate\Http\Request;

class BastItemFaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = BastItemFa::with('bast')->paginate(20);
        return view('bast-item-fa.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('bast-item-fa.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'bast_id' => 'required|exists:bast,id',
            'no_fa' => 'required|string|max:50',
            'merk_type_spesifikasi' => 'required|string|max:255',
            'qty' => 'required|integer|min:1',
            'kondisi' => 'required|in:baik,rusak',
            'keterangan' => 'nullable|string',
        ]);

        $item = BastItemFa::create($validated);
        return redirect()->route('bast.show', $item->bast_id)
            ->with('success', 'Item FA berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(BastItemFa $bastItemFa)
    {
        return view('bast-item-fa.show', compact('bastItemFa'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BastItemFa $bastItemFa)
    {
        return view('bast-item-fa.edit', compact('bastItemFa'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BastItemFa $bastItemFa)
    {
        $validated = $request->validate([
            'no_fa' => 'required|string|max:50',
            'merk_type_spesifikasi' => 'required|string|max:255',
            'qty' => 'required|integer|min:1',
            'kondisi' => 'required|in:baik,rusak',
            'keterangan' => 'nullable|string',
        ]);

        $bastItemFa->update($validated);
        return redirect()->route('bast.show', $bastItemFa->bast_id)
            ->with('success', 'Item FA berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BastItemFa $bastItemFa)
    {
        $bastItemFa->delete();
        return redirect()->route('bast.show', $bastItemFa->bast_id)
            ->with('success', 'Item FA berhasil dihapus.');
    }
}
