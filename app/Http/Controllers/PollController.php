<?php

namespace App\Http\Controllers;

use App\Models\Poll;
use App\Models\PollOption;
use App\Models\PollVote;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class PollController extends Controller
{
    // Halaman utama setelah login: pilih/buat sesi vote
    public function home()
    {
        $user = auth()->user();

        // Hanya polling yang sudah disetujui admin
        $polls = Poll::where('status', 'approved')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('polls.home', compact('user', 'polls'));
    }

    // Form membuat polling vote
    public function create()
    {
        return view('polls.create');
    }

    // Simpan polling baru (status = pending, menunggu persetujuan admin)
    public function store(Request $request)
    {
        // Ambil semua opsi, buang yang kosong
        $options = collect($request->input('options', []))
            ->filter(function ($opt) {
                return !empty(trim($opt));
            })
            ->values();

        if ($options->count() < 2) {
            return back()
                ->withInput()
                ->withErrors(['options' => 'Minimal harus ada 2 pilihan yang terisi.']);
        }

        // VALIDASI – perhatikan allow_multiple sekarang pakai in:0,1
        $data = $request->validate([
            'title'          => ['required', 'string', 'max:255'],
            'deadline'       => ['nullable', 'date'],
            'allow_multiple' => ['nullable', 'in:0,1'],
        ]);

        $deadline = null;
        if (!empty($data['deadline'])) {
            $deadline = Carbon::parse($data['deadline']);
        }

        // Konversi 0/1 dari form menjadi boolean/angka yang konsisten
        $allowMultiple = $request->input('allow_multiple', 0) == 1 ? 1 : 0;

        $poll = Poll::create([
            'title'          => $data['title'],
            'allow_multiple' => $allowMultiple,
            'deadline'       => $deadline,
            'created_by'     => auth()->user()->id_user,
            'status'         => 'pending',   // masuk antrian admin
            'is_closed'      => false,
        ]);

        foreach ($options as $text) {
            PollOption::create([
                'poll_id'     => $poll->id,
                'option_text' => $text,
            ]);
        }

        return redirect()->route('home')
            ->with('success', 'Polling berhasil diajukan dan menunggu persetujuan admin.');
    }

    // Proses tombol "Lanjut" pada halaman home (pilih sesi vote)
    public function goFromHome(Request $request)
    {
        $request->validate([
            'poll_id' => ['required', 'exists:polls,id'],
        ]);

        return redirect()->route('polls.vote.form', $request->poll_id);
    }

    // Form voting
    public function showVoteForm(Poll $poll)
    {
        // Hanya polling approved yang boleh diakses user biasa
        if ($poll->status !== 'approved' && auth()->user()->role !== 'admin') {
            abort(403, 'Polling ini belum disetujui admin.');
        }

        // Jika sudah ditutup admin
        if ($poll->is_closed) {
            return redirect()->route('polls.dashboard', $poll->id)
                ->with('error', 'Voting untuk polling ini sudah ditutup.');
        }

        $poll->load('options');

        $alreadyVoted = PollVote::where('poll_id', $poll->id)
            ->where('user_id', auth()->user()->id_user)
            ->exists();

        return view('polls.vote', compact('poll', 'alreadyVoted'));
    }

    // Submit vote
    public function submitVote(Request $request, Poll $poll)
    {
        $userId = auth()->user()->id_user;

        if ($poll->status !== 'approved') {
            return back()->with('error', 'Polling ini belum disetujui admin.');
        }

        if ($poll->is_closed) {
            return back()->with('error', 'Voting untuk polling ini sudah ditutup.');
        }

        $alreadyVoted = PollVote::where('poll_id', $poll->id)
            ->where('user_id', $userId)
            ->exists();

        if ($alreadyVoted) {
            return back()->with('error', 'Anda sudah memberikan suara pada polling ini.');
        }

        if ($poll->deadline && now()->greaterThan($poll->deadline)) {
            return back()->with('error', 'Waktu voting sudah berakhir.');
        }

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

    // Dashboard realtime
    public function dashboard(Poll $poll)
    {
        $poll->load('options');

        $totalVotes   = PollVote::where('poll_id', $poll->id)->count();
        $totalVoters  = PollVote::where('poll_id', $poll->id)
            ->distinct('user_id')
            ->count('user_id');
        $totalUsers   = User::count();

        $percentage = 0;
        if ($totalUsers > 0) {
            $percentage = round($totalVoters * 100 / $totalUsers);
        }

        $now      = now();
        $deadline = $poll->deadline;

        $sisaJam    = null;
        $sisaMenit  = null;
        $waktuHabis = false;

        if ($deadline) {
            if ($now->greaterThanOrEqualTo($deadline)) {
                $waktuHabis = true;
                $sisaJam    = 0;
                $sisaMenit  = 0;
            } else {
                $diffMinutes = $now->diffInMinutes($deadline);
                $sisaJam     = intdiv($diffMinutes, 60);
                $sisaMenit   = $diffMinutes % 60;
            }
        }

        // Kalau ditutup paksa admin, dianggap waktu habis
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

    // Hasil vote – hanya boleh diakses setelah waktu habis / polling ditutup (user biasa)
    public function result(Poll $poll)
    {
        $now      = now();
        $deadline = $poll->deadline;

        $waktuHabis = false;

        if ($poll->is_closed) {
            $waktuHabis = true;
        } elseif ($deadline && $now->greaterThanOrEqualTo($deadline)) {
            $waktuHabis = true;
        }

        // Kalau belum habis dan user BUKAN admin, larang lihat hasil
        if (!$waktuHabis && auth()->user()->role !== 'admin') {
            return redirect()->route('polls.dashboard', $poll->id)
                ->with('error', 'Hasil akhir hanya bisa dilihat setelah waktu voting berakhir.');
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
