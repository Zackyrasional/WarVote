@extends('layouts.app')

@section('title', 'Detail Aspirasi (Admin)')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <h3 class="mb-2">{{ $aspirasi->judul }}</h3>
        <p class="text-muted mb-1">
            Diajukan oleh: {{ $aspirasi->user->name ?? 'Warga' }}
        </p>
        <p class="text-muted mb-3">
            Tanggal: {{ \Carbon\Carbon::parse($aspirasi->tanggal_post)->format('d-m-Y H:i') }}
            | Status: {{ ucfirst($aspirasi->status) }}
        </p>

        <div class="card mb-3">
            <div class="card-body">
                {!! nl2br(e($aspirasi->deskripsi)) !!}
            </div>
        </div>

        @if($aspirasi->status === 'submitted')
            <form method="POST" action="{{ route('admin.aspirasi.approve', $aspirasi->id_aspirasi) }}" class="d-inline">
                @csrf
                <button class="btn btn-success" type="submit">Setujui</button>
            </form>

            <form method="POST" action="{{ route('admin.aspirasi.reject', $aspirasi->id_aspirasi) }}" class="d-inline">
                @csrf
                <button class="btn btn-danger" type="submit">Tolak</button>
            </form>
        @endif

        <a href="{{ route('admin.aspirasi.index') }}" class="btn btn-secondary mt-3">Kembali</a>
    </div>
</div>
@endsection
