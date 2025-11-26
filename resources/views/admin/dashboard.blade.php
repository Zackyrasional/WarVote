@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="row">
    <div class="col-md-10 mx-auto">

        <h3 class="mb-4">Dashboard Admin RT</h3>

        <div class="row">
            <div class="col-md-4 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5>Total Pengguna</h5>
                        <p class="display-6">{{ $totalUsers }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5>Total Polling</h5>
                        <p class="display-6">{{ $totalPolls }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h5>Total Suara Masuk</h5>
                        <p class="display-6">{{ $totalVotes }}</p>
                    </div>
                </div>
            </div>
        </div>

        <a href="{{ route('admin.polls.index') }}" class="btn btn-primary mt-3">
            Kelola Polling
        </a>

    </div>
</div>
@endsection
