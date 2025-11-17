<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'BookReserve') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            color-scheme: dark;
            --bg-primary: #030014;
            --bg-secondary: #0a0f24;
            --bg-accent: #120c3c;
            --card-bg: rgba(9, 13, 35, 0.78);
            --card-border: rgba(94, 96, 206, 0.35);
            --text-primary: #f8fafc;
            --text-muted: rgba(226, 232, 240, 0.7);
            --accent-1: #c084fc;
            --accent-2: #38bdf8;
            --accent-3: #f472b6;
            --danger: #fb7185;
            --success: #4ade80;
            --warning: #facc15;
        }

        body.modern-shell {
            min-height: 100vh;
            margin: 0;
            font-family: 'Space Grotesk', 'Plus Jakarta Sans', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: radial-gradient(circle at 25% 20%, rgba(56, 189, 248, 0.25), transparent 45%),
                radial-gradient(circle at 80% 0%, rgba(236, 72, 153, 0.18), transparent 50%),
                radial-gradient(circle at 10% 90%, rgba(192, 132, 252, 0.22), transparent 40%),
                linear-gradient(135deg, #020617 0%, #0f172a 45%, #1e1b4b 100%);
            color: var(--text-primary);
            position: relative;
            overflow-x: hidden;
        }

        body.modern-shell::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image: linear-gradient(rgba(255, 255, 255, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
            background-size: 140px 140px;
            opacity: 0.6;
            pointer-events: none;
        }

        body.modern-shell::after {
            content: '';
            position: fixed;
            inset: 0;
            background: radial-gradient(circle at 15% 30%, rgba(14, 165, 233, 0.15), transparent 35%),
                radial-gradient(circle at 85% 10%, rgba(236, 72, 153, 0.22), transparent 40%),
                radial-gradient(circle at 60% 80%, rgba(59, 130, 246, 0.18), transparent 35%);
            filter: blur(100px);
            opacity: 0.8;
            pointer-events: none;
        }

        .navbar-modern {
            background: rgba(3, 4, 15, 0.9);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 25px 60px rgba(2, 6, 23, 0.45);
            backdrop-filter: blur(18px);
            position: sticky;
            top: 0;
            z-index: 60;
        }

        .navbar-modern .navbar-brand {
            font-weight: 700;
            font-size: 1.35rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--text-primary);
        }

        .navbar-modern .navbar-brand span {
            background: linear-gradient(120deg, var(--accent-1), var(--accent-2));
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .navbar-modern .navbar-brand i {
            font-size: 1.5rem;
            color: var(--accent-2);
            filter: drop-shadow(0 0 16px rgba(56, 189, 248, 0.65));
        }

        .navbar-modern .nav-link {
            position: relative;
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.65rem 0.95rem;
            font-weight: 500;
            color: var(--text-muted);
            transition: color 0.25s ease, transform 0.25s ease;
        }

        .navbar-modern .nav-link::after {
            content: '';
            position: absolute;
            inset-inline: 0.35rem;
            bottom: -0.4rem;
            height: 3px;
            border-radius: 999px;
            background: linear-gradient(90deg, var(--accent-2), var(--accent-1));
            transform: scaleX(0);
            transform-origin: center;
            transition: transform 0.25s ease;
        }

        .navbar-modern .nav-link:hover,
        .navbar-modern .nav-link:focus,
        .navbar-modern .nav-link.active {
            color: var(--text-primary);
            transform: translateY(-1px);
        }

        .navbar-modern .nav-link:hover::after,
        .navbar-modern .nav-link:focus::after,
        .navbar-modern .nav-link.active::after {
            transform: scaleX(1);
        }

        .navbar-modern .dropdown-menu {
            background: rgba(3, 4, 15, 0.98);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 16px;
            box-shadow: 0 25px 60px rgba(2, 6, 23, 0.65);
            backdrop-filter: blur(18px);
            padding: 0.5rem;
            margin-top: 0.75rem;
            min-width: 220px;
        }

        .navbar-modern .dropdown-item {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            padding: 0.75rem 1rem;
            border-radius: 10px;
            color: var(--text-muted);
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .navbar-modern .dropdown-item:hover,
        .navbar-modern .dropdown-item:focus {
            background: rgba(56, 189, 248, 0.12);
            color: var(--accent-2);
        }

        .navbar-modern .dropdown-item i {
            font-size: 1.1rem;
            width: 1.25rem;
        }

        .navbar-modern .dropdown-toggle::after {
            margin-left: 0.35rem;
            vertical-align: 0.15em;
        }

        main.modern-main {
            padding: clamp(2.5rem, 4vw, 4rem) 0 4rem;
        }

        .modern-container {
            max-width: 1080px;
            margin: 0 auto;
            padding-inline: clamp(1rem, 5vw, 2rem);
            position: relative;
            z-index: 1;
        }

        .modern-surface {
            background: var(--card-bg);
            border-radius: 28px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 25px 80px rgba(2, 6, 23, 0.55);
            backdrop-filter: blur(18px);
        }

        .page-heading {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            font-size: clamp(2rem, 4vw, 2.8rem);
            font-weight: 700;
            letter-spacing: -0.02em;
            margin-bottom: 0.5rem;
            color: var(--text-primary);
        }

        .page-heading i {
            font-size: 2rem;
            color: var(--accent-2);
            filter: drop-shadow(0 8px 20px rgba(56, 189, 248, 0.5));
        }

        .page-heading + .page-subheading {
            color: var(--text-muted);
            font-size: 1.05rem;
            max-width: 38rem;
        }

        .data-panel {
            padding: clamp(2rem, 3vw, 2.75rem);
            display: flex;
            flex-direction: column;
            gap: 1.75rem;
        }

        .data-panel-header {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            gap: 1.5rem;
        }

        .panel-title {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            font-size: clamp(1.6rem, 3vw, 2rem);
            font-weight: 600;
            color: var(--text-primary);
            margin: 0;
        }

        .panel-title i {
            font-size: 1.6rem;
            color: var(--accent-1);
            filter: drop-shadow(0 8px 22px rgba(192, 132, 252, 0.45));
        }

        .panel-subtitle {
            color: var(--text-muted);
        }

        .panel-actions {
            display: flex;
            gap: 0.85rem;
            flex-wrap: wrap;
        }

        .table-actions {
            display: flex;
            justify-content: flex-end;
            flex-wrap: wrap;
            gap: 0.6rem;
        }

        .table-actions form {
            margin: 0;
        }

        .modern-table-wrapper {
            padding-bottom: 0.75rem;
        }

        .table-modern {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 0.85rem;
            color: var(--text-primary);
        }

        .table-modern thead th {
            font-size: 0.78rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            font-weight: 600;
            color: rgba(248, 250, 252, 0.65);
            padding: 0 1.25rem 0.2rem;
            border: none;
        }

        .table-modern tbody tr {
            background: rgba(9, 14, 35, 0.85);
            border-radius: 20px;
            box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.02), 0 20px 50px rgba(3, 7, 18, 0.55);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .table-modern tbody tr:hover {
            transform: translateY(-4px);
            box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.08), 0 28px 60px rgba(3, 7, 18, 0.65);
        }

        .table-modern tbody td {
            padding: 1.15rem 1.25rem;
            border: none;
        }

        .table-modern .table-cell-title {
            display: inline-flex;
            align-items: flex-start;
            gap: 0.65rem;
            font-weight: 600;
        }

        .table-modern .table-cell-title i {
            color: var(--accent-2);
            margin-top: 0.35rem;
        }

        .table-modern .table-cell-note {
            display: inline-flex;
            gap: 0.55rem;
            color: var(--text-muted);
        }

        .table-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.35rem 0.85rem;
            border-radius: 999px;
            font-size: 0.78rem;
            font-weight: 600;
            background: rgba(59, 130, 246, 0.18);
            color: #93c5fd;
        }

        .table-chip.success {
            background: rgba(74, 222, 128, 0.15);
            color: #86efac;
        }

        .table-chip.warning {
            background: rgba(250, 204, 21, 0.15);
            color: #fef08a;
        }

        .table-chip.danger {
            background: rgba(251, 113, 133, 0.18);
            color: #fecdd3;
        }

        .panel-empty {
            text-align: center;
            padding: 2.75rem 1rem;
            color: var(--text-muted);
        }

        .panel-empty h3 {
            color: var(--text-primary);
        }

        .quick-links-grid {
            display: grid;
            gap: 1.4rem;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        }

        .quick-link-card {
            position: relative;
            display: grid;
            gap: 0.9rem;
            padding: 1.75rem;
            border-radius: 20px;
            text-decoration: none;
            color: inherit;
            background: radial-gradient(circle at 0% 0%, rgba(56, 189, 248, 0.15), transparent 55%),
                rgba(12, 18, 42, 0.85);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 25px 60px rgba(1, 6, 34, 0.5);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .quick-link-card:hover,
        .quick-link-card:focus {
            transform: translateY(-6px);
            box-shadow: 0 35px 70px rgba(1, 6, 34, 0.65);
        }

        .quick-link-card .status-pill {
            background: rgba(255, 255, 255, 0.1);
            color: var(--text-primary);
        }

        .quick-link-card p {
            margin-bottom: 0;
            color: var(--text-muted);
        }

        .quick-link-card .cta {
            color: var(--accent-2);
        }

        .btn-elevated {
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
            border-radius: 999px;
            padding: 0.65rem 1.6rem;
            font-weight: 600;
            border: none;
            background: linear-gradient(120deg, var(--accent-2), var(--accent-1));
            color: #0f172a;
            box-shadow: 0 18px 45px rgba(56, 189, 248, 0.45);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .btn-elevated:hover,
        .btn-elevated:focus {
            transform: translateY(-2px);
            box-shadow: 0 25px 55px rgba(56, 189, 248, 0.55);
        }

        .btn-outline-soft {
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
            border-radius: 999px;
            font-weight: 600;
            border: 1px solid rgba(255, 255, 255, 0.25);
            color: var(--text-primary);
            background: transparent;
            padding: 0.6rem 1.4rem;
            transition: all 0.2s ease;
        }

        .btn-outline-soft:hover,
        .btn-outline-soft:focus {
            border-color: var(--accent-2);
            color: var(--accent-2);
        }

        .btn-outline-soft.btn-outline-danger {
            border-color: rgba(251, 113, 133, 0.5);
            color: var(--danger);
            background: rgba(251, 113, 133, 0.08);
            box-shadow: 0 15px 35px rgba(251, 113, 133, 0.25);
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.32rem 0.9rem;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.08);
            color: var(--text-primary);
            font-weight: 600;
        }

        .surface-divider {
            width: 100%;
            height: 1px;
            background: linear-gradient(90deg, rgba(255, 255, 255, 0), rgba(255, 255, 255, 0.3), rgba(255, 255, 255, 0));
            margin: 1.5rem 0;
        }

        .flash-modern {
            border-radius: 18px;
            padding: 1rem 1.25rem;
            font-weight: 500;
            background: rgba(34, 197, 94, 0.15);
            border: 1px solid rgba(34, 197, 94, 0.45);
            color: #bbf7d0;
        }

        .hero-spotlight {
            position: relative;
            margin-bottom: 2.25rem;
            padding: clamp(2.5rem, 5vw, 3.8rem);
            border-radius: 32px;
            background: radial-gradient(circle at 20% 20%, rgba(56, 189, 248, 0.18), transparent 45%),
                radial-gradient(circle at 80% 0%, rgba(192, 132, 252, 0.2), transparent 45%),
                rgba(4, 6, 22, 0.9);
            border: 1px solid rgba(255, 255, 255, 0.08);
            overflow: hidden;
        }

        .hero-spotlight::after {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 15% 20%, rgba(255, 255, 255, 0.15), transparent 35%);
            filter: blur(60px);
            opacity: 0.7;
            pointer-events: none;
        }

        .hero-content {
            position: relative;
            z-index: 1;
        }

        .holo-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.35rem 0.95rem;
            border-radius: 999px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            background: rgba(255, 255, 255, 0.08);
            font-size: 0.82rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .hero-title {
            font-size: clamp(2.35rem, 5vw, 3.4rem);
            font-weight: 700;
            line-height: 1.1;
            margin: 1rem 0 0.75rem;
        }

        .hero-description {
            color: var(--text-muted);
            max-width: 42rem;
            font-size: 1.05rem;
        }

        .hero-actions {
            margin-top: 1.75rem;
            display: flex;
            gap: 0.85rem;
            flex-wrap: wrap;
        }

        .btn-ghost {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-primary);
            text-decoration: none;
        }

        .btn-ghost:hover {
            color: var(--accent-2);
        }

        .hero-stats {
            margin: 2.25rem 0 0;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 1.25rem;
        }

        .hero-stat {
            display: grid;
            gap: 0.25rem;
        }

        .hero-stat .stat-value {
            font-size: 1.65rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .hero-stat .stat-label {
            font-size: 0.85rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--text-muted);
        }

        @media (max-width: 768px) {
            .navbar-modern {
                border-radius: 0 0 18px 18px;
            }

            .data-panel {
                padding: 1.75rem 1.35rem;
            }

            .hero-spotlight {
                padding: 2rem;
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
                    @if (isset($item['dropdown']))
                        @php
                            $activePatterns = explode('|', $item['active'] ?? '');
                            $isActive = false;
                            foreach ($activePatterns as $pattern) {
                                if (request()->routeIs($pattern)) {
                                    $isActive = true;
                                    break;
                                }
                            }
                        @endphp
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle {{ $isActive ? 'active' : '' }}" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi {{ $item['icon'] }}"></i>
                                {{ $item['label'] }}
                            </a>
                            <ul class="dropdown-menu">
                                @foreach ($item['dropdown'] as $subItem)
                                    <li>
                                        <a class="dropdown-item" href="{{ route($subItem['route']) }}">
                                            <i class="bi {{ $subItem['icon'] }}"></i>
                                            {{ $subItem['label'] }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @else
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
                    @endif
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
        @if (session('warning'))
            <div class="alert alert-warning flash-modern">{{ session('warning') }}</div>
        @endif
        @yield('content')
    </div>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
@stack('scripts')
</body>
</html>
