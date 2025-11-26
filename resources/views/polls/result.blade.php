@extends('layouts.app')

@section('title', 'Hasil Voting')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">

        <h3 class="mb-3">Hasil Voting</h3>
        <h4 class="mb-4">{{ $poll->title }}</h4>

        @if($totalVotes == 0)
            <p class="text-muted">Belum ada suara.</p>
        @else
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Pilihan</th>
                        <th>Suara</th>
                        <th>Persentase</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($options as $opt)
                        @php
                            $count = $opt->votes_count;
                            $pct = $totalVotes > 0 ? round($count * 100 / $totalVotes, 2) : 0;
                        @endphp
                        <tr>
                            <td>{{ $opt->option_text }}</td>
                            <td>{{ $count }}</td>
                            <td>{{ $pct }}%</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <a href="{{ route('home') }}" class="btn btn-secondary">
            Kembali ke Beranda
        </a>
    </div>
</div>
@endsection
