<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>PNOC Inventory | Item Status Monitoring</title>
    <link rel="icon" href="qw.png" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/jspdf@2.5.1/dist/jspdf.umd.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/jspdf-autotable@3.8.2/dist/jspdf.plugin.autotable.min.js"></script>
    <style>
        :root{--bg:#f6f8fb;--card:#fff;--muted:#64748b;--text:#0f172a;--accent:#2563eb;--green:#10b981;--yellow:#f59e0b;--red:#ef4444;--gray:#6b7280;--radius:12px;--sidebar:260px;--shadow:0 8px 30px rgba(2,6,23,0.06)}
        *{box-sizing:border-box}
        body{font-family:Inter,Segoe UI,system-ui,-apple-system,sans-serif;margin:0;background:var(--bg);color:var(--text)}
        .app{display:flex;min-height:100vh}
        .sidebar{width:var(--sidebar);background:var(--card);border-right:1px solid #e6edf3;padding:18px;position:fixed;inset:0 auto auto 0}
        .brand{display:flex;gap:10px;align-items:center;margin-bottom:12px}
        .brand img{width:36px;height:36px}
        .nav{margin-top:8px}
        .nav a{display:flex;align-items:center;gap:10px;padding:10px;border-radius:8px;color:var(--text);text-decoration:none;margin-bottom:6px}
        .nav a.active{background:linear-gradient(90deg,#eef4ff,#f6fbff);box-shadow:var(--shadow)}
        .main{flex:1;margin-left:var(--sidebar);padding:28px}
		.page-head{display:flex;justify-content:space-between;align-items:center}
		.page-head h1{margin:0;font-size:22px}
		.home-btn{display:inline-flex;align-items:center;gap:0.5rem;padding:8px 10px;border-radius:8px;background:#fff;border:1px solid #eef4fb;color:var(--text);text-decoration:none;font-weight:700}
		.home-btn:hover{transform:translateY(-2px);box-shadow:0 10px 30px rgba(2,6,23,0.06)}

        /* Summary cards */
        .cards{display:grid;grid-template-columns:repeat(5,1fr);gap:12px;margin-top:18px}
		.card[data-status]{cursor:pointer}
		.card.active{box-shadow:0 8px 30px rgba(2,6,23,0.08);border:2px solid rgba(37,99,235,0.12)}
        .card{background:var(--card);padding:14px;border-radius:var(--radius);box-shadow:var(--shadow);display:flex;gap:12px;align-items:center}
        .card .icon{width:46px;height:46;border-radius:10px;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700}
        .card .meta .num{font-size:18px;font-weight:700}
        .card .meta .label{font-size:12px;color:var(--muted)}

		/* layout */
		.layout{display:grid;grid-template-columns:1fr;gap:14px;margin-top:18px}
        .panel{background:var(--card);border-radius:12px;padding:14px;box-shadow:var(--shadow)}
        .filters{display:flex;gap:8px;align-items:center}
        .filters input,.filters select{padding:10px;border-radius:10px;border:1px solid #eef4fb;background:#fff}

        /* alerts and actions */
        .alerts{display:flex;gap:8px;margin-top:12px}
        .alert{padding:10px;border-radius:8px;border:1px solid #eef4fb;background:#fff;font-size:13px}

        /* table */
        .table-wrap{margin-top:14px}
        table{width:100%;border-collapse:collapse;background:transparent}
        thead th{position:sticky;top:0;background:var(--card);padding:12px;text-align:left;border-bottom:1px solid #eef4fb}
        tbody tr{background:var(--card);border-bottom:1px solid #f3f6fb}
        tbody tr:nth-child(even){background:#fbfdff}
        td{padding:12px;vertical-align:middle}
        tr:hover td{background:#fcfeff}
		.thumb{width:96px;height:96px;border-radius:12px;object-fit:cover;border:1px solid #d6e1ec;background:#f8fafc;display:block;cursor:zoom-in;box-shadow:0 6px 16px rgba(15,23,42,0.08)}
		.thumb-empty{width:96px;height:96px;border-radius:12px;border:1px dashed #cbd5e1;background:#f8fafc;display:flex;align-items:center;justify-content:center;color:#94a3b8;font-size:11px;font-weight:600}
		.image-viewer{position:fixed;inset:0;background:rgba(15,23,42,0.78);display:none;align-items:center;justify-content:center;z-index:9999;padding:24px}
		.image-viewer.open{display:flex}
		.image-viewer-card{background:#fff;border-radius:16px;max-width:min(92vw,1100px);max-height:92vh;padding:14px;box-shadow:0 24px 60px rgba(0,0,0,0.28);display:flex;flex-direction:column;gap:10px}
		.image-viewer-card img{max-width:100%;max-height:78vh;object-fit:contain;border-radius:12px;background:#f8fafc}
		.image-viewer-header{display:flex;justify-content:space-between;align-items:center;gap:10px}
		.image-viewer-title{font-weight:700;color:#0f172a;font-size:14px}
		.image-viewer-close{border:1px solid #d7e1ec;background:#fff;border-radius:10px;height:34px;padding:0 12px;font-weight:600;cursor:pointer}
		.img-cell{display:flex;align-items:center;gap:10px}
		.img-actions{display:flex;flex-direction:column;gap:6px}
		.img-action-btn{height:28px;padding:0 10px;border-radius:8px;border:1px solid #d7e1ec;background:#fff;color:var(--text-dark);font-size:12px;font-weight:600;cursor:pointer;white-space:nowrap}
		.img-action-btn:hover{border-color:#b9c7d6;box-shadow:0 6px 14px rgba(15,23,42,0.06)}
		.img-action-btn.danger{color:#b42318;border-color:#f2c7c3;background:#fff7f7}
		.img-action-btn.danger:hover{border-color:#f0a39c;box-shadow:0 6px 14px rgba(180,35,24,0.08)}
		.status-badge{
			display:inline-flex;
			align-items:center;
			justify-content:center;
			min-width:124px;
			padding:7px 12px;
			border-radius:999px;
			color:#fff;
			font-weight:700;
			font-size:12px;
			letter-spacing:.03em;
			text-transform:uppercase;
			border:1px solid transparent;
			box-shadow:0 6px 14px rgba(15,23,42,0.12), inset 0 1px 0 rgba(255,255,255,0.25);
		}
		.s-retain{background:linear-gradient(135deg,#12b981,#0f8f68);border-color:#0f8f68}
		.s-repair{background:linear-gradient(135deg,#f7b733,#dd8b00);color:#1f1400;border-color:#dd8b00}
		.s-retired{background:linear-gradient(135deg,#f46d6d,#d33f3f);border-color:#c83535}
		.s-missing{background:linear-gradient(135deg,#64748b,#475569);border-color:#475569}
        .expand{display:none;background:#fbfdff;padding:12px;border-top:1px solid #eef4fb}
        .bulk{display:none;align-items:center;gap:12px;padding:10px;border-radius:8px;background:linear-gradient(90deg,#fff,#fbfdff);box-shadow:var(--shadow);margin-bottom:12px}

        /* responsiveness */
        @media(max-width:1100px){.cards{grid-template-columns:repeat(2,1fr)}.layout{grid-template-columns:1fr}}
		@media(max-width:700px){.cards{grid-template-columns:1fr}.filters{flex-direction:column;align-items:stretch}}

		/* Unified sidebar override: always-expanded */
		.sidebar {
			width: 260px;	
			background: linear-gradient(180deg,#ffffff,#fbfdff);
			position: fixed;
			top: 0;
			left: 0;
			height: 100vh;
			font-family: 'Inter', system-ui, -apple-system, sans-serif;
			font-size: 14px;
			box-shadow: 0 6px 18px rgba(2,6,23,0.06);
			overflow: hidden;
			z-index: 200;
		}

		.sidebar .brand-link { display:flex; align-items:center; gap:0.75rem; padding:1rem; text-decoration:none; color:inherit; }
		.sidebar .brand-link img { width:36px; height:36px; border-radius:6px; object-fit:cover }
		.sidebar .brand-text { opacity:1; transform:translateX(0); white-space:nowrap; }

		.sidebar-nav { padding:0.75rem; }
		.nav-section { margin-top:0.75rem; padding-top:0.5rem; border-top:1px dashed rgba(0,0,0,0.04); }
		.nav-section-title { font-size:0.65rem; font-weight:700; color:var(--muted); padding-left:0.5rem; margin-bottom:0.5rem; text-transform:uppercase; letter-spacing:0.06em }

		.nav-link { display:flex; align-items:center; gap:0.75rem; padding:0.65rem; border-radius:10px; color:var(--text-muted); text-decoration:none; font-weight:600; transition:all .18s ease; margin-bottom:0.2rem }
		.nav-icon { width:36px; height:36px; border-radius:8px; background:transparent; display:flex; align-items:center; justify-content:center; font-size:1.05rem }
		.nav-label { opacity:1; transform:translateX(0); white-space:nowrap }
		.nav-link:hover { background: #fbfdff; color: var(--text-dark); }

		.nav-link.active { background: linear-gradient(90deg,var(--primary,#4f46e5),var(--primary-light,#6366f1)); color: white; position:relative }
		.nav-link.active::before { content: ''; position:absolute; left:0; top:8px; bottom:8px; width:4px; background:var(--primary,#4f46e5); border-radius:4px }

		/* Professional readability refinements */
		:root {
			--text-dark: #000000;
			--text-muted: #000000;
			--line-soft: #dbe4ef;
			--line-strong: #c9d6e5;
		}
		body {
			background:
				radial-gradient(circle at 8% 0%, rgba(37,99,235,0.07), transparent 38%),
				radial-gradient(circle at 100% 100%, rgba(2,132,199,0.06), transparent 42%),
				#f4f7fb;
			color: var(--text-dark);
		}
		.main { padding: 34px 38px; }
		.page-head h1 {
			font-family: 'Playfair Display', Georgia, 'Times New Roman', serif;
			font-size: 36px;
			font-weight: 700;
			letter-spacing: 0.01em;
			line-height: 1.15;
			color: #000;
		}
		.page-head > div:first-child > div {
			font-size: 16px;
			font-family: Inter, 'Segoe UI', sans-serif;
			font-weight: 500;
			color: #000;
		}
		.hero {
			background: #fff;
			border: 1px solid #e4ebf3;
			border-radius: 16px;
			overflow: hidden;
			margin-top: 18px;
			box-shadow: 0 12px 28px rgba(15,23,42,0.08);
		}
		.hero-grid {
			display: grid;
			grid-template-columns: 1fr 1.1fr;
			align-items: center;
			gap: 18px;
		}
		.hero-copy { padding: 24px; }
		.hero-copy h2 {
			font-family: 'Playfair Display', Georgia, serif;
			font-size: clamp(1.35rem, 2.4vw, 2rem);
			line-height: 1.15;
			margin-bottom: 8px;
			color: #000;
		}
		.hero-copy p {
			font-family: Inter, 'Segoe UI', sans-serif;
			font-size: 15px;
			line-height: 1.55;
			color: #000;
		}
		.hero-image {
			width: 100%;
			height: 230px;
			object-fit: cover;
			border-left: 1px solid #e4ebf3;
			display: block;
		}

		.home-btn,
		button,
		select,
		input {
			font-family: Inter, Segoe UI, system-ui, -apple-system, sans-serif;
			font-size: 15px;
			color: #000;
		}
		button,
		.home-btn {
			height: 40px;
			padding: 0 14px;
			border-radius: 10px;
			border: 1px solid var(--line-soft);
			background: #ffffff;
			color: var(--text-dark);
			font-weight: 600;
			cursor: pointer;
			transition: transform .14s ease, box-shadow .2s ease, border-color .2s ease;
		}
		button:hover,
		.home-btn:hover {
			transform: translateY(-1px);
			border-color: var(--line-strong);
			box-shadow: 0 8px 20px rgba(15,23,42,0.08);
		}

		.cards {
			grid-template-columns: repeat(5, minmax(0, 1fr));
			gap: 16px;
			margin-top: 24px;
		}
		.card {
			position: relative;
			border: 1px solid #e4ebf3;
			padding: 16px;
			min-height: 88px;
			border-radius: 14px;
			background: linear-gradient(180deg,#ffffff,#fbfdff);
			box-shadow: 0 10px 24px rgba(15,23,42,0.06);
			transition: transform .16s ease, box-shadow .2s ease, border-color .2s ease;
		}
		.card[data-status]{cursor:pointer}
		.card[data-status]::after{
			content:'';
			position:absolute;
			left:10px;
			right:10px;
			bottom:0;
			height:3px;
			border-radius:3px 3px 0 0;
			background:linear-gradient(90deg,#3b82f6,#60a5fa);
			opacity:0;
			transform:scaleX(.6);
			transition:opacity .2s ease, transform .2s ease;
		}
		.card[data-status]:hover{
			transform: translateY(-2px);
			border-color:#cfdbea;
			box-shadow: 0 14px 28px rgba(15,23,42,0.10);
		}
		.card.active{
			border-color:#9ec3f5;
			box-shadow: 0 16px 30px rgba(37,99,235,0.14);
			background: linear-gradient(180deg,#ffffff,#f5f9ff);
		}
		.card.active::after{opacity:1;transform:scaleX(1)}
		.card .icon {
			width: 38px;
			height: 38px;
			border-radius: 10px;
			box-shadow: inset 0 0 0 1px rgba(255,255,255,0.28);
		}
		.card .meta .num { font-size: 20px; line-height: 1.05; font-weight: 700; }
		.card .meta .label { font-size: 12px; color: #111827; letter-spacing: .03em; text-transform: uppercase; font-weight: 600; }

		.panel {
			border: 1px solid #e4ebf3;
			border-radius: 14px;
			padding: 20px;
			box-shadow: 0 12px 28px rgba(15,23,42,0.06);
		}
		.layout { gap: 18px; margin-top: 22px; }
		.filters {
			display: grid;
			grid-template-columns: minmax(340px, 1.8fr) minmax(150px, .9fr) minmax(190px, 1fr) auto;
			gap: 12px;
			align-items: center;
		}
		.filters input,
		.filters select {
			height: 40px;
			padding: 0 12px;
			border-radius: 10px;
			border: 1px solid var(--line-soft);
			background: #fff;
			color: #000;
		}
		.filters input::placeholder { color: #000; opacity: .7; }
		.filters input:focus,
		.filters select:focus {
			outline: none;
			border-color: #3b82f6;
			box-shadow: 0 0 0 3px rgba(59,130,246,0.16);
		}

		.table-wrap { margin-top: 20px; overflow: hidden; }
		table {
			border: 1px solid #e4ebf3;
			border-radius: 12px;
			overflow: hidden;
			font-size: 15px;
		}
		thead th {
			background: linear-gradient(180deg, #fbfdff, #f4f8fc);
			color: #000;
			font-size: 13.5px;
			font-weight: 700;
			letter-spacing: .03em;
			text-transform: uppercase;
			border-bottom: 1px solid #d7e1ec;
			padding-top: 13px;
			padding-bottom: 13px;
		}
		td {
			padding-top: 13px;
			padding-bottom: 13px;
			color: #000;
			line-height: 1.4;
			border-bottom: 1px solid #edf2f7;
		}
		tbody tr:nth-child(4n+1),
		tbody tr:nth-child(4n+2) { background: #ffffff; }
		tbody tr:nth-child(4n+3),
		tbody tr:nth-child(4n+4) { background: #f8fbff; }
		tr:hover td { background: #eef6ff; }

		.status-badge {
			font-size: 12px;
			font-weight: 700;
			letter-spacing: .02em;
			padding: 7px 11px;
		}
		.disposition-card {
			margin-top: 18px;
			background: #fff;
			border: 1px solid #e4ebf3;
			border-radius: 14px;
			padding: 16px 18px;
			box-shadow: 0 10px 22px rgba(15,23,42,0.05);
		}
		.disposition-head {
			display: flex;
			align-items: center;
			justify-content: space-between;
			font-family: Inter, 'Segoe UI', sans-serif;
			font-size: 14px;
			font-weight: 700;
			color: #000;
			margin-bottom: 8px;
		}
		.disposition-toggle {
			height: 30px;
			padding: 0 10px;
			border-radius: 8px;
			border: 1px solid #d7e1ec;
			background: #fff;
			color: #000;
			font-size: 12px;
			font-weight: 600;
			cursor: pointer;
		}
		.disposition-toggle:hover { border-color: #b9c7d6; }
		.disposition-body {
			overflow: hidden;
			max-height: 220px;
			opacity: 1;
			transform: translateY(0);
			transition: max-height .32s ease, opacity .22s ease, transform .22s ease;
		}
		.disposition-card.collapsed .disposition-body {
			max-height: 0;
			opacity: 0;
			transform: translateY(-4px);
		}
		.disposition-grid {
			display: grid;
			grid-template-columns: repeat(4, minmax(0, 1fr));
			gap: 12px;
		}
		.disposition-pill {
			border: 1px solid #d9e3ef;
			border-radius: 10px;
			padding: 10px 12px;
			font-size: 13px;
			font-weight: 600;
			color: #000;
			background: #f8fbff;
		}
		.disposition-pill span {
			display: block;
			font-size: 12px;
			font-weight: 500;
			margin-top: 2px;
			color: #000;
			opacity: .85;
		}
		.expand {
			border-top: 1px dashed #d6e1ed;
			background: #f6faff;
		}

		@media(max-width:1100px){
			.main { padding: 24px 22px; }
			.filters { grid-template-columns: 1fr 1fr; }
			.cards { grid-template-columns: repeat(2, minmax(0, 1fr)); }
		}
		@media(max-width:700px){
			.main { padding: 16px 12px; }
			.cards { grid-template-columns: 1fr; }
			.card { min-height: 68px; }
			.disposition-grid { grid-template-columns: 1fr; }
		}
		@media(max-width:900px){
			.hero-grid { grid-template-columns: 1fr; }
			.hero-image { height: 200px; border-left: 0; border-top: 1px solid #e4ebf3; }
			.disposition-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
		}

		@media (max-width: 768px) { .sidebar { transform: translateX(-100%); } .main { margin-left: 0; } }

	</style>
</head>
<body>
    <div class="app">
		<aside class="sidebar" aria-label="Primary Navigation">
			<div class="sidebar-header">
				<a href="index.html" class="sidebar-brand">
					<img src="qw.png" alt="PNOC Logo" />
					<div>
						<div class="sidebar-brand-text">PNOC Inventory</div>
						<div class="sidebar-brand-sub">Management System</div>
					</div>
				</a>
			</div>
			<nav class="sidebar-nav" role="navigation">
				<div class="nav-section">
					<div class="nav-section-title">Main Menu</div>
					<a href="inventory-dashboard.php" class="nav-link"><span class="nav-icon">⌂</span><span class="nav-label">Dashboard</span></a>
					<a href="bentaco-inventory.php" class="nav-link"><span class="nav-icon">☐</span><span class="nav-label">BENTACO Inventory</span></a>
					<a href="iot-inventory.php" class="nav-link"><span class="nav-icon">◎</span><span class="nav-label">IOT Inventory</span></a>
				</div>
				<div class="nav-section">
					<div class="nav-section-title">Management</div>
					<a href="location-management.php" class="nav-link"><span class="nav-icon">⌖</span><span class="nav-label">Location Management</span></a>
					<!-- Item Allocation removed from sidebar -->
					<a href="item-status-monitoring.php" class="nav-link active"><span class="nav-icon">◉</span><span class="nav-label">Item Status Monitoring</span></a>
				</div>
				<div class="nav-section">
					<div class="nav-section-title">Analytics</div>
					<a href="report-generation.php" class="nav-link"><span class="nav-icon">☰</span><span class="nav-label">Reports</span></a>
				</div>
			</nav>
		</aside>

        <main class="main">
			<div class="page-head">
				<div><h1>Item Status Monitoring</h1></div>
				<div style="display:flex;gap:8px;align-items:center"><a href="index.html" class="home-btn">Home</a></div>
			</div>

			<section class="hero" aria-label="Monitoring Overview">
				<div class="hero-grid">
					<div class="hero-copy">
						<h2>Track Fast. Report Smart.</h2>
						<p>Monitor status, destination, and images in one clean view. Export a professional PDF in seconds.</p>
					</div>
					<img src="as.jpg" alt="PNOC operations visual" class="hero-image" />
				</div>
			</section>

			<div class="cards">
				<div class="card" data-status="all"><div class="icon" style="background:linear-gradient(90deg,#60a5fa,#3b82f6)">T</div><div class="meta"><div class="num" id="totalAssets">0</div><div class="label">Active Assets</div></div></div>
				<div class="card" data-status="Retain"><div class="icon" style="background:linear-gradient(90deg,#34d399,#10b981)">R</div><div class="meta"><div class="num" id="retainCount">0</div><div class="label">Retain</div></div></div>
				<div class="card" data-status="Transfer"><div class="icon" style="background:linear-gradient(90deg,#facc15,#f59e0b)">T</div><div class="meta"><div class="num" id="transferCount">0</div><div class="label">Transfer</div></div></div>
				<div class="card" data-status="Dispose"><div class="icon" style="background:linear-gradient(90deg,#f87171,#ef4444)">D</div><div class="meta"><div class="num" id="disposeCount">0</div><div class="label">Dispose</div></div></div>
				<div class="card" data-status="Store/Warehouse"><div class="icon" style="background:linear-gradient(90deg,#94a3b8,#6b7280)">S</div><div class="meta"><div class="num" id="warehouseCount">0</div><div class="label">Store/Warehouse</div></div></div>
			</div>

			<section class="disposition-card" aria-label="Disposition Guide">
				<div class="disposition-head">
					<span>Disposition Guide</span>
					<button id="dispositionToggle" class="disposition-toggle" type="button" onclick="toggleDispositionGuide()" aria-expanded="true">Hide</button>
				</div>
				<div class="disposition-body" id="dispositionBody">
					<div class="disposition-grid">
						<div class="disposition-pill">Retain<span>Keep in active use</span></div>
						<div class="disposition-pill">Transfer<span>Move to another unit</span></div>
						<div class="disposition-pill">Dispose<span>For retirement/disposal</span></div>
						<div class="disposition-pill">Store/Warehouse<span>For storage and reserve</span></div>
					</div>
				</div>
			</section>

			<div class="layout">
				<div>
					<div class="panel" style="margin-bottom:12px">
                        <div style="display:flex;justify-content:space-between;align-items:center">
                            <div class="filters" style="flex:1">
                                <input id="search" placeholder="Search item, number, notes, serial" />
                            </div>
                            <div style="display:flex;gap:8px;margin-left:12px">
								<button id="exportPdf">Export PDF</button>
                                <button id="print">Print</button>
                            </div>
                        </div>
                    </div>
                    <div class="panel table-wrap">
                        <table id="itemsTable">
                            <thead>
									<tr>
										<th style="width:36px"><input id="selectAll" type="checkbox"/></th>
										<th>Group</th>
										<th style="width:150px">Image</th>
										<th>Item Number</th>
										<th>Item Description</th>
										<th>Destination</th>
										<th>Item Status</th>
										<th>Actions</th>
									</tr>
                            </thead>
							<tbody id="tbody">
									<tr><td colspan="8">Loading…</td></tr>
							</tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
	<div id="imageViewer" class="image-viewer" onclick="if(event.target===this) closeImageViewer()" role="dialog" aria-modal="true" aria-label="Image preview">
		<div class="image-viewer-card">
			<div class="image-viewer-header">
				<div id="imageViewerTitle" class="image-viewer-title">Item Image</div>
				<button type="button" class="image-viewer-close" onclick="closeImageViewer()">Close</button>
			</div>
			<img id="imageViewerImg" alt="Item image preview" />
		</div>
	</div>
		<input id="imagePicker" type="file" accept="image/*" style="display:none" />

    <script>
        const KEYS = ['pnoc_inventory_bentaco_v1','pnoc_inventory_iot_v1'];
        let all = [];
        let view = [];

        function load(){ all = []; KEYS.forEach(k=>{ try{ const r=localStorage.getItem(k); const arr=r?JSON.parse(r):[]; if(Array.isArray(arr)) arr.forEach(it=>all.push(Object.assign({},it,{source: k.includes('iot')? 'IOT':'BENTACO'}))); }catch(e){}}); }

		function summarize(){
			const total = all.length;
			const stat = { Retain:0, Transfer:0, Dispose:0, 'Store/Warehouse':0 };
			all.forEach(i=>{ const s = statusValue(i).toLowerCase(); if(s==='retain') stat.Retain++; else if(s==='transfer') stat.Transfer++; else if(s==='dispose') stat.Dispose++; else if(s==='store/warehouse') stat['Store/Warehouse']++; });
			document.getElementById('totalAssets').textContent = total; document.getElementById('retainCount').textContent = stat.Retain; document.getElementById('transferCount').textContent = stat.Transfer; document.getElementById('disposeCount') && (document.getElementById('disposeCount').textContent = stat.Dispose); document.getElementById('warehouseCount').textContent = stat['Store/Warehouse'];
		}

		// Chart display removed — renderChart no longer used

		function activeStatusFilter(){
			const active = document.querySelector('.card[data-status].active');
			return active ? String(active.getAttribute('data-status') || 'all') : 'all';
		}

		function apply(){
			const q=document.getElementById('search').value.toLowerCase();
			const statusFilter = activeStatusFilter();
			view = all.filter(it=>{
				if(statusFilter !== 'all' && statusValue(it).toLowerCase() !== statusFilter.toLowerCase()) return false;
				if(q){ const text = ((it.itemDescription||'')+' '+(it.propertyNumber||it.itemId||'')+' '+(it.serialNumber||it.serial||'')+' '+(it.notes||'')+' '+(it.destination||'')+' '+statusValue(it)).toLowerCase(); if(text.indexOf(q)===-1) return false; }
				return true;
			});
			document.getElementById('tbody').innerHTML = view.length ? view.map((it,idx)=>`<tr><td><input class="sel" data-idx="${idx}" type="checkbox"/></td><td>${it.source||''}</td><td style="min-width:150px">${imageCellHtml(it, idx)}</td><td>${it.propertyNumber||it.itemId||''}</td><td>${(it.itemDescription||it.item_description||it.description||'')}</td><td>${destinationSelectHtml(it, idx)}</td><td>${statusCellHtml(it)}</td><td>${actionSelectHtml(it, idx)}</td></tr><tr class="expand"><td colspan="8">${expandHtml(it)}</td></tr>`).join('') : '<tr><td colspan="8" style="padding:18px;text-align:center;color:var(--muted)">No items found.</td></tr>';
            attachSelectors(); document.getElementById('resultCount') && (document.getElementById('resultCount').textContent = view.length);
        }

		function statusValue(it){ return String(it.action || '').trim(); }
		function statusCellHtml(it){ const s = statusValue(it); if(!s) return ''; return `<span class="status-badge ${statusClass(it)}">${escapeHtml(s)}</span>`; }
		function statusClass(it){ const s=statusValue(it).toLowerCase(); if(s==='retain') return 's-retain'; if(s==='transfer') return 's-repair'; if(s==='dispose') return 's-retired'; return 's-missing'; }
        function escapeHtml(v){ return String(v||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;'); }
		function imageCellHtml(it, idx){ const src = it.image || it.itemImage || ''; return src ? `<img src="${escapeHtml(src)}" alt="Item image" class="thumb" onclick="openImageViewer('${escapeHtml(src)}','${escapeHtml(it.itemDescription||it.item_description||it.description||'Item Image')}')" />` : '<div class="thumb-empty">No img</div>'; }
		function destinationSelectHtml(it, idx){
			const current = String(it.destination || '').toLowerCase();
			const options = ['Bataan','Banaba','Main Office'].map(name=>`<option value="${name}" ${current===name.toLowerCase()?'selected':''}>${name}</option>`).join('');
			return `<select onchange="changeDestination(${idx}, this.value)"><option value="">Select Destination</option>${options}</select>`;
		}
		function actionSelectHtml(it, idx){
			const current = String(it.action || '').toLowerCase();
			const imageOps = (it.image || it.itemImage || '') ? ['Replace Image','Remove Image'] : ['Attach Image'];
			const options = ['Retain','Transfer','Dispose','Store/Warehouse'].concat(imageOps).map(name=>`<option value="${name}" ${current===name.toLowerCase()?'selected':''}>${name}</option>`).join('');
			return `<select class="action-select" onchange="changeAction(${idx}, this.value)"><option value="">Select Action</option>${options}</select>`;
		}
		function expandHtml(it){ return `<div style="display:grid;grid-template-columns:1fr 1fr 1fr 260px;gap:12px"><div><strong>Serial No</strong><div>${escapeHtml(it.serialNumber||it.serial_number||'-')}</div><strong style="margin-top:8px">Assigned</strong><div>${escapeHtml(it.assignedTo||it.user||'-')}</div></div><div><strong>Purchase Date</strong><div>${escapeHtml(it.purchaseDate||it.purchase_date||'-')}</div><strong style="margin-top:8px">Warranty</strong><div>${escapeHtml(it.warranty||'-')}</div></div><div><strong>Notes</strong><div style="color:var(--muted)">${escapeHtml((it.notes||'').substring(0,300))}</div><strong style="margin-top:8px">Action</strong><div>${escapeHtml(it.action||'-')}</div></div><div><strong>Status History</strong><div style="color:var(--muted);margin-top:6px">${sampleHistory(it)}</div></div></div>` }
		function sampleHistory(it){ return `Apr 10 – ${statusValue(it)||'Retain'}<br>Apr 15 – In Review<br>Apr 20 – ${statusValue(it)||'Retain'}`; }

        function attachSelectors(){ document.querySelectorAll('.sel').forEach(el=>el.onchange=onSel); document.getElementById('selectAll').onchange=function(){ document.querySelectorAll('.sel').forEach(s=>s.checked=this.checked); onSel(); } }
		function onSel(){
			const n = document.querySelectorAll('.sel:checked').length;
			const bar = document.getElementById('bulkBar');
			const count = document.getElementById('bulkCount');
			if(!bar || !count) return;
			if(n) { bar.style.display='flex'; count.textContent = n+' selected'; }
			else bar.style.display='none';
		}

		function viewItem(idx){ const it=view[idx]; alert(`${it.source} ${it.propertyNumber||it.itemId}\n${it.itemDescription||''}\nStatus: ${statusValue(it)||'-'}`); }
		function changeStatus(idx){ const it=view[idx]; const v=prompt('Set status (Retain, Transfer, Dispose, Store/Warehouse)', statusValue(it)||'Retain'); if(v===null) return; it.itemStatus=v; it.action=v; persist(it); render(); }
		function changeStatusFromSelect(idx, value){ if(!value) return; const it = view[idx]; if(!it) return; it.itemStatus = value; persist(it); render(); }
		function changeDestination(idx, value){ const it = view[idx]; if(!it) return; it.destination = value || ''; persist(it); }
		function changeAction(idx, value){
			const it = view[idx];
			if(!it) return;
			const normalized = String(value || '').trim();
			if(!normalized) return;
			if(normalized === 'Attach Image' || normalized === 'Replace Image'){
				attachImage(idx);
				return;
			}
			if(normalized === 'Remove Image'){
				removeImage(idx);
				return;
			}
			it.action = normalized;
			it.itemStatus = normalized;
			persist(it);
			render();
		}

		function attachImage(idx){
			const it = view[idx];
			if(!it) return;
			const picker = document.getElementById('imagePicker');
			if(!picker) return;
			picker.value = '';
			picker.onchange = function(){
				const file = picker.files && picker.files[0];
				if(!file) return;
				if(!file.type || file.type.indexOf('image/') !== 0){
					alert('Please select a valid image file.');
					return;
				}
				if(file.size > 2 * 1024 * 1024){
					alert('Image is too large. Max allowed size is 2MB.');
					return;
				}
				const reader = new FileReader();
				reader.onload = function(e){
					it.image = String((e.target && e.target.result) || '');
					persist(it);
					render();
				};
				reader.readAsDataURL(file);
			};
			picker.click();
		}

		function removeImage(idx){
			const it = view[idx];
			if(!it) return;
			it.image = '';
			it.itemImage = '';
			persist(it);
			render();
		}

		function openImageViewer(src, title){
			const viewer = document.getElementById('imageViewer');
			const image = document.getElementById('imageViewerImg');
			const heading = document.getElementById('imageViewerTitle');
			if(!viewer || !image || !heading || !src) return;
			image.src = src;
			heading.textContent = title || 'Item Image';
			viewer.classList.add('open');
		}

		function closeImageViewer(){
			const viewer = document.getElementById('imageViewer');
			const image = document.getElementById('imageViewerImg');
			if(viewer) viewer.classList.remove('open');
			if(image) image.src = '';
		}

		function toggleDispositionGuide(){
			const card = document.querySelector('.disposition-card');
			const toggle = document.getElementById('dispositionToggle');
			if(!card || !toggle) return;
			const collapsed = card.classList.toggle('collapsed');
			toggle.textContent = collapsed ? 'Show' : 'Hide';
			toggle.setAttribute('aria-expanded', collapsed ? 'false' : 'true');
		}

		function setStatusTab(status){
			const s = (status||'').toString();
			document.querySelectorAll('.card[data-status]').forEach(c=>{
				const ds = c.getAttribute('data-status')||'all';
				if(ds.toLowerCase() === (s||'all').toLowerCase()) c.classList.add('active'); else c.classList.remove('active');
			});
			const statusEl = document.getElementById('status'); if(statusEl) statusEl.value = (s==='all'?'all':s);
		}

		function attachStatusTabs(){
			document.querySelectorAll('.card[data-status]').forEach(c=>{
				c.addEventListener('click', ()=>{
					const ds = c.getAttribute('data-status') || 'all';
					const isActive = c.classList.contains('active');
					if(isActive) {
						setStatusTab('all');
					} else {
						setStatusTab(ds);
					}
					apply();
				});
			});
		}

        function persist(it){ try{ const key = (it.source==='IOT')? KEYS[1] : KEYS[0]; const raw = localStorage.getItem(key); const arr = raw?JSON.parse(raw):[]; const idx = arr.findIndex(x=> (x.propertyNumber||x.itemId) === (it.propertyNumber||it.itemId)); if(idx>-1) arr[idx]=Object.assign({},arr[idx],it); else arr.push(it); localStorage.setItem(key,JSON.stringify(arr)); }catch(e){console.error(e)} }

        function bulkUpdate(){ alert('Bulk update flow (placeholder)'); }
        function bulkAllocate(){ alert('Bulk allocate (placeholder)'); }
        function bulkMove(){ alert('Bulk move (placeholder)'); }
        function bulkExport(){ alert('Bulk export (placeholder)'); }

		function loadImageAsDataURL(src, callback){
			const img = new Image();
			img.crossOrigin = 'anonymous';
			img.onload = function(){
				try {
					const canvas = document.createElement('canvas');
					canvas.width = img.naturalWidth || img.width;
					canvas.height = img.naturalHeight || img.height;
					const ctx = canvas.getContext('2d');
					if(!ctx){ callback(null); return; }
					ctx.drawImage(img, 0, 0);
					callback(canvas.toDataURL('image/png'));
				} catch(err){
					callback(null);
				}
			};
			img.onerror = function(){ callback(null); };
			img.src = src;
		}

		function reportFilterSummary(){
			const search = document.getElementById('search') ? document.getElementById('search').value.trim() : '';
			const group = document.getElementById('group') ? document.getElementById('group').value : 'all';
			const status = document.getElementById('status') ? document.getElementById('status').value : 'all';
			const parts = [];
			if(search) parts.push(`Search: ${search}`);
			if(group && group !== 'all') parts.push(`Group: ${group}`);
			if(status && status !== 'all') parts.push(`Status: ${status}`);
			return parts.length ? parts.join(' | ') : 'All records';
		}

		function reportStatusStyle(statusText){
			const status = String(statusText || '').toLowerCase();
			if(status === 'retain') return { fillColor:[16,185,129], textColor:255 };
			if(status === 'transfer') return { fillColor:[245,158,11], textColor:17 };
			if(status === 'dispose') return { fillColor:[239,68,68], textColor:255 };
			if(status === 'store/warehouse') return { fillColor:[100,116,139], textColor:255 };
			return { fillColor:[226,232,240], textColor:15 };
		}

		function exportPDF(){
			if(!window.jspdf || !window.jspdf.jsPDF){
				alert('PDF library failed to load. Please refresh the page and try again.');
				return;
			}
			const rows = (view && view.length) ? view : all;
			if(!rows.length){
				alert('No table data available to export.');
				return;
			}

			loadImageAsDataURL('qw.png', function(logoData){
				const { jsPDF } = window.jspdf;
				const doc = new jsPDF({ orientation:'landscape', unit:'mm', format:'a4' });
				const generatedAt = new Date();
				const timestamp = generatedAt.toLocaleString();
				const period = `${generatedAt.getFullYear()}-${String(generatedAt.getMonth()+1).padStart(2,'0')}-${String(generatedAt.getDate()).padStart(2,'0')}`;
				const filterSummary = reportFilterSummary();
				const pageWidth = doc.internal.pageSize.getWidth();

				doc.setFillColor(248, 251, 255);
				doc.rect(10, 8, pageWidth - 20, 26, 'F');
				doc.setDrawColor(207, 219, 232);
				doc.line(10, 34, pageWidth - 10, 34);

				if(logoData){
					try {
						doc.addImage(logoData, 'PNG', 14, 11, 18, 18);
					} catch(err){
						// Keep PDF export working even if logo cannot be rendered.
					}
				}

				doc.setFont('helvetica','bold');
				doc.setFontSize(14);
				doc.text('PHILIPPINE NATIONAL OIL COMPANY (PNOC)', 36, 17);
				doc.setFont('helvetica','italic');
				doc.setFontSize(11);
				doc.text('Item Status Monitoring Report', 36, 24);
				doc.setFont('helvetica','normal');
				doc.setFontSize(9);
				doc.text(`Generated: ${timestamp}`, 14, 40);
				doc.text(`Total Records: ${rows.length}`, 95, 40);
				doc.text(`Scope: ${(view && view.length) ? 'Filtered View' : 'Full Inventory View'}`, 145, 40);
				doc.text(`Filters: ${filterSummary}`, 14, 44);

				const tableRows = rows.map((r, i)=>([
					String(i + 1),
					String(r.source || ''),
					String(r.image || r.itemImage || ''),
					String(r.propertyNumber || r.itemId || ''),
					String(r.itemDescription || r.item_description || r.description || ''),
					String(r.destination || '-'),
					String(statusValue(r) || '-')
				]));

				doc.autoTable({
					startY: 48,
					margin: { left: 10, right: 10 },
					tableWidth: 'auto',
					head: [['#','Group','Image','Item Number','Item Description','Destination','Item Status']],
					body: tableRows,
					theme: 'grid',
					headStyles: { fillColor:[15,23,42], textColor:255, fontStyle:'bold', halign:'center' },
					styles: { font:'helvetica', fontSize:8.5, cellPadding:2, textColor:[15,23,42] },
					columnStyles: {
						0: { halign:'center', cellWidth:8 },
						1: { halign:'center', cellWidth:20 },
						2: { halign:'center', cellWidth:24 },
						3: { halign:'center', cellWidth:32 },
						4: { cellWidth:120 },
						5: { halign:'center', cellWidth:30 },
						6: { halign:'center', cellWidth:34 }
					},
					didParseCell: function(data){
						if(data.section === 'body' && data.column.index === 2){
							data.cell.styles.minCellHeight = 14;
							data.cell.text = [''];
						}
						if(data.section === 'body' && data.column.index === 6){
							const cellStyle = reportStatusStyle(data.cell.raw);
							data.cell.styles.fillColor = cellStyle.fillColor;
							data.cell.styles.textColor = cellStyle.textColor;
							data.cell.styles.fontStyle = 'bold';
							data.cell.styles.halign = 'center';
						}
					},
					didDrawCell: function(data){
						if(data.section !== 'body' || data.column.index !== 2){
							return;
						}
						const raw = tableRows[data.row.index] && tableRows[data.row.index][2] ? String(tableRows[data.row.index][2]) : '';
						if(!raw || raw.indexOf('data:image/') !== 0){
							return;
						}
						let format = 'PNG';
						if(raw.indexOf('data:image/jpeg') === 0 || raw.indexOf('data:image/jpg') === 0) format = 'JPEG';
						const padding = 1;
						const size = Math.max(8, Math.min(data.cell.width, data.cell.height) - (padding * 2));
						const x = data.cell.x + ((data.cell.width - size) / 2);
						const y = data.cell.y + ((data.cell.height - size) / 2);
						try {
							doc.addImage(raw, format, x, y, size, size);
						} catch (err) {
							// Skip unsupported image data in PDF render.
						}
					},
					didDrawPage: function(data){
						doc.setFontSize(8);
						doc.setTextColor(100);
						doc.text(`Page ${doc.internal.getNumberOfPages()}`, data.settings.margin.left, doc.internal.pageSize.getHeight() - 6);
						doc.text('Confidential - PNOC Internal Use', doc.internal.pageSize.getWidth() - 72, doc.internal.pageSize.getHeight() - 6);
					}
				});

				doc.save(`pnoc-item-status-report-${period}.pdf`);
			});
		}

		document.getElementById('search').addEventListener('input',apply);
		document.getElementById('exportPdf').addEventListener('click',exportPDF); document.getElementById('print').addEventListener('click',()=>window.print());

		function render(){ load(); summarize(); setStatusTab('all'); apply(); }
		attachStatusTabs();
		render();
    </script>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>PNOC Inventory | Item Status Monitoring</title>
	<link rel="icon" type="image/png" href="qw.png" />
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
	<style>
		:root {
			--primary: #4f46e5;
			--primary-light: #6366f1;
			--primary-glow: rgba(79,70,229,0.15);
			--bg-body: #f8f9fa;
			--card-bg: #ffffff;
			--border-color: #e2e8f0;
			--text-dark: #1e293b;
			--text-muted: #64748b;
			--sidebar-width: 240px;
			--shadow-sm: 0 1px 2px rgba(0,0,0,0.04);
			--shadow-md: 0 4px 12px rgba(0,0,0,0.06);
			--shadow-lg: 0 8px 24px rgba(0,0,0,0.08);
			--shadow-hover: 0 12px 32px rgba(79,70,229,0.12);
			--compact-padding: 0.28rem;
			--table-min-width: 900px;
			--radius-md: 12px;
			--radius-lg: 16px;
			--emerald: #10b981;
			--rose: #f43f5e;
			--amber: #f59e0b;
		}
		* { box-sizing: border-box; margin: 0; padding: 0; }
		body {
			font-family: 'Inter', system-ui, -apple-system, sans-serif;
			background: var(--bg-body);
			color: var(--text-dark);
			line-height: 1.5;
			min-height: 100vh;
		}
		.app-layout { display: flex; min-height: 100vh; }
		.sidebar {
			width: var(--sidebar-width);
			background: var(--card-bg);
			border-right: 1px solid var(--border-color);
			position: fixed;
			top: 0;
			left: 0;
			height: 100vh;
			overflow-y: auto;
			z-index: 100;
			box-shadow: var(--shadow-sm);
		}
		.sidebar-header {
			padding: 1.25rem 1rem;
			border-bottom: 1px solid var(--border-color);
		}
		.sidebar-brand {
			display: flex;
			align-items: center;
			gap: 0.75rem;
			text-decoration: none;
			color: var(--text-dark);
		}
			.sidebar-brand img { width: 36px; height: 36px; object-fit: contain; }
			.sidebar-brand-text { font-weight: 700; font-size: 0.9rem; }
			.sidebar-brand-sub { font-size: 0.7rem; color: var(--text-muted); font-weight: 400; }

			/* Sidebar text color override: make all sidebar text black */
			.sidebar { color: #000; }
			.sidebar .sidebar-brand-text,
			.sidebar .sidebar-brand-sub,
			.sidebar .nav-link,
			.sidebar .nav-section-title { color: #000; }
		.sidebar-nav { padding: 1rem 0.75rem; }
		.nav-section { margin-bottom: 1.5rem; }
		.nav-section-title {
			font-size: 0.7rem;
			font-weight: 600;
			text-transform: uppercase;
			letter-spacing: 0.05em;
			color: var(--text-muted);
			padding: 0 0.5rem;
			margin-bottom: 0.5rem;
		}
		.nav-link {
			display: flex;
			align-items: center;
			gap: 0.75rem;
			padding: 0.65rem 0.75rem;
			border-radius: 8px;
			text-decoration: none;
			color: var(--text-dark);
			font-size: 0.875rem;
			font-weight: 500;
			transition: all 0.2s ease;
			margin-bottom: 0.25rem;
		}
		.nav-link:hover { background: var(--bg-body); color: var(--primary); }
		.nav-link.active { background: var(--primary); color: white; box-shadow: 0 4px 12px rgba(79,70,229,0.3); }
		.nav-link.active .nav-icon { background: rgba(255, 255, 255, 0.2); color: white; }
		.nav-icon {
			display: flex;
			align-items: center;
			justify-content: center;
			width: 32px;
			height: 32px;
			border-radius: 8px;
			background: var(--bg-body);
			font-size: 1rem;
			flex-shrink: 0;
			transition: all 0.2s ease;
		}
		.main-content {
			flex: 1;
			margin-left: var(--sidebar-width);
			padding: 1.5rem 2rem;
		}
		.page-header { margin-bottom: 1.5rem; }
		.page-title { font-size: 1.75rem; font-weight: 700; color: var(--text-dark); margin-bottom: 0.25rem; }
		.page-subtitle { font-size: 0.875rem; color: var(--text-muted); }
		.meta { color: var(--text-muted); font-size: 0.84rem; }
		.stats-row {
			display: grid;
			grid-template-columns: repeat(4, minmax(0, 1fr));
			gap: 0.75rem;
			margin-bottom: 1rem;
		}
		.stat-card {
			background: var(--card-bg);
			border: 1px solid var(--border-color);
			border-radius: var(--radius-md);
			padding: 1rem;
			display: flex;
			flex-direction: column;
			justify-content: center;
			min-height: 86px;
			box-shadow: var(--shadow-sm);
			transition: all 0.2s ease;
		}
		.stat-card:hover {
			transform: translateY(-4px);
			box-shadow: var(--shadow-hover);
			border-top: 3px solid var(--primary);
		}
		.stat-label { font-size: 0.75rem; color: var(--text-muted); }
		.stat-value { font-size: 1.42rem; font-weight: 700; line-height: 1.1; margin-top: 0.15rem; }
		.stat-note { font-size: 0.8rem; color: var(--text-muted); margin-top: 0.1rem; }
		.usability-wrap { display: grid; grid-template-columns: auto 1fr; align-items: center; gap: 0.5rem; }
		.usability-gauge {
			--gauge-value: 0%;
			position: relative;
			width: 110px;
			height: 55px;
			overflow: hidden;
		}
		.usability-gauge::before {
			content: "";
			position: absolute;
			inset: 0;
			border-radius: 110px 110px 0 0;
			background: conic-gradient(from 180deg at 50% 100%, var(--emerald) 0 var(--gauge-value), #e2e6ef var(--gauge-value) 100%);
		}
		.usability-gauge::after {
			content: "";
			position: absolute;
			left: 50%;
			bottom: 0;
			transform: translateX(-50%);
			width: 78px;
			height: 39px;
			border-radius: 78px 78px 0 0;
			background: #fff;
			border-top: 1px solid #edf0f6;
		}
		.usability-info { display: grid; gap: 0.05rem; }
		.usability-percent { font-size: 1.2rem; font-weight: 700; }
		.usability-level { font-size: 0.8rem; color: var(--text-muted); }
		.section-card {
			background: var(--card-bg);
			border: 1px solid var(--border-color);
			border-radius: var(--radius-lg);
			padding: 1rem;
			margin-bottom: 1rem;
			box-shadow: var(--shadow-md);
		}
		.toolbar {
			display: grid;
			grid-template-columns: 1.3fr repeat(3, minmax(0, 1fr)) auto;
			gap: 0.5rem;
			margin-bottom: 0.65rem;
		}
		.toolbar input,
		.toolbar select,
		.toolbar button,
		.pagination button,
		.row-actions button {
			width: 100%;
			padding: 0.36rem 0.5rem;
			border-radius: 10px;
			border: 1px solid var(--border-color);
			font: inherit;
			font-size: 0.84rem;
			background: #fff;
			transition: all 0.16s ease;
			box-shadow: 0 1px 0 rgba(16,24,40,0.03);
		}
		.toolbar input:focus,
		.toolbar select:focus {
			border-color: var(--primary);
			box-shadow: 0 0 0 3px var(--primary-glow);
			outline: none;
		}
		.toolbar input,
		.toolbar select { background: #fff; }
		.toolbar button,
		.pagination button,
		.row-actions button { cursor: pointer; font-weight: 600; }
		.toolbar button:hover,
		.pagination button:hover { border-color: var(--primary); color: var(--primary); }
		.table-wrap { overflow: auto; border: 1px solid var(--border-color); border-radius: var(--radius-md); box-shadow: var(--shadow-sm); }
		table { width: 100%; border-collapse: collapse; min-width: var(--table-min-width); background: #fff; }
		th, td {
			border-bottom: 1px solid #eef4fb;
			border-right: 1px solid #f3f6fb;
			padding: var(--compact-padding) 0.42rem;
			font-size: 0.80rem;
			text-align: left;
			vertical-align: middle;
		}
		thead th { background: linear-gradient(90deg, rgba(99,102,241,0.06), rgba(99,102,241,0.02)); white-space: nowrap; position: sticky; top: 0; z-index: 3; color: var(--text-dark); }
		th.sortable { cursor: pointer; user-select: none; }
		th.sortable:hover { background: rgba(99,102,241,0.04); }
		tr:nth-child(even) td { background: #fbfdff; }
		tr:hover td { background: #fcfeff; }
		/* Ensure each table row has at least 100px height */
		tbody tr { height: 100px; }
		tbody td { height: 100px; max-height: 140px; vertical-align: middle; }
		tbody td > * { display: inline-flex; align-items: center; gap: 0.4rem; }
		.badge {
			display: inline-block;
			padding: 0.12rem 0.36rem;
			border-radius: 999px;
			font-size: 0.72rem;
			font-weight: 700;
			border: 1px solid rgba(0,0,0,0.06);
			background: rgba(99,102,241,0.06);
			color: var(--primary);
		}
		.badge.status-retain { background: rgba(16,185,129,0.08); border-color: rgba(16,185,129,0.15); color: var(--emerald); }
		.badge.status-maintenance { background: rgba(245,158,11,0.08); border-color: rgba(245,158,11,0.15); color: var(--amber); }
		.badge.status-retired { background: #f1f3f5; border-color: #e6e9ec; color: #5f6872; }
		.badge.status-retain { background: rgba(16,185,129,0.1); border-color: rgba(16,185,129,0.3); color: var(--emerald); }
		.badge.status-maintenance { background: rgba(245,158,11,0.1); border-color: rgba(245,158,11,0.3); color: var(--amber); }
			/* status-damaged removed — damaged rows are remapped to Retired */
		.badge.status-retired { background: #f1f3f5; border-color: #d7dce1; color: #5f6872; }

		/* Status dropdown styles (from location-management.php) */
		.status-select {
			padding: 0.35rem 0.5rem;
			border: 1px solid var(--border-color);
			border-radius: 6px;
			font-size: 0.8rem;
			color: var(--text-muted);
			background: var(--card-bg);
			cursor: pointer;
			min-width: 130px;
		}
		.status-select:focus {
			outline: none;
			border-color: var(--primary);
		}

		/* Styled action select to visually match the summary cards / pills */
		.action-select {
			-webkit-appearance: none;
			appearance: none;
			padding: 0.36rem 0.6rem;
			border-radius: 999px;
			border: 1px solid var(--border-color);
			background: linear-gradient(90deg,#fff,#fbfdff);
			font-weight: 700;
			color: var(--text-dark);
			cursor: pointer;
			min-width: 140px;
			box-shadow: 0 2px 8px rgba(16,24,40,0.03);
		}
		.action-select:focus { outline: none; box-shadow: 0 0 0 4px rgba(99,102,241,0.08); border-color: var(--primary); }
		.badge.overdue { background: rgba(244,63,94,0.1); border-color: rgba(244,63,94,0.3); color: var(--rose); }
		.row-actions { display: flex; gap: 0.32rem; }
		.row-actions button[data-action="view"],
		.row-actions button[data-action="status"],
		.row-actions button[data-action="maintenance"] {
			background: #fff;
			border-color: var(--border-color);
			color: var(--text-dark);
		}
		.row-actions button:hover {
			border-color: var(--primary);
			color: var(--primary);
		}
		.pagination {
			display: flex;
			justify-content: space-between;
			align-items: center;
			gap: 0.6rem;
			margin-top: 0.6rem;
		}
		.page-controls { display: flex; gap: 0.4rem; }
		@media (max-width: 1200px) {
			.stats-row { grid-template-columns: 1fr 1fr; }
			.toolbar { grid-template-columns: 1fr; }
			.pagination { flex-direction: column; align-items: stretch; }
		}
		@media (max-width: 768px) {
			.sidebar { transform: translateX(-100%); }
			.main-content { margin-left: 0; padding: 1rem; }
			.stats-row { grid-template-columns: 1fr; }
			.usability-wrap { grid-template-columns: 1fr; justify-items: start; }
		}


		/* Unified sidebar override: always-expanded */
		.sidebar {
			width: 240px;
			background: linear-gradient(180deg,#ffffff,#fbfdff);
			position: fixed;
			top: 0;
			left: 0;
			height: 100vh;
			box-shadow: 0 6px 18px rgba(2,6,23,0.06);
			overflow: hidden;
			z-index: 200;
		}

		.sidebar .brand-link { display:flex; align-items:center; gap:0.75rem; padding:1rem; text-decoration:none; color:inherit; }
		.sidebar .brand-link img { width:36px; height:36px; border-radius:6px; object-fit:cover }
		.sidebar .brand-text { opacity:1; transform:translateX(0); white-space:nowrap; }

		.sidebar-nav { padding:0.75rem; }
		.nav-section { margin-top:0.75rem; padding-top:0.5rem; border-top:1px dashed rgba(0,0,0,0.04); }
		.nav-section-title { font-size:0.65rem; font-weight:700; color:var(--text-muted); padding-left:0.5rem; margin-bottom:0.5rem; text-transform:uppercase; letter-spacing:0.06em }

			.nav-link { display:flex; align-items:center; gap:0.75rem; padding:0.65rem; border-radius:10px; color:var(--text-muted); text-decoration:none; font-weight:600; font-size:0.82rem; transition:all .18s ease; margin-bottom:0.2rem }
		.nav-icon { width:36px; height:36px; border-radius:8px; background:transparent; display:flex; align-items:center; justify-content:center; font-size:1.05rem }
		.nav-label { opacity:1; transform:translateX(0); white-space:nowrap }
		.nav-link:hover { background: var(--bg-body); color: var(--text-dark); }

		.nav-link.active { background: var(--primary-glow); color: var(--primary); position:relative }
		.nav-link.active::before { content: ''; position:absolute; left:0; top:8px; bottom:8px; width:4px; background:var(--primary); border-radius:4px }

		@media (max-width: 768px) { .sidebar { transform: translateX(-100%); } .main-content { margin-left: 0; } }

	</style>
</head>
<body>
	<div class="app-layout">
		<aside class="sidebar" aria-label="Primary Navigation">
			<div>
				<a href="index.html" class="brand-link">
					<img src="qw.png" alt="PNOC" />
					<div class="brand-text">
						<div style="font-weight:700;">PNOC Inventory</div>
						<div style="font-size:0.75rem;color:var(--text-muted);">Management System</div>
					</div>
				</a>
			</div>
			<nav class="sidebar-nav" role="navigation">
				<div class="nav-section">
					<div class="nav-section-title">Main Menu</div>
					<a href="inventory-dashboard.php" class="nav-link"><span class="nav-icon">⌂</span><span class="nav-label">Dashboard</span></a>
					<a href="bentaco-inventory.php" class="nav-link"><span class="nav-icon">☐</span><span class="nav-label">BENTACO Inventory</span></a>
					<a href="iot-inventory.php" class="nav-link"><span class="nav-icon">◎</span><span class="nav-label">IOT Inventory</span></a>
				</div>
				<div class="nav-section">
					<div class="nav-section-title">Management</div>
					<a href="location-management.php" class="nav-link"><span class="nav-icon">⊕</span><span class="nav-label">Location Management</span></a>
					<!-- Item Allocation removed from sidebar -->
					<a href="item-status-monitoring.php" class="nav-link active"><span class="nav-icon">◉</span><span class="nav-label">Item Status Monitoring</span></a>
				</div>
				<div class="nav-section">
					<div class="nav-section-title">Analytics</div>
					<a href="report-generation.php" class="nav-link"><span class="nav-icon">☰</span><span class="nav-label">Reports</span></a>
				</div>
			</nav>
		</aside>

		<main class="main-content">
		</main>
	</div>

	<script>
		const STORAGE_KEYS = {
			BENTACO: "pnoc_inventory_bentaco_v1",
			IOT: "pnoc_inventory_iot_v1"
		};

		// Minimal initialization - table and filters removed
		console.log("Item Status Monitoring page loaded");
	</script>
</body>
</html>
