<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Aspirasi;
use App\Models\Poll;
use App\Models\PollOption;
use App\Models\PollVote;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Dashboard admin:
     * - Total pengguna
     * - Total polling
     * - Total suara masuk
     */
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

    /**
     * Kelola aspirasi warga (daftar aspirasi).
     */
    public function aspirasiIndex()
    {
        $aspirasis = Aspirasi::with('user')
            ->orderBy('tanggal_post', 'desc')
            ->get();

        return view('admin.aspirasi.index', compact('aspirasis'));
    }

    /**
     * Detail satu aspirasi.
     */
    public function aspirasiShow($id)
    {
        $aspirasi = Aspirasi::with('user')->findOrFail($id);

        return view('admin.aspirasi.show', compact('aspirasi'));
    }

    /**
     * Setujui aspirasi.
     */
    public function aspirasiApprove($id)
    {
        $aspirasi = Aspirasi::findOrFail($id);
        $aspirasi->status = 'approved';
        $aspirasi->save();

        return redirect()
            ->route('admin.aspirasi.index')
            ->with('success', 'Aspirasi berhasil disetujui.');
    }

    /**
     * Tolak aspirasi.
     */
    public function aspirasiReject($id)
    {
        $aspirasi = Aspirasi::findOrFail($id);
        $aspirasi->status = 'rejected';
        $aspirasi->save();

        return redirect()
            ->route('admin.aspirasi.index')
            ->with('success', 'Aspirasi berhasil ditolak.');
    }

    /**
     * Daftar semua polling untuk admin.
     */
    public function pollIndex()
    {
        $polls = Poll::orderBy('created_at', 'desc')->get();

        return view('admin.polls.index', compact('polls'));
    }

    /**
     * Form membuat polling baru oleh admin.
     */
    public function pollCreate()
    {
        return view('admin.polls.create');
    }

    /**
     * Simpan polling baru.
     */
    public function pollStore(Request $request)
    {
        $request->validate([
            'title'    => ['required', 'string', 'max:255'],
            'deadline' => ['nullable', 'date'],
            // checkbox, jangan divalidasi in:0,1 karena akan bernilai "on"
            'allow_multiple' => ['nullable'],
            'options'        => ['required', 'array'],
            'options.*'      => ['nullable', 'string', 'max:255'],
        ]);

        // Bersihkan pilihan yang kosong
        $options = collect($request->options)
            ->filter(function ($text) {
                return $text !== null && trim($text) !== '';
            })
            ->values();

        if ($options->count() < 2) {
            return back()
                ->withInput()
                ->withErrors(['options' => 'Minimal harus ada 2 pilihan pada polling.']);
        }

        $allowMultiple = $request->has('allow_multiple') ? 1 : 0;

        $poll = Poll::create([
            'title'          => $request->title,
            'allow_multiple' => $allowMultiple,
            'deadline'       => $request->deadline,
            'created_by'     => auth()->id(),
            'status'         => 'approved',   // polling dibuat admin -> langsung disetujui
            'is_closed'      => false,
        ]);

        foreach ($options as $text) {
            PollOption::create([
                'poll_id'     => $poll->id,
                'option_text' => $text,
            ]);
        }

        return redirect()
            ->route('admin.polls.index')
            ->with('success', 'Polling baru berhasil dibuat.');
    }

    /**
     * Form edit polling.
     * Jika polling sudah ditutup (is_closed = 1) → tidak boleh diedit.
     */
    public function pollEdit(Poll $poll)
    {
        if ($poll->is_closed) {
            return redirect()
                ->route('admin.polls.index')
                ->with('error', 'Polling sudah ditutup dan tidak dapat diedit.');
        }

        return view('admin.polls.edit', compact('poll'));
    }

    /**
     * Update polling.
     * Jika polling sudah ditutup → tolak update.
     */
    public function pollUpdate(Request $request, Poll $poll)
    {
        if ($poll->is_closed) {
            return redirect()
                ->route('admin.polls.index')
                ->with('error', 'Polling sudah ditutup. Pengeditan tidak diizinkan.');
        }

        $request->validate([
            'title'          => ['required', 'string', 'max:255'],
            'deadline'       => ['nullable', 'date'],
            'allow_multiple' => ['nullable'],
        ]);

        $allowMultiple = $request->has('allow_multiple') ? 1 : 0;

        $poll->update([
            'title'          => $request->title,
            'deadline'       => $request->deadline,
            'allow_multiple' => $allowMultiple,
        ]);

        return redirect()
            ->route('admin.polls.index')
            ->with('success', 'Polling berhasil diperbarui.');
    }

    /**
     * Tutup polling (paksa). Setelah ditutup, tidak dapat diubah lagi.
     * Hasil tetap dapat dilihat oleh warga.
     */
    public function pollClose(Poll $poll)
    {
        $poll->is_closed = true;
        $poll->save();

        return redirect()
            ->route('admin.polls.index')
            ->with('success', 'Polling berhasil ditutup. Pemilih tidak dapat mengirim suara baru.');
    }

    /**
     * Hapus polling beserta semua opsi & suara.
     */
    public function pollDestroy(Poll $poll)
    {
        // Hapus suara dan pilihan terkait polling ini
        PollVote::where('poll_id', $poll->id)->delete();
        PollOption::where('poll_id', $poll->id)->delete();

        $poll->delete();

        return redirect()
            ->route('admin.polls.index')
            ->with('success', 'Polling berhasil dihapus.');
    }
}
