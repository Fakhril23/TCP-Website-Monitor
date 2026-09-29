@extends('layouts.app')

@section('title', 'Daftar Website')
@section('page-title', 'Daftar Website')
@section('page-description', 'Kelola daftar website yang dipantau, tambah satu per satu atau impor massal lewat Excel.')

@section('content')

<div class="page-toolbar">
    <a href="{{ route('websites.create') }}" class="btn btn--primary">+ Tambah Website</a>

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
                <td>{{ $loop->iteration }}</td>
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
                <td colspan="8" class="empty-row">Belum ada website yang ditambahkan.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
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
        margin-bottom:22px;
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

    @media (max-width: 640px){
        .page-toolbar{
            flex-direction:column;
            align-items:flex-start;
        }
    }
</style>
@endpush
