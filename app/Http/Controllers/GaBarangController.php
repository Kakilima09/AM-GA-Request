<?php

namespace App\Http\Controllers;

use App\Models\GaBarang;
use App\Http\Requests\GaBarangRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GaBarangController extends ApprovalController
{
    protected function getIndexRoute(): string
    {
        return 'ga-barang.index';
    }

    public function index()
    {
        $barangs = GaBarang::with('user')->orderBy('created_at', 'desc')->paginate(10);
        return view('ga-barang.index', compact('barangs'));
    }

    public function create()
    {
        return view('ga-barang.create');
    }

    public function store(GaBarangRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        $data['tgl_terima'] = now(); // otomatis diisi sistem
        $data['status'] = 'pending';

        $barang = GaBarang::create($data);
        $this->createApprovals($barang);

        return redirect()->route('ga-barang.show', $barang)
            ->with('success', 'Pengajuan barang berhasil dibuat.');
    }

    public function show(GaBarang $gaBarang)
    {
        $barang = $gaBarang->load('approvals.user');
        $nextLevel = $barang->getNextPendingLevel();
        return view('ga-barang.show', compact('barang', 'nextLevel'));
    }

    public function edit(GaBarang $gaBarang)
    {
        return view('ga-barang.edit', compact('gaBarang'));
    }

    public function update(GaBarangRequest $request, GaBarang $gaBarang)
    {
        $data = $request->validated();
        // tgl_terima tetap tidak diubah
        $gaBarang->update($data);
        return redirect()->route('ga-barang.index')
            ->with('success', 'Data barang diperbarui.');
    }

    public function destroy(GaBarang $gaBarang)
    {
        $gaBarang->delete();
        return redirect()->route('ga-barang.index')
            ->with('success', 'Data barang dihapus.');
    }

    public function approve(Request $request, GaBarang $gaBarang)
    {
        return $this->processApproval($request, $gaBarang, 'approved');
    }

    public function reject(Request $request, GaBarang $gaBarang)
    {
        return $this->processApproval($request, $gaBarang, 'rejected');
    }
}

