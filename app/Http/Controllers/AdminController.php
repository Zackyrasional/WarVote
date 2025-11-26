<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Poll;
use App\Models\PollOption;
use App\Models\PollVote;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AdminController extends Controller
{
    // Dashboard admin
    public function dashboard()
    {
        $totalUsers = User::count();
        $totalPolls = Poll::count();
        $totalVotes = PollVote::count();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalPolls',
            'totalVotes'
        ));
    }

    // List semua polling (CRUD)
    public function polls()
    {
        $polls = Poll::with('creator')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.polls.index', compact('polls'));
    }

    // Form edit polling
    public function editPoll(Poll $poll)
    {
        $poll->load('options');
        return view('admin.polls.edit', compact('poll'));
    }

    // Update polling
    public function updatePoll(Request $request, Poll $poll)
    {
        $data = $request->validate([
            'title'          => ['required', 'string', 'max:255'],
            'allow_multiple' => ['nullable', 'boolean'],
            'deadline'       => ['nullable', 'date'],
        ]);

        $deadline = null;
        if (!empty($data['deadline'])) {
            $deadline = Carbon::parse($data['deadline']);
        }

        $poll->update([
            'title'          => $data['title'],
            'allow_multiple' => $request->boolean('allow_multiple'),
            'deadline'       => $deadline,
        ]);

        return redirect()->route('admin.polls.index')
            ->with('success', 'Polling berhasil diperbarui.');
    }

    // Hapus polling
    public function deletePoll(Poll $poll)
    {
        $poll->options()->delete();
        $poll->votes()->delete();
        $poll->delete();

        return back()->with('success', 'Polling berhasil dihapus.');
    }

    // Setujui polling
    public function approvePoll(Poll $poll)
    {
        $poll->update(['status' => 'approved']);

        return back()->with('success', 'Polling disetujui dan bisa diikuti warga.');
    }

    // Tolak polling
    public function rejectPoll(Poll $poll)
    {
        $poll->update(['status' => 'rejected']);

        return back()->with('success', 'Polling ditolak.');
    }

    // Tutup polling paksa
    public function closePoll(Poll $poll)
    {
        $poll->update(['is_closed' => true]);

        return back()->with('success', 'Voting untuk polling ini telah ditutup.');
    }
}
