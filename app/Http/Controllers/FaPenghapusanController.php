<?php

namespace App\Http\Controllers;

use App\Models\FaPenghapusan;
use App\Http\Requests\FaPenghapusanRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FaPenghapusanController extends ApprovalController
{
    protected function getIndexRoute(): string
    {
        return 'fa-penghapusan.index';
    }

    public function index()
    {
        $penghapusans = FaPenghapusan::with('user')->paginate(10);
        return view('fa-penghapusan.index', compact('penghapusans'));
    }

    public function create()
    {
        return view('fa-penghapusan.create');
    }

    public function store(FaPenghapusanRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        $data['status'] = 'pending';

        // Upload foto jika ada
        if ($request->hasFile('foto_fa')) {
            $data['foto_fa'] = $request->file('foto_fa')->store('fa-penghapusan', 'public');
        }

        $penghapusan = FaPenghapusan::create($data);
        $this->createApprovals($penghapusan);

        return redirect()->route('fa-penghapusan.show', $penghapusan)
            ->with('success', 'Pengajuan penghapusan FA berhasil.');
    }

    public function show(FaPenghapusan $faPenghapusan)
    {
        $penghapusan = $faPenghapusan->load('approvals.user');
        $nextLevel = $penghapusan->getNextPendingLevel();
        return view('fa-penghapusan.show', compact('penghapusan', 'nextLevel'));
    }

    public function edit(FaPenghapusan $faPenghapusan)
    {
        return view('fa-penghapusan.edit', compact('faPenghapusan'));
    }

    public function update(FaPenghapusanRequest $request, FaPenghapusan $faPenghapusan)
    {
        $data = $request->validated();
        if ($request->hasFile('foto_fa')) {
            if ($faPenghapusan->foto_fa) {
                Storage::disk('public')->delete($faPenghapusan->foto_fa);
            }
            $data['foto_fa'] = $request->file('foto_fa')->store('fa-penghapusan', 'public');
        }
        $faPenghapusan->update($data);
        return redirect()->route('fa-penghapusan.index')->with('success', 'Data diperbarui.');
    }

    public function destroy(FaPenghapusan $faPenghapusan)
    {
        if ($faPenghapusan->foto_fa) {
            Storage::disk('public')->delete($faPenghapusan->foto_fa);
        }
        $faPenghapusan->delete();
        return redirect()->route('fa-penghapusan.index')->with('success', 'Data dihapus.');
    }

    public function approve(Request $request, FaPenghapusan $faPenghapusan)
    {
        return $this->processApproval($request, $faPenghapusan, 'approved');
    }

    public function reject(Request $request, FaPenghapusan $faPenghapusan)
    {
        return $this->processApproval($request, $faPenghapusan, 'rejected');
    }
}
