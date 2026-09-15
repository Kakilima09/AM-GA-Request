<?php

namespace App\Http\Controllers;

use App\Models\FaMutasi;
use App\Http\Requests\FaMutasiRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FaMutasiController extends ApprovalController
{
    protected function getIndexRoute(): string
    {
        return 'fa-mutasi.index';
    }

    public function index()
    {
        $mutasis = FaMutasi::with('user')->orderBy('created_at', 'desc')->paginate(10);
        return view('fa-mutasi.index', compact('mutasis'));
    }

    public function create()
    {
        return view('fa-mutasi.create');
    }

    public function store(FaMutasiRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        $data['status'] = 'pending';

        $mutasi = FaMutasi::create($data);
        $this->createApprovals($mutasi);

        return redirect()->route('fa-mutasi.show', $mutasi)
            ->with('success', 'Pengajuan mutasi FA berhasil.');
    }

    public function show(FaMutasi $faMutasi)
    {
        $mutasi = $faMutasi->load('approvals.user');
        $nextLevel = $mutasi->getNextPendingLevel();
        return view('fa-mutasi.show', compact('mutasi', 'nextLevel'));
    }

    public function edit(FaMutasi $faMutasi)
    {
        return view('fa-mutasi.edit', compact('faMutasi'));
    }

    public function update(FaMutasiRequest $request, FaMutasi $faMutasi)
    {
        $faMutasi->update($request->validated());
        return redirect()->route('fa-mutasi.index')
            ->with('success', 'Data mutasi diperbarui.');
    }

    public function destroy(FaMutasi $faMutasi)
    {
        $faMutasi->delete();
        return redirect()->route('fa-mutasi.index')
            ->with('success', 'Data mutasi dihapus.');
    }

    public function approve(Request $request, FaMutasi $faMutasi)
    {
        return $this->processApproval($request, $faMutasi, 'approved');
    }

    public function reject(Request $request, FaMutasi $faMutasi)
    {
        return $this->processApproval($request, $faMutasi, 'rejected');
    }
}
