<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Aspirasi;
use App\Models\Poll;
use App\Models\PollOption;
use App\Models\PollVote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | DASHBOARD ADMIN
    |--------------------------------------------------------------------------
    */

    public function dashboard()
    {
        $totalUsers  = User::count();
        $totalPolls  = Poll::count();
        $totalVotes  = PollVote::count();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalPolls',
            'totalVotes'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | KELOLA ASPIRASI
    |--------------------------------------------------------------------------
    */

    // Daftar semua aspirasi untuk admin
    public function aspirasiIndex()
    {
        $aspirasis = Aspirasi::with('user')
            ->orderByDesc('tanggal_post')
            ->get();

        return view('admin.aspirasi.index', compact('aspirasis'));
    }

    // Detail aspirasi
    public function aspirasiShow(Aspirasi $aspirasi)
    {
        $aspirasi->load('user');

        return view('admin.aspirasi.show', compact('aspirasi'));
    }

    // Setujui aspirasi
    public function aspirasiApprove(Aspirasi $aspirasi)
    {
        $aspirasi->status = 'approved';
        $aspirasi->save();

        return redirect()
            ->route('admin.aspirasi.index')
            ->with('success', 'Aspirasi berhasil disetujui.');
    }

    // Tolak aspirasi
    public function aspirasiReject(Aspirasi $aspirasi)
    {
        $aspirasi->status = 'rejected';
        $aspirasi->save();

        return redirect()
            ->route('admin.aspirasi.index')
            ->with('success', 'Aspirasi berhasil ditolak.');
    }

    /*
    |--------------------------------------------------------------------------
    | KELOLA POLLING
    |--------------------------------------------------------------------------
    */

    // List semua polling
    public function pollIndex()
    {
        $polls = Poll::with('creator')
            ->orderByDesc('created_at')
            ->get();

        return view('admin.polls.index', compact('polls'));
    }

    // Form buat polling baru
    public function pollCreate()
    {
        return view('admin.polls.create');
    }

    // Simpan polling baru
    public function pollStore(Request $request)
    {
        $request->validate([
            'title'          => ['required', 'string', 'max:255'],
            'deadline'       => ['nullable', 'date'],
            'allow_multiple' => ['nullable', 'boolean'],
            'options'        => ['required', 'array', 'min:2'],
            'options.*'      => ['nullable', 'string', 'max:255'],
        ]);

        // Bersihkan opsi kosong
        $options = collect($request->input('options', []))
            ->map(fn($text) => $text !== null ? trim($text) : '')
            ->filter(fn($text) => $text !== '')
            ->values();

        if ($options->count() < 2) {
            return back()
                ->withInput()
                ->withErrors([
                    'options' => 'Minimal harus ada 2 opsi yang terisi.',
                ]);
        }

        DB::transaction(function () use ($request, $options) {
            $poll = Poll::create([
                'title'          => $request->input('title'),
                'allow_multiple' => $request->boolean('allow_multiple'),
                'deadline'       => $request->input('deadline'),
                'created_by'     => Auth::user()->id_user,
                'status'         => 'approved',   // polling admin langsung aktif
                'is_closed'      => false,
            ]);

            foreach ($options as $text) {
                PollOption::create([
                    'poll_id'     => $poll->id,
                    'option_text' => $text,
                ]);
            }
        });

        return redirect()
            ->route('admin.polls.index')
            ->with('success', 'Polling baru berhasil dibuat.');
    }

    // Form edit polling
    public function pollEdit(Poll $poll)
    {
        $poll->load('creator', 'options');

        return view('admin.polls.edit', compact('poll'));
    }

    // Update polling + opsi (edit judul, deadline, allow_multiple, dan opsi)
    public function pollUpdate(Request $request, Poll $poll)
    {
        $request->validate([
            'title'             => ['required', 'string', 'max:255'],
            'deadline'          => ['nullable', 'date'],
            'allow_multiple'    => ['nullable', 'boolean'],
            'options_existing'  => ['nullable', 'array'],
            'options_existing.*'=> ['nullable', 'string', 'max:255'],
            'options_new'       => ['nullable', 'array'],
            'options_new.*'     => ['nullable', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($request, $poll) {
            // Update data utama polling
            $poll->update([
                'title'          => $request->input('title'),
                'allow_multiple' => $request->boolean('allow_multiple'),
                'deadline'       => $request->input('deadline'),
            ]);

            // 1) Update / hapus opsi yang sudah ada
            $existingInput = collect($request->input('options_existing', []));

            foreach ($poll->options as $option) {
                $text = $existingInput->has($option->id)
                    ? trim($existingInput[$option->id])
                    : null;

                if ($text === null || $text === '') {
                    // Kalau kosong: hanya boleh dihapus jika belum pernah dipakai vote
                    $hasVotes = PollVote::where('option_id', $option->id)->exists();
                    if (!$hasVotes) {
                        $option->delete();
                    }
                    // Kalau sudah ada vote, biarkan saja (tidak diubah)
                } else {
                    $option->option_text = $text;
                    $option->save();
                }
            }

            // 2) Tambah opsi baru
            $newOptions = collect($request->input('options_new', []))
                ->map(fn($text) => $text !== null ? trim($text) : '')
                ->filter(fn($text) => $text !== '');

            foreach ($newOptions as $text) {
                PollOption::create([
                    'poll_id'     => $poll->id,
                    'option_text' => $text,
                ]);
            }

            // 3) Validasi minimal 2 opsi tersisa
            $totalOptions = PollOption::where('poll_id', $poll->id)->count();
            if ($totalOptions < 2) {
                // Paksa rollback dengan exception
                abort(422, 'Minimal harus ada 2 opsi pada polling.');
            }
        });

        return redirect()
            ->route('admin.polls.index')
            ->with('success', 'Polling berhasil diperbarui.');
    }

    // Hapus polling (dan seluruh vote + options terkait)
    public function pollDestroy(Poll $poll)
    {
        DB::transaction(function () use ($poll) {
            PollVote::where('poll_id', $poll->id)->delete();
            PollOption::where('poll_id', $poll->id)->delete();
            $poll->delete();
        });

        return redirect()
            ->route('admin.polls.index')
            ->with('success', 'Polling berhasil dihapus.');
    }

    // Tutup paksa polling
    public function pollClose(Poll $poll)
    {
        $poll->is_closed = true;
        $poll->save();

        return redirect()
            ->route('admin.polls.index')
            ->with('success', 'Polling berhasil ditutup.');
    }

    // Buka kembali polling
    public function pollReopen(Poll $poll)
    {
        $poll->is_closed = false;
        $poll->save();

        return redirect()
            ->route('admin.polls.index')
            ->with('success', 'Polling berhasil dibuka kembali.');
    }
}
