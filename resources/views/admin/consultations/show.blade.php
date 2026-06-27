@extends('layouts.admin')
@section('title', 'Detail Konsultasi')
@section('page_title', 'Detail Konsultasi')

@section('content')
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3">{{ $consultation->subject }}</h5>
                <div class="mb-3">
                    <small class="text-muted">Dari:</small>
                    <p class="mb-1"><strong>{{ $consultation->name }}</strong></p>
                    <small class="text-muted">{{ $consultation->email }} {{ $consultation->phone ? '| '.$consultation->phone : '' }}</small>
                </div>
                <hr>
                <h6 class="fw-semibold">Pertanyaan:</h6>
                <p>{{ $consultation->question }}</p>

                @if($consultation->answer)
                <hr>
                <h6 class="fw-semibold">Jawaban:</h6>
                <div class="alert alert-success">{{ $consultation->answer }}</div>
                @endif

                @if($consultation->status === 'pending')
                <hr>
                <form action="{{ route('admin.consultations.answer', $consultation) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Jawaban</label>
                        <textarea name="answer" class="form-control" rows="4" required>{{ old('answer') }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="feather-send me-1"></i> Kirim Jawaban</button>
                </form>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Status</span>
                    @if($consultation->status === 'pending') <span class="badge bg-warning text-dark">Pending</span>
                    @elseif($consultation->status === 'answered') <span class="badge bg-success">Dijawab</span>
                    @else <span class="badge bg-secondary">Ditutup</span> @endif
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Tanggal</span>
                    <small>{{ $consultation->created_at->translatedFormat('d M Y H:i') }}</small>
                </div>
                @if($consultation->answered_at)
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Dijawab</span>
                    <small>{{ $consultation->answered_at->translatedFormat('d M Y H:i') }}</small>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
