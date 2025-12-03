@extends('layouts.app')

@section('title', 'Edit Polling')

@section('content')
<div class="container mt-4">

    <h4 class="mb-3">Edit Polling</h4>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Terjadi kesalahan:</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">

            <form method="POST" action="{{ route('admin.polls.update', $poll->id) }}">
                @csrf
                @method('PUT')

                {{-- Judul --}}
                <div class="mb-3">
                    <label class="form-label">Judul Polling</label>
                    <input
                        type="text"
                        class="form-control @error('title') is-invalid @enderror"
                        name="title"
                        value="{{ old('title', $poll->title) }}"
                        required
                    >
                    @error('title')
                        <div class="invalid-feedback">{{ $error }}</div>
                    @enderror
                </div>

                {{-- Izinkan multi pilihan --}}
                <div class="mb-3 form-check">
                    <input
                        type="checkbox"
                        class="form-check-input"
                        id="allow_multiple"
                        name="allow_multiple"
                        {{ old('allow_multiple', $poll->allow_multiple) ? 'checked' : '' }}
                    >
                    <label for="allow_multiple" class="form-check-label">
                        Izinkan memilih lebih dari satu opsi
                    </label>
                </div>

                {{-- Deadline --}}
                <div class="mb-3">
                    <label class="form-label">Tanggal Berakhir (opsional)</label>
                    <input
                        type="datetime-local"
                        class="form-control @error('deadline') is-invalid @enderror"
                        name="deadline"
                        value="{{ old('deadline', $poll->deadline ? \Carbon\Carbon::parse($poll->deadline)->format('Y-m-d\TH:i') : '') }}"
                    >
                    <small class="text-muted">
                        Kosongkan jika polling tidak memiliki batas waktu.
                    </small>
                    @error('deadline')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Opsi yang sudah ada --}}
                <div class="mb-3">
                    <label class="form-label d-block">Opsi yang Sudah Ada</label>

                    @forelse ($poll->options as $option)
                        <div class="input-group mb-2">
                            <span class="input-group-text">Opsi</span>
                            <input
                                type="text"
                                name="options_existing[{{ $option->id }}]"
                                class="form-control"
                                value="{{ old('options_existing.' . $option->id, $option->option_text) }}"
                            >
                            <span class="input-group-text">
                                @php
                                    $hasVotes = \App\Models\PollVote::where('option_id', $option->id)->exists();
                                @endphp
                                @if($hasVotes)
                                    Tidak bisa dihapus (sudah ada suara)
                                @else
                                    Kosongkan teks untuk menghapus
                                @endif
                            </span>
                        </div>
                    @empty
                        <p class="text-muted">Belum ada opsi pada polling ini.</p>
                    @endforelse
                </div>

                {{-- Opsi baru --}}
                <div class="mb-3">
                    <label class="form-label d-block">Tambah Opsi Baru</label>
                    <div id="new-options-container">
                        {{-- jika ada old input --}}
                        @if (is_array(old('options_new')))
                            @foreach (old('options_new') as $text)
                                <div class="input-group mb-2">
                                    <span class="input-group-text">Opsi Baru</span>
                                    <input
                                        type="text"
                                        name="options_new[]"
                                        class="form-control"
                                        value="{{ $text }}"
                                    >
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm" id="btn-add-option">
                        + Tambah Opsi Baru
                    </button>
                    <small class="d-block text-muted mt-1">
                        Anda dapat menambahkan beberapa opsi baru di sini.
                    </small>
                </div>

                {{-- Catatan minimal opsi --}}
                <div class="mb-3">
                    <small class="text-muted">
                        Pastikan total opsi (lama + baru yang tidak kosong) minimal 2.
                    </small>
                </div>

                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="{{ route('admin.polls.index') }}" class="btn btn-secondary">Batal</a>
            </form>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const btnAdd = document.getElementById('btn-add-option');
    const container = document.getElementById('new-options-container');

    if (btnAdd && container) {
        btnAdd.addEventListener('click', function () {
            const wrapper = document.createElement('div');
            wrapper.className = 'input-group mb-2';
            wrapper.innerHTML = `
                <span class="input-group-text">Opsi Baru</span>
                <input type="text" name="options_new[]" class="form-control">
            `;
            container.appendChild(wrapper);
        });
    }
});
</script>
@endsection
