<?php

namespace App\Http\Controllers;

use App\Models\AmSewa;
use App\Http\Requests\AmSewaRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AmSewaController extends ApprovalController
{
    protected function getIndexRoute(): string
    {
        return 'am-sewa.index';
    }

    public function index()
    {
        $sewas = AmSewa::with('user')->paginate(10);
        return view('am-sewa.index', compact('sewas'));
    }

    public function create()
    {
        return view('am-sewa.create');
    }

    public function store(AmSewaRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        $data['status'] = 'pending';

        $sewa = AmSewa::create($data);
        $this->createApprovals($sewa);

        return redirect()->route('am-sewa.show', $sewa)
            ->with('success', 'Pengajuan sewa berhasil.');
    }

    public function show(AmSewa $amSewa)
    {
        $sewa = $amSewa->load('approvals.user');
        $nextLevel = $sewa->getNextPendingLevel();
        return view('am-sewa.show', compact('sewa', 'nextLevel'));
    }

    public function edit(AmSewa $amSewa)
    {
        return view('am-sewa.edit', compact('amSewa'));
    }

    public function update(AmSewaRequest $request, AmSewa $amSewa)
    {
        $amSewa->update($request->validated());
        return redirect()->route('am-sewa.index')->with('success', 'Data diperbarui.');
    }

    public function destroy(AmSewa $amSewa)
    {
        $amSewa->delete();
        return redirect()->route('am-sewa.index')->with('success', 'Data dihapus.');
    }

    public function approve(Request $request, AmSewa $amSewa)
    {
        return $this->processApproval($request, $amSewa, 'approved');
    }

    public function reject(Request $request, AmSewa $amSewa)
    {
        return $this->processApproval($request, $amSewa, 'rejected');
    }
}
