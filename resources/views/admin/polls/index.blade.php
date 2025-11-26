@extends('layouts.app')

@section('title', 'Kelola Polling')

@section('content')
<div class="row">
    <div class="col-md-10 mx-auto">

        <h3 class="mb-4">Kelola Polling Vote</h3>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>Judul</th>
                    <th>Pembuat</th>
                    <th>Status</th>
                    <th>Ditutup?</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($polls as $poll)
                    <tr>
                        <td>{{ $poll->title }}</td>
                        <td>{{ optional($poll->creator)->name }}</td>
                        <td>
                            @if($poll->status === 'pending')
                                <span class="badge bg-warning">Pending</span>
                            @elseif($poll->status === 'approved')
                                <span class="badge bg-success">Approved</span>
                            @else
                                <span class="badge bg-danger">Rejected</span>
                            @endif
                        </td>
                        <td>
                            @if($poll->is_closed)
                                <span class="text-success fw-bold">Ya</span>
                            @else
                                <span class="text-danger fw-bold">Belum</span>
                            @endif
                        </td>
                        <td>
                            {{-- Approve / Reject --}}
                            @if($poll->status === 'pending')
                                <form method="POST" action="{{ route('admin.polls.approve', $poll->id) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm">Setujui</button>
                                </form>
                                <form method="POST" action="{{ route('admin.polls.reject', $poll->id) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm">Tolak</button>
                                </form>
                            @endif

                            {{-- Tutup paksa --}}
                            @if(!$poll->is_closed && $poll->status === 'approved')
                                <form method="POST" action="{{ route('admin.polls.close', $poll->id) }}"
                                      class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger btn-sm">
                                        Tutup Voting
                                    </button>
                                </form>
                            @endif

                            {{-- Edit --}}
                            <a href="{{ route('admin.polls.edit', $poll->id) }}"
                               class="btn btn-primary btn-sm">
                                Edit
                            </a>

                            {{-- Hapus --}}
                            <form method="POST" action="{{ route('admin.polls.delete', $poll->id) }}"
                                  class="d-inline"
                                  onsubmit="return confirm('Hapus polling ini?')">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">
                            Belum ada polling.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>
</div>
@endsection
