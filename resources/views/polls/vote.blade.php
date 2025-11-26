@extends('layouts.app')

@section('title', 'Voting')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">

        <h3 class="mb-3">{{ $poll->title }}</h3>

        @if($alreadyVoted)
            <div class="alert alert-info">
                Anda sudah memberikan suara untuk polling ini.
            </div>
            <a href="{{ route('polls.dashboard', $poll->id) }}" class="btn btn-primary">
                Lihat Dashboard
            </a>
        @else
            <form method="POST" action="{{ route('polls.vote.submit', $poll->id) }}">
                @csrf

                <p>Pilih opsi voting:</p>

                @if($poll->allow_multiple)
                    @foreach($poll->options as $opt)
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox"
                                   name="options[]" value="{{ $opt->id }}" id="opt{{ $opt->id }}">
                            <label class="form-check-label" for="opt{{ $opt->id }}">
                                {{ $opt->option_text }}
                            </label>
                        </div>
                    @endforeach
                @else
                    @foreach($poll->options as $opt)
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio"
                                   name="option_id" value="{{ $opt->id }}" id="opt{{ $opt->id }}">
                            <label class="form-check-label" for="opt{{ $opt->id }}">
                                {{ $opt->option_text }}
                            </label>
                        </div>
                    @endforeach
                @endif

                <button type="submit" class="btn btn-primary mt-3">Kirim Suara</button>
                <a href="{{ route('polls.dashboard', $poll->id) }}" class="btn btn-secondary mt-3">
                    Lihat Dashboard
                </a>
            </form>
        @endif

    </div>
</div>
@endsection
