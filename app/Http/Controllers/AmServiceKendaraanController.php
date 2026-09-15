<?php

namespace App\Http\Controllers;

use App\Models\AmServiceKendaraan;
use App\Http\Requests\AmServiceKendaraanRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AmServiceKendaraanController extends ApprovalController
{
    protected function getIndexRoute(): string
    {
        return 'am-service-kendaraan.index';
    }

    public function index()
    {
        $services = AmServiceKendaraan::with('user')->paginate(10);
        return view('am-service-kendaraan.index', compact('services'));
    }

    public function create()
    {
        return view('am-service-kendaraan.create');
    }

    public function store(AmServiceKendaraanRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        $data['status'] = 'pending';
        $data['km'] = 0; // atau null, karena diisi foto

        if ($request->hasFile('foto_km')) {
            $data['foto_km'] = $request->file('foto_km')->store('am-service-kendaraan', 'public');
        }

        $service = AmServiceKendaraan::create($data);
        $this->createApprovals($service);

        return redirect()->route('am-service-kendaraan.show', $service)
            ->with('success', 'Pengajuan service berhasil dibuat.');
    }

    public function show(AmServiceKendaraan $amServiceKendaraan)
    {
        $service = $amServiceKendaraan->load('approvals.user');
        $nextLevel = $service->getNextPendingLevel();
        return view('am-service-kendaraan.show', compact('service', 'nextLevel'));
    }

    public function edit(AmServiceKendaraan $amServiceKendaraan)
    {
        return view('am-service-kendaraan.edit', compact('amServiceKendaraan'));
    }

    public function update(AmServiceKendaraanRequest $request, AmServiceKendaraan $amServiceKendaraan)
    {
        $data = $request->validated();
        $data['km'] = 0;

        if ($request->hasFile('foto_km')) {
            // Hapus foto lama jika ada
            if ($amServiceKendaraan->foto_km) {
                Storage::disk('public')->delete($amServiceKendaraan->foto_km);
            }
            $data['foto_km'] = $request->file('foto_km')->store('am-service-kendaraan', 'public');
        }

        $amServiceKendaraan->update($data);
        return redirect()->route('am-service-kendaraan.index')
            ->with('success', 'Data service diperbarui.');
    }

    public function destroy(AmServiceKendaraan $amServiceKendaraan)
    {
        $amServiceKendaraan->delete();
        return redirect()->route('am-service-kendaraan.index')
            ->with('success', 'Data service dihapus.');
    }

    public function approve(Request $request, AmServiceKendaraan $amServiceKendaraan)
    {
        return $this->processApproval($request, $amServiceKendaraan, 'approved');
    }

    public function reject(Request $request, AmServiceKendaraan $amServiceKendaraan)
    {
        return $this->processApproval($request, $amServiceKendaraan, 'rejected');
    }
}
