<?php

namespace App\Http\Controllers;

use App\Models\Approval;
use App\Notifications\ApprovalProcessed;
use App\Notifications\ApprovalRequired;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ApprovalController extends Controller
{
    /**
     * Display a listing of pending approvals for current user.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        $level = approvalLevelForRole($user->role);

        $approvals = Approval::with(['approvable', 'user'])
            ->where('status', 'pending')
            ->when($level, function($query) use ($level) {
                return $query->where('level', $level);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $level = $level; // super_admin (null) melihat semua pending

        return view('approval.index', compact('approvals', 'level'));
    }

    /**
     * Redirect to the detail page of the approvable model.
     *
     * @param  string  $type
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleDetailRedirect($type, $id)
    {
        $routeMap = [
            'AmServiceKendaraan' => 'am-service-kendaraan.show',
            'AmServiceFaNonKendaraan' => 'am-service-fa-non-kendaraan.show',
            'AmSewa' => 'am-sewa.show',
            'AmRenovasiRelokasi' => 'am-renovasi.show',
            'FaBaru' => 'fa-baru.show',
            'FaPenghapusan' => 'fa-penghapusan.show',
            'FaPenjualan' => 'fa-penjualan.show',
            'FaMutasi' => 'fa-mutasi.show',
            'GaBarang' => 'ga-barang.show',
            'GaJasaLembur' => 'ga-jasa-lembur.show',
            'GaRuangMeeting' => 'ga-ruang-meeting.show',
        ];

        $routeName = $routeMap[$type] ?? null;

        if ($routeName) {
            // Cari model berdasarkan tipe dan id
            $modelClass = 'App\\Models\\' . $type;
            if (class_exists($modelClass)) {
                $model = $modelClass::find($id);
                if ($model) {
                    return redirect()->route($routeName, $model);
                }
            }
        }

        // Jika tidak ditemukan, redirect ke dashboard
        return redirect()->route('dashboard')->with('error', 'Data tidak ditemukan.');
    }

    /**
     * Helper untuk mendapatkan approver berdasarkan level.
     * Bisa di-override di child class jika diperlukan.
     */
    protected function getApproverForLevel($level)
    {
        $role = approverRoleForLevel($level);
        if (!$role) return null;

        return User::where('role', $role)->first();
    }

    /**
     * Helper untuk membuat approval records saat store.
     */
    protected function createApprovals($model)
    {
        $levels = $model->getApprovalLevels();
        foreach ($levels as $level) {
            $approver = $this->getApproverForLevel($level);
            if ($approver) {
                $approval = $model->approvals()->create([
                    'level' => $level,
                    'user_id' => $approver->id,
                    'status' => 'pending',
                ]);

                // Notifikasi ke atasan (email + notifikasi database)
                $approver->notify(new ApprovalRequired($model, $level, $approval));
            }
        }
    }

    /**
     * Proses persetujuan / penolakan pada model yang dipanggil dari route approve/reject.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $status
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function processApproval(Request $request, $model, $status)
    {
        $user = Auth::user();

        if (!$user->canApprove($model)) {
            abort(403, 'Anda tidak berhak menyetujui/menolak pengajuan ini.');
        }

        $level = $model->getNextPendingLevel();
        if (!$level) {
            return back()->with('error', 'Tidak ada approval pending untuk pengajuan ini.');
        }

        $approval = $model->approvals()
            ->where('level', $level)
            ->where('status', 'pending')
            ->first();

        if (!$approval) {
            return back()->with('error', 'Level approval tidak ditemukan.');
        }

        $approval->update([
            'status' => $status,
            'user_id' => $user->id,
            'notes' => $request->input('notes') ?: $approval->notes,
        ]);

        if ($status === 'rejected') {
            $model->update(['status' => 'rejected']);
            $this->notifyRequester($model, 'rejected', $user);
            return back()->with('success', 'Pengajuan ditolak.');
        }

        if ($model->isFullyApproved()) {
            $model->update(['status' => 'approved']);
            $this->notifyRequester($model, 'approved', $user);
            return back()->with('success', 'Semua level approval telah disetujui. Pengajuan disetujui.');
        }

        // Level disetujui, belum final: beri tahu approver level berikutnya
        $nextLevel = $model->getNextPendingLevel();
        if ($nextLevel) {
            $nextApproval = $model->approvals()
                ->where('level', $nextLevel)
                ->where('status', 'pending')
                ->first();

            if ($nextApproval && $nextApproval->user) {
                $nextApproval->user->notify(new ApprovalRequired($model, $nextLevel, $nextApproval));
            }
        }

        return back()->with('success', 'Approval level ' . ucwords(str_replace('_', ' ', $level)) . ' disetujui.');
    }

    /**
     * Kirim notifikasi (email + database) ke pemohon saat pengajuan disetujui/ditolak.
     */
    protected function notifyRequester($model, $status, $actor)
    {
        $requester = $model->user;
        if ($requester) {
            $requester->notify(new ApprovalProcessed($model, $status, $actor));
        }
    }

}
