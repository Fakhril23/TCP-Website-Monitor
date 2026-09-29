@extends('layouts.app')

@section('title', 'Monitoring Website')
@section('page-title', 'Monitoring Website')
@section('page-description', 'Status TCP dan tren response time dari setiap website yang dipantau.')

@section('content')

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

@else

    <p>Belum ada website yang terdaftar.</p>

@endif

@endsection

@push('styles')
<style>
    .monitor-card{
        border:1px solid var(--border);
        border-radius:12px;
        padding:22px 24px;
        margin-bottom:20px;
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

    @media (max-width: 640px){
        .monitor-card__meta{
            grid-template-columns:repeat(2, minmax(0,1fr));
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
