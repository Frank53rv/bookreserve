<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'BookReserve') }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            color-scheme: light;
        }

        body.modern-shell {
            min-height: 100vh;
            margin: 0;
            font-family: 'Inter', 'Segoe UI', system-ui, -apple-system, BlinkMacSystemFont, 'Helvetica Neue', sans-serif;
            background: radial-gradient(circle at 20% 20%, rgba(59, 130, 246, 0.3), transparent 45%),
                radial-gradient(circle at 80% 0%, rgba(16, 185, 129, 0.25), transparent 40%),
                linear-gradient(135deg, #0f172a 0%, #1d4ed8 45%, #2563eb 65%, #0ea5e9 100%);
            color: #0f1c2e;
        }

        .navbar-modern {
            background: #0b1120;
            border-bottom: 1px solid rgba(148, 163, 184, 0.25);
            box-shadow: 0 16px 42px rgba(15, 23, 42, 0.38);
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .navbar-modern .navbar-brand {
            font-weight: 700;
            font-size: 1.25rem;
            letter-spacing: 0.02em;
            display: inline-flex;
            align-items: center;
            gap: 0.65rem;
        }

        .navbar-modern .navbar-brand i {
            font-size: 1.4rem;
            color: #38bdf8;
            filter: drop-shadow(0 0 10px rgba(56, 189, 248, 0.6));
        }

        .navbar-modern .nav-link {
            position: relative;
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.55rem 0.85rem;
            font-weight: 500;
            color: rgba(226, 232, 240, 0.82);
            transition: color 0.2s ease;
        }

        .navbar-modern .nav-link i {
            font-size: 1rem;
        }

        .navbar-modern .nav-link:hover,
        .navbar-modern .nav-link:focus,
        .navbar-modern .nav-link.active {
            color: #38bdf8;
        }

        .navbar-modern .nav-link::after {
            content: '';
            position: absolute;
            left: 0.85rem;
            right: 0.85rem;
            bottom: -0.6rem;
            height: 3px;
            border-radius: 999px;
            background: linear-gradient(90deg, rgba(14, 165, 233, 0.8), rgba(99, 102, 241, 0.85));
            transform: scaleX(0);
            transform-origin: center;
            transition: transform 0.2s ease;
        }

        .navbar-modern .nav-link:hover::after,
        .navbar-modern .nav-link:focus::after,
        .navbar-modern .nav-link.active::after {
            transform: scaleX(1);
        }

        main.modern-main {
            padding: 4rem 0;
        }

        .modern-container {
            max-width: 960px;
            margin: 0 auto;
        }

        .modern-surface {
            background: #ffffff;
            border-radius: 24px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 28px 60px rgba(15, 23, 42, 0.12);
        }

        .page-heading {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            font-size: clamp(2rem, 4vw, 2.8rem);
            font-weight: 700;
            letter-spacing: -0.01em;
            margin-bottom: 0.5rem;
            background: linear-gradient(135deg, #0ea5e9 0%, #2563eb 45%, #7c3aed 100%);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 14px 28px rgba(15, 23, 42, 0.2);
        }

        .page-heading i {
            font-size: 2rem;
            filter: drop-shadow(0 10px 18px rgba(14, 165, 233, 0.4));
        }

        .page-heading + .page-subheading {
            color: rgba(15, 23, 42, 0.65);
            font-size: 1.05rem;
            max-width: 38rem;
        }

        .data-panel {
            padding: clamp(1.85rem, 3vw, 2.6rem);
            display: flex;
            flex-direction: column;
            gap: 1.75rem;
        }

        .data-panel-header {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 1.25rem;
        }

        .data-panel-header > div:first-child {
            display: grid;
            gap: 0.45rem;
        }

        .panel-title {
            display: inline-flex;
            align-items: center;
            gap: 0.65rem;
            font-size: clamp(1.6rem, 3vw, 2rem);
            font-weight: 600;
            color: #0f172a;
            margin-bottom: 0;
        }

        .panel-title i {
            font-size: 1.6rem;
            filter: drop-shadow(0 10px 18px rgba(14, 165, 233, 0.35));
        }

        .panel-subtitle {
            font-size: 0.98rem;
            color: rgba(15, 23, 42, 0.62);
            max-width: 42rem;
        }

        .panel-actions {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            flex-wrap: wrap;
        }

        .table-actions {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 0.6rem;
            flex-wrap: wrap;
        }

        .table-actions form {
            margin: 0;
        }

        .table-actions .btn {
            white-space: nowrap;
        }

        .data-panel-body {
            display: block;
            overflow: hidden;
        }

        .modern-table-wrapper {
            padding: 0.35rem 0.5rem 0.75rem;
        }

        .modern-table-wrapper .table-modern {
            margin-bottom: 0;
            min-width: 100%;
        }

        .table-modern {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 0.75rem;
            color: #0f172a;
        }

        .table-modern thead th {
            font-size: 0.78rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            font-weight: 600;
            color: rgba(15, 23, 42, 0.55);
            padding: 0 1.2rem 0.2rem;
            border: none;
            background: transparent;
        }

        .table-modern tbody tr {
            background: #ffffff;
            box-shadow: 0 18px 36px rgba(15, 23, 42, 0.12);
            border-radius: 18px;
            transition: transform 0.18s ease, box-shadow 0.18s ease;
        }

        .table-modern tbody tr:hover {
            transform: translateY(-3px);
            box-shadow: 0 24px 44px rgba(15, 23, 42, 0.18);
        }

        .table-modern tbody tr:hover td {
            background: #f8fafc;
        }

        .table-modern tbody td {
            padding: 1.1rem 1.2rem;
            vertical-align: top;
            border-top: none;
            background: #ffffff;
        }

        .table-modern tbody td:first-child {
            border-radius: 18px 0 0 18px;
        }

        .table-modern tbody td:last-child {
            border-radius: 0 18px 18px 0;
        }

        .table-modern .table-cell-title {
            display: inline-flex;
            align-items: flex-start;
            gap: 0.6rem;
            font-weight: 600;
        }

        .table-modern .table-cell-title i {
            color: #2563eb;
            margin-top: 0.25rem;
        }

        .table-modern .table-cell-note {
            display: inline-flex;
            align-items: flex-start;
            gap: 0.55rem;
            color: rgba(15, 23, 42, 0.6);
            font-size: 0.92rem;
        }

        .table-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.35rem 0.7rem;
            border-radius: 999px;
            font-size: 0.78rem;
            font-weight: 600;
            letter-spacing: 0.02em;
            background: #e0e7ff;
            color: #3730a3;
        }

        .table-chip.success {
            background: #dcfce7;
            color: #166534;
        }

        .table-chip.warning {
            background: #fef3c7;
            color: #92400e;
        }

        .table-chip.danger {
            background: #fee2e2;
            color: #b91c1c;
        }

        .table-chip.info {
            background: #e0f2fe;
            color: #0369a1;
        }

        .panel-empty {
            text-align: center;
            padding: 2.5rem 1rem;
            color: rgba(15, 23, 42, 0.65);
        }

        .panel-empty i {
            font-size: 2rem;
            color: rgba(14, 165, 233, 0.75);
            margin-bottom: 0.75rem;
            display: inline-block;
        }

        .panel-empty h3 {
            font-size: 1.35rem;
            font-weight: 600;
            color: #0f172a;
            margin-bottom: 0.75rem;
        }

        .quick-links-grid {
            display: grid;
            gap: 1.5rem;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        }

        .quick-link-card {
            display: grid;
            gap: 0.85rem;
            padding: 1.75rem;
            border-radius: 20px;
            text-decoration: none;
            color: inherit;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            box-shadow: 0 22px 48px rgba(15, 23, 42, 0.12);
            transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
        }

        .quick-link-card:hover,
        .quick-link-card:focus {
            transform: translateY(-4px);
            background: #f8fafc;
            box-shadow: 0 28px 56px rgba(15, 23, 42, 0.18);
        }

        .quick-link-card .status-pill {
            margin-bottom: 0.35rem;
        }

        .quick-link-card p {
            margin-bottom: 0;
            color: rgba(15, 24, 46, 0.7);
        }

        .quick-link-card .cta {
            font-weight: 600;
            color: #2563eb;
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
        }

        .btn-elevated {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            border-radius: 999px;
            padding-inline: 1.4rem;
            font-weight: 600;
            letter-spacing: 0.02em;
            transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
            box-shadow: 0 14px 30px rgba(37, 99, 235, 0.25);
        }

        .btn-elevated:hover,
        .btn-elevated:focus {
            transform: translateY(-2px);
            box-shadow: 0 20px 36px rgba(37, 99, 235, 0.35);
        }

        .btn-outline-soft {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            border-radius: 999px;
            font-weight: 600;
            border-color: rgba(15, 23, 42, 0.18);
            color: #0f172a;
            background: #ffffff;
            transition: border-color 0.2s ease, color 0.2s ease, background 0.2s ease;
        }

        .btn-outline-soft:hover,
        .btn-outline-soft:focus {
            border-color: rgba(59, 130, 246, 0.6);
            color: #1d4ed8;
            background: #f1f5f9;
        }

        .btn-outline-soft.btn-outline-danger {
            border-color: rgba(220, 38, 38, 0.45);
            color: #b91c1c;
            background: #fee2e2;
            box-shadow: 0 12px 28px rgba(220, 38, 38, 0.18);
        }

        .btn-outline-soft.btn-outline-danger:hover,
        .btn-outline-soft.btn-outline-danger:focus {
            border-color: rgba(220, 38, 38, 0.75);
            color: #991b1b;
            background: #fecaca;
        }

        .btn i,
        .navbar a i,
        .status-pill i {
            pointer-events: none;
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.35rem 0.85rem;
            border-radius: 999px;
            background: #dbeafe;
            color: #1d4ed8;
            font-weight: 600;
            letter-spacing: 0.02em;
        }

        .surface-divider {
            width: 100%;
            height: 1px;
            background: linear-gradient(90deg, rgba(148, 163, 184, 0), rgba(148, 163, 184, 0.65), rgba(148, 163, 184, 0));
            margin: 1.5rem 0;
        }

        .flash-modern {
            border-radius: 16px;
            padding: 1rem 1.25rem;
            font-weight: 500;
            box-shadow: 0 18px 36px rgba(22, 163, 74, 0.25);
        }

        @media (max-width: 768px) {
            main.modern-main {
                padding: 2.5rem 0;
            }

            .modern-container {
                padding-inline: 1rem;
            }

            .navbar-modern {
                border-radius: 0 0 18px 18px;
            }

            .data-panel {
                padding: 1.75rem 1.25rem;
            }

            .panel-title {
                font-size: 1.5rem;
            }
        }
    </style>
    @stack('styles')
</head>
<body class="modern-shell">
<nav class="navbar navbar-expand-lg navbar-dark navbar-modern">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}"><i class="bi bi-stars"></i><span>{{ config('app.name', 'BookReserve') }}</span></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                @foreach ($navigationItems as $item)
                    @php
                        $activePattern = $item['active'] ?? $item['route'];
                        $isActive = request()->routeIs($activePattern);
                    @endphp
                    <li class="nav-item">
                        <a class="nav-link {{ $isActive ? 'active' : '' }}" href="{{ route($item['route']) }}">
                            <i class="bi {{ $item['icon'] }}"></i>
                            {{ $item['label'] }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</nav>
<main class="modern-main">
    <div class="modern-container">
        @if (session('status'))
            <div class="alert alert-success flash-modern">{{ session('status') }}</div>
        @endif
        @yield('content')
    </div>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
@stack('scripts')
</body>
</html>
