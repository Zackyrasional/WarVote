@extends('layouts.app')

@section('title', 'Buat Polling')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">

        <h3 class="mb-4">Form Membuat Polling Vote</h3>

        <form method="POST" action="{{ route('polls.store') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">Tujuan Polling</label>
                <input type="text" name="title" class="form-control"
                       value="{{ old('title') }}" placeholder="Contoh: Pemilihan Ketua RT 33" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Buat Pilihan</label>

                @php
                    $oldOptions = old('options', ['', '', '']);
                @endphp

                <div id="options-container">
                    @foreach($oldOptions as $index => $opt)
                        <div class="input-group mb-2">
                            <span class="input-group-text">Pilihan {{ $index+1 }}</span>
                            <input type="text" name="options[]" class="form-control"
                                   value="{{ $opt }}">
                        </div>
                    @endforeach
                </div>

                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="addOption()">
                    + Tambah Pilihan
                </button>
            </div>

            {{-- Hidden 0 agar selalu ada nilai, checkbox akan override jadi 1 jika dicentang --}}
            <input type="hidden" name="allow_multiple" value="0">

            <div class="mb-3 form-check">
                <input
                    type="checkbox"
                    name="allow_multiple"
                    value="1"
                    class="form-check-input"
                    id="allow_multiple"
                    {{ old('allow_multiple', 0) == 1 ? 'checked' : '' }}
                >
                <label class="form-check-label" for="allow_multiple">
                    Izinkan beberapa pilihan (multi vote)
                </label>
            </div>

            <div class="mb-3">
                <label class="form-label">Tanggal & Waktu Berakhir (opsional)</label>
                <input type="datetime-local" name="deadline" class="form-control"
                       value="{{ old('deadline') }}">
            </div>

            <button type="submit" class="btn btn-primary">Buat Polling Vote</button>
            <a href="{{ route('home') }}" class="btn btn-secondary">Batal</a>
        </form>

    </div>
</div>

<script>
    function addOption() {
        const container = document.getElementById('options-container');
        const count = container.querySelectorAll('.input-group').length + 1;

        const div = document.createElement('div');
        div.className = 'input-group mb-2';
        div.innerHTML =
            '<span class="input-group-text">Pilihan ' + count + '</span>' +
            '<input type="text" name="options[]" class="form-control">';

        container.appendChild(div);
    }
</script>
@endsection
