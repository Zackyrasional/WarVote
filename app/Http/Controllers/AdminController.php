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
        // relasi user sudah di-load otomatis jika dipanggil dari index
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
            ->map(fn ($text) => $text !== null ? trim($text) : '')
            ->filter(fn ($text) => $text !== '')
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
                'deadline'       => $request->input('deadline'), // bisa null
                'created_by'     => Auth::user()->id_user,
                'status'         => 'approved', // polling yang dibuat admin langsung aktif
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
        // polling + creator + options
        $poll->load('creator', 'options');

        return view('admin.polls.edit', compact('poll'));
    }

    // Update polling (judul, deadline, allow_multiple saja)
    public function pollUpdate(Request $request, Poll $poll)
    {
        $request->validate([
            'title'          => ['required', 'string', 'max:255'],
            'deadline'       => ['nullable', 'date'],
            'allow_multiple' => ['nullable', 'boolean'],
        ]);

        $poll->update([
            'title'          => $request->input('title'),
            'allow_multiple' => $request->boolean('allow_multiple'),
            'deadline'       => $request->input('deadline'),
        ]);

        return redirect()
            ->route('admin.polls.index')
            ->with('success', 'Polling berhasil diperbarui.');
    }

    // Hapus polling (dan seluruh vote + options terkait)
    public function pollDestroy(Poll $poll)
    {
        DB::transaction(function () use ($poll) {
            // hapus suara dan opsi terkait, kemudian polling-nya
            PollVote::where('poll_id', $poll->id)->delete();
            PollOption::where('poll_id', $poll->id)->delete();
            $poll->delete();
        });

        return redirect()
            ->route('admin.polls.index')
            ->with('success', 'Polling berhasil dihapus.');
    }

    // Tutup paksa polling (warga sudah tidak bisa vote, tapi hasil tetap bisa dilihat)
    public function pollClose(Poll $poll)
    {
        $poll->is_closed = true;
        $poll->save();

        return redirect()
            ->route('admin.polls.index')
            ->with('success', 'Polling berhasil ditutup.');
    }

    // Buka kembali polling (selama belum lewat deadline)
    public function pollReopen(Poll $poll)
    {
        $poll->is_closed = false;
        $poll->save();

        return redirect()
            ->route('admin.polls.index')
            ->with('success', 'Polling berhasil dibuka kembali.');
    }
}
