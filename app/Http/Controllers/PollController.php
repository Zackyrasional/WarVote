<?php

namespace App\Http\Controllers;

use App\Models\Poll;
use App\Models\PollOption;
use App\Models\PollVote;
use App\Models\User;
use Illuminate\Http\Request;

class PollController extends Controller
{
    // Beranda setelah login
    public function home()
    {
        $user = auth()->user();

        // Admin diarahkan ke dashboard admin
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        // Warga: lihat semua polling yang approved (baik aktif maupun ditutup)
        $polls = Poll::where('status', 'approved')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('polls.home', compact('user', 'polls'));
    }

    // Dari beranda: pilih polling kemudian diarahkan ke vote atau hasil
    public function goFromHome(Request $request)
    {
        $request->validate([
            'poll_id' => ['required', 'exists:polls,id'],
        ]);

        $poll = Poll::findOrFail($request->poll_id);

        $now      = now();
        $deadline = $poll->deadline;

        $waktuHabis = false;

        if ($poll->is_closed) {
            $waktuHabis = true;
        } elseif ($deadline && $now->greaterThanOrEqualTo($deadline)) {
            $waktuHabis = true;
        }

        // Jika voting sudah berakhir / polling ditutup → langsung ke hasil
        if ($waktuHabis) {
            return redirect()->route('polls.result', $poll->id);
        }

        // Kalau masih aktif → ke form vote
        return redirect()->route('polls.vote.form', $poll->id);
    }

    // Form voting
    public function showVoteForm(Poll $poll)
    {
        $user = auth()->user();

        // Larang admin ngevote
        if ($user->role === 'admin') {
            return redirect()->route('polls.dashboard', $poll->id)
                ->with('error', 'Admin tidak dapat memberikan suara, hanya memantau hasil voting.');
        }

        if ($poll->status !== 'approved') {
            abort(403, 'Polling ini belum disetujui admin.');
        }

        // Kalau sudah ditutup, jangan bisa vote
        if ($poll->is_closed) {
            return redirect()->route('polls.dashboard', $poll->id)
                ->with('error', 'Voting untuk polling ini sudah ditutup.');
        }

        // Kalau deadline lewat, jangan bisa vote
        if ($poll->deadline && now()->greaterThanOrEqualTo($poll->deadline)) {
            return redirect()->route('polls.dashboard', $poll->id)
                ->with('error', 'Waktu voting untuk polling ini sudah berakhir.');
        }

        $poll->load('options');

        $alreadyVoted = PollVote::where('poll_id', $poll->id)
            ->where('user_id', $user->id_user)
            ->exists();

        return view('polls.vote', compact('poll', 'alreadyVoted'));
    }

    // Submit vote
    public function submitVote(Request $request, Poll $poll)
    {
        $user = auth()->user();
        $userId = $user->id_user;

        // Larang admin ngevote (meski paksa lewat URL)
        if ($user->role === 'admin') {
            return redirect()->route('polls.dashboard', $poll->id)
                ->with('error', 'Admin tidak diizinkan memberikan suara pada polling.');
        }

        if ($poll->status !== 'approved') {
            return back()->with('error', 'Polling ini belum disetujui admin.');
        }

        if ($poll->is_closed) {
            return back()->with('error', 'Voting untuk polling ini sudah ditutup.');
        }

        if ($poll->deadline && now()->greaterThanOrEqualTo($poll->deadline)) {
            return back()->with('error', 'Waktu voting sudah berakhir.');
        }

        // Cek sudah pernah vote
        $alreadyVoted = PollVote::where('poll_id', $poll->id)
            ->where('user_id', $userId)
            ->exists();

        if ($alreadyVoted) {
            return back()->with('error', 'Anda sudah memberikan suara pada polling ini.');
        }

        // Single vs multiple choice
        if ($poll->allow_multiple) {
            $data = $request->validate([
                'options'   => ['required', 'array', 'min:1'],
                'options.*' => ['exists:poll_options,id'],
            ]);

            foreach ($data['options'] as $optId) {
                PollVote::create([
                    'poll_id'   => $poll->id,
                    'option_id' => $optId,
                    'user_id'   => $userId,
                ]);
            }
        } else {
            $data = $request->validate([
                'option_id' => ['required', 'exists:poll_options,id'],
            ]);

            PollVote::create([
                'poll_id'   => $poll->id,
                'option_id' => $data['option_id'],
                'user_id'   => $userId,
            ]);
        }

        return redirect()->route('polls.dashboard', $poll->id)
            ->with('success', 'Terima kasih, suara Anda telah tercatat.');
    }

    // Dashboard polling (monitoring)
    public function dashboard(Poll $poll)
    {
        $poll->load('options');

        $totalVotes = PollVote::where('poll_id', $poll->id)->count();
        $totalVoters = PollVote::where('poll_id', $poll->id)
            ->distinct('user_id')
            ->count('user_id');
        $totalUsers = User::count();

        $percentage = 0;
        if ($totalUsers > 0) {
            $percentage = round($totalVoters * 100 / $totalUsers);
        }

        $now = now();
        $deadline = $poll->deadline;

        $sisaJam = null;
        $sisaMenit = null;
        $waktuHabis = false;

        if ($deadline) {
            if ($now->greaterThanOrEqualTo($deadline)) {
                $waktuHabis = true;
                $sisaJam = 0;
                $sisaMenit = 0;
            } else {
                $diffMinutes = $now->diffInMinutes($deadline);
                $sisaJam = intdiv($diffMinutes, 60);
                $sisaMenit = $diffMinutes % 60;
            }
        }

        if ($poll->is_closed) {
            $waktuHabis = true;
        }

        return view('polls.dashboard', compact(
            'poll',
            'totalVotes',
            'totalVoters',
            'totalUsers',
            'percentage',
            'deadline',
            'sisaJam',
            'sisaMenit',
            'waktuHabis'
        ));
    }

    // Hasil akhir
    public function result(Poll $poll)
    {
        $now = now();
        $deadline = $poll->deadline;

        $waktuHabis = false;

        if ($poll->is_closed) {
            $waktuHabis = true;
        } elseif ($deadline && $now->greaterThanOrEqualTo($deadline)) {
            $waktuHabis = true;
        }

        // User biasa hanya boleh lihat hasil jika voting sudah selesai (deadline habis atau polling ditutup)
        if (!$waktuHabis && auth()->user()->role !== 'admin') {
            return redirect()->route('polls.dashboard', $poll->id)
                ->with('error', 'Hasil akhir hanya bisa dilihat setelah voting berakhir atau polling ditutup admin.');
        }

        $poll->load(['options' => function ($q) {
            $q->withCount('votes');
        }]);

        $totalVotes = $poll->options->sum('votes_count');

        $optionsSorted = $poll->options
            ->sortByDesc('votes_count')
            ->values();

        return view('polls.result', [
            'poll'       => $poll,
            'options'    => $optionsSorted,
            'totalVotes' => $totalVotes,
        ]);
    }
}
