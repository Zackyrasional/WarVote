@extends('layouts.app')

@section('title', 'Dashboard Voting')

@section('content')
<div class="row">
    <div class="col-md-10 mx-auto">

        <h3 class="mb-3">Dashboard Voting</h3>
        <h4 class="mb-4">{{ $poll->title }}</h4>

        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5>Total Suara Masuk</h5>
                        <p class="display-6">{{ $totalVotes }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mt-3 mt-md-0">
                <div class="card text-center">
                    <div class="card-body">
                        <h5>Total Pemilih Unik</h5>
                        <p class="display-6">{{ $totalVoters }}</p>
                        <small class="text-muted">
                            {{ $percentage }}% dari {{ $totalUsers }} warga terdaftar
                        </small>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mt-3 mt-md-0">
                <div class="card text-center">
                    <div class="card-body">
                        <h5>Status Waktu</h5>
                        @if($waktuHabis)
                            <p class="text-danger fw-bold">Waktu voting selesai</p>
                        @elseif($deadline)
                            <p class="mb-1">Sisa waktu:</p>
                            <p class="fw-bold">
                                {{ $sisaJam }} jam {{ $sisaMenit }} menit
                            </p>
                            <small class="text-muted">
                                Sampai {{ $deadline->format('d-m-Y H:i') }}
                            </small>
                        @else
                            <p class="text-muted">Tidak ada batas waktu.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <h5>Perolehan Suara Sementara</h5>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Pilihan</th>
                    <th>Jumlah Suara</th>
                </tr>
            </thead>
            <tbody>
                @foreach($poll->options as $opt)
                    @php
                        $count = $opt->votes()->count();
                    @endphp
                    <tr>
                        <td>{{ $opt->option_text }}</td>
                        <td>{{ $count }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Tombol Lihat Hasil Akhir hanya muncul jika waktu voting sudah habis / polling ditutup --}}
        @if($waktuHabis)
            <a href="{{ route('polls.result', $poll->id) }}" class="btn btn-success">
                Lihat Hasil Akhir
            </a>
        @endif

        <a href="{{ route('home') }}" class="btn btn-secondary">
            Kembali ke Beranda
        </a>
    </div>
</div>
@endsection
