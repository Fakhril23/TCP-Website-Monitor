@extends('layouts.app')

@section('title', 'TCP Website Testing')
@section('page-title', 'TCP Website Testing')
@section('page-description', 'Jalankan pengecekan TCP untuk semua website terdaftar, lalu lihat hasilnya di bawah.')

@section('content')

<div class="testing-card">

    <div class="testing-card__header">
        <h2>Website yang akan dicek</h2>
        <p class="testing-card__hint">{{ count($websites) }} website terdaftar &mdash; semuanya akan dicek sekaligus saat kamu menekan tombol di bawah.</p>
    </div>

    <form method="POST" action="{{ route('testings.check') }}">
        @csrf

        <div class="table-wrap table-wrap--scroll">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>URL</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($websites as $index => $website)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td class="cell-strong">{{ $website->name }}</td>
                        <td class="cell-mono">{{ $website->url }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="testing-card__footer">
            <button type="submit" class="btn btn--primary btn--wide">Check Website</button>
        </div>
    </form>

</div>

@if(isset($results) && count($results) > 0)

    <div class="testing-card testing-card--results">

        <div class="testing-card__header">
            <h2>Hasil Testing</h2>
            <p class="testing-card__hint">Dicek pada {{ $check_date }}, {{ $check_time }}</p>
        </div>

        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Website</th>
                        <th class="text-center">HTTP</th>
                        <th class="text-center">HTTPS</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($results as $index => $result)
                    @php
                        $overallBadge = match(strtolower($result['status'])) {
                            'healthy' => 'up',
                            'partial' => 'partial',
                            default => 'down',
                        };
                    @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td class="cell-mono">{{ $result['website'] }}</td>
                        <td class="text-center">
                            <span class="status-badge status-badge--{{ $result['http'] == 'Online' ? 'up' : 'down' }}">
                                {{ $result['http'] }}
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="status-badge status-badge--{{ $result['https'] == 'Online' ? 'up' : 'down' }}">
                                {{ $result['https'] }}
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="status-badge status-badge--{{ $overallBadge }}">
                                {{ $result['status'] }}
                            </span>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

    </div>

@endif

@endsection

@push('styles')
<style>
    .testing-card{
        border:1px solid var(--border);
        border-radius:12px;
        padding:22px 24px;
    }

    .testing-card--results{
        margin-top:24px;
    }

    .testing-card__header{
        margin-bottom:16px;
    }

    .testing-card__header h2{
        font-size:18px;
        font-weight:700;
    }

    .testing-card__hint{
        margin-top:2px;
        color:var(--text-muted);
        font-size:13px;
    }

    .testing-card__footer{
        margin-top:16px;
    }

    @media (min-width: 480px){
        .btn--wide{
            width:auto;
            min-width:220px;
        }
    }
</style>
@endpush
