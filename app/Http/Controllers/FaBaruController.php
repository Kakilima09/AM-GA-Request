<?php

namespace App\Http\Controllers;

use App\Models\FaBaru;
use App\Http\Requests\FaBaruRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Approval;
use App\Models\FaBaruItem;

class FaBaruController extends ApprovalController
{
    protected function getIndexRoute(): string
    {
        return 'fa-baru.index';
    }

    public function index()
    {
        $faBarus = FaBaru::with('user')->paginate(10);
        return view('fa-baru.index', compact('faBarus'));
    }

    public function create()
    {
        return view('fa-baru.create');
    }

    public function store(FaBaruRequest $request)
    {
        // Ambil item pertama untuk dijadikan header
        $firstItem = $request->items[0] ?? null;

        $data = [
            'user_id' => Auth::id(),
            'kategori' => $request->kategori,
            'tipe_kendaraan' => $request->tipe_kendaraan,
            'is_cop' => $request->boolean('is_cop', false),
            'status' => 'pending',
            // Isi dari item pertama
            'no_fa' => $firstItem['no_fa'] ?? null,
            'nama_fa' => $firstItem['nama_fa'] ?? null,
        ];

        $faBaru = FaBaru::create($data);

        // Simpan semua item (termasuk item pertama)
        foreach ($request->items as $item) {
            $faBaru->items()->create($item);
        }

        // Buat approval
        $this->createApprovals($faBaru);

        return redirect()->route('fa-baru.show', $faBaru)
            ->with('success', 'Pengajuan FA berhasil.');
    }

    public function show(FaBaru $faBaru)
    {
        // Load relasi
        $faBaru->load(['items', 'approvals.user']);

        // Hitung total estimasi harga dari semua item
        $totalEstimasi = $faBaru->items->sum('estimasi_harga');

        $nextLevel = $faBaru->getNextPendingLevel();

        return view('fa-baru.show', compact('faBaru', 'totalEstimasi', 'nextLevel'));
    }

    public function edit(FaBaru $faBaru)
    {
        $items = $faBaru->items;
        return view('fa-baru.edit', compact('faBaru', 'items'));
    }

    public function update(FaBaruRequest $request, FaBaru $faBaru)
    {
        $faBaru->update($request->only(['kategori', 'tipe_kendaraan', 'is_cop']));

        // Hapus item lama
        $faBaru->items()->delete();

        // Simpan item baru
        foreach ($request->items as $item) {
            $faBaru->items()->create($item);
        }

        return redirect()->route('fa-baru.index')
            ->with('success', 'Data FA diperbarui.');
    }

    public function destroy(FaBaru $faBaru)
    {
        $faBaru->items()->delete();
        $faBaru->delete();
        return redirect()->route('fa-baru.index')
            ->with('success', 'Data FA dihapus.');
    }

    public function approve(Request $request, FaBaru $faBaru)
    {
        return $this->processApproval($request, $faBaru, 'approved');
    }

    public function reject(Request $request, FaBaru $faBaru)
    {
        return $this->processApproval($request, $faBaru, 'rejected');
    }

}
