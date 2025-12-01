@extends('layouts.app')

@section('title', 'Edit Polling')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">

        <h3 class="mb-3">Edit Polling</h3>

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Terjadi kesalahan:</strong>
                <ul class="mb-0">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.polls.update', $poll->id) }}">
            @csrf
            @method('PUT')

            {{-- Judul Polling --}}
            <div class="mb-3">
                <label class="form-label">Tujuan Polling</label>
                <input
                    type="text"
                    name="title"
                    class="form-control"
                    value="{{ old('title', $poll->title) }}"
                    required
                >
            </div>

            {{-- Izinkan multiple --}}
            <div class="form-check mb-3">
                <input
                    class="form-check-input"
                    type="checkbox"
                    name="allow_multiple"
                    value="1"
                    id="allow_multiple_checkbox"
                    {{ old('allow_multiple', $poll->allow_multiple) ? 'checked' : '' }}
                >
                <label class="form-check-label" for="allow_multiple_checkbox">
                    Izinkan beberapa pilihan (multi-select)
                </label>
            </div>

            {{-- Deadline --}}
            <div class="mb-3">
                <label class="form-label">Tanggal Berakhir (optional)</label>
                <input
                    type="datetime-local"
                    class="form-control"
                    name="deadline"
                    value="{{ old('deadline', $poll->deadline ? $poll->deadline->format('Y-m-d\TH:i') : '') }}"
                >
                <small class="text-muted">
                    Kosongkan jika polling tidak memiliki batas waktu.
                </small>
            </div>

            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="{{ route('admin.polls.index') }}" class="btn btn-secondary">Batal</a>
        </form>

    </div>
</div>
@endsection
