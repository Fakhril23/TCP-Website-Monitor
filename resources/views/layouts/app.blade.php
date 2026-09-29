<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'TCP Website Monitor')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        :root{
            --bg: #eef1f6;
            --surface: #ffffff;
            --border: #dde2ea;
            --text: #14213d;
            --text-muted: #545e75;
            --nav-bg: #0a0f1e;
            --nav-bg-soft: #121a2e;
            --nav-border: #202b45;
            --accent: #22d3ee;
            --accent-soft: rgba(34, 211, 238, .15);
            --signal: #34d399;
            --font-ui: 'Manrope', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            --font-mono: 'JetBrains Mono', 'SFMono-Regular', Consolas, monospace;
        }

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        html{
            scroll-padding-top: 90px;
        }

        body{
            background:var(--bg);
            color:var(--text);
            font-family:var(--font-ui);
            line-height:1.5;
            -webkit-font-smoothing:antialiased;
        }

        a{ color:inherit; }

        /* ---------- Nav ---------- */

        .site-nav{
            background:var(--nav-bg);
            border-bottom:1px solid var(--nav-border);
            position:sticky;
            top:0;
            z-index:50;
        }

        .site-nav::after{
            content:"";
            display:block;
            height:2px;
            background:linear-gradient(90deg, var(--accent), var(--signal));
        }

        .nav-inner{
            max-width:1180px;
            margin:0 auto;
            padding:16px 30px;
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:20px;
            flex-wrap:wrap;
        }

        .brand{
            display:flex;
            align-items:center;
            gap:12px;
            text-decoration:none;
        }

        .brand-mark{
            width:34px;
            height:34px;
            flex-shrink:0;
            border-radius:9px;
            background:var(--nav-bg-soft);
            border:1px solid var(--nav-border);
            display:flex;
            align-items:center;
            justify-content:center;
        }

        .brand-mark svg{ display:block; }

        .brand-text{
            display:flex;
            flex-direction:column;
            line-height:1.2;
        }

        .brand-text strong{
            color:#f4f6fb;
            font-size:16px;
            font-weight:700;
            letter-spacing:.2px;
        }

        .brand-text span{
            font-family:var(--font-mono);
            font-size:11px;
            color:var(--accent);
        }

        .nav-links{
            list-style:none;
            display:flex;
            gap:6px;
            flex-wrap:wrap;
        }

        .nav-links a{
            display:block;
            padding:8px 14px;
            border-radius:7px;
            font-size:14px;
            font-weight:600;
            color:#aab4cc;
            text-decoration:none;
            transition:background .15s ease, color .15s ease;
        }

        .nav-links a:hover{
            background:var(--nav-bg-soft);
            color:#fff;
        }

        .nav-links a.active{
            background:var(--accent-soft);
            color:var(--accent);
        }

        /* ---------- Layout ---------- */

        .container{
            max-width:1180px;
            width:90%;
            margin:36px auto;
        }

        .page-header{
            margin-bottom:20px;
        }

        .page-header h1{
            font-size:24px;
            font-weight:800;
            color:var(--text);
        }

        .page-header p{
            margin-top:4px;
            color:var(--text-muted);
            font-size:14px;
        }

        .card{
            background:var(--surface);
            border:1px solid var(--border);
            border-radius:12px;
            padding:28px;
        }

        /* ---------- Shared components ---------- */

        .btn{
            display:inline-flex;
            align-items:center;
            justify-content:center;
            gap:6px;
            padding:9px 16px;
            border-radius:8px;
            border:1px solid transparent;
            font-family:var(--font-ui);
            font-size:14px;
            font-weight:700;
            cursor:pointer;
            text-decoration:none;
            transition:filter .15s ease, background .15s ease;
        }

        .btn--primary{
            background:var(--text);
            color:#fff;
        }
        .btn--primary:hover{ filter:brightness(1.15); }

        .btn--ghost{
            background:#fff;
            border-color:var(--border);
            color:var(--text);
        }
        .btn--ghost:hover{ background:#f4f6fa; }

        .btn--danger{
            background:#fff;
            border-color:#f6c2c2;
            color:#c0392b;
        }
        .btn--danger:hover{ background:#fdecec; }

        .btn--small{
            padding:6px 12px;
            font-size:13px;
        }

        .btn--wide{
            width:100%;
            padding:12px 16px;
            font-size:15px;
        }

        .status-badge{
            display:inline-block;
            font-size:12px;
            font-weight:700;
            padding:5px 12px;
            border-radius:999px;
            white-space:nowrap;
        }
        .status-badge--up{
            background:rgba(52, 211, 153, .12);
            color:#0f9d6b;
        }
        .status-badge--partial{
            background:rgba(251, 191, 36, .16);
            color:#b45309;
        }
        .status-badge--down{
            background:rgba(248, 113, 113, .14);
            color:#c0392b;
        }

        .alert{
            padding:12px 16px;
            border-radius:8px;
            font-size:14px;
            margin-bottom:16px;
        }
        .alert--success{
            background:rgba(52, 211, 153, .12);
            color:#0f9d6b;
        }
        .alert--error{
            background:rgba(248, 113, 113, .12);
            color:#c0392b;
        }

        .table-wrap{
            overflow-x:auto;
            border:1px solid var(--border);
            border-radius:12px;
        }

        .table-wrap--scroll{
            max-height:340px;
            overflow-y:auto;
        }

        .data-table{
            width:100%;
            border-collapse:collapse;
            font-size:14px;
            min-width:520px;
        }

        .data-table th,
        .data-table td{
            padding:13px 16px;
            border-bottom:1px solid var(--border);
            text-align:left;
        }

        .data-table thead th{
            background:#f7f8fb;
            color:var(--text-muted);
            font-size:12px;
            font-weight:700;
            position:sticky;
            top:0;
        }

        .data-table tbody tr:last-child td{
            border-bottom:none;
        }

        .data-table tbody tr:hover td{
            background:#fafbfd;
        }

        .cell-strong{ font-weight:700; }

        .cell-mono{
            font-family:var(--font-mono);
            font-size:13px;
            color:var(--text-muted);
        }

        .text-center{ text-align:center; }

        .empty-row{
            text-align:center;
            color:var(--text-muted);
            padding:28px 16px;
        }

        /* ---------- Footer ---------- */

        .site-footer{
            max-width:1180px;
            width:90%;
            margin:0 auto 40px;
            padding-top:18px;
            border-top:1px solid var(--border);
            color:var(--text-muted);
            font-size:13px;
            font-family:var(--font-mono);
        }

        /* ---------- Responsive ---------- */

        @media (max-width: 720px){
            .nav-inner{
                flex-direction:column;
                align-items:flex-start;
                gap:14px;
            }
            .container, .site-footer{
                width:94%;
            }
            .card{
                padding:20px;
            }
        }

        /* ---------- Accessibility ---------- */

        a:focus-visible, button:focus-visible{
            outline:2px solid var(--accent);
            outline-offset:2px;
        }

        @media (prefers-reduced-motion: reduce){
            .nav-links a{ transition:none; }
        }
    </style>

    @stack('styles')

</head>
<body>

<nav class="site-nav">
    <div class="nav-inner">
        <a href="/" class="brand">
            <span class="brand-mark">
                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="4" cy="14" r="2.2" fill="#34d399"/>
                    <circle cx="14" cy="4" r="2.2" fill="#22d3ee"/>
                    <path d="M5.6 12.4L12.4 5.6" stroke="#3a4666" stroke-width="1.6" stroke-linecap="round"/>
                </svg>
            </span>
            <span class="brand-text">
                <strong>TCP Website Monitor</strong>
                <span>uptime &amp; port checks</span>
            </span>
        </a>

        <ul class="nav-links">
            <li><a href="/websites" class="{{ request()->is('websites*') ? 'active' : '' }}">Website</a></li>
            <li><a href="/skpds" class="{{ request()->is('skpds*') ? 'active' : '' }}">Monitoring</a></li>
            <li><a href="/testings" class="{{ request()->is('testings*') ? 'active' : '' }}">Testing</a></li>
        </ul>
    </div>
</nav>

<div class="container">

    @hasSection('page-title')
        <div class="page-header">
            <h1>@yield('page-title')</h1>
            @hasSection('page-description')
                <p>@yield('page-description')</p>
            @endif
        </div>
    @endif

    <div class="card">
        @yield('content')
    </div>

</div>

<div class="site-footer">
    TCP Website Monitor
</div>

@stack('scripts')

</body>
</html>
