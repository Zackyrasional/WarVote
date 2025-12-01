@extends('layouts.app')

@section('title', 'Buat Polling Baru')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">

        <h3 class="mb-3">Form Membuat Polling Vote</h3>

        <form method="POST" action="{{ route('admin.polls.store') }}">
            @csrf

            {{-- Judul Polling --}}
            <div class="mb-3">
                <label class="form-label">Tujuan Polling</label>
                <input type="text" name="title" class="form-control" placeholder="Contoh: Pemilihan Ketua RT 33" required>
            </div>

            {{-- Pilihan --}}
            <div class="mb-3">
                <label class="form-label">Buat Pilihan</label>

                <div id="option-container">
                    <input type="text" name="options[]" class="form-control mb-2" placeholder="Pilihan 1" required>
                    <input type="text" name="options[]" class="form-control mb-2" placeholder="Pilihan 2" required>
                </div>

                <button type="button" class="btn btn-sm btn-secondary" onclick="addOption()">+ Tambah pilihan</button>
            </div>

            {{-- Izinkan multiple --}}
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="allow_multiple" value="1">
                <label class="form-check-label">
                    Izinkan beberapa pilihan (multi-select)
                </label>
            </div>

            {{-- Deadline --}}
            <div class="mb-3">
                <label class="form-label">Tanggal Berakhir (optional)</label>
                <input type="datetime-local" class="form-control" name="deadline">
            </div>

            <button type="submit" class="btn btn-primary">Buat Polling</button>
            <a href="{{ route('admin.polls.index') }}" class="btn btn-secondary">Batal</a>

        </form>
    </div>
</div>

<script>
    function addOption() {
        const container = document.getElementById('option-container');
        const input = document.createElement('input');

        input.type = 'text';
        input.name = 'options[]';
        input.className = 'form-control mb-2';
        input.placeholder = 'Pilihan tambahan';

        container.appendChild(input);
    }
</script>
@endsection
