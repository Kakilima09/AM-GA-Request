<?php

namespace App\Http\Controllers;

use App\Models\GaJasaLembur;
use App\Http\Requests\GaJasaLemburRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GaJasaLemburController extends ApprovalController
{
    protected function getIndexRoute(): string
    {
        return 'ga-jasa-lembur.index';
    }

    public function index()
    {
        $lemburs = GaJasaLembur::with('user')->orderBy('created_at', 'desc')->paginate(10);
        return view('ga-jasa-lembur.index', compact('lemburs'));
    }

    public function create()
    {
        return view('ga-jasa-lembur.create');
    }

    public function store(GaJasaLemburRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        $data['status'] = 'pending';

        $lembur = GaJasaLembur::create($data);
        $this->createApprovals($lembur);

        return redirect()->route('ga-jasa-lembur.show', $lembur)
            ->with('success', 'Pengajuan jasa lembur berhasil.');
    }

    public function show(GaJasaLembur $gaJasaLembur)
    {
        $lembur = $gaJasaLembur->load('approvals.user');
        $nextLevel = $lembur->getNextPendingLevel();
        return view('ga-jasa-lembur.show', compact('lembur', 'nextLevel'));
    }

    public function edit(GaJasaLembur $gaJasaLembur)
    {
        return view('ga-jasa-lembur.edit', compact('gaJasaLembur'));
    }

    public function update(GaJasaLemburRequest $request, GaJasaLembur $gaJasaLembur)
    {
        $gaJasaLembur->update($request->validated());
        return redirect()->route('ga-jasa-lembur.index')
            ->with('success', 'Data lembur diperbarui.');
    }

    public function destroy(GaJasaLembur $gaJasaLembur)
    {
        $gaJasaLembur->delete();
        return redirect()->route('ga-jasa-lembur.index')
            ->with('success', 'Data lembur dihapus.');
    }

    public function approve(Request $request, GaJasaLembur $gaJasaLembur)
    {
        return $this->processApproval($request, $gaJasaLembur, 'approved');
    }

    public function reject(Request $request, GaJasaLembur $gaJasaLembur)
    {
        return $this->processApproval($request, $gaJasaLembur, 'rejected');
    }
}
