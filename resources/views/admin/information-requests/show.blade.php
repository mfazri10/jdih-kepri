@extends('layouts.admin')
@section('title', 'Detail Permintaan Informasi')
@section('page_title', 'Detail Permintaan Informasi')

@section('content')
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3">{{ $informationRequest->subject }}</h5>
                <table class="table table-borderless">
                    <tr><td class="text-muted" style="width:200px;">No. Register</td><td><code>{{ $informationRequest->register_number }}</code></td></tr>
                    <tr><td class="text-muted">Nama</td><td>{{ $informationRequest->name }}</td></tr>
                    <tr><td class="text-muted">Email</td><td>{{ $informationRequest->email }}</td></tr>
                    <tr><td class="text-muted">Jenis</td><td><span class="badge bg-info">{{ $informationRequest->request_type }}</span></td></tr>
                </table>
                <hr>
                <h6 class="fw-semibold">Deskripsi:</h6>
                <p>{{ $informationRequest->description }}</p>

                @if($informationRequest->response)
                <hr>
                <h6 class="fw-semibold">Respon:</h6>
                <div class="alert alert-success">{{ $informationRequest->response }}</div>
                @endif

                @if($informationRequest->status === 'pending' || $informationRequest->status === 'processing')
                <hr>
                <form action="{{ route('admin.information-requests.respond', $informationRequest) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Respon</label>
                        <textarea name="response" class="form-control" rows="4" required>{{ old('response') }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="feather-send me-1"></i> Kirim Respon</button>
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
                    @if($informationRequest->status === 'pending') <span class="badge bg-warning text-dark">Pending</span>
                    @elseif($informationRequest->status === 'processing') <span class="badge bg-info">Proses</span>
                    @elseif($informationRequest->status === 'fulfilled') <span class="badge bg-success">Selesai</span>
                    @else <span class="badge bg-danger">Ditolak</span> @endif
                </div>
                @if($informationRequest->due_date)
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Batas Waktu</span>
                    <small>{{ $informationRequest->due_date->translatedFormat('d M Y') }}</small>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
