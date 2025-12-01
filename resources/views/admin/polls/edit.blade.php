@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Edit Polling</h1>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <p>Terjadi kesalahan pada input:</p>
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

                {{-- Judul polling --}}
                <div class="mb-3">
                    <label class="form-label">Judul / Tujuan Polling</label>
                    <input
                        type="text"
                        name="title"
                        class="form-control @error('title') is-invalid @enderror"
                        value="{{ old('title', $poll->title) }}"
                        required
                    >
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Izinkan beberapa pilihan --}}
                <div class="mb-3 form-check">
                    <input
                        type="checkbox"
                        class="form-check-input"
                        id="allow_multiple"
                        name="allow_multiple"
                        value="1"
                        {{ old('allow_multiple', $poll->allow_multiple) ? 'checked' : '' }}
                    >
                    <label class="form-check-label" for="allow_multiple">
                        Izinkan beberapa pilihan
                    </label>
                </div>

                {{-- Deadline polling --}}
                @php
                    use Illuminate\Support\Carbon;

                    $deadlineValue = old('deadline');

                    if (!$deadlineValue && $poll->deadline) {
                        try {
                            $deadlineValue = Carbon::parse($poll->deadline)
                                ->format('Y-m-d\TH:i');
                        } catch (\Exception $e) {
                            $deadlineValue = '';
                        }
                    }
                @endphp

                <div class="mb-3">
                    <label class="form-label">Tanggal Berakhir (opsional)</label>
                    <input
                        type="datetime-local"
                        class="form-control @error('deadline') is-invalid @enderror"
                        name="deadline"
                        value="{{ $deadlineValue }}"
                    >
                    <small class="text-muted">
                        Kosongkan jika polling tidak memiliki batas waktu.
                    </small>
                    @error('deadline')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Opsi polling --}}
                <div class="mb-3">
                    <label class="form-label">Pilihan Suara</label>

                    <p class="text-muted mb-2">
                        Anda dapat mengubah teks pilihan, menambah pilihan baru,
                        atau menghapus pilihan dengan mengosongkan isinya.
                        Minimal harus ada 2 pilihan yang terisi.
                    </p>

                    @php
                        // Data lama dari old() jika validasi gagal
                        $oldOptions    = old('options');
                        $oldOptionIds  = old('option_ids');
                        $useOld        = is_array($oldOptions);

                        if ($useOld) {
                            $pairs = [];
                            foreach ($oldOptions as $i => $text) {
                                $pairs[] = [
                                    'id'   => $oldOptionIds[$i] ?? null,
                                    'text' => $text,
                                ];
                            }
                        } else {
                            // Ambil dari database (opsi yang sudah ada)
                            $pairs = [];
                            foreach ($poll->options as $opt) {
                                $pairs[] = [
                                    'id'   => $opt->id,
                                    'text' => $opt->option_text,
                                ];
                            }
                            // Tambah 2 baris kosong untuk opsi baru
                            $pairs[] = ['id' => null, 'text' => ''];
                            $pairs[] = ['id' => null, 'text' => ''];
                        }
                    @endphp

                    @foreach ($pairs as $pair)
                        <div class="input-group mb-2">
                            <input type="hidden" name="option_ids[]" value="{{ $pair['id'] }}">
                            <input
                                type="text"
                                name="options[]"
                                class="form-control @error('options.*') is-invalid @enderror"
                                value="{{ $pair['text'] }}"
                                placeholder="Tulis pilihan suara..."
                            >
                        </div>
                    @endforeach

                    @error('options')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Status & kondisi (informasi saja) --}}
                <div class="mb-3">
                    <label class="form-label d-block">Status Polling</label>
                    <span class="badge
                        @if ($poll->status === 'approved') bg-success
                        @elseif ($poll->status === 'rejected') bg-danger
                        @else bg-warning text-dark
                        @endif
                    ">
                        {{ $poll->status_label }}
                    </span>
                </div>

                <div class="mb-3">
                    <label class="form-label d-block">Kondisi Polling</label>
                    @if ($poll->is_closed)
                        <span class="badge bg-secondary">Sudah Ditutup</span>
                    @else
                        <span class="badge bg-primary">Sedang Berjalan</span>
                    @endif
                </div>

                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="{{ route('admin.polls.index') }}" class="btn btn-secondary">Batal</a>
            </form>

        </div>
    </div>

</div>
@endsection
