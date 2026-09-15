<?php

namespace App\Traits;

use App\Models\Approval;

trait HasApprovals
{
    public function approvals()
    {
        return $this->morphMany(Approval::class, 'approvable');
    }

    public function isFullyApproved(): bool
    {
        $levels = $this->getApprovalLevels();
        $approved = $this->approvals()->where('status', 'approved')->pluck('level')->toArray();
        return empty(array_diff($levels, $approved));
    }

    // Harus diimplementasikan di setiap model
    abstract public function getApprovalLevels(): array;

    // Helper untuk mendapatkan level berikutnya yang pending
    public function getNextPendingLevel()
    {
        $levels = $this->getApprovalLevels();
        foreach ($levels as $level) {
            $approval = $this->approvals()->where('level', $level)->first();
            if ($approval && $approval->status === 'pending') {
                return $level;
            }
        }
        return null;
    }
}
