@extends('layouts.app')

@section('title', 'Edit Polling')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">

        <h3 class="mb-4">Edit Polling</h3>

        <form method="POST" action="{{ route('admin.polls.update', $poll->id) }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">Judul Polling</label>
                <input type="text" name="title" class="form-control"
                       value="{{ old('title', $poll->title) }}" required>
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" name="allow_multiple" class="form-check-input"
                       id="allow_multiple"
                    {{ old('allow_multiple', $poll->allow_multiple) ? 'checked' : '' }}>
                <label class="form-check-label" for="allow_multiple">
                    Izinkan beberapa pilihan (multi vote)
                </label>
            </div>

            <div class="mb-3">
                <label class="form-label">Deadline (opsional)</label>
                <input type="datetime-local" name="deadline" class="form-control"
                       value="{{ $poll->deadline ? $poll->deadline->format('Y-m-d\TH:i') : '' }}">
            </div>

            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="{{ route('admin.polls.index') }}" class="btn btn-secondary">Kembali</a>
        </form>

    </div>
</div>
@endsection
