<?php

namespace App\Http\Controllers;

use App\Models\GaRuangMeeting;
use App\Http\Requests\GaRuangMeetingRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GaRuangMeetingController extends ApprovalController
{
    protected function getIndexRoute(): string
    {
        return 'ga-ruang-meeting.index';
    }

    public function index()
    {
        $meetings = GaRuangMeeting::with('user')->orderBy('created_at', 'desc')->paginate(10);
        return view('ga-ruang-meeting.index', compact('meetings'));
    }

    public function create()
    {
        return view('ga-ruang-meeting.create');
    }

    public function store(GaRuangMeetingRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        $data['status'] = 'pending';

        $meeting = GaRuangMeeting::create($data);
        $this->createApprovals($meeting);

        return redirect()->route('ga-ruang-meeting.show', $meeting)
            ->with('success', 'Pengajuan ruang meeting berhasil.');
    }

    public function show(GaRuangMeeting $gaRuangMeeting)
    {
        $meeting = $gaRuangMeeting->load('approvals.user');
        $nextLevel = $meeting->getNextPendingLevel();
        return view('ga-ruang-meeting.show', compact('meeting', 'nextLevel'));
    }

    public function edit(GaRuangMeeting $gaRuangMeeting)
    {
        return view('ga-ruang-meeting.edit', compact('gaRuangMeeting'));
    }

    public function update(GaRuangMeetingRequest $request, GaRuangMeeting $gaRuangMeeting)
    {
        $gaRuangMeeting->update($request->validated());
        return redirect()->route('ga-ruang-meeting.index')
            ->with('success', 'Data ruang meeting diperbarui.');
    }

    public function destroy(GaRuangMeeting $gaRuangMeeting)
    {
        $gaRuangMeeting->delete();
        return redirect()->route('ga-ruang-meeting.index')
            ->with('success', 'Data ruang meeting dihapus.');
    }

    public function approve(Request $request, GaRuangMeeting $gaRuangMeeting)
    {
        return $this->processApproval($request, $gaRuangMeeting, 'approved');
    }

    public function reject(Request $request, GaRuangMeeting $gaRuangMeeting)
    {
        return $this->processApproval($request, $gaRuangMeeting, 'rejected');
    }
}
