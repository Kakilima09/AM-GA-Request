<?php

namespace App\Http\Controllers;

use App\Models\AmServiceFaNonKendaraan;
use App\Http\Requests\AmServiceFaNonKendaraanRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AmServiceFaNonKendaraanController extends ApprovalController
{
    protected function getIndexRoute(): string
    {
        return 'am-service-fa-non-kendaraan.index';
    }

    public function index()
    {
        $services = AmServiceFaNonKendaraan::with('user')->paginate(10);
        return view('am-service-fa-non-kendaraan.index', compact('services'));
    }

    public function create()
    {
        return view('am-service-fa-non-kendaraan.create');
    }

    public function store(AmServiceFaNonKendaraanRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        $data['status'] = 'pending';

        $service = AmServiceFaNonKendaraan::create($data);
        $this->createApprovals($service);

        return redirect()->route('am-service-fa-non-kendaraan.show', $service)
            ->with('success', 'Pengajuan service FA Non berhasil.');
    }

    public function show(AmServiceFaNonKendaraan $amServiceFaNonKendaraan)
    {
        $service = $amServiceFaNonKendaraan->load('approvals.user');
        $nextLevel = $service->getNextPendingLevel();
        return view('am-service-fa-non-kendaraan.show', compact('service', 'nextLevel'));
    }

    public function edit(AmServiceFaNonKendaraan $amServiceFaNonKendaraan)
    {
        return view('am-service-fa-non-kendaraan.edit', compact('amServiceFaNonKendaraan'));
    }

    public function update(AmServiceFaNonKendaraanRequest $request, AmServiceFaNonKendaraan $amServiceFaNonKendaraan)
    {
        $amServiceFaNonKendaraan->update($request->validated());
        return redirect()->route('am-service-fa-non-kendaraan.index')
            ->with('success', 'Data diperbarui.');
    }

    public function destroy(AmServiceFaNonKendaraan $amServiceFaNonKendaraan)
    {
        $amServiceFaNonKendaraan->delete();
        return redirect()->route('am-service-fa-non-kendaraan.index')
            ->with('success', 'Data dihapus.');
    }

    public function approve(Request $request, AmServiceFaNonKendaraan $amServiceFaNonKendaraan)
    {
        return $this->processApproval($request, $amServiceFaNonKendaraan, 'approved');
    }

    public function reject(Request $request, AmServiceFaNonKendaraan $amServiceFaNonKendaraan)
    {
        return $this->processApproval($request, $amServiceFaNonKendaraan, 'rejected');
    }
}
