@extends('layouts.app')

@section('title', 'Detail Aspirasi')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0">Detail Aspirasi</h3>
            <a href="{{ route('aspirasi.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
        </div>

        <div class="card mb-3">
            <div class="card-body">
                <h4 class="card-title mb-2">{{ $aspirasi->judul }}</h4>

                <p class="mb-1">
                    Oleh: {{ $aspirasi->user->name ?? '-' }}
                </p>
                <p class="mb-1">
                    Tanggal: {{ $aspirasi->tanggal_post }}
                </p>
                <p class="mb-2">
                    Status:
                    @if($aspirasi->status === 'approved')
                        <span class="badge bg-success">Disetujui Admin</span>
                    @elseif($aspirasi->status === 'rejected')
                        <span class="badge bg-danger">Ditolak Admin</span>
                    @else
                        <span class="badge bg-warning text-dark">Menunggu Persetujuan</span>
                    @endif
                </p>

                <hr>

                <h6>Deskripsi Aspirasi</h6>
                <p style="white-space: pre-line;">
                    {{ $aspirasi->deskripsi }}
                </p>
            </div>
        </div>

        @php
            $totalSetuju = $aspirasi->votings->where('nilai', 'setuju')->count();
            $totalTidakSetuju = $aspirasi->votings->where('nilai', 'tidak_setuju')->count();
            $totalSuara = $aspirasi->votings->count();
            $sudahVote = auth()->check()
                ? $aspirasi->votings->where('id_user', auth()->id())->first()
                : null;
        @endphp

        <div class="card mb-3">
            <div class="card-body">
                <h6 class="mb-3">Ringkasan Voting</h6>
                <div class="row text-center">
                    <div class="col-md-4 mb-2">
                        <div class="border rounded p-2">
                            <div>Total Suara</div>
                            <div class="fs-4 fw-bold">{{ $totalSuara }}</div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-2">
                        <div class="border rounded p-2">
                            <div>Setuju</div>
                            <div class="fs-4 fw-bold">{{ $totalSetuju }}</div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-2">
                        <div class="border rounded p-2">
                            <div>Tidak Setuju</div>
                            <div class="fs-4 fw-bold">{{ $totalTidakSetuju }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @auth
            <div class="card mb-4">
                <div class="card-body">
                    <h6 class="mb-3">Beri Suara Anda</h6>

                    @if($aspirasi->status !== 'approved')
                        <div class="alert alert-warning mb-0">
                            Aspirasi ini belum disetujui admin, sehingga voting belum dibuka.
                        </div>
                    @elseif($sudahVote)
                        <div class="alert alert-info mb-0">
                            Anda sudah memberikan suara:
                            <strong>{{ $sudahVote->nilai === 'setuju' ? 'Setuju' : 'Tidak Setuju' }}</strong>.
                        </div>
                    @else
                        <form method="POST" action="{{ route('aspirasi.vote', $aspirasi->id_aspirasi) }}">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label d-block">Pilihan Suara</label>

                                <div class="form-check form-check-inline">
                                    <input
                                        class="form-check-input @error('nilai') is-invalid @enderror"
                                        type="radio"
                                        name="nilai"
                                        id="nilai_setuju"
                                        value="setuju"
                                        {{ old('nilai') === 'setuju' ? 'checked' : '' }}
                                        required
                                    >
                                    <label class="form-check-label" for="nilai_setuju">
                                        Setuju
                                    </label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input
                                        class="form-check-input @error('nilai') is-invalid @enderror"
                                        type="radio"
                                        name="nilai"
                                        id="nilai_tidak_setuju"
                                        value="tidak_setuju"
                                        {{ old('nilai') === 'tidak_setuju' ? 'checked' : '' }}
                                        required
                                    >
                                    <label class="form-check-label" for="nilai_tidak_setuju">
                                        Tidak Setuju
                                    </label>
                                </div>

                                @error('nilai')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary">
                                Kirim Suara
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @else
            <div class="alert alert-info">
                Silakan login terlebih dahulu untuk memberikan suara.
            </div>
        @endauth

    </div>
</div>
@endsection
