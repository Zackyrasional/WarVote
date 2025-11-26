@extends('layouts.app')

@section('title', 'Aspirasi')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h3>Daftar Aspirasi Disetujui</h3>

    @if(auth()->user()->role === 'user')
        <a href="{{ route('aspirasi.create') }}" class="btn btn-success">+ Buat Aspirasi</a>
    @endif
</div>

@if($aspirasis->isEmpty())
    <div class="alert alert-info">Belum ada aspirasi yang disetujui.</div>
@else
    <div class="list-group">
        @foreach($aspirasis as $a)
            <a href="{{ route('aspirasi.show', $a->id_aspirasi) }}"
               class="list-group-item list-group-item-action">
                <div class="fw-bold">{{ $a->judul }}</div>
                <small class="text-muted">Oleh: {{ $a->user->name }} | {{ $a->tanggal_post }}</small>
            </a>
        @endforeach
    </div>

    <div class="mt-3">
        {{ $aspirasis->links() }}
    </div>
@endif
@endsection
