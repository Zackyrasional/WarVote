@extends('layouts.app')

@section('title', 'Ajukan Aspirasi')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <h3 class="mb-4">Form Pengajuan Aspirasi</h3>

        <form method="POST" action="{{ route('aspirasi.store') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">Judul Aspirasi</label>
                <input type="text" name="judul" class="form-control"
                       value="{{ old('judul') }}" required maxlength="200">
                @error('judul')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Deskripsi / Uraian Aspirasi</label>
                <textarea name="deskripsi" class="form-control" rows="5" required>{{ old('deskripsi') }}</textarea>
                @error('deskripsi')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Kirim Aspirasi</button>
            <a href="{{ route('home') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
