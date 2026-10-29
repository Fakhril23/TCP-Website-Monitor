@extends('layouts.app')

@section('title', 'Monitoring Website')
@section('page-title', 'Monitoring Website')
@section('page-description', 'Status TCP dan tren response time dari setiap website yang dipantau.')

@section('content')

<!-- Form Filter & Pencarian -->
<div class="filter-toolbar">
    <form action="{{ route('skpds.index') }}" method="GET" class="search-filter-form">
        <div class="search-group">
            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="Cari Nama Website..."
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
            <a href="{{ route('skpds.index') }}" class="btn btn--danger btn--small" style="padding: 10px 14px;">Reset</a>
        @endif
    </form>
</div>

@if($websites->count() > 0)

    @foreach($websites as $website)

        <div class="monitor-card">

            @php
                $badgeClass = match(strtolower($website->status)) {
                    'healthy' => 'up',
                    'partial' => 'partial',
                    default => 'down',
                };
            @endphp
            <div class="monitor-card__header">
                <div>
                    <h2>{{ $website->name }}</h2>
                    <p class="monitor-card__url">{{ $website->url }}</p>
                </div>
                <span class="status-badge status-badge--{{ $badgeClass }}">
                    {{ $website->status }}
                </span>
            </div>

            <dl class="monitor-card__meta">
                <div>
                    <dt>HTTP</dt>
                    <dd>{{ $website->status_http }}</dd>
                </div>
                <div>
                    <dt>HTTPS</dt>
                    <dd>{{ $website->status_https }}</dd>
                </div>
                <div>
                    <dt>Last Check</dt>
                    <dd>{{ $website->last_check ?? 'Belum pernah dicek' }}</dd>
                </div>
                <div>
                    <dt>Jumlah Testing</dt>
                    <dd>{{ $website->testings->count() }}</dd>
                </div>
            </dl>

            @if(!empty($website->chart_labels))
                <div class="monitor-card__chart">
                    <canvas id="chart-website-{{ $website->id }}" height="90"></canvas>
                </div>
            @else
                <p class="monitor-card__empty">Belum ada riwayat pengecekan untuk ditampilkan sebagai grafik.</p>
            @endif

        </div>

    @endforeach

    <!-- Navigasi Pagination -->
    <div class="pagination-container">
        <nav>
            {{ $websites->links() }}
        </nav>
    </div>

@else

    <p style="margin-top: 20px; color: var(--text-muted);">Tidak ada website yang ditemukan.</p>

@endif

@endsection

@push('styles')
<style>
    /* Styling Filter & Search Toolbar */
    .filter-toolbar{
        margin-bottom:24px;
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

    .monitor-card{
        border:1px solid var(--border);
        border-radius:12px;
        padding:22px 24px;
        margin-bottom:20px;
        background: #fff;
    }
    .monitor-card:last-child{ margin-bottom:0; }

    .monitor-card__header{
        display:flex;
        justify-content:space-between;
        align-items:flex-start;
        gap:16px;
        flex-wrap:wrap;
    }

    .monitor-card__header h2{
        font-size:18px;
        font-weight:700;
    }

    .monitor-card__url{
        margin-top:2px;
        color:var(--text-muted);
        font-family:var(--font-mono);
        font-size:13px;
    }

    .status-badge{
        white-space:nowrap;
    }

    .monitor-card__meta{
        display:grid;
        grid-template-columns:repeat(4, minmax(0,1fr));
        gap:14px;
        margin:18px 0;
    }

    .monitor-card__meta dt{
        font-size:12px;
        color:var(--text-muted);
        margin-bottom:2px;
    }

    .monitor-card__meta dd{
        font-size:14px;
        font-weight:600;
    }

    .monitor-card__chart{
        margin-top:6px;
        padding-top:16px;
        border-top:1px solid var(--border);
    }

    .monitor-card__empty{
        margin-top:14px;
        color:var(--text-muted);
        font-size:13px;
    }

    /* Styling Pagination di Tengah */
    .pagination-container {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: 30px;
        margin-bottom: 20px;
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
        .monitor-card__meta{
            grid-template-columns:repeat(2, minmax(0,1fr));
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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
    @foreach($websites as $website)
        @if(!empty($website->chart_labels))
        new Chart(document.getElementById('chart-website-{{ $website->id }}'), {
            type: 'line',
            data: {
                labels: @json($website->chart_labels),
                datasets: [{
                    label: 'Response time (ms)',
                    data: @json($website->chart_response_times),
                    borderColor: '#22d3ee',
                    backgroundColor: 'rgba(34, 211, 238, .12)',
                    pointBackgroundColor: @json($website->chart_status_colors),
                    pointBorderColor: @json($website->chart_status_colors),
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    tension: 0.3,
                    fill: true,
                    spanGaps: true
                }]
            },
            options: {
                responsive: true,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: { display: true, text: 'Response time (ms)' }
                    }
                }
            }
        });
        @endif
    @endforeach
</script>
@endpush
