@extends('layouts.app')

@section('title', 'Kelola Aspirasi')

@section('content')
<div class="mb-3">
    <h3>Kelola Aspirasi Warga</h3>
    <p class="text-muted mb-0">
        Admin dapat menyetujui atau menolak aspirasi warga.
    </p>
</div>

@if($aspirasis->isEmpty())
    <div class="alert alert-info">Belum ada aspirasi masuk.</div>
@else
<div class="card">
    <div class="card-body table-responsive">
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Judul</th>
                    <th>Pengaju</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($aspirasis as $i => $asp)
                <tr>
                    <td>{{ $aspirasis->firstItem() + $i }}</td>

                    <td>
                        <div class="fw-semibold">{{ $asp->judul }}</div>
                        <small class="text-muted">
                            {{ Str::limit($asp->deskripsi, 70) }}
                        </small>
                    </td>

                    <td>{{ $asp->user->name }}</td>
                    <td>{{ $asp->tanggal_post }}</td>

                    <td>
                        @if($asp->status === 'approved')
                            <span class="badge bg-success">Disetujui</span>
                        @elseif($asp->status === 'rejected')
                            <span class="badge bg-danger">Ditolak</span>
                        @else
                            <span class="badge bg-warning text-dark">Menunggu</span>
                        @endif
                    </td>

                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-2">

                            <a href="{{ route('aspirasi.show', $asp->id_aspirasi) }}"
                               class="btn btn-sm btn-outline-secondary">
                                Detail
                            </a>

                            @if($asp->status !== 'approved')
                            <form method="POST"
                                  action="{{ route('admin.aspirasi.approve', $asp->id_aspirasi) }}">
                                @csrf
                                <button class="btn btn-sm btn-success"
                                        onclick="return confirm('Setujui aspirasi ini?')">
                                    Setujui
                                </button>
                            </form>
                            @endif

                            @if($asp->status !== 'rejected')
                            <form method="POST"
                                  action="{{ route('admin.aspirasi.reject', $asp->id_aspirasi) }}">
                                @csrf
                                <button class="btn btn-sm btn-danger"
                                        onclick="return confirm('Tolak aspirasi ini?')">
                                    Tolak
                                </button>
                            </form>
                            @endif

                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-3">
            {{ $aspirasis->links() }}
        </div>
    </div>
</div>
@endif
@endsection
