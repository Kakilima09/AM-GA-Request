<?php

namespace App\Http\Controllers;

use App\Models\AmRenovasiRelokasi;
use App\Http\Requests\AmRenovasiRelokasiRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AmRenovasiRelokasiController extends ApprovalController
{
    protected function getIndexRoute(): string
    {
        return 'am-renovasi.index';
    }

    public function index()
    {
        $renovasis = AmRenovasiRelokasi::with('user')->paginate(10);
        return view('am-renovasi-relokasi.index', compact('renovasis'));
    }

    public function create()
    {
        return view('am-renovasi-relokasi.create');
    }

    public function store(AmRenovasiRelokasiRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        $data['status'] = 'pending';

        $renovasi = AmRenovasiRelokasi::create($data);
        $this->createApprovals($renovasi);

        return redirect()->route('am-renovasi.show', $renovasi)
            ->with('success', 'Pengajuan renovasi/relokasi berhasil.');
    }

    public function show(AmRenovasiRelokasi $amRenovasiRelokasi)
    {
        $renovasi = $amRenovasiRelokasi->load('approvals.user');
        $nextLevel = $renovasi->getNextPendingLevel();
        return view('am-renovasi-relokasi.show', compact('renovasi', 'nextLevel'));
    }

    public function edit(AmRenovasiRelokasi $amRenovasiRelokasi)
    {
        return view('am-renovasi-relokasi.edit', compact('amRenovasiRelokasi'));
    }

    public function update(AmRenovasiRelokasiRequest $request, AmRenovasiRelokasi $amRenovasiRelokasi)
    {
        $amRenovasiRelokasi->update($request->validated());
        return redirect()->route('am-renovasi.index')->with('success', 'Data diperbarui.');
    }

    public function destroy(AmRenovasiRelokasi $amRenovasiRelokasi)
    {
        $amRenovasiRelokasi->delete();
        return redirect()->route('am-renovasi.index')->with('success', 'Data dihapus.');
    }

    public function approve(Request $request, AmRenovasiRelokasi $amRenovasiRelokasi)
    {
        return $this->processApproval($request, $amRenovasiRelokasi, 'approved');
    }

    public function reject(Request $request, AmRenovasiRelokasi $amRenovasiRelokasi)
    {
        return $this->processApproval($request, $amRenovasiRelokasi, 'rejected');
    }
}
