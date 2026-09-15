<?php

namespace App\Http\Controllers;

use App\Models\FaPenjualan;
use App\Http\Requests\FaPenjualanRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FaPenjualanController extends ApprovalController
{
    protected function getIndexRoute(): string
    {
        return 'fa-penjualan.index';
    }

    public function index()
    {
        $penjualans = FaPenjualan::with('user')->orderBy('created_at', 'desc')->paginate(10);
        return view('fa-penjualan.index', compact('penjualans'));
    }

    public function create()
    {
        return view('fa-penjualan.create');
    }

    public function store(FaPenjualanRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        $data['status'] = 'pending';

        $penjualan = FaPenjualan::create($data);
        $this->createApprovals($penjualan);

        return redirect()->route('fa-penjualan.show', $penjualan)
            ->with('success', 'Pengajuan penjualan FA berhasil.');
    }

    public function show(FaPenjualan $faPenjualan)
    {
        $penjualan = $faPenjualan->load('approvals.user');
        $nextLevel = $penjualan->getNextPendingLevel();
        return view('fa-penjualan.show', compact('penjualan', 'nextLevel'));
    }

    public function edit(FaPenjualan $faPenjualan)
    {
        return view('fa-penjualan.edit', compact('faPenjualan'));
    }

    public function update(FaPenjualanRequest $request, FaPenjualan $faPenjualan)
    {
        $faPenjualan->update($request->validated());
        return redirect()->route('fa-penjualan.index')
            ->with('success', 'Data penjualan FA diperbarui.');
    }

    public function destroy(FaPenjualan $faPenjualan)
    {
        $faPenjualan->delete();
        return redirect()->route('fa-penjualan.index')
            ->with('success', 'Data penjualan FA dihapus.');
    }

    public function approve(Request $request, FaPenjualan $faPenjualan)
    {
        return $this->processApproval($request, $faPenjualan, 'approved');
    }

    public function reject(Request $request, FaPenjualan $faPenjualan)
    {
        return $this->processApproval($request, $faPenjualan, 'rejected');
    }
}