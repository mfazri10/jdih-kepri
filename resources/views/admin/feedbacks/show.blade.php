@extends('layouts.admin')
@section('title', 'Detail Feedback')
@section('page_title', 'Detail Feedback')

@section('content')
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3">{{ $feedback->subject }}</h5>
                <div class="mb-3">
                    <small class="text-muted">Dari: {{ $feedback->name ?? $feedback->user?->name ?? 'Anonim' }}</small>
                    @if($feedback->email) <br><small class="text-muted">{{ $feedback->email }}</small> @endif
                </div>
                @if($feedback->rating)
                <div class="mb-3">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="bi bi-star{{ $i <= $feedback->rating ? '-fill text-warning' : '' }} fs-5"></i>
                    @endfor
                </div>
                @endif
                <hr>
                <p>{{ $feedback->message }}</p>

                @if($feedback->admin_reply)
                <hr>
                <h6 class="fw-semibold">Balasan:</h6>
                <div class="alert alert-success">{{ $feedback->admin_reply }}</div>
                @endif

                @if($feedback->status !== 'resolved')
                <hr>
                <form action="{{ route('admin.feedbacks.reply', $feedback) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Balasan</label>
                        <textarea name="admin_reply" class="form-control" rows="4" required>{{ old('admin_reply') }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="feather-send me-1"></i> Kirim Balasan</button>
                </form>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Tipe</span>
                    @if($feedback->type === 'saran') <span class="badge bg-info">Saran</span>
                    @elseif($feedback->type === 'masalah') <span class="badge bg-danger">Masalah</span>
                    @else <span class="badge bg-success">Pujian</span> @endif
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Status</span>
                    @if($feedback->status === 'new') <span class="badge bg-warning text-dark">Baru</span>
                    @elseif($feedback->status === 'read') <span class="badge bg-info">Dibaca</span>
                    @else <span class="badge bg-success">Selesai</span> @endif
                </div>
                @if($feedback->page_url)
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Halaman</span>
                    <small>{{ Str::limit($feedback->page_url, 30) }}</small>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
