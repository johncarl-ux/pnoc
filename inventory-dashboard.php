<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>PNOC Inventory | Dashboard</title>
    <meta name="description" content="Analytics dashboard for BENTACO and IOT inventory with disposition tabs and key operational insights." />
    <link rel="icon" type="image/png" href="qw.png" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <style>
        :root {
            --primary: #4f46e5;
            --primary-light: #6366f1;
            --primary-glow: rgba(79, 70, 229, 0.15);
            --bg-body: #f4f7fb;
            --card-bg: #ffffff;
            --text-dark: #0f172a;
            --text-muted: #64748b;
            --text-soft: #94a3b8;
            --sidebar-width: 260px;
            --border-color: #dbe4ef;
            --shadow-sm: 0 1px 2px rgba(15, 23, 42, 0.05);
            --shadow-md: 0 10px 24px rgba(15, 23, 42, 0.06);
            --shadow-lg: 0 18px 40px rgba(15, 23, 42, 0.10);
            --shadow-hover: 0 16px 30px rgba(37, 99, 235, 0.14);
            --radius-md: 14px;
            --radius-lg: 20px;
            --emerald: #10b981;
            --rose: #f43f5e;
            --amber: #f59e0b;
            --slate: #64748b;
            --sky: #0ea5e9;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background:
                radial-gradient(circle at 0% 0%, rgba(79, 70, 229, 0.09), transparent 30%),
                radial-gradient(circle at 100% 100%, rgba(14, 165, 233, 0.08), transparent 36%),
                var(--bg-body);
            color: var(--text-dark);
            line-height: 1.5;
            min-height: 100vh;
        }

        .app-layout { display: flex; min-height: 100vh; }

        .sidebar {
            width: var(--sidebar-width);
            background: linear-gradient(180deg, #ffffff, #fbfdff);
            box-shadow: 0 6px 18px rgba(2, 6, 23, 0.06);
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            overflow: hidden;
            z-index: 200;
            font-size: 14px;
            color: #000;
        }

        .sidebar-header {
            padding: 1.35rem 1.2rem;
            border-bottom: 1px solid #eef4fb;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: inherit;
        }

        .sidebar-brand img { width: 36px; height: 36px; object-fit: cover; border-radius: 8px; }
        .sidebar-brand-text { font-weight: 800; font-size: 0.96rem; }
        .sidebar-brand-sub { font-size: 0.7rem; color: #000; opacity: 0.72; font-weight: 500; }

        .sidebar-nav { padding: 1rem 0.8rem 1.15rem; }
        .nav-section { margin-bottom: 1.35rem; }
        .nav-section-title {
            font-size: 0.65rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #000;
            opacity: 0.42;
            padding: 0 0.6rem;
            margin-bottom: 0.55rem;
        }
        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.68rem 0.75rem;
            border-radius: 10px;
            text-decoration: none;
            color: #000;
            font-size: 0.875rem;
            font-weight: 600;
            transition: all 0.18s ease;
            margin-bottom: 0.2rem;
        }
        .nav-link:hover { background: #f7faff; color: var(--text-dark); }
        .nav-link.active {
            background: linear-gradient(90deg, var(--primary-glow), rgba(99, 102, 241, 0.08));
            color: var(--text-dark);
            position: relative;
        }
        .nav-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 8px;
            bottom: 8px;
            width: 4px;
            border-radius: 4px;
            background: var(--primary);
        }
        .nav-icon {
            width: 36px;
            height: 36px;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: transparent;
            flex-shrink: 0;
            font-size: 1rem;
        }
        .nav-link.active .nav-icon {
            background: var(--primary);
            color: white;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
        }
        .nav-label { white-space: nowrap; }

        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            padding: 2rem 2.75rem 2.75rem;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1.4rem;
        }
        .page-title {
            font-family: 'Playfair Display', Georgia, serif;
            font-size: clamp(2rem, 3vw, 2.6rem);
            line-height: 1.05;
            font-weight: 700;
            letter-spacing: -0.02em;
        }
        .page-subtitle {
            margin-top: 0.4rem;
            color: var(--text-muted);
            font-size: 0.95rem;
            max-width: 62rem;
        }
        .home-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            height: 40px;
            padding: 0 0.95rem;
            border-radius: 10px;
            border: 1px solid var(--border-color);
            background: #fff;
            color: var(--text-dark);
            text-decoration: none;
            font-weight: 700;
            box-shadow: 0 6px 18px rgba(2, 6, 23, 0.06);
            transition: transform 140ms ease, box-shadow 140ms ease, border-color 140ms ease;
        }
        .home-btn:hover {
            transform: translateY(-2px);
            border-color: #c8d7e6;
            box-shadow: 0 12px 28px rgba(2, 6, 23, 0.08);
        }

        .hero {
            background:
                linear-gradient(135deg, rgba(255,255,255,0.96) 0%, rgba(248,251,255,0.94) 100%),
                url('as.jpg') center/cover no-repeat;
            border: 1px solid #e4ebf3;
            border-radius: var(--radius-lg);
            overflow: hidden;
            box-shadow: var(--shadow-md);
            margin-bottom: 1.25rem;
        }
        .hero-grid {
            display: grid;
            grid-template-columns: 1.15fr 0.85fr;
            gap: 0;
            align-items: stretch;
        }
        .hero-copy { padding: 1.7rem; }
        .hero-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            font-size: 0.72rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--primary);
            margin-bottom: 0.8rem;
        }
        .hero-copy h2 {
            font-family: 'Playfair Display', Georgia, serif;
            font-size: clamp(1.6rem, 3.4vw, 2.4rem);
            line-height: 1.08;
            margin-bottom: 0.55rem;
            color: var(--text-dark);
        }
        .hero-copy p {
            color: var(--text-muted);
            font-size: 0.98rem;
            max-width: 46rem;
        }
        .hero-art {
            padding: 1.05rem;
            background: linear-gradient(180deg, rgba(248,251,255,0.72), rgba(255,255,255,0.95));
            border-left: 1px solid #e4ebf3;
            display: flex;
            flex-direction: column;
            gap: 0.85rem;
            justify-content: center;
        }
        .hero-image {
            width: 100%;
            height: 170px;
            object-fit: cover;
            border-radius: 18px;
            border: 1px solid #dbe4ef;
            box-shadow: 0 18px 32px rgba(15, 23, 42, 0.12);
            display: block;
        }
        .hero-stats-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0.85rem;
        }
        .hero-art .status-tabs {
            display: grid;
            grid-auto-flow: column;
            grid-auto-columns: minmax(150px, 1fr);
            gap: 0.9rem;
            overflow-x: auto;
            padding: 0.15rem 0 0.45rem;
            scrollbar-width: thin;
        }
        .hero-art .status-tab {
            min-height: 98px;
            padding: 0.95rem 0.95rem 0.85rem;
            border-radius: 14px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .hero-art .status-tab-label {
            margin-bottom: 0.45rem;
            font-size: 0.68rem;
            letter-spacing: 0.09em;
        }
        .hero-art .status-tab-value {
            font-size: 1.34rem;
            margin-bottom: 0.38rem;
            line-height: 1.05;
        }
        .hero-art .status-tab-note {
            font-size: 0.76rem;
            line-height: 1.28;
        }
        .hero-stat {
            background: #fff;
            border: 1px solid #e4ebf3;
            border-radius: 16px;
            padding: 1rem;
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.05);
        }
        .hero-stat span {
            display: block;
            font-size: 0.72rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.06em;
            font-weight: 700;
            margin-bottom: 0.35rem;
        }
        .hero-stat strong {
            display: block;
            font-size: 1.7rem;
            line-height: 1;
            margin-bottom: 0.25rem;
            color: var(--text-dark);
        }
        .hero-stat small {
            color: var(--text-muted);
            font-size: 0.78rem;
        }
        .report-grid-3 {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 1rem;
        }

        .report-grid-2 {
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            gap: 1rem;
        }

        .section-card {
            background: var(--card-bg);
            border: 1px solid #e4ebf3;
            border-radius: var(--radius-lg);
            padding: 1.25rem;
            margin-bottom: 1.25rem;
            box-shadow: var(--shadow-md);
        }
        .section-head {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        .section-head h3 {
            font-size: 1rem;
            font-weight: 800;
            color: var(--text-dark);
        }
        .section-head p {
            color: var(--text-muted);
            font-size: 0.86rem;
            margin-top: 0.2rem;
        }

        .status-tabs {
            display: grid;
            grid-template-columns: repeat(5, minmax(0, 1fr));
            gap: 0.9rem;
        }
        .status-tab {
            appearance: none;
            border: 1px solid #e4ebf3;
            background: linear-gradient(180deg, #ffffff, #fbfdff);
            border-radius: 16px;
            padding: 1rem;
            text-align: left;
            cursor: pointer;
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.05);
            transition: transform 160ms ease, box-shadow 180ms ease, border-color 180ms ease, background 180ms ease;
            position: relative;
            overflow: hidden;
            min-height: 96px;
        }
        .status-tab::before {
            content: '';
            position: absolute;
            left: 12px;
            right: 12px;
            bottom: 0;
            height: 3px;
            border-radius: 999px;
            background: linear-gradient(90deg, var(--primary), var(--primary-light));
            opacity: 0;
            transform: scaleX(0.6);
            transition: opacity 180ms ease, transform 180ms ease;
        }
        .status-tab:hover {
            transform: translateY(-2px);
            border-color: #cfdbea;
            box-shadow: 0 14px 28px rgba(15, 23, 42, 0.10);
        }
        .status-tab.active {
            border-color: #9ec3f5;
            background: linear-gradient(180deg, #ffffff, #f5f9ff);
            box-shadow: var(--shadow-hover);
        }
        .status-tab.active::before { opacity: 1; transform: scaleX(1); }
        .status-tab-label {
            display: block;
            font-size: 0.72rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--text-muted);
            margin-bottom: 0.35rem;
        }
        .status-tab.active .status-tab-label { color: var(--primary); }
        .status-tab-value {
            display: block;
            font-size: 1.55rem;
            line-height: 1;
            font-weight: 800;
            margin-bottom: 0.3rem;
        }
        .status-tab-note {
            font-size: 0.8rem;
            color: var(--text-muted);
        }

        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 1rem;
        }
        .kpi-card {
            background: linear-gradient(180deg, #ffffff, #fbfdff);
            border: 1px solid #e4ebf3;
            border-radius: 16px;
            padding: 1.1rem 1.1rem 1rem;
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.05);
            position: relative;
            overflow: hidden;
        }
        .kpi-card::before {
            content: '';
            position: absolute;
            inset: 0 auto auto 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, var(--primary), var(--primary-light));
            opacity: 0.85;
        }
        .kpi-label {
            display: block;
            font-size: 0.73rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--text-muted);
            margin-bottom: 0.35rem;
            margin-top: 0.15rem;
        }
        .kpi-value {
            font-size: 1.85rem;
            font-weight: 800;
            letter-spacing: -0.03em;
            line-height: 1;
            margin-bottom: 0.35rem;
        }
        .kpi-copy {
            font-size: 0.82rem;
            color: var(--text-muted);
        }

        .charts-grid {
            display: grid;
            grid-template-columns: 1.05fr 0.95fr 1fr;
            gap: 1rem;
        }
        .chart-card {
            background: #fff;
            border: 1px solid #e4ebf3;
            border-radius: var(--radius-lg);
            padding: 1.15rem;
            box-shadow: var(--shadow-md);
            min-height: 360px;
        }
        .chart-card h4 {
            font-size: 0.95rem;
            font-weight: 800;
            color: var(--text-dark);
            margin-bottom: 0.25rem;
        }
        .chart-card p {
            color: var(--text-muted);
            font-size: 0.82rem;
            margin-bottom: 0.85rem;
        }
        .chart-box {
            height: 280px;
            position: relative;
        }
        .chart-box canvas { width: 100% !important; height: 100% !important; }

        .table-card {
            background: #fff;
            border: 1px solid #e4ebf3;
            border-radius: var(--radius-lg);
            padding: 1.15rem;
            box-shadow: var(--shadow-md);
        }
        .table-toolbar {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 0.75rem;
            margin-bottom: 0.9rem;
        }
        .table-search {
            height: 40px;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            padding: 0 0.9rem;
            font: inherit;
            color: var(--text-dark);
            background: #fff;
        }
        .table-search:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--primary-glow);
        }
        .drill-table-wrap {
            overflow: auto;
            border: 1px solid #e4ebf3;
            border-radius: 14px;
            background: #fff;
        }
        .drill-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 860px;
        }
        .drill-table th,
        .drill-table td {
            padding: 0.8rem 0.85rem;
            border-bottom: 1px solid #edf2f7;
            font-size: 0.84rem;
            text-align: left;
            vertical-align: middle;
        }
        .drill-table thead th {
            position: sticky;
            top: 0;
            background: linear-gradient(180deg, #fbfdff, #f4f8fc);
            font-size: 0.72rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #111827;
            font-weight: 800;
        }
        .drill-table tbody tr:nth-child(even) td { background: #fafcff; }
        .drill-table tbody tr:hover td { background: #eef6ff; }
        .table-note {
            margin-top: 0.65rem;
            font-size: 0.8rem;
            color: var(--text-muted);
        }

        .exception-list {
            display: grid;
            gap: 0.75rem;
        }
        .exception-item {
            display: flex;
            justify-content: space-between;
            gap: 1rem;
            padding: 0.85rem 0.95rem;
            border: 1px solid #e4ebf3;
            border-radius: 12px;
            background: #f8fbff;
        }
        .exception-item strong {
            display: block;
            font-size: 0.88rem;
            color: var(--text-dark);
            margin-bottom: 0.2rem;
        }
        .exception-item span {
            font-size: 0.8rem;
            color: var(--text-muted);
        }
        .exception-count {
            font-size: 1.15rem;
            font-weight: 800;
            color: var(--text-dark);
            white-space: nowrap;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 86px;
            padding: 0.32rem 0.55rem;
            border-radius: 999px;
            font-size: 0.72rem;
            font-weight: 800;
            border: 1px solid transparent;
        }
        .badge.source-bentaco { background: #eef2ff; color: #4338ca; border-color: #c7d2fe; }
        .badge.source-iot { background: #ecfeff; color: #0e7490; border-color: #a5f3fc; }
        .badge.status-usable { background: #ecfdf5; color: #047857; border-color: #a7f3d0; }
        .badge.status-maintenance { background: #eff6ff; color: #1d4ed8; border-color: #bfdbfe; }
        .badge.status-retired { background: #f1f5f9; color: #475569; border-color: #cbd5e1; }
        .badge.disposition-retain { background: #ecfdf5; color: #047857; border-color: #a7f3d0; }
        .badge.disposition-transfer { background: #fffbeb; color: #b45309; border-color: #fde68a; }
        .badge.disposition-dispose { background: #fef2f2; color: #b91c1c; border-color: #fecaca; }
        .badge.disposition-store-warehouse { background: #f8fafc; color: #334155; border-color: #cbd5e1; }
        .badge.disposition-unassigned { background: #f8fafc; color: #64748b; border-color: #e2e8f0; }

        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 250px;
            color: var(--text-muted);
            text-align: center;
            padding: 1.5rem;
        }
        .empty-state-icon {
            width: 52px;
            height: 52px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f3f6fb;
            margin-bottom: 0.85rem;
            font-size: 1.45rem;
            color: var(--text-soft);
        }
        .empty-state-text { font-size: 0.92rem; font-weight: 700; color: var(--text-dark); }
        .empty-state-sub { font-size: 0.8rem; margin-top: 0.25rem; }

        @media (max-width: 1200px) {
            .main-content { padding: 1.5rem 1.4rem 2rem; }
            .hero-grid { grid-template-columns: 1fr; }
            .hero-art { border-left: 0; border-top: 1px solid #e4ebf3; }
            .report-grid-3 { grid-template-columns: 1fr; }
            .report-grid-2 { grid-template-columns: 1fr; }
            .status-tabs { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .kpi-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .drill-table { min-width: 760px; }
        }

        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .main-content { margin-left: 0; padding: 1rem 0.85rem 1.5rem; }
            .page-header { flex-direction: column; align-items: flex-start; }
            .page-title { font-size: 1.9rem; }
            .status-tabs { grid-template-columns: 1fr; }
            .kpi-grid { grid-template-columns: 1fr; }
            .hero-art .status-tabs { grid-auto-columns: minmax(140px, 1fr); }
            .table-toolbar { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="app-layout">
        <aside class="sidebar" aria-label="Primary Navigation">
            <div class="sidebar-header">
                <a href="index.html" class="sidebar-brand">
                    <img src="qw.png" alt="PNOC" />
                    <div>
                        <div class="sidebar-brand-text">PNOC Inventory</div>
                        <div class="sidebar-brand-sub">Management System</div>
                    </div>
                </a>
            </div>
            <nav class="sidebar-nav" role="navigation">
                <div class="nav-section">
                    <div class="nav-section-title">Main Menu</div>
                    <a href="inventory-dashboard.php" class="nav-link active"><span class="nav-icon">⌂</span><span class="nav-label">Dashboard</span></a>
                    <a href="bentaco-inventory.php" class="nav-link"><span class="nav-icon">☐</span><span class="nav-label">BENTACO Inventory</span></a>
                    <a href="iot-inventory.php" class="nav-link"><span class="nav-icon">◎</span><span class="nav-label">IOT Inventory</span></a>
                </div>
                <div class="nav-section">
                    <div class="nav-section-title">Management</div>
                    <a href="location-management.php" class="nav-link"><span class="nav-icon">⊕</span><span class="nav-label">Location Management</span></a>
                    <a href="item-status-monitoring.php" class="nav-link"><span class="nav-icon">◉</span><span class="nav-label">Item Status Monitoring</span></a>
                </div>
            </nav>
        </aside>

        <main class="main-content">
            <div class="page-header">
                <div>
                    <h1 class="page-title">Inventory Dashboard</h1>
                </div>
                <a href="index.html" class="home-btn">Home</a>
            </div>

            <section class="hero" aria-label="Dashboard overview">
                <div class="hero-grid">
                    <div class="hero-copy">
                        <div class="hero-eyebrow">Analytics overview</div>
                        <h2>One screen for the signals that matter.</h2>
                        <p>
                            This dashboard combines BENTACO and IOT inventory records, then surfaces the disposition tabs, source split, and status mix so the team can read the portfolio at a glance.
                        </p>
                    </div>
                    <div class="hero-art">
                        <img src="as.jpg" alt="PNOC inventory visual" class="hero-image" />
                        <div style="display:flex;justify-content:space-between;align-items:center;margin-top:0.2rem;margin-bottom:0.1rem;">
                            <strong style="font-size:0.82rem;letter-spacing:0.06em;text-transform:uppercase;color:var(--text-muted);">Disposition Tabs</strong>
                            <div style="font-size:0.84rem;color:var(--text-muted);font-weight:600;" id="scopeLabel">All records</div>
                        </div>
                        <div class="status-tabs" id="statusTabs">
                            <button class="status-tab active" type="button" data-status="all">
                                <span class="status-tab-label">All</span>
                                <span class="status-tab-value" id="tabCountAll">0</span>
                                <span class="status-tab-note">Full inventory scope</span>
                            </button>
                            <button class="status-tab" type="button" data-status="retain">
                                <span class="status-tab-label">Retain</span>
                                <span class="status-tab-value" id="tabCountRetain">0</span>
                                <span class="status-tab-note">Keep in active use</span>
                            </button>
                            <button class="status-tab" type="button" data-status="transfer">
                                <span class="status-tab-label">Transfer</span>
                                <span class="status-tab-value" id="tabCountTransfer">0</span>
                                <span class="status-tab-note">Move to another unit</span>
                            </button>
                            <button class="status-tab" type="button" data-status="dispose">
                                <span class="status-tab-label">Dispose</span>
                                <span class="status-tab-value" id="tabCountDispose">0</span>
                                <span class="status-tab-note">For retirement or disposal</span>
                            </button>
                            <button class="status-tab" type="button" data-status="store/warehouse">
                                <span class="status-tab-label">Store/Warehouse</span>
                                <span class="status-tab-value" id="tabCountStore">0</span>
                                <span class="status-tab-note">Reserve and storage</span>
                            </button>
                        </div>
                    </div>
                </div>
            </section>

            <section class="report-section" aria-label="Report insights">
                <div class="report-grid-3">
                    <div class="chart-card">
                        <h4>Trend Over Time</h4>
                        <p>Monthly item activity using the available record dates.</p>
                        <div class="chart-box" id="trendChartBox">
                            <div class="empty-state">
                                <div class="empty-state-icon">◌</div>
                                <div class="empty-state-text">Loading chart</div>
                                <div class="empty-state-sub">Preparing trend analysis</div>
                            </div>
                        </div>
                    </div>
                    <div class="chart-card">
                        <h4>Retain / Transfer / Dispose / Store-Warehouse</h4>
                        <p>Stacked source comparison for the key disposition actions.</p>
                        <div class="chart-box" id="comparisonChartBox">
                            <div class="empty-state">
                                <div class="empty-state-icon">◌</div>
                                <div class="empty-state-text">Loading chart</div>
                                <div class="empty-state-sub">Preparing comparison analysis</div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="report-section" aria-label="Exceptions and drill-down">
                <div class="report-grid-2">
                    <div class="table-card">
                        <div class="section-head">
                            <div>
                                <h3>Exceptions</h3>
                                <p>Items that need attention before reporting or actioning.</p>
                            </div>
                        </div>
                        <div class="exception-list" id="exceptionList"></div>
                    </div>
                    <div class="table-card">
                        <div class="section-head">
                            <div>
                                <h3>Drill-down Records</h3>
                                <p>Current tab view with searchable detail rows.</p>
                            </div>
                        </div>
                        <div class="table-toolbar">
                            <input id="tableSearch" class="table-search" type="search" placeholder="Search item, department, status, source" />
                            <div style="display:flex;align-items:center;font-size:0.84rem;color:var(--text-muted);font-weight:600;" id="tableCount">0 records</div>
                        </div>
                        <div class="drill-table-wrap">
                            <table class="drill-table">
                                <thead>
                                    <tr>
                                        <th>Source</th>
                                        <th>Item</th>
                                        <th>Department</th>
                                        <th>Status</th>
                                        <th>Disposition</th>
                                        <th>Last Updated</th>
                                    </tr>
                                </thead>
                                <tbody id="drillTableBody">
                                    <tr><td colspan="6">Loading...</td></tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="table-note">Rows update with the selected disposition tab and search term.</div>
                    </div>
                </div>
            </section>

        </main>
    </div>

    <script>
        const STORAGE_KEYS = ["pnoc_inventory_bentaco_v1", "pnoc_inventory_iot_v1"];
        const TAB_LABELS = {
            all: "All records",
            retain: "Retain",
            transfer: "Transfer",
            dispose: "Dispose",
            "store/warehouse": "Store/Warehouse"
        };
        const SOURCE_PALETTE = {
            BENTACO: "#4f46e5",
            IOT: "#0ea5e9",
            Unknown: "#94a3b8"
        };
        const STATUS_PALETTE = {
            Usable: "#10b981",
            "Under Maintenance": "#0ea5e9",
            Retired: "#64748b",
            "No Status": "#cbd5e1"
        };
        const DISPOSITION_PALETTE = {
            Retain: "#10b981",
            Transfer: "#f59e0b",
            Dispose: "#ef4444",
            "Store/Warehouse": "#64748b",
            Unassigned: "#94a3b8"
        };

        const charts = [];
        let activeTab = "all";
        let allRows = [];
        let tableSearchValue = "";
        const INVENTORY_API_URL = "api/inventory.php";

        function normalizeText(value) {
            return String(value || "").trim();
        }

        function loadLocalRows() {
            const rows = [];
            STORAGE_KEYS.forEach((key) => {
                try {
                    const raw = localStorage.getItem(key);
                    const parsed = raw ? JSON.parse(raw) : [];
                    if (Array.isArray(parsed)) {
                        const source = key.includes("iot") ? "IOT" : "BENTACO";
                        parsed.forEach((row) => rows.push({ ...row, source }));
                    }
                } catch (error) {
                    console.error("Failed to load inventory rows:", error);
                }
            });
            return rows;
        }

        async function loadRows() {
            const localRows = loadLocalRows();
            try {
                const response = await fetch(`${INVENTORY_API_URL}?source=all`, { cache: "no-store" });
                if (!response.ok) {
                    throw new Error("Unable to load inventory API data.");
                }
                const payload = await response.json();
                const apiRows = Array.isArray(payload.rows) ? payload.rows : [];
                if (apiRows.length) {
                    return apiRows;
                }
            } catch (error) {
                console.error("Inventory API load failed:", error);
            }
            return localRows;
        }

        function normalizeStatus(value) {
            const text = normalizeText(value).toLowerCase();
            if (text === "usable") return "Usable";
            if (text === "under maintenance" || text === "maintenance") return "Under Maintenance";
            if (text === "retired") return "Retired";
            if (text === "damaged" || text === "defective" || text === "unusable" || text === "not usable") return "Retired";
            return normalizeText(value) || "No Status";
        }

        function normalizeDisposition(row) {
            const candidates = [row.action, row.disposition, row.itemAction, row.transferAction, row.statusAction];
            for (const candidate of candidates) {
                const text = normalizeText(candidate).toLowerCase();
                if (!text) continue;
                if (text === "retain") return "Retain";
                if (text === "transfer") return "Transfer";
                if (text === "dispose" || text === "disposal" || text === "retire" || text === "retired") return "Dispose";
                if (text === "store/warehouse" || text === "store" || text === "warehouse") return "Store/Warehouse";
            }
            return "Unassigned";
        }

        function normalizeDepartment(row) {
            return normalizeText(row.department || row.office || row.section || row.location || row.itemLocation || "No Department") || "No Department";
        }

        function normalizeDateText(row) {
            return normalizeText(row.lastUpdated || row.dateAdded || row.allocationDate || row.returnDate || row.createdAt || row.updatedAt || row.purchaseDate || row.acquisitionDate || "");
        }

        function parseDateValue(value) {
            const text = normalizeText(value);
            if (!text) return null;
            const parsed = new Date(text);
            if (!Number.isNaN(parsed.getTime())) return parsed;
            const yearMatch = text.match(/(19|20)\d{2}/);
            if (yearMatch) {
                const monthMatch = text.match(/\b(0?[1-9]|1[0-2])\b/);
                const month = monthMatch ? Number(monthMatch[1]) - 1 : 0;
                return new Date(Number(yearMatch[0]), month, 1);
            }
            return null;
        }

        function monthKey(dateValue) {
            return `${dateValue.getFullYear()}-${String(dateValue.getMonth() + 1).padStart(2, "0")}`;
        }

        function monthLabel(key) {
            const [year, month] = key.split("-").map(Number);
            if (!year || !month) return key;
            return new Date(year, month - 1, 1).toLocaleString("en-US", { month: "short", year: "numeric" });
        }

        function statusClass(status) {
            const normalized = normalizeStatus(status).toLowerCase();
            if (normalized === "usable") return "status-usable";
            if (normalized === "under maintenance") return "status-maintenance";
            return "status-retired";
        }

        function dispositionClass(disposition) {
            const normalized = normalizeText(disposition).toLowerCase().replace(/[^a-z0-9]+/g, "-");
            return `disposition-${normalized || "unassigned"}`;
        }

        function countBy(rows, valueFn, fallbackLabel) {
            return rows.reduce((acc, row) => {
                const key = normalizeText(valueFn(row)) || fallbackLabel;
                acc[key] = (acc[key] || 0) + 1;
                return acc;
            }, {});
        }

        function topEntries(mapObject, limit = 6) {
            return Object.entries(mapObject).sort((a, b) => b[1] - a[1]).slice(0, limit);
        }

        function sortedMonthlyCounts(rows) {
            const counts = rows.reduce((acc, row) => {
                const dateValue = parseDateValue(normalizeDateText(row));
                if (!dateValue) return acc;
                const key = monthKey(dateValue);
                acc[key] = (acc[key] || 0) + 1;
                return acc;
            }, {});
            return Object.entries(counts).sort((a, b) => a[0].localeCompare(b[0]));
        }

        function currentViewRows() {
            const tabRows = selectedRows(allRows);
            const search = tableSearchValue.trim().toLowerCase();
            if (!search) return tabRows;
            return tabRows.filter((row) => {
                const haystack = [
                    row.source,
                    row.itemDescription,
                    row.item_description,
                    row.description,
                    row.propertyNumber,
                    row.itemId,
                    row.department,
                    row.office,
                    row.section,
                    row.location,
                    row.itemLocation,
                    row.itemStatus,
                    normalizeDisposition(row),
                    normalizeDateText(row)
                ].map(normalizeText).join(" ").toLowerCase();
                return haystack.includes(search);
            });
        }

        function destroyCharts() {
            while (charts.length) {
                charts.pop().destroy();
            }
        }

        function renderEmpty(containerId, title, subtitle) {
            const container = document.getElementById(containerId);
            if (!container) return;
            container.innerHTML = `
                <div class="empty-state">
                    <div class="empty-state-icon">📊</div>
                    <div class="empty-state-text">${title}</div>
                    <div class="empty-state-sub">${subtitle}</div>
                </div>
            `;
        }

        function buildChart(canvasId, config) {
            const container = document.getElementById(canvasId);
            if (!container) return null;
            const canvas = document.createElement("canvas");
            container.innerHTML = "";
            container.appendChild(canvas);
            const chart = new Chart(canvas, config);
            charts.push(chart);
            return chart;
        }

        function selectedRows(rows) {
            if (activeTab === "all") return rows;
            return rows.filter((row) => normalizeDisposition(row).toLowerCase() === activeTab);
        }

        function updateTabStates() {
            document.querySelectorAll(".status-tab").forEach((button) => {
                const status = button.getAttribute("data-status") || "all";
                const isActive = status === activeTab;
                button.classList.toggle("active", isActive);
                button.setAttribute("aria-pressed", isActive ? "true" : "false");
            });
        }

        function setTab(status) {
            activeTab = status;
            updateTabStates();
            render();
        }

        function bindTabs() {
            document.querySelectorAll(".status-tab").forEach((button) => {
                button.addEventListener("click", () => {
                    const status = button.getAttribute("data-status") || "all";
                    setTab(status);
                });
            });
        }

        function updateTabCounts(rows) {
            const counts = {
                all: rows.length,
                retain: 0,
                transfer: 0,
                dispose: 0,
                "store/warehouse": 0
            };

            rows.forEach((row) => {
                const disposition = normalizeDisposition(row).toLowerCase();
                if (counts.hasOwnProperty(disposition)) {
                    counts[disposition] += 1;
                }
            });

            const map = {
                tabCountAll: counts.all,
                tabCountRetain: counts.retain,
                tabCountTransfer: counts.transfer,
                tabCountDispose: counts.dispose,
                tabCountStore: counts["store/warehouse"]
            };

            Object.entries(map).forEach(([id, value]) => {
                const element = document.getElementById(id);
                if (element) element.textContent = String(value);
            });
        }

        function updateHeroMetrics(viewRows) {
            const scopeLabel = document.getElementById("scopeLabel");
            if (scopeLabel) scopeLabel.textContent = TAB_LABELS[activeTab] || "All records";
        }

        function updateKpis(viewRows) {
            return viewRows.length;
        }

        function updateTabSummary(viewRows) {
            const label = TAB_LABELS[activeTab] || "All records";
            const scopeLabel = document.getElementById("scopeLabel");
            if (scopeLabel) scopeLabel.textContent = label;
        }

        function renderCharts(viewRows) {
            destroyCharts();

            const monthlyEntries = sortedMonthlyCounts(viewRows);
            const dispositionCategories = ["Retain", "Transfer", "Dispose", "Store/Warehouse"];
            const sourceRows = ["BENTACO", "IOT"];

            const trendBox = document.getElementById("trendChartBox");
            const comparisonBox = document.getElementById("comparisonChartBox");

            if (!viewRows.length) {
                renderEmpty("trendChartBox", "No trend data", "Change tabs or load inventory records.");
                renderEmpty("comparisonChartBox", "No comparison data", "Change tabs or load inventory records.");
                return;
            }

            trendBox.innerHTML = '<canvas id="trendChart"></canvas>';
            comparisonBox.innerHTML = '<canvas id="comparisonChart"></canvas>';

            buildChart("trendChart", {
                type: "line",
                data: {
                    labels: monthlyEntries.map((entry) => monthLabel(entry[0])),
                    datasets: [{
                        label: "Items",
                        data: monthlyEntries.map((entry) => entry[1]),
                        borderColor: "#4f46e5",
                        backgroundColor: "rgba(79,70,229,0.12)",
                        fill: true,
                        tension: 0.35,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: { callbacks: { label: (context) => `${context.raw || 0} items` } }
                    },
                    scales: {
                        x: {
                            grid: { color: "#f1f5f9" },
                            ticks: { maxRotation: 0, autoSkip: true }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: { precision: 0 },
                            grid: { color: "#eef3f8" }
                        }
                    }
                }
            });

            buildChart("comparisonChart", {
                type: "bar",
                data: {
                    labels: dispositionCategories,
                    datasets: sourceRows.map((source) => ({
                        label: source,
                        data: dispositionCategories.map((category) => viewRows.filter((row) => row.source === source && normalizeDisposition(row) === category).length),
                        backgroundColor: SOURCE_PALETTE[source] || SOURCE_PALETTE.Unknown,
                        borderRadius: 8,
                        borderSkipped: false
                    }))
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: "bottom", labels: { usePointStyle: true, pointStyle: "circle", padding: 16 } },
                        tooltip: { callbacks: { label: (context) => `${context.dataset.label}: ${context.raw || 0}` } }
                    },
                    scales: {
                        x: { stacked: true, grid: { display: false } },
                        y: {
                            stacked: true,
                            beginAtZero: true,
                            ticks: { precision: 0 },
                            grid: { color: "#eef3f8" }
                        }
                    }
                }
            });
        }

        function render() {
            updateTabCounts(allRows);
            const viewRows = selectedRows(allRows);
            updateTabSummary(viewRows);
            updateHeroMetrics(viewRows);
            updateKpis(viewRows);
            renderCharts(viewRows);
            renderExceptions(viewRows);
            renderDrillTable(viewRows);
        }

        async function bootstrap() {
            allRows = await loadRows();
            updateTabCounts(allRows);
            const viewRows = selectedRows(allRows);
            updateTabSummary(viewRows);
            updateHeroMetrics(viewRows);
            updateKpis(viewRows);
            renderCharts(viewRows);
            renderExceptions(viewRows);
            renderDrillTable(viewRows);
        }

        function exceptionRows(viewRows) {
            return [
                {
                    label: "Unassigned Disposition",
                    description: "Records without a disposition action.",
                    count: viewRows.filter((row) => normalizeDisposition(row) === "Unassigned").length
                },
                {
                    label: "Missing Department",
                    description: "Rows without a department/location value.",
                    count: viewRows.filter((row) => normalizeDepartment(row) === "No Department").length
                },
                {
                    label: "Under Maintenance",
                    description: "Items still in active handling.",
                    count: viewRows.filter((row) => normalizeStatus(row.itemStatus) === "Under Maintenance").length
                },
                {
                    label: "Retired Items",
                    description: "Items already marked as retired.",
                    count: viewRows.filter((row) => normalizeStatus(row.itemStatus) === "Retired").length
                }
            ].filter((entry) => entry.count > 0 || entry.label === "Unassigned Disposition");
        }

        function renderExceptions(viewRows) {
            const container = document.getElementById("exceptionList");
            if (!container) return;
            const rows = exceptionRows(viewRows);
            if (!rows.length) {
                container.innerHTML = '<div class="empty-state" style="min-height:180px;"><div class="empty-state-icon">✓</div><div class="empty-state-text">No exceptions found</div><div class="empty-state-sub">Current view is clean</div></div>';
                return;
            }
            container.innerHTML = rows.map((entry) => `
                <div class="exception-item">
                    <div>
                        <strong>${entry.label}</strong>
                        <span>${entry.description}</span>
                    </div>
                    <div class="exception-count">${entry.count}</div>
                </div>
            `).join("");
        }

        function renderDrillTable(viewRows) {
            const container = document.getElementById("drillTableBody");
            const count = document.getElementById("tableCount");
            const rows = currentViewRows();
            if (count) count.textContent = `${rows.length} record${rows.length === 1 ? "" : "s"}`;
            if (!container) return;

            if (!rows.length) {
                container.innerHTML = '<tr><td colspan="6" style="padding:1rem;text-align:center;color:var(--text-muted);">No rows match the selected filters.</td></tr>';
                return;
            }

            container.innerHTML = rows.slice(0, 80).map((row) => {
                const itemLabel = normalizeText(row.itemDescription || row.item_description || row.description || row.propertyNumber || row.itemId || "Unnamed item");
                const status = normalizeStatus(row.itemStatus);
                const disposition = normalizeDisposition(row);
                return `
                    <tr>
                        <td><span class="badge ${row.source === "IOT" ? "source-iot" : "source-bentaco"}">${row.source || "Unknown"}</span></td>
                        <td>${itemLabel}</td>
                        <td>${normalizeDepartment(row)}</td>
                        <td><span class="badge ${statusClass(status)}">${status}</span></td>
                        <td><span class="badge ${dispositionClass(disposition)}">${disposition}</span></td>
                        <td>${normalizeDateText(row) || "-"}</td>
                    </tr>
                `;
            }).join("");
        }

        bindTabs();
        const searchInput = document.getElementById("tableSearch");
        if (searchInput) {
            searchInput.addEventListener("input", () => {
                tableSearchValue = searchInput.value || "";
                renderDrillTable(selectedRows(allRows));
            });
        }
        updateTabStates();
        bootstrap();
    </script>
</body>
</html>
