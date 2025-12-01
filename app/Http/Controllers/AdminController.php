<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Aspirasi;
use App\Models\Poll;
use App\Models\PollOption;
use App\Models\PollVote;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalUsers  = User::count();
        $totalPolls  = Poll::count();
        $totalVotes  = PollVote::count();
        $totalAsp    = Aspirasi::count();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalPolls',
            'totalVotes',
            'totalAsp'
        ));
    }

    /*
     * ASPIRASI
     */

    public function aspirasiIndex()
    {
        $aspirasis = Aspirasi::with('user')
            ->orderBy('tanggal_post', 'desc')
            ->get();

        return view('admin.aspirasi.index', compact('aspirasis'));
    }

    public function aspirasiShow(Aspirasi $aspirasi)
    {
        $aspirasi->load('user');

        return view('admin.aspirasi.show', compact('aspirasi'));
    }

    public function aspirasiApprove(Aspirasi $aspirasi)
    {
        if ($aspirasi->status !== 'approved') {
            $aspirasi->status = 'approved';
            $aspirasi->save();
        }

        return redirect()
            ->route('admin.aspirasi.index')
            ->with('success', 'Aspirasi berhasil disetujui.');
    }

    public function aspirasiReject(Aspirasi $aspirasi)
    {
        if ($aspirasi->status !== 'rejected') {
            $aspirasi->status = 'rejected';
            $aspirasi->save();
        }

        return redirect()
            ->route('admin.aspirasi.index')
            ->with('success', 'Aspirasi berhasil ditolak.');
    }

    /*
     * POLLING
     */

    public function pollIndex()
    {
        $polls = Poll::with('creator')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.polls.index', compact('polls'));
    }

    public function pollCreate()
    {
        return view('admin.polls.create');
    }

    public function pollStore(Request $request)
    {
        $request->validate([
            'title'          => ['required', 'string', 'max:255'],
            'deadline'       => ['nullable', 'date'],
            'allow_multiple' => ['nullable', 'in:0,1'],
            'options'        => ['required', 'array', 'min:2'],
            'options.*'      => ['nullable', 'string', 'max:255'],
        ]);

        $rawOptions = $request->input('options', []);

        // Bersihkan opsi kosong
        $cleanOptions = [];
        foreach ($rawOptions as $text) {
            $text = trim((string)$text);
            if ($text !== '') {
                $cleanOptions[] = $text;
            }
        }

        if (count($cleanOptions) < 2) {
            return back()
                ->withErrors(['options' => 'Minimal harus ada 2 pilihan suara.'])
                ->withInput();
        }

        $poll = Poll::create([
            'title'          => $request->input('title'),
            'allow_multiple' => $request->filled('allow_multiple') ? 1 : 0,
            'deadline'       => $request->input('deadline') ?: null,
            'created_by'     => auth()->user()->id_user,
            'status'         => 'approved',   // admin membuat -> langsung aktif
            'is_closed'      => false,
        ]);

        foreach ($cleanOptions as $text) {
            PollOption::create([
                'poll_id'     => $poll->id,
                'option_text' => $text,
            ]);
        }

        return redirect()
            ->route('admin.polls.index')
            ->with('success', 'Polling baru berhasil dibuat.');
    }

    public function pollEdit(Poll $poll)
    {
        // Jika sudah ditutup, tidak boleh diedit
        if ($poll->is_closed) {
            return redirect()
                ->route('admin.polls.index')
                ->with('error', 'Polling sudah ditutup dan tidak dapat diedit.');
        }

        $poll->load('options', 'creator');

        return view('admin.polls.edit', compact('poll'));
    }

    public function pollUpdate(Request $request, Poll $poll)
    {
        // Cegah perubahan jika polling sudah ditutup
        if ($poll->is_closed) {
            return back()
                ->with('error', 'Polling sudah ditutup dan tidak dapat diubah.')
                ->withInput();
        }

        $request->validate([
            'title'          => ['required', 'string', 'max:255'],
            'allow_multiple' => ['nullable', 'in:0,1'],
            'deadline'       => ['nullable', 'date'],
            'options'        => ['required', 'array'],
            'options.*'      => ['nullable', 'string', 'max:255'],
            'option_ids'     => ['array'],
        ]);

        $rawOptions = $request->input('options', []);
        $optionIds  = $request->input('option_ids', []);

        // Hitung opsi yang tidak kosong
        $cleanOptions = [];
        foreach ($rawOptions as $i => $text) {
            $text = trim((string)$text);
            if ($text !== '') {
                $cleanOptions[$i] = $text;
            }
        }

        if (count($cleanOptions) < 2) {
            return back()
                ->withErrors(['options' => 'Minimal harus ada 2 pilihan suara.'])
                ->withInput();
        }

        // Update data polling
        $poll->title          = $request->input('title');
        $poll->allow_multiple = $request->filled('allow_multiple') ? 1 : 0;
        $poll->deadline       = $request->input('deadline') ?: null;
        $poll->save();

        // Kelola opsi
        $keptIds = [];

        foreach ($rawOptions as $i => $text) {
            $text = trim((string)$text);
            $id   = $optionIds[$i] ?? null;

            // Jika teks kosong -> hapus opsi lama (jika ada id)
            if ($text === '') {
                if ($id) {
                    PollOption::where('poll_id', $poll->id)
                        ->where('id', $id)
                        ->delete();
                }
                continue;
            }

            // Jika ada id -> update opsi
            if ($id) {
                $option = PollOption::where('poll_id', $poll->id)
                    ->where('id', $id)
                    ->first();

                if ($option) {
                    $option->option_text = $text;
                    $option->save();
                    $keptIds[] = $option->id;
                }
            } else {
                // Jika tidak ada id -> buat opsi baru
                $option = PollOption::create([
                    'poll_id'     => $poll->id,
                    'option_text' => $text,
                ]);
                $keptIds[] = $option->id;
            }
        }

        return redirect()
            ->route('admin.polls.index')
            ->with('success', 'Polling berhasil diperbarui.');
    }

    public function pollClose(Poll $poll)
    {
        if (!$poll->is_closed) {
            $poll->is_closed = true;
            $poll->save();
        }

        return redirect()
            ->route('admin.polls.index')
            ->with('success', 'Polling berhasil ditutup. Warga tetap dapat melihat hasil akhirnya.');
    }

    public function pollReopen(Poll $poll)
    {
        if ($poll->is_closed) {
            $poll->is_closed = false;
            $poll->save();
        }

        return redirect()
            ->route('admin.polls.index')
            ->with('success', 'Polling berhasil dibuka kembali.');
    }

    public function pollDelete(Poll $poll)
    {
        // Hapus semua suara dan opsi dulu
        PollVote::where('poll_id', $poll->id)->delete();
        PollOption::where('poll_id', $poll->id)->delete();

        $poll->delete();

        return redirect()
            ->route('admin.polls.index')
            ->with('success', 'Polling berhasil dihapus.');
    }
}
