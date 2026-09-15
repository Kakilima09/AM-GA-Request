<?php

namespace App\Http\Controllers;

use App\Models\BastItemJasa;
use Illuminate\Http\Request;

class BastItemJasaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = BastItemJasa::with('bast')->paginate(20);
        return view('bast-item-jasa.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('bast-item-jasa.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'bast_id' => 'required|exists:bast,id',
            'tanggal_permintaan' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_permintaan',
            'keterangan' => 'required|string',
        ]);

        $item = BastItemJasa::create($validated);
        return redirect()->route('bast.show', $item->bast_id)
            ->with('success', 'Item Jasa berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(BastItemJasa $bastItemJasa)
    {
        return view('bast-item-jasa.show', compact('bastItemJasa'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BastItemJasa $bastItemJasa)
    {
        return view('bast-item-jasa.edit', compact('bastItemJasa'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BastItemJasa $bastItemJasa)
    {
        $validated = $request->validate([
            'tanggal_permintaan' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_permintaan',
            'keterangan' => 'required|string',
        ]);

        $bastItemJasa->update($validated);
        return redirect()->route('bast.show', $bastItemJasa->bast_id)
            ->with('success', 'Item Jasa berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BastItemJasa $bastItemJasa)
    {
        $bastItemJasa->delete();
        return redirect()->route('bast.show', $bastItemJasa->bast_id)
            ->with('success', 'Item Jasa berhasil dihapus.');
    }
}
