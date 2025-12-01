@extends('layouts.app')

@section('title', 'Aspirasi Disetujui')

@section('content')
<div class="row">
    <div class="col-md-10 mx-auto">
        <h3 class="mb-4">Daftar Aspirasi Disetujui</h3>

        @if($aspirasis->isEmpty())
            <div class="alert alert-info">
                Belum ada aspirasi yang disetujui.
            </div>
        @else
            <div class="list-group">
                @foreach($aspirasis as $aspirasi)
                    <a href="{{ route('aspirasi.show', $aspirasi->id_aspirasi) }}"
                       class="list-group-item list-group-item-action">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1">{{ $aspirasi->judul }}</h5>
                            <small>{{ \Carbon\Carbon::parse($aspirasi->tanggal_post)->format('d-m-Y H:i') }}</small>
                        </div>
                        <p class="mb-1 text-muted">
                            {{ \Illuminate\Support\Str::limit($aspirasi->deskripsi, 120) }}
                        </p>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
