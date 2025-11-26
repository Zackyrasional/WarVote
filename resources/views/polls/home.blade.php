@extends('layouts.app')

@section('title', 'Beranda WarVote')

@section('content')
<div class="row">
    <div class="col-md-10 mx-auto">

        <div class="mb-4">
            <h2>WarVote</h2>
            <p>Selamat datang, {{ $user->name }}!</p>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                Pilih Sesi Voting
            </div>
            <div class="card-body">
                @if($polls->isEmpty())
                    <p class="text-muted">Belum ada polling yang disetujui admin.</p>
                @else
                    <form method="POST" action="{{ route('polls.go') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Pilih Polling</label>
                            <select name="poll_id" class="form-select" required>
                                <option value="">-- Pilih salah satu --</option>
                                @foreach($polls as $poll)
                                    <option value="{{ $poll->id }}">
                                        {{ $poll->title }}
                                        @if($poll->deadline)
                                            (s.d. {{ $poll->deadline->format('d-m-Y H:i') }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Lanjut</button>
                        <a href="{{ route('polls.create') }}" class="btn btn-secondary">Buat Polling Baru</a>
                    </form>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection
