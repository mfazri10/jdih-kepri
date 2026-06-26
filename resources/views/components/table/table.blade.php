@props([
    'title' => null,
    'headers' => [],
    'records' => null,
    'searchRoute' => null,
    'searchValue' => '',
    'searchPlaceholder' => 'Cari data...',
    'createRoute' => null,
    'createLabel' => 'Tambah Data',
    'createPermission' => null,
])

<div class="card stretch stretch-full border-0 shadow-sm">
    @if($title || $searchRoute || $createRoute)
        <div class="card-header bg-white border-bottom px-4 py-3">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                @if($title)
                    <h5 class="card-title fw-semibold mb-0">{{ $title }}</h5>
                @endif
                
                <div class="d-flex flex-wrap align-items-center gap-2 ms-md-auto">
                    @if(isset($headerActions))
                        {{ $headerActions }}
                    @else
                        @if($searchRoute)
                            <form class="d-flex align-items-center" action="{{ $searchRoute }}" method="GET">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="feather-search"></i></span>
                                    <input type="text" class="form-control border-start-0 ps-0" name="search" value="{{ $searchValue }}" placeholder="{{ $searchPlaceholder }}" style="min-width: 200px;">
                                    <button class="btn btn-primary px-3" type="submit">Cari</button>
                                    @if(filled($searchValue))
                                        <a href="{{ $searchRoute }}" class="btn btn-light border">Reset</a>
                                    @endif
                                </div>
                            </form>
                        @endif

                        @if($createRoute)
                            @if($createPermission)
                                @can($createPermission)
                                    <a href="{{ $createRoute }}" class="btn btn-sm btn-primary d-flex align-items-center gap-2">
                                        <i class="feather-plus"></i>
                                        <span>{{ $createLabel }}</span>
                                    </a>
                                @endcan
                            @else
                                <a href="{{ $createRoute }}" class="btn btn-sm btn-primary d-flex align-items-center gap-2">
                                    <i class="feather-plus"></i>
                                    <span>{{ $createLabel }}</span>
                                </a>
                            @endif
                        @endif
                    @endif
                </div>
            </div>
        </div>
    @endif
    
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                @if(count($headers) > 0)
                    <thead class="table-light">
                        <tr>
                            @foreach($headers as $index => $header)
                                @php
                                    $isFirst = $index === 0;
                                    $isLast = $index === count($headers) - 1;
                                    
                                    // Parse header format: could be string or array
                                    $label = is_array($header) ? ($header['label'] ?? '') : $header;
                                    $align = is_array($header) && isset($header['align']) 
                                        ? $header['align'] 
                                        : ($isLast ? 'end' : 'start');
                                    $width = is_array($header) && isset($header['width']) 
                                        ? "width: {$header['width']};" 
                                        : '';
                                @endphp
                                <th class="{{ $isFirst ? 'ps-4' : '' }} {{ $isLast ? 'pe-4' : '' }} text-{{ $align }} text-uppercase text-secondary fw-semibold fs-12" 
                                    style="letter-spacing: 0.5px; {{ $width }}">
                                    {{ $label }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                @endif
                <tbody>
                    @if($records && $records->count() > 0)
                        {{ $slot }}
                    @else
                        <tr>
                            <td colspan="{{ count($headers) ?: 10 }}" class="text-center py-5 text-muted">
                                <i class="feather-activity fs-24 mb-2 d-block text-secondary opacity-50"></i>
                                Tidak ada data ditemukan.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    
    @if ($records && method_exists($records, 'links'))
        <div class="card-footer bg-white border-top px-4 py-3">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 text-center text-md-start">
                <div class="text-muted fs-13">
                    @if(method_exists($records, 'total'))
                        Menampilkan {{ $records->firstItem() ?? 0 }} sampai {{ $records->lastItem() ?? 0 }} dari {{ $records->total() ?? 0 }} data.
                    @else
                        Menampilkan {{ $records->firstItem() ?? 0 }} sampai {{ $records->lastItem() ?? 0 }} data.
                    @endif
                </div>
                <div class="table-pagination">
                    {{ $records->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    @endif
</div>

<style>
    .table-pagination .pagination {
        margin-bottom: 0 !important;
    }
    .table-pagination .page-link {
        padding: 0.3rem 0.6rem !important;
        font-size: 0.75rem !important;
        border-radius: 4px !important;
    }
    .table-pagination .page-item {
        margin: 0 2px;
    }
</style>
