@extends('layouts.app')

@section('title', 'Daftar Website')
@section('page-title', 'Daftar Website')
@section('page-description', 'Kelola daftar website yang dipantau, tambah satu per satu atau impor massal lewat Excel.')

@section('content')

<div class="page-toolbar">
    <div class="toolbar-left">
        <a href="{{ route('websites.create') }}" class="btn btn--primary">+ Tambah Website</a>
    </div>

    <div class="toolbar-right" style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
        <!-- Tombol Export Excel -->
        <a href="{{ route('websites.export') }}" class="btn btn--ghost">Export Excel</a>

        <form action="{{ route('websites.import') }}"
              method="POST"
              enctype="multipart/form-data"
              class="import-form">
            @csrf
            <label class="file-input">
                <input type="file" name="file" accept=".xlsx,.xls,.csv" required>
                <span>Pilih file Excel/CSV</span>
            </label>
            <button type="submit" class="btn btn--ghost">Import Excel</button>
        </form>
    </div>
</div>

<!-- Form Filter & Pencarian -->
<div class="filter-toolbar">
    <form action="{{ route('websites.index') }}" method="GET" class="search-filter-form">
        <div class="search-group">
            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="Cari nama website"
                   class="form-control">
        </div>

        <div class="filter-group">
            <select name="status" class="form-control" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="Healthy" {{ request('status') == 'Healthy' ? 'selected' : '' }}>Online (Healthy)</option>
                <option value="Down" {{ request('status') == 'Down' ? 'selected' : '' }}>Offline (Down)</option>
                <option value="Partial" {{ request('status') == 'Partial' ? 'selected' : '' }}>Partial</option>
            </select>
        </div>

        <button type="submit" class="btn btn--ghost">Cari</button>

        @if(request('search') || request('status'))
            <a href="{{ route('websites.index') }}" class="btn btn--danger btn--small" style="padding: 10px 14px;">Reset</a>
        @endif
    </form>
</div>

@if(session('success'))
    <div class="alert alert--success">{{ session('success') }}</div>
@endif

@if($errors->any())
    <div class="alert alert--error">
        @foreach($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif

<div class="table-wrap">
    <table class="data-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>URL</th>
                <th class="text-center">HTTP</th>
                <th class="text-center">HTTPS</th>
                <th class="text-center">Status</th>
                <th>Last Check</th>
                <th class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
        @forelse($website as $site)
            @php
                $badgeClass = match(strtolower($site->status)) {
                    'healthy' => 'up',
                    'partial' => 'partial',
                    default => 'down',
                };
            @endphp
            <tr>
                <td>{{ $website->firstItem() + $loop->index }}</td>
                <td class="cell-strong">{{ $site->name }}</td>
                <td class="cell-mono">{{ $site->url }}</td>
                <td class="text-center">{{ $site->status_http }}</td>
                <td class="text-center">{{ $site->status_https }}</td>
                <td class="text-center">
                    <span class="status-badge status-badge--{{ $badgeClass }}">{{ $site->status }}</span>
                </td>
                <td class="cell-mono">{{ $site->last_check }}</td>
                <td class="text-center">
                    <div class="row-actions">
                        <a href="{{ route('websites.edit', $site->id) }}" class="btn btn--small btn--ghost">Edit</a>

                        <form action="{{ route('websites.destroy', $site->id) }}"
                              method="POST"
                              class="inline-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="btn btn--small btn--danger"
                                    onclick="return confirm('Yakin ingin menghapus website ini?')">
                                Hapus
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="empty-row">Tidak ada website yang ditemukan.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

<!-- Navigasi Pagination -->
<div class="pagination-container">
    <nav>
        {{ $website->links() }}
    </nav>
</div>

@endsection

@push('styles')
<style>
    .page-toolbar{
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap:16px;
        flex-wrap:wrap;
        margin-bottom:16px;
    }

    .filter-toolbar{
        margin-bottom:20px;
    }

    .search-filter-form{
        display:flex;
        gap:12px;
        flex-wrap:wrap;
        align-items:center;
    }

    .search-group input.form-control,
    .filter-group select.form-control{
        padding:9px 14px;
        border:1px solid var(--border, #cbd5e1);
        border-radius:8px;
        font-size:13px;
        background:#fff;
        color:var(--text, #1e293b);
        outline:none;
        min-width:220px;
    }

    .search-group input.form-control:focus,
    .filter-group select.form-control:focus{
        border-color:var(--primary, #0ea5e9);
    }

    .import-form{
        display:flex;
        align-items:center;
        gap:10px;
        flex-wrap:wrap;
    }

    .file-input{
        display:inline-flex;
        align-items:center;
        gap:8px;
        padding:8px 14px;
        border:1px dashed var(--border);
        border-radius:8px;
        font-size:13px;
        color:var(--text-muted);
        background:#fafbfd;
    }

    .file-input input[type="file"]{
        font-size:13px;
        max-width:180px;
    }

    .data-table th,
    .data-table td{
        white-space:nowrap;
    }

    .data-table{
        min-width:760px;
    }

    .row-actions{
        display:flex;
        gap:8px;
        justify-content:center;
    }

    .inline-form{ display:inline; }

    /* Styling Pagination yang Rapi di Tengah */
    .pagination-container {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: 25px;
        margin-bottom: 15px;
        width: 100%;
    }

    .pagination-container nav {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
    }

    .pagination-container nav > div:first-child,
    .pagination-container .flex.justify-between.flex-1.sm\:hidden {
        display: none !important;
    }

    .pagination-container .hidden.sm\:flex-1.sm\:flex.sm\:items-center.sm\:justify-between {
        display: flex !important;
        justify-content: center !important;
        width: 100%;
    }

    .pagination-container nav svg {
        width: 16px;
        height: 16px;
    }

    @media (max-width: 640px){
        .page-toolbar{
            flex-direction:column;
            align-items:flex-start;
        }
        .search-filter-form{
            flex-direction:column;
            align-items:stretch;
        }
        .search-group input.form-control,
        .filter-group select.form-control{
            width:100%;
        }
    }
</style>
@endpush
