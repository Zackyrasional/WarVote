<?php

namespace App\Http\Controllers;

use App\Models\Aspirasi;
use Illuminate\Http\Request;

class AspirasiController extends Controller
{
    // Halaman daftar aspirasi untuk semua user (hanya yang sudah disetujui)
    public function index()
    {
        $aspirasis = Aspirasi::with('user')
            ->where('status', 'approved')
            ->latest()
            ->paginate(10);

        return view('aspirasi.index', compact('aspirasis'));
    }

    // Form buat aspirasi (warga)
    public function create()
    {
        return view('aspirasi.create');
    }

    // Simpan aspirasi baru (warga)
    public function store(Request $request)
    {
        $data = $request->validate([
            'judul'     => ['required', 'string', 'max:200'],
            'deskripsi' => ['required', 'string'],
        ]);

        Aspirasi::create([
            'id_user'      => auth()->id(),
            'judul'        => $data['judul'],
            'deskripsi'    => $data['deskripsi'],
            'status'       => 'submitted',
            'tanggal_post' => now(),
        ]);

        return redirect()
            ->route('aspirasi.index')
            ->with('success', 'Aspirasi berhasil dikirim, menunggu persetujuan admin.');
    }

    // Detail aspirasi (untuk voting dan melihat isi lengkap)
    public function show($id)
    {
        $aspirasi = Aspirasi::with('user', 'votings')->findOrFail($id);

        return view('aspirasi.show', compact('aspirasi'));
    }

    // ========== BAGIAN ADMIN ==========

    // Daftar semua aspirasi (admin) untuk dikelola
    public function adminIndex()
    {
        $aspirasis = Aspirasi::with('user')
            ->latest()
            ->paginate(10);

        return view('admin.aspirasi.index', compact('aspirasis'));
    }

    // Admin menyetujui aspirasi
    public function approve($id)
    {
        $aspirasi = Aspirasi::findOrFail($id);
        $aspirasi->status = 'approved';
        $aspirasi->save();

        return back()->with('success', 'Aspirasi berhasil disetujui.');
    }

    // Admin menolak aspirasi
    public function reject($id)
    {
        $aspirasi = Aspirasi::findOrFail($id);
        $aspirasi->status = 'rejected';
        $aspirasi->save();

        return back()->with('success', 'Aspirasi berhasil ditolak.');
    }
}
