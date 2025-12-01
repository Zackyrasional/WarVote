<?php

namespace App\Http\Controllers;

use App\Models\Aspirasi;
use Illuminate\Http\Request;

class AspirasiController extends Controller
{
    // Daftar aspirasi yang sudah disetujui (untuk warga)
    public function indexApproved()
    {
        $aspirasis = Aspirasi::where('status', 'approved')
            ->orderBy('tanggal_post', 'desc')
            ->get();

        return view('aspirasi.index', compact('aspirasis'));
    }

    // Form pengajuan aspirasi (warga)
    public function create()
    {
        return view('aspirasi.create');
    }

    // Simpan aspirasi baru
    public function store(Request $request)
    {
        $data = $request->validate([
            'judul'     => ['required', 'string', 'max:200'],
            'deskripsi' => ['required', 'string'],
        ]);

        Aspirasi::create([
            'id_user'      => auth()->user()->id_user,
            'judul'        => $data['judul'],
            'deskripsi'    => $data['deskripsi'],
            'status'       => 'submitted',
            'tanggal_post' => now(),
        ]);

        return redirect()->route('aspirasi.index')
            ->with('success', 'Aspirasi berhasil diajukan dan menunggu persetujuan admin.');
    }

    // Detail aspirasi (hanya boleh lihat jika approved atau milik sendiri atau admin)
    public function show(Aspirasi $aspirasi)
    {
        $user = auth()->user();

        if (
            $aspirasi->status !== 'approved' &&
            $user->role !== 'admin' &&
            $aspirasi->id_user !== $user->id_user
        ) {
            abort(403, 'Anda tidak berhak melihat aspirasi ini.');
        }

        return view('aspirasi.show', compact('aspirasi'));
    }
}
