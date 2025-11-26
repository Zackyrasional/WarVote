@extends('layouts.app')

@section('title', 'Buat Aspirasi')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <h3 class="mb-3">Buat Aspirasi Baru</h3>

        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('aspirasi.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="judul" class="form-label">Judul Aspirasi</label>
                        <input
                            type="text"
                            id="judul"
                            name="judul"
                            class="form-control @error('judul') is-invalid @enderror"
                            value="{{ old('judul') }}"
                            required
                        >
                        @error('judul')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi Aspirasi</label>
                        <textarea
                            id="deskripsi"
                            name="deskripsi"
                            rows="6"
                            class="form-control @error('deskripsi') is-invalid @enderror"
                            required
                        >{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            Tuliskan masalah, usulan solusi, dan alasan kenapa aspirasi ini penting bagi warga RT.
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('aspirasi.index') }}" class="btn btn-secondary">
                            Kembali
                        </a>
                        <button type="submit" class="btn btn-success">
                            Kirim Aspirasi
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
