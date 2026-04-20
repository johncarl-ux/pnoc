<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>PNOC Inventory | BENTACO Inventory</title>
	<link rel="icon" type="image/png" href="qw.png" />
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/tesseract.js@5/dist/tesseract.min.js"></script>
	<style>
		:root {
			--primary: #4f46e5;
			--primary-light: #6366f1;
			--primary-glow: rgba(79, 70, 229, 0.15);
			--bg-body: #f8f9fa;
			--card-bg: #ffffff;
			--text-dark: #111827;
			--text-muted: #6b7280;
			--text-light: #9ca3af;
			--sidebar-width: 260px;
			--shadow-sm: 0 1px 2px rgba(0,0,0,0.04);
			--shadow-md: 0 4px 12px rgba(0,0,0,0.06);
			--shadow-lg: 0 8px 24px rgba(0,0,0,0.08);
			--shadow-hover: 0 12px 28px rgba(0,0,0,0.12);
			--radius-md: 12px;
			--radius-lg: 16px;
			--emerald: #10b981;
			--rose: #f43f5e;
			--amber: #f59e0b;
			--sky: #0ea5e9;
			--border-color: #e5e7eb;
		}
		* { box-sizing: border-box; margin: 0; padding: 0; }
		body {
			font-family: 'Inter', system-ui, -apple-system, sans-serif;
			background: var(--bg-body);
			color: var(--text-dark);
			line-height: 1.5;
			min-height: 100vh;
			font-weight: 400;
		}
		.app-layout { display: flex; min-height: 100vh; }
		.sidebar {
			width: var(--sidebar-width);
			background: var(--card-bg);
			box-shadow: var(--shadow-sm);
			position: fixed;
			top: 0;
			left: 0;
			height: 100vh;
			overflow-y: auto;
			z-index: 100;
		}

		/* unified sidebar font */
		.sidebar { font-family: 'Inter', system-ui, -apple-system, sans-serif; font-size:14px }
		.sidebar-header {
			padding: 1.5rem 1.25rem;
			border-bottom: 1px solid #f1f3f4;
		}
		.sidebar-brand {
			display: flex;
			align-items: center;
			gap: 0.75rem;
			text-decoration: none;
			color: var(--text-dark);
		}
		.sidebar-brand img { width: 32px; height: 32px; object-fit: contain; }
			.sidebar-brand-text { font-weight: 700; font-size: 0.95rem; }
			.sidebar-brand-sub { font-size: 0.65rem; color: var(--text-muted); font-weight: 400; }

			/* Sidebar text color override: make all sidebar text black */
			.sidebar { color: #000; }
			.sidebar .sidebar-brand-text,
			.sidebar .sidebar-brand-sub,
			.sidebar .nav-link,
			.sidebar .nav-section-title { color: #000; }
		.sidebar-nav { padding: 1.25rem 0.75rem; }
		.nav-section { margin-bottom: 1.75rem; }
		.nav-section-title {
			font-size: 0.65rem;
			font-weight: 600;
			text-transform: uppercase;
			letter-spacing: 0.08em;
			color: var(--text-light);
			padding: 0 0.75rem;
			margin-bottom: 0.5rem;
		}
		.nav-link {
			display: flex;
			align-items: center;
			gap: 0.75rem;
			padding: 0.6rem 0.75rem;
			border-radius: 10px;
			text-decoration: none;
			color: var(--text-muted);
			font-size: 0.85rem;
			font-weight: 500;
			transition: all 0.2s ease;
			margin-bottom: 0.15rem;
		}
		.nav-link:hover { background: var(--bg-body); color: var(--text-dark); }
		.nav-link.active { background: var(--primary-glow); color: var(--primary); }
		.nav-link.active .nav-icon { background: var(--primary); color: white; box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3); }
		.nav-icon {
			display: flex;
			align-items: center;
			justify-content: center;
			width: 30px;
			height: 30px;
			border-radius: 8px;
			background: transparent;
			font-size: 0.95rem;
			flex-shrink: 0;
			transition: all 0.2s ease;
		}
		.main-content {
			flex: 1;
			margin-left: var(--sidebar-width);
			padding: 2rem 2.75rem;
		}
		.page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
		.page-title { font-size: 1.65rem; font-weight: 700; color: var(--text-dark); margin-bottom: 0.35rem; letter-spacing: -0.02em; }
		.home-btn {
			display: inline-flex;
			align-items: center;
			gap: 0.5rem;
			padding: 0.45rem 0.75rem;
			border-radius: 8px;
			background: var(--card-bg);
			border: 1px solid var(--border-color);
			color: var(--text-dark);
			text-decoration: none;
			font-weight: 600;
			box-shadow: 0 6px 18px rgba(2,6,23,0.06);
			transition: transform 140ms ease, box-shadow 140ms ease;
		}
		.home-btn:hover { transform: translateY(-2px); box-shadow: 0 12px 28px rgba(2,6,23,0.08); }
		.page-subtitle { font-size: 0.875rem; color: var(--text-muted); }
		.content-card {
			background: var(--card-bg);
			border-radius: var(--radius-lg);
			padding: 1.6rem;
			margin-bottom: 1.9rem;
			box-shadow: var(--shadow-md);
		}
		.card-header {
			display: flex;
			justify-content: space-between;
			align-items: center;
			margin-bottom: 1.25rem;
		}
		.card-title { font-size: 1rem; font-weight: 600; letter-spacing: -0.01em; }
		.card-subtitle { font-size: 0.75rem; color: var(--text-muted); margin-top: 0.15rem; }
		.stats-grid {
			display: grid;
			grid-template-columns: repeat(3, minmax(0, 1fr));
			gap: 1.2rem;
		}
		.stat-card {
			background: var(--card-bg);
			border-radius: var(--radius-md);
			padding: 1.45rem;
			box-shadow: var(--shadow-sm);
			transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
			cursor: default;
			position: relative;
			overflow: hidden;
		}
		.stat-card::before {
			content: '';
			position: absolute;
			top: 0;
			left: 0;
			right: 0;
			height: 3px;
			background: linear-gradient(90deg, var(--primary), var(--primary-light));
			opacity: 0;
			transition: opacity 0.3s ease;
		}
		.stat-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-hover); }
		.stat-card:hover::before { opacity: 1; }
		.stat-card .label {
			display: block;
			font-size: 0.75rem;
			font-weight: 500;
			color: var(--text-muted);
			margin-bottom: 0.5rem;
			text-transform: uppercase;
			letter-spacing: 0.04em;
		}
		.stat-card .value {
			font-size: 1.75rem;
			font-weight: 700;
			color: var(--text-dark);
			letter-spacing: -0.02em;
		}
		@keyframes kpiRise {
			from { opacity: 0; transform: translateY(6px); }
			to { opacity: 1; transform: translateY(0); }
		}
		@keyframes rowFadeIn {
			from { opacity: 0; transform: translateY(4px); }
			to { opacity: 1; transform: translateY(0); }
		}
		.hero {
			background: var(--card-bg);
			border-radius: var(--radius-lg);
			overflow: hidden;
			margin-bottom: 1.9rem;
			box-shadow: var(--shadow-md);
		}
		.hero-grid {
			display: grid;
			grid-template-columns: 1fr 1.1fr;
			align-items: center;
			gap: 1.25rem;
		}
		.hero-copy { padding: 1.9rem; }
		.hero-copy h2 {
			font-family: 'Playfair Display', Georgia, serif;
			font-size: clamp(1.4rem, 3.2vw, 2.2rem);
			margin-bottom: 0.35rem;
			font-weight: 600;
			letter-spacing: -0.01em;
			color: var(--text-dark);
			line-height: 1.05;
		}
		.hero-copy p { color: var(--text-muted); font-size: 0.9rem; }
		.hero-image {
			width: 100%;
			height: 260px;
			object-fit: cover;
			border-left: 1px solid var(--border-color);
			background: #eef4fa;
			border-radius: 12px;
			box-shadow: 0 12px 30px rgba(17,24,39,0.08);
			transition: transform 220ms ease, box-shadow 220ms ease;
			display: block;
		}

		.hero-image:hover {
			transform: scale(1.03);
			box-shadow: 0 20px 48px rgba(17,24,39,0.12);
		}
		.section-card {
			background: var(--card-bg);
			border-radius: var(--radius-lg);
			padding: 1.6rem;
			margin-bottom: 1.9rem;
			box-shadow: var(--shadow-md);
		}
		.section-head {
			display: flex;
			justify-content: space-between;
			align-items: center;
			gap: 0.5rem;
			margin-bottom: 1.25rem;
		}
		.section-head h2 { font-size: 1rem; font-weight: 600; }
		.section-head span { font-size: 0.8rem; color: var(--text-muted); }
		.kpi-grid {
			display: grid;
			grid-template-columns: repeat(3, minmax(0, 1fr));
			gap: 1.2rem;
		}
		.kpi {
			background: var(--card-bg);
			border-radius: var(--radius-md);
			padding: 1.2rem 1.4rem;
			box-shadow: var(--shadow-sm);
			animation: kpiRise 0.35s ease-out;
			transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
			position: relative;
			overflow: hidden;
		}
		.kpi::before {
			content: '';
			position: absolute;
			top: 0;
			left: 0;
			right: 0;
			height: 3px;
			background: linear-gradient(90deg, var(--primary), var(--primary-light));
			opacity: 0;
			transition: opacity 0.3s ease;
		}
		.kpi:hover { transform: translateY(-4px); box-shadow: var(--shadow-hover); }
		.kpi:hover::before { opacity: 1; }
		.kpi-label {
			font-size: 0.75rem;
			font-weight: 500;
			color: var(--text-muted);
			margin-bottom: 0.35rem;
			text-transform: uppercase;
			letter-spacing: 0.04em;
		}
		.kpi-value {
			font-size: 1.35rem;
			font-weight: 700;
			line-height: 1.2;
			letter-spacing: -0.02em;
		}
		.meta { color: var(--text-muted); font-size: 0.84rem; }
		.toolbar {
			display: grid;
			grid-template-columns: 1fr 250px auto;
			gap: 1rem;
			margin-bottom: 1.25rem;
		}
		.upload-bar {
			display: grid;
			grid-template-columns: 1.3fr auto auto;
			gap: 1rem;
			margin-bottom: 1.25rem;
		}
		.toolbar input,
		.upload-bar input,
		.toolbar select,
		.toolbar button,
		.upload-bar button,
		.pagination button {
			width: 100%;
			padding: 0.6rem 0.75rem;
			border-radius: 10px;
			border: 1px solid var(--border-color);
			font: inherit;
			font-size: 0.85rem;
			background: var(--card-bg);
			transition: all 0.2s ease;
		}
		.toolbar input:focus,
		.upload-bar input:focus,
		.toolbar select:focus {
			outline: none;
			border-color: var(--primary);
			box-shadow: 0 0 0 3px var(--primary-glow);
		}
		.toolbar button,
		.upload-bar button,
		.pagination button { 
			cursor: pointer; 
			font-weight: 600;
			transition: all 0.2s ease;
		}
		.toolbar button:hover,
		.upload-bar button:hover,
		.pagination button:hover {
			background: var(--bg-body);
		}
		.table-wrap { 
			overflow: auto; 
			border-radius: var(--radius-md); 
			box-shadow: var(--shadow-sm);
			margin-top: 0.4rem;
		}
		table {
			width: 100%;
			border-collapse: collapse;
			min-width: 920px;
			background: var(--card-bg);
		}
		th,
		td {
			border: 1px solid var(--border-color);
			padding: 0.65rem 0.8rem;
			font-size: 0.83rem;
			text-align: left;
			vertical-align: middle;
		}
		thead th { background: #edf2f7; white-space: nowrap; }
		th.sortable { cursor: pointer; user-select: none; }
		th.sortable:hover { background: #e4edf7; }
		tr:nth-child(even) td { background: #fafcff; }
		.center { text-align: center; }
		.badge {
			display: inline-block;
			padding: 0.16rem 0.45rem;
			border-radius: 999px;
			font-size: 0.73rem;
			font-weight: 600;
			border: 1px solid #c8d7e6;
			background: #f3f8fd;
			color: #284a6a;
		}
		.badge.status-usable { background: #e9f8ef; border-color: #b8e6c6; color: #1f7a3f; }
		.badge.status-maintenance { background: #fff8df; border-color: #f0db8f; color: #8a6a07; }
			/* .badge.status-damaged removed — map damaged/defective/unusable to Retired */
		.badge.status-retired { background: #f1f3f5; border-color: #d7dce1; color: #5f6872; }
		.row-actions { display: flex; gap: 0.32rem; }
		.row-actions button {
			padding: 0.3rem 0.46rem;
			border-radius: 7px;
			border: 1px solid var(--border-color);
			background: #fff;
			font: inherit;
			font-size: 0.78rem;
			cursor: pointer;
		}
		.data-row { cursor: pointer; }
		.data-row.row-appear { animation: rowFadeIn 0.28s ease-out; }
		.data-row:hover td { background: #f3f8fd !important; }
		.pagination {
			display: flex;
			justify-content: space-between;
			align-items: center;
			gap: 0.6rem;
			margin-top: 1rem;
		}
		.page-controls { display: flex; gap: 0.4rem; }
		@media (max-width: 1200px) {
			.main-content { padding: 1.5rem 1.4rem; }
			.stats-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
			.hero-grid { grid-template-columns: 1fr; }
			.kpi-grid { grid-template-columns: 1fr; }
			.hero-image {
				height: 130px;
				border-left: 0;
				border-top: 1px solid var(--border-color);
			}
			.toolbar { grid-template-columns: 1fr; }
			.upload-bar { grid-template-columns: 1fr; }
			.pagination { flex-direction: column; align-items: stretch; }
		}
		@media (max-width: 768px) {
			.sidebar { transform: translateX(-100%); }
			.main-content { margin-left: 0; padding: 1rem 0.875rem; }
			.stats-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
		}
		/* Unified sidebar override: always-expanded sidebar (no hover collapse) */
		.sidebar {
			width: 240px;
			background: linear-gradient(180deg,#ffffff,#fbfdff);
			position: fixed;
			top: 0;
			left: 0;
			height: 100vh;
			box-shadow: 0 6px 18px rgba(2,6,23,0.06);
			/* width is fixed; no hover collapse */
			overflow: hidden;
			z-index: 200;
		}

		.sidebar .brand-link { display:flex; align-items:center; gap:0.75rem; padding:1rem; text-decoration:none; color:inherit; }
		.sidebar .brand-link img { width:36px; height:36px; border-radius:6px; object-fit:cover }
		.sidebar .brand-text { opacity:1; transform:translateX(0); white-space:nowrap; }

		.sidebar-nav { padding:0.75rem; }
		.nav-section { margin-top:0.75rem; padding-top:0.5rem; border-top:1px dashed rgba(0,0,0,0.04); }
		.nav-section-title { font-size:0.65rem; font-weight:700; color:var(--text-muted); padding-left:0.5rem; margin-bottom:0.5rem; text-transform:uppercase; letter-spacing:0.06em }

			.nav-link { display:flex; align-items:center; gap:0.75rem; padding:0.65rem; border-radius:10px; color:var(--text-muted); text-decoration:none; font-weight:600; font-size:0.875rem; transition:all .18s ease; margin-bottom:0.2rem }
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
					<a href="bentaco-inventory.php" class="nav-link active"><span class="nav-icon">☐</span><span class="nav-label">BENTACO Inventory</span></a>
					<a href="iot-inventory.php" class="nav-link"><span class="nav-icon">◎</span><span class="nav-label">IOT Inventory</span></a>
				</div>
				<div class="nav-section">
					<div class="nav-section-title">Management</div>
					<a href="location-management.php" class="nav-link"><span class="nav-icon">⊕</span><span class="nav-label">Location Management</span></a>
					<!-- Item Allocation removed from sidebar -->
					<a href="item-status-monitoring.php" class="nav-link"><span class="nav-icon">◉</span><span class="nav-label">Item Status Monitoring</span></a>
				</div>
			</nav>
		</aside>

		<main class="main-content">
			<div class="page-header">
				<div>
					<h1 class="page-title">BENTACO Inventory</h1>
					<p class="page-subtitle" id="metaInfo">0 results</p>
				</div>
				<a href="index.html" class="home-btn">Home</a>
			</div>
			<div class="hero" aria-label="BENTACO inventory hero">
				<div class="hero-grid">
					<div class="hero-copy">
						<h2>Organized BENTACO asset workspace</h2>
						<p>Search, filter, and review assets faster in a cleaner, user-friendly layout.</p>
					</div>
					<img class="hero-image" src="as.jpg" alt="BENTACO inventory visual" />
				</div>
			</div>

			<div class="section-card" aria-label="Inventory overview">
				<div class="section-head">
					<h2>Overview</h2>
					<span>Live summary</span>
				</div>
				<div class="kpi-grid">
					<div class="kpi">
						<div class="kpi-label">Total</div>
						<div class="kpi-value" id="overviewTotalAssets">0</div>
					</div>
					<div class="kpi">
						<div class="kpi-label">Visible</div>
						<div class="kpi-value" id="overviewVisibleAssets">0</div>
					</div>
					<div class="kpi">
						<div class="kpi-label">Value</div>
						<div class="kpi-value" id="overviewTotalValue">₱0.00</div>
					</div>
				</div>
			</div>

			<div class="section-card" aria-label="Inventory controls">
				<div class="section-head">
					<h2>Search</h2>
					<span>Find assets quickly</span>
				</div>
				<div class="toolbar">
					<input id="searchInput" type="text" placeholder="Search property, asset, description, employee, cost, MR no." />
					<select id="employeeFilter" aria-label="Filter by employee">
						<option value="">All employees</option>
					</select>
					<button type="button" id="clearSearch">Clear</button>
				</div>

				<div class="upload-bar" aria-label="PDF bulk upload">
					<input id="pdfUploadInput" type="file" accept="application/pdf,.pdf" />
					<button type="button" id="uploadPdfBtn">Bulk Upload PDF</button>
				</div>
			</div>

			<div class="section-card" aria-label="Inventory table section">
				<div class="section-head">
					<h2>Inventory Table</h2>
					<span>Sortable and pageable</span>
				</div>

				<div class="table-wrap">
					<table aria-label="BENTACO inventory table view">
						<thead>
							<tr>
								<th class="sortable" data-sort="propertyNumber">Property No.</th>
								<th class="sortable" data-sort="assetNumber">Asset Number</th>
								<th class="sortable" data-sort="itemDescription">Item Description</th>
								<th class="sortable" data-sort="assignedEmployee">Employee</th>
								<th class="sortable" data-sort="acquisitionCost">Acquisition Cost</th>
								<th class="sortable" data-sort="mrNo">MR No.</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody id="tableBody">
							<tr><td colspan="7">No records.</td></tr>
						</tbody>
					</table>
				</div>

				<div class="pagination">
					<div class="meta" id="pageInfo">Page 1 of 1</div>
					<div class="page-controls">
						<button type="button" id="prevPage">Previous</button>
						<button type="button" id="nextPage">Next</button>
					</div>
				</div>
			</div>
		</main>
	</div>

	<script>
		const GROUP_NAME = "BENTACO";
		const STORAGE_KEY = "pnoc_inventory_bentaco_v1";
		const API_SOURCE = "bentaco";
		const INVENTORY_API_URL = "api/inventory.php";

		const state = {
			rows: [],
			search: "",
			employeeFilter: "",
			sortKey: "propertyNumber",
			sortDir: "asc",
			page: 1,
			pageSize: 50
		};

		const refs = {
			searchInput: document.getElementById("searchInput"),
			employeeFilter: document.getElementById("employeeFilter"),
			clearSearch: document.getElementById("clearSearch"),
			pdfUploadInput: document.getElementById("pdfUploadInput"),
			uploadPdfBtn: document.getElementById("uploadPdfBtn"),
			tableBody: document.getElementById("tableBody"),
			metaInfo: document.getElementById("metaInfo"),
			pageInfo: document.getElementById("pageInfo"),
			prevPage: document.getElementById("prevPage"),
			nextPage: document.getElementById("nextPage"),
			overviewTotalAssets: document.getElementById("overviewTotalAssets"),
			overviewVisibleAssets: document.getElementById("overviewVisibleAssets"),
			overviewTotalValue: document.getElementById("overviewTotalValue")
		};

		const escapeHtml = (value) =>
			String(value ?? "")
				.replace(/&/g, "&amp;")
				.replace(/</g, "&lt;")
				.replace(/>/g, "&gt;")
				.replace(/\"/g, "&quot;")
				.replace(/'/g, "&#39;");

		const formatMoney = (value) => {
			const num = Number(value || 0);
			return Number.isFinite(num) ? new Intl.NumberFormat("en-PH", { style: "currency", currency: "PHP" }).format(num) : "₱0.00";
		};

		if (window.pdfjsLib) {
			window.pdfjsLib.GlobalWorkerOptions.workerSrc = "https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.worker.min.js";
		}

		function readLocalRows() {
			try {
				const raw = localStorage.getItem(STORAGE_KEY);
				const rows = raw ? JSON.parse(raw) : [];
				return Array.isArray(rows) ? rows : [];
			} catch {
				return [];
			}
		}

		async function persistRowsToApi(rows) {
			const response = await fetch(INVENTORY_API_URL, {
				method: "POST",
				headers: { "Content-Type": "application/json" },
				body: JSON.stringify({ source: API_SOURCE, rows })
			});
			if (!response.ok) {
				throw new Error(`Failed to save ${GROUP_NAME} rows.`);
			}
		}

		async function loadRows() {
			const localRows = readLocalRows();
			try {
				const response = await fetch(`${INVENTORY_API_URL}?source=${API_SOURCE}`, { cache: "no-store" });
				if (!response.ok) {
					throw new Error("Unable to load API data.");
				}
				const payload = await response.json();
				const apiRows = Array.isArray(payload.rows) ? payload.rows : [];
				if (apiRows.length) {
					state.rows = apiRows;
					localStorage.setItem(STORAGE_KEY, JSON.stringify(apiRows));
					return;
				}
				state.rows = localRows;
				if (localRows.length) {
					await persistRowsToApi(localRows);
				}
			} catch {
				state.rows = localRows;
			}
		}

		function saveRows() {
			localStorage.setItem(STORAGE_KEY, JSON.stringify(state.rows));
			persistRowsToApi(state.rows).catch((error) => {
				console.error(error);
			});
		}

		function nextItemId() {
			const max = state.rows.reduce((acc, item) => {
				const match = String(item.itemId || "").match(/(\d+)$/);
				const num = match ? Number(match[1]) : 0;
				return Math.max(acc, num);
			}, 0);
			return `BEN-${String(max + 1).padStart(5, "0")}`;
		}

		function locationSummary(row) {
			const building = String(row.building || "").trim();
			const room = String(row.room || "").trim();
			const storageArea = String(row.storageArea || "").trim();
			const parts = [building, room, storageArea].filter(Boolean);
			if (parts.length) {
				return parts.join(" / ");
			}
			return String(row.itemLocation || "").trim();
		}

		function normalizeLocationFields(row) {
			if (!row || typeof row !== "object") return row;
			const locationText = String(row.itemLocation || "").trim();
			if (!row.building) {
				row.building = locationText;
			}
			if (!row.room) {
				row.room = "";
			}
			if (!row.storageArea) {
				row.storageArea = "";
			}
			if (!row.department) {
				row.department = "";
			}
			row.itemLocation = locationSummary(row);
			return row;
		}

		function normalizeAllocationFields(row) {
			if (!row || typeof row !== "object") return row;
			const fallbackAllocatedTo = String(row.allocatedTo || row.allocationTo || "").trim();
			row.allocatedTo = fallbackAllocatedTo;
			row.allocationType = String(row.allocationType || "").trim();
			row.allocationDate = String(row.allocationDate || "").trim();
			row.returnDate = String(row.returnDate || "").trim();
			row.allocationNotes = String(row.allocationNotes || "").trim();
			row.allocationTo = fallbackAllocatedTo;
			return row;
		}

		function normalizeHistory(row) {
			if (!row || typeof row !== "object") return row;
			if (!Array.isArray(row.historyLog)) {
				row.historyLog = [];
			}
			if (!row.historyLog.length) {
				row.historyLog.push({
					type: "created",
					title: "Asset record created",
					details: "Initial record setup",
					at: String(row.dateAdded || new Date().toLocaleString())
				});
			}
			return row;
		}

		function addHistory(row, type, title, details) {
			normalizeHistory(row);
			row.historyLog.unshift({
				type,
				title,
				details,
				at: new Date().toLocaleString()
			});
		}

		function normalizeStatus(value) {
			const text = String(value || "").trim().toLowerCase();
			if (text === "usable") return "Usable";
			if (text === "under maintenance" || text === "maintenance") return "Under Maintenance";
			if (text === "retired") return "Retired";
			if (text === "damaged" || text === "not usable" || text === "defective" || text === "unusable") return "Retired";
			return "Usable";
		}

		function normalizePdfHeader(text) {
			return String(text || "").toLowerCase().replace(/[^a-z0-9]/g, "");
		}

		function headerFieldFromCell(text) {
			const normalized = normalizePdfHeader(text);
			if (!normalized) return null;
			if (
				normalized === "property"
				|| normalized === "propertyno"
				|| normalized === "propertynumber"
				|| normalized === "propertynum"
				|| normalized.includes("propertynumber")
				|| normalized === "propertyno"
				|| normalized === "propertynum"
				|| normalized.includes("propertyno")
			) return "propertyNumber";
			if (
				normalized === "asset"
				|| normalized === "assetno"
				|| normalized === "assetnumber"
				|| normalized.includes("assetnumber")
				|| normalized === "assetno"
				|| normalized.includes("assetno")
			) return "assetNumber";
			if (
				normalized === "item"
				|| normalized === "description"
				|| normalized === "itemdescription"
				|| normalized.includes("itemdescription")
				|| normalized === "itemdesc"
				|| normalized === "description"
			) return "itemDescription";
			if (
				normalized === "acquisition"
				|| normalized === "cost"
				|| normalized === "acquisitioncost"
				|| normalized.includes("acquisitioncost")
				|| normalized.includes("acquisitionvalue")
				|| normalized === "cost"
				|| normalized === "amount"
				|| normalized.includes("totalcost")
				|| normalized.includes("unitcost")
				|| normalized.includes("totalamount")
				|| normalized.includes("itemamount")
			) return "acquisitionCost";
			if (normalized === "mr" || normalized === "mrno" || normalized.includes("mrno") || normalized.includes("mrnumber") || normalized.includes("paricsnumber") || normalized === "parnumber" || normalized === "icsnumber") return "mrNo";
			if (
				normalized.includes("employeename")
				|| normalized.includes("assignedemployee")
				|| normalized.includes("assignedto")
				|| normalized.includes("enduser")
				|| normalized.includes("accountableofficer")
				|| normalized === "employee"
			) return "assignedEmployee";
			return null;
		}

		function splitPdfLineToCells(items) {
			const sorted = [...items]
				.filter((entry) => String(entry.str || "").trim())
				.sort((a, b) => a.x - b.x);
			const cells = [];
			const gapThreshold = 18;
			sorted.forEach((entry) => {
				const text = String(entry.str || "").trim();
				if (!text) return;
				const last = cells[cells.length - 1];
				if (!last) {
					cells.push({ text, x: entry.x, endX: entry.x + entry.w });
					return;
				}
				const gap = entry.x - last.endX;
				if (gap > gapThreshold) {
					cells.push({ text, x: entry.x, endX: entry.x + entry.w });
				} else {
					last.text = `${last.text} ${text}`.trim();
					last.endX = Math.max(last.endX, entry.x + entry.w);
				}
			});
			return cells;
		}

		function mapHeader(cells) {
			const indexByField = {};
			cells.forEach((cell, index) => {
				const cellText = String(cell.text || "").trim();
				const nextText = String(cells[index + 1]?.text || "").trim();
				const combinedText = nextText ? `${cellText} ${nextText}` : cellText;
				const field = headerFieldFromCell(combinedText) || headerFieldFromCell(cellText);
				if (field && indexByField[field] === undefined) {
					indexByField[field] = index;
				}
			});
			const required = ["propertyNumber", "assetNumber", "itemDescription", "acquisitionCost", "mrNo"];
			const validRequired = required.every((field) => indexByField[field] !== undefined);
			if (!validRequired) return null;

			const columns = required
				.map((field) => ({ field, index: indexByField[field] }))
				.map(({ field, index }) => {
					const cell = cells[index];
					const x = Number(cell?.x || 0);
					const endX = Number(cell?.endX || x);
					return {
						field,
						x,
						endX,
						center: (x + endX) / 2
					};
				})
				.sort((a, b) => a.center - b.center);

			const boundaries = [];
			for (let i = 0; i < columns.length - 1; i += 1) {
				boundaries.push((columns[i].center + columns[i + 1].center) / 2);
			}

			const ranges = columns.map((column, index) => {
				const start = index === 0 ? Number.NEGATIVE_INFINITY : boundaries[index - 1];
				const end = index === columns.length - 1 ? Number.POSITIVE_INFINITY : boundaries[index];
				return { field: column.field, start, end };
			});

			return { indexByField, columns, boundaries, ranges };
		}

		function buildHeaderMetaFromColumns(columns) {
			const sortedColumns = [...columns].sort((a, b) => a.center - b.center);
			const boundaries = [];
			for (let i = 0; i < sortedColumns.length - 1; i += 1) {
				boundaries.push((sortedColumns[i].center + sortedColumns[i + 1].center) / 2);
			}

			const ranges = sortedColumns.map((column, index) => {
				const start = index === 0 ? Number.NEGATIVE_INFINITY : boundaries[index - 1];
				const end = index === sortedColumns.length - 1 ? Number.POSITIVE_INFINITY : boundaries[index];
				return { field: column.field, start, end };
			});

			const indexByField = {};
			sortedColumns.forEach((column, index) => {
				indexByField[column.field] = index;
			});

			return {
				indexByField,
				columns: sortedColumns,
				boundaries,
				ranges
			};
		}

		function mapHeaderFromLineItems(lineItems) {
			const required = ["propertyNumber", "assetNumber", "itemDescription", "acquisitionCost", "mrNo"];
			const words = [...lineItems]
				.map((item) => ({
					text: String(item.str || "").trim(),
					x: Number(item.x || 0),
					w: Math.max(1, Number(item.w || 0))
				}))
				.filter((item) => item.text)
				.sort((a, b) => a.x - b.x);

			if (!words.length) return null;

			const fieldColumns = {};
			for (let i = 0; i < words.length; i += 1) {
				const current = words[i];
				const next = words[i + 1];
				const singleField = headerFieldFromCell(current.text);
				const pairText = next ? `${current.text} ${next.text}` : "";
				const pairField = pairText ? headerFieldFromCell(pairText) : null;

				const field = pairField || singleField;
				if (!field || fieldColumns[field]) continue;

				const endX = pairField && next
					? Math.max(current.x + current.w, next.x + next.w)
					: current.x + current.w;

				fieldColumns[field] = {
					field,
					x: current.x,
					endX,
					center: (current.x + endX) / 2
				};
			}

			const hasAllRequired = required.every((field) => fieldColumns[field]);
			if (!hasAllRequired) return null;

			return buildHeaderMetaFromColumns(required.map((field) => fieldColumns[field]));
		}

		function toAmount(value) {
			const cleaned = String(value || "").replace(/[^\d.-]/g, "");
			const parsed = Number(cleaned);
			return Number.isFinite(parsed) ? parsed : 0;
		}

		function bucketIndexForX(x, boundaries) {
			for (let i = 0; i < boundaries.length; i += 1) {
				if (x <= boundaries[i]) return i;
			}
			return boundaries.length;
		}

		function assignLineToColumns(lineItems, headerMeta) {
			const buckets = headerMeta.columns.map(() => []);
			const sorted = [...lineItems].sort((a, b) => a.x - b.x);
			sorted.forEach((item) => {
				const text = String(item.str || "").trim();
				if (!text) return;
				const x = Number(item.x || 0);
				const w = Math.max(1, Number(item.w || 0));
				const itemEnd = x + w;

				let index = -1;
				let maxOverlap = -1;
				headerMeta.ranges.forEach((range, rangeIndex) => {
					const overlapStart = Math.max(x, range.start);
					const overlapEnd = Math.min(itemEnd, range.end);
					const overlap = overlapEnd - overlapStart;
					if (overlap > maxOverlap) {
						maxOverlap = overlap;
						index = rangeIndex;
					}
				});

				if (index < 0) {
					const center = x + w / 2;
					index = bucketIndexForX(center, headerMeta.boundaries);
				}
				buckets[index].push(text);
			});

			const record = {};
			headerMeta.columns.forEach((column, index) => {
				record[column.field] = buckets[index].join(" ").replace(/\s+/g, " ").trim();
			});
			return record;
		}

		function lineItemsToText(items) {
			return [...items]
				.sort((a, b) => Number(a.x || 0) - Number(b.x || 0))
				.map((item) => String(item.str || "").trim())
				.filter(Boolean)
				.join(" ")
				.replace(/\s+/g, " ")
				.trim();
		}

		function extractGlobalEmployeeFromLines(lines) {
			const patterns = [
				/employee\s*name\s*[:\-]\s*(.+)$/i,
				/assigned\s*to\s*[:\-]\s*(.+)$/i,
				/assigned\s*employee\s*[:\-]\s*(.+)$/i,
				/end\s*user\s*[:\-]\s*(.+)$/i,
				/accountable\s*officer\s*[:\-]\s*(.+)$/i
			];

			for (const line of lines) {
				const text = lineItemsToText(line.items || []);
				if (!text) continue;
				for (const pattern of patterns) {
					const match = text.match(pattern);
					if (!match) continue;
					const name = String(match[1] || "").trim();
					if (name && !/^(all|n\/a|none)$/i.test(name)) {
						return name;
					}
				}
			}

			return "";
		}

		function looksLikeHeaderRow(record) {
			const propertyHeader = normalizePdfHeader(record.propertyNumber);
			const descriptionHeader = normalizePdfHeader(record.itemDescription);
			const mrHeader = normalizePdfHeader(record.mrNo);
			return propertyHeader === "propertynumber"
				|| propertyHeader === "propertyno"
				|| propertyHeader === "propertynum"
				|| descriptionHeader === "itemdescription"
				|| descriptionHeader === "itemdesc"
				|| descriptionHeader === "description"
				|| mrHeader.includes("mrno")
				|| mrHeader.includes("paricsnumber");
		}

		function isLikelyValidTableRow(record) {
			const propertyNumber = String(record.propertyNumber || "").trim();
			const assetNumber = String(record.assetNumber || "").trim();
			const itemDescription = String(record.itemDescription || "").trim();
			const mrNo = String(record.mrNo || record.parIcsNumber || "").trim();
			const hasAnyCore = Boolean(propertyNumber || assetNumber || itemDescription || mrNo);
			if (!hasAnyCore) return false;
			if (looksLikeHeaderRow(record)) return false;
			if (/^total\b/i.test(propertyNumber)) return false;
			if (propertyNumber && !/[a-z0-9]/i.test(propertyNumber)) return false;
			return true;
		}

		function normalizeMissingPdfValue(value) {
			const text = String(value || "").trim();
			if (!text) return "N/A";
			if (/^(n\/?a|na|null|none|-|--|nill?)$/i.test(text)) return "N/A";
			return text;
		}

		function looksLikeAssetCode(value) {
			const text = String(value || "").trim();
			if (!text) return false;
			if (/^n\/?a$/i.test(text)) return false;
			if (/\d{2,}[-/]\d{2,}/.test(text)) return true;
			if (/^[a-z0-9\-\/\.]{4,}$/i.test(text) && !/\s{2,}/.test(text) && text.split(/\s+/).length <= 4) return true;
			return false;
		}

		function looksLikeDescriptionText(value) {
			const text = String(value || "").trim();
			if (!text) return false;
			if (/^n\/?a$/i.test(text)) return false;
			const words = text.split(/\s+/).length;
			const hasPunctuation = /[,;:]/.test(text);
			return words >= 3 || hasPunctuation;
		}

		function repairMisplacedPdfFields(row) {
			const fixed = { ...row };
			const property = String(fixed.propertyNumber || "").trim();
			const asset = String(fixed.assetNumber || "").trim();
			const description = String(fixed.itemDescription || "").trim();
			const mrNo = String(fixed.mrNo || fixed.parIcsNumber || "").trim();

			const descriptionMissing = !description || /^n\/?a$/i.test(description);
			const assetLooksDescription = looksLikeDescriptionText(asset);

			if (descriptionMissing && assetLooksDescription) {
				fixed.itemDescription = asset;
				fixed.assetNumber = "N/A";
			}

			if (/^n\/?a$/i.test(asset) && looksLikeDescriptionText(description)) {
				fixed.assetNumber = "N/A";
			}

			if (looksLikeAssetCode(description) && looksLikeDescriptionText(asset)) {
				fixed.assetNumber = description;
				fixed.itemDescription = asset;
			}

			if ((!mrNo || /^n\/?a$/i.test(mrNo)) && looksLikeAssetCode(asset) && /\b(sn|s\/n|mr|par|ics)\b/i.test(asset)) {
				fixed.mrNo = asset;
			}

			return fixed;
		}

		function fillMissingPdfValues(row) {
			const repaired = repairMisplacedPdfFields(row);
			const propertyNumber = normalizeMissingPdfValue(repaired.propertyNumber);
			const assetNumber = normalizeMissingPdfValue(repaired.assetNumber);
			const itemDescription = normalizeMissingPdfValue(repaired.itemDescription);
			const mrNo = normalizeMissingPdfValue(repaired.mrNo || repaired.parIcsNumber);
			const parsedCost = Number(String(repaired.acquisitionCost ?? "").replace(/[^\d.-]/g, ""));
			const acquisitionCost = Number.isFinite(parsedCost) && parsedCost >= 0 ? parsedCost : 0;

			return {
				...repaired,
				propertyNumber,
				assetNumber,
				itemDescription,
				mrNo,
				acquisitionCost
			};
		}

		function normalizeIntegrityToken(value) {
			return String(value || "")
				.toLowerCase()
				.replace(/\s+/g, " ")
				.replace(/[^a-z0-9 ]/g, "")
				.trim();
		}

		function validateParsedPdfRows(parsedRows, existingRows) {
			const errors = [];
			const validRows = [];
			void existingRows;

			parsedRows.forEach((row, index) => {
				const rowNo = Number(row.__sourceRow || index + 1);
				if (!isLikelyValidTableRow(row)) {
					errors.push(`Row ${rowNo}: missing or invalid required columns.`);
					return;
				}

				const normalizedRow = fillMissingPdfValues(row);
				const propertyNumber = String(normalizedRow.propertyNumber || "").trim();
				const assetNumber = String(normalizedRow.assetNumber || "").trim();
				const itemDescription = String(normalizedRow.itemDescription || "").trim();
				const mrNo = String(normalizedRow.mrNo || "").trim();
				const cost = Number(normalizedRow.acquisitionCost || 0);

				const rowErrors = [];
				const hasMeaningfulText = [propertyNumber, assetNumber, itemDescription, mrNo]
					.some((value) => normalizeIntegrityToken(value) !== "na" && normalizeIntegrityToken(value) !== "");
				const hasMeaningfulRow = hasMeaningfulText || cost > 0;
				if (!hasMeaningfulRow) {
					errors.push(`Row ${rowNo}: no usable table values found.`);
					return;
				}
				if (!Number.isFinite(cost) || cost < 0) {
					rowErrors.push(`Row ${rowNo}: Acquisition Cost must be a valid non-negative number.`);
				}

				if (rowErrors.length) {
					errors.push(...rowErrors);
					return;
				}

				validRows.push({
					...normalizedRow,
					assetNumber,
					acquisitionCost: cost
				});
			});

			return {
				isValid: errors.length === 0,
				errors,
				validRows
			};
		}

		function parsePdfTableLines(lines, options = {}) {
			let header = null;
			const parsed = [];
			let pending = null;
			const globalEmployee = String(options.globalEmployee || "").trim();
			const hasCoreRecordData = (record) => {
				const propertyNumber = String(record?.propertyNumber || "").trim();
				const assetNumber = String(record?.assetNumber || "").trim();
				const itemDescription = String(record?.itemDescription || "").trim();
				const mrNo = String(record?.mrNo || record?.parIcsNumber || "").trim();
				const acquisitionCost = Number(record?.acquisitionCost || 0);
				return Boolean(propertyNumber || assetNumber || itemDescription || mrNo || acquisitionCost > 0);
			};

			const normalizeRow = (record, sourceLine) => ({
				propertyNumber: String(record.propertyNumber || "").trim(),
				assetNumber: String(record.assetNumber || "").trim(),
				itemDescription: String(record.itemDescription || "").trim(),
				assignedEmployee: String(record.assignedEmployee || "").trim(),
				acquisitionCost: toAmount(record.acquisitionCost || ""),
				mrNo: String(record.mrNo || record.parIcsNumber || "").trim(),
				__sourceRow: Number(sourceLine || 0)
			});

			const appendParsedRow = (row) => {
				parsed.push(row);
			};

			lines.forEach((line, lineIndex) => {
				const lineItems = Array.isArray(line.items) ? line.items : [];
				const cells = splitPdfLineToCells(lineItems);
				if (!cells.length) return;

				const maybeHeader = mapHeaderFromLineItems(lineItems) || mapHeader(cells);
				if (maybeHeader) {
					header = maybeHeader;
					return;
				}

				if (!header) return;

				const record = normalizeRow(assignLineToColumns(lineItems, header), lineIndex + 1);
				if (looksLikeHeaderRow(record)) return;
				if (/^total\b/i.test(record.propertyNumber)) return;
				if (!hasCoreRecordData(record)) return;

				const hasAnchor = Boolean(String(record.propertyNumber || "").trim() || String(record.assetNumber || "").trim());
				if (hasAnchor) {
					if (pending && hasCoreRecordData(pending)) appendParsedRow(pending);
					pending = record;
					return;
				}

				if (pending) {
					if (record.itemDescription) {
						pending.itemDescription = `${pending.itemDescription} ${record.itemDescription}`.replace(/\s+/g, " ").trim();
					}
					if (!pending.mrNo && record.mrNo) pending.mrNo = record.mrNo;
					if (!pending.assignedEmployee && record.assignedEmployee) pending.assignedEmployee = record.assignedEmployee;
					if (!pending.acquisitionCost && record.acquisitionCost) pending.acquisitionCost = record.acquisitionCost;
					return;
				}

				appendParsedRow(record);
			});

			if (pending && isLikelyValidTableRow(pending)) {
				appendParsedRow(pending);
			}

			let lastEmployee = "";
			const withEmployeeCarry = parsed.map((row) => {
				const currentEmployee = String(row.assignedEmployee || "").trim();
				if (currentEmployee) {
					lastEmployee = currentEmployee;
					return row;
				}
				if (lastEmployee) return { ...row, assignedEmployee: lastEmployee };
				return row;
			});

			if (!globalEmployee) return withEmployeeCarry;

			return withEmployeeCarry.map((row) => {
				if (String(row.assignedEmployee || "").trim()) return row;
				return { ...row, assignedEmployee: globalEmployee };
			});
		}

		async function extractPdfTableRows(file) {
			if (!window.pdfjsLib) {
				throw new Error("PDF parser unavailable.");
			}
			const buffer = await file.arrayBuffer();
			const loadingTask = window.pdfjsLib.getDocument({ data: buffer });
			const pdf = await loadingTask.promise;
			const allLines = [];
			for (let pageNum = 1; pageNum <= pdf.numPages; pageNum += 1) {
				const page = await pdf.getPage(pageNum);
				const content = await page.getTextContent();
				const lines = [];
				const tolerance = 1.6;

				content.items.forEach((item) => {
					const text = String(item.str || "").trim();
					if (!text) return;
					const x = Number(item.transform?.[4] || 0);
					const y = Number(item.transform?.[5] || 0);
					const w = Number(item.width || 0);
					let line = lines.find((entry) => Math.abs(entry.y - y) <= tolerance);
					if (!line) {
						line = { y, items: [] };
						lines.push(line);
					}
					line.items.push({ str: text, x, w });
				});

				lines.sort((a, b) => b.y - a.y);
				allLines.push(...lines);
			}

			const globalEmployee = extractGlobalEmployeeFromLines(allLines);
			return parsePdfTableLines(allLines, { globalEmployee });
		}

		function ocrWordItemsFromLine(line) {
			const words = Array.isArray(line?.words) ? line.words : [];
			if (!words.length) {
				const text = String(line?.text || "").trim();
				if (!text) return [];
				const x0 = Number(line?.bbox?.x0 || 0);
				const x1 = Number(line?.bbox?.x1 || x0 + text.length * 8);
				return [{ str: text, x: x0, w: Math.max(1, x1 - x0) }];
			}

			return words
				.map((word) => {
					const str = String(word?.text || "").trim();
					const x0 = Number(word?.bbox?.x0 || 0);
					const x1 = Number(word?.bbox?.x1 || x0 + str.length * 8);
					return { str, x: x0, w: Math.max(1, x1 - x0) };
				})
				.filter((word) => word.str);
		}

		async function extractPdfTableRowsViaOcr(file) {
			if (!window.pdfjsLib) {
				throw new Error("PDF parser unavailable.");
			}
			if (!window.Tesseract || typeof window.Tesseract.recognize !== "function") {
				throw new Error("OCR engine unavailable.");
			}

			const buffer = await file.arrayBuffer();
			const loadingTask = window.pdfjsLib.getDocument({ data: buffer });
			const pdf = await loadingTask.promise;
			const allLines = [];

			for (let pageNum = 1; pageNum <= pdf.numPages; pageNum += 1) {
				const page = await pdf.getPage(pageNum);
				const viewport = page.getViewport({ scale: 2 });
				const canvas = document.createElement("canvas");
				const context = canvas.getContext("2d", { willReadFrequently: true });
				canvas.width = Math.ceil(viewport.width);
				canvas.height = Math.ceil(viewport.height);
				if (!context) continue;

				await page.render({ canvasContext: context, viewport }).promise;

				const ocrResult = await window.Tesseract.recognize(canvas, "eng", { logger: () => {} });
				const lines = Array.isArray(ocrResult?.data?.lines) ? ocrResult.data.lines : [];
				lines
					.sort((a, b) => Number(a?.bbox?.y0 || 0) - Number(b?.bbox?.y0 || 0))
					.forEach((line) => {
						const y = Number(line?.bbox?.y0 || 0);
						const items = ocrWordItemsFromLine(line);
						if (!items.length) return;
						allLines.push({ y, items });
					});
			}

			const globalEmployee = extractGlobalEmployeeFromLines(allLines);
			return parsePdfTableLines(allLines, { globalEmployee });
		}

		async function extractPdfText(file) {
			if (!window.pdfjsLib) {
				throw new Error("PDF parser unavailable.");
			}
			const buffer = await file.arrayBuffer();
			const loadingTask = window.pdfjsLib.getDocument({ data: buffer });
			const pdf = await loadingTask.promise;
			const texts = [];
			for (let pageNum = 1; pageNum <= pdf.numPages; pageNum += 1) {
				const page = await pdf.getPage(pageNum);
				const content = await page.getTextContent();
				const lines = [];
				const tolerance = 1.6;

				content.items.forEach((item) => {
					const text = String(item.str || "").trim();
					if (!text) return;
					const x = Number(item.transform?.[4] || 0);
					const y = Number(item.transform?.[5] || 0);
					let line = lines.find((entry) => Math.abs(entry.y - y) <= tolerance);
					if (!line) {
						line = { y, items: [] };
						lines.push(line);
					}
					line.items.push({ str: text, x });
				});

				const pageText = lines
					.sort((a, b) => b.y - a.y)
					.map((line) => line.items.sort((a, b) => a.x - b.x).map((item) => item.str).join(" "))
					.join("\n");

				texts.push(pageText);
			}
			return texts.join("\n\n");
		}

		function parseLegacyPdfTextToItems(text) {
			const blocks = String(text || "").split(/\n\s*\n+/).map((block) => block.trim()).filter(Boolean);
			const parsed = [];
			const aliases = {
				"property number": "propertyNumber",
				"property no": "propertyNumber",
				"asset number": "assetNumber",
				"item description": "itemDescription",
				"description": "itemDescription",
				"acquisition cost": "acquisitionCost",
				"mr no": "mrNo",
				"mr number": "mrNo",
				"par / ics number": "mrNo",
				"par/ics number": "mrNo",
				"par number": "mrNo",
				"ics number": "mrNo",
				"assigned employee": "assignedEmployee",
				"assigned to": "assignedEmployee"
			};

			const parseKeyValueBlock = (block) => {
				const result = {};
				const lines = block.split(/\r?\n/).map((line) => line.trim()).filter(Boolean);
				lines.forEach((line) => {
					const pair = line.match(/^([^:|-]+)\s*[:|-]\s*(.+)$/);
					if (!pair) return;
					const key = String(pair[1] || "").trim().toLowerCase();
					const val = String(pair[2] || "").trim();
					const mapped = aliases[key];
					if (!mapped) return;
					result[mapped] = val;
				});
				return result;
			};

			blocks.forEach((block, index) => {
				const keyValue = parseKeyValueBlock(block);
				if (Object.keys(keyValue).length >= 3) {
					parsed.push({
						propertyNumber: String(keyValue.propertyNumber || "").trim(),
						assetNumber: String(keyValue.assetNumber || keyValue.propertyNumber || "").trim(),
						itemDescription: String(keyValue.itemDescription || "").trim(),
						acquisitionCost: toAmount(keyValue.acquisitionCost || ""),
						mrNo: String(keyValue.mrNo || "").trim(),
						assignedEmployee: String(keyValue.assignedEmployee || "").trim(),
						__sourceRow: index + 1
					});
					return;
				}

				const lines = block.split(/\r?\n/).map((line) => line.trim()).filter(Boolean);
				lines.forEach((line, lineIndex) => {
					const commaCells = line.includes(",") ? line.split(",").map((cell) => cell.trim()) : [];
					const spacedCells = line.split(/\t+|\s{2,}/).map((cell) => cell.trim()).filter(Boolean);
					const cells = commaCells.length >= spacedCells.length ? commaCells : spacedCells;
					if (cells.length < 5) return;

					const firstCellHeader = normalizePdfHeader(cells[0] || "");
					if (firstCellHeader.includes("property") || firstCellHeader.includes("item") || firstCellHeader.includes("asset")) return;

					parsed.push({
						propertyNumber: cells[0] || "",
						assetNumber: cells[1] || cells[0] || "",
						itemDescription: cells[2] || "",
						acquisitionCost: toAmount(cells[3] || ""),
						mrNo: cells[4] || "",
						assignedEmployee: cells[5] || "",
						__sourceRow: index + lineIndex + 1
					});
				});
			});

			return parsed.filter((row) => isLikelyValidTableRow(row));
		}

		function createItemFromParsed(data) {
			const stamp = new Date().toLocaleString("en-GB", {
				day: "2-digit",
				month: "short",
				year: "numeric",
				hour: "2-digit",
				minute: "2-digit"
			});

			const propertyNumber = normalizeMissingPdfValue(data.propertyNumber);
			const assetNumber = normalizeMissingPdfValue(data.assetNumber);
			const itemDescription = normalizeMissingPdfValue(data.itemDescription);

			const item = {
				itemId: nextItemId(),
				propertyNumber,
				assetNumber,
				itemDescription,
				building: String(data.building || "").trim(),
				room: String(data.room || "").trim(),
				department: String(data.department || "").trim(),
				storageArea: String(data.storageArea || "").trim(),
				itemLocation: String(data.itemLocation || "").trim(),
				assignedEmployee: String(data.assignedEmployee || "").trim(),
				acquisitionCost: Math.max(0, Number(data.acquisitionCost || 0) || 0),
				mrNo: normalizeMissingPdfValue(data.mrNo || data.parIcsNumber),
				itemStatus: normalizeStatus(data.itemStatus || "Usable"),
				allocationType: String(data.allocationType || "").trim(),
				allocatedTo: String(data.allocatedTo || "").trim(),
				allocationTo: String(data.allocatedTo || "").trim(),
				allocationDate: String(data.allocationDate || "").trim(),
				returnDate: String(data.returnDate || "").trim(),
				allocationNotes: String(data.allocationNotes || "").trim(),
				dateAdded: stamp,
				lastUpdated: stamp,
				historyLog: [{
					type: "created",
					title: "Asset record created",
					details: "Imported via PDF bulk upload",
					at: new Date().toLocaleString()
				}]
			};
			normalizeLocationFields(item);
			normalizeAllocationFields(item);
			return item;
		}

		function normalizedValue(row, key) {
			if (key === "acquisitionCost") return Number(row.acquisitionCost || 0);
			return String(row[key] || "").toLowerCase();
		}

		function syncEmployeeFilterOptions() {
			if (!refs.employeeFilter) return;
			const employees = [...new Set(
				state.rows
					.map((row) => String(row.assignedEmployee || "").trim())
					.filter(Boolean)
			)].sort((left, right) => left.localeCompare(right));

			const current = state.employeeFilter;
			refs.employeeFilter.innerHTML = [
				'<option value="">All employees</option>',
				...employees.map((name) => `<option value="${escapeHtml(name)}">${escapeHtml(name)}</option>`)
			].join("");

			if (current && employees.includes(current)) {
				refs.employeeFilter.value = current;
			} else {
				refs.employeeFilter.value = "";
				state.employeeFilter = "";
			}
		}

		function filteredRows() {
			const text = state.search.trim().toLowerCase();
			const employee = state.employeeFilter.trim().toLowerCase();

			return state.rows.filter((row) => {
				const assignedEmployee = String(row.assignedEmployee || "").trim().toLowerCase();
				const matchesEmployee = !employee || assignedEmployee === employee;
				if (!matchesEmployee) return false;

				if (!text) return true;
				return [
					row.propertyNumber,
					row.assetNumber,
					row.itemDescription,
					row.assignedEmployee,
					row.acquisitionCost,
					row.mrNo
				].some((value) => String(value || "").toLowerCase().includes(text));
			});
		}

		function sortedRows(rows) {
			return [...rows].sort((left, right) => {
				const a = normalizedValue(left, state.sortKey);
				const b = normalizedValue(right, state.sortKey);
				if (a < b) return state.sortDir === "asc" ? -1 : 1;
				if (a > b) return state.sortDir === "asc" ? 1 : -1;
				return 0;
			});
		}

		function pagedRows(rows) {
			const totalPages = Math.max(1, Math.ceil(rows.length / state.pageSize));
			if (state.page > totalPages) state.page = totalPages;
			const start = (state.page - 1) * state.pageSize;
			return {
				rows: rows.slice(start, start + state.pageSize),
				totalPages
			};
		}

		function render() {
			syncEmployeeFilterOptions();
			const filtered = filteredRows();
			const sorted = sortedRows(filtered);
			const pager = pagedRows(sorted);
			const pageRows = pager.rows;
			const totalValue = filtered.reduce((sum, row) => sum + Number(row.acquisitionCost || 0), 0);

			refs.metaInfo.textContent = `${filtered.length} results in ${GROUP_NAME}`;
			refs.pageInfo.textContent = `Page ${state.page} of ${pager.totalPages}`;
			if (refs.overviewTotalAssets) refs.overviewTotalAssets.textContent = String(state.rows.length);
			if (refs.overviewVisibleAssets) refs.overviewVisibleAssets.textContent = String(filtered.length);
			if (refs.overviewTotalValue) refs.overviewTotalValue.textContent = formatMoney(totalValue);
			refs.prevPage.disabled = state.page <= 1;
			refs.nextPage.disabled = state.page >= pager.totalPages;

			if (!pageRows.length) {
				refs.tableBody.innerHTML = "<tr><td colspan='7'>No records.</td></tr>";
				return;
			}

			refs.tableBody.innerHTML = pageRows.map((row) => {
				return `
				<tr class="data-row row-appear" data-row-id="${escapeHtml(row.itemId)}">
					<td>${escapeHtml(row.propertyNumber)}</td>
					<td>${escapeHtml(row.assetNumber)}</td>
					<td>${escapeHtml(row.itemDescription)}</td>
					<td>${escapeHtml(row.assignedEmployee || "")}</td>
					<td>${escapeHtml(formatMoney(row.acquisitionCost))}</td>
					<td>${escapeHtml(row.mrNo || "")}</td>
					<td>
						<div class="row-actions">
							<button type="button" data-action="edit" data-id="${escapeHtml(row.itemId)}">Edit</button>
							<button type="button" data-action="delete" data-id="${escapeHtml(row.itemId)}">Delete</button>
						</div>
					</td>
				</tr>`;
			}).join("");
		}

		function sortBy(key) {
			if (state.sortKey === key) {
				state.sortDir = state.sortDir === "asc" ? "desc" : "asc";
			} else {
				state.sortKey = key;
				state.sortDir = "asc";
			}
			state.page = 1;
			render();
		}

		function byId(itemId) {
			return state.rows.find((row) => row.itemId === itemId);
		}

		function runAction(action, itemId) {
			const row = byId(itemId);
			if (!row) return;
			normalizeLocationFields(row);
			normalizeAllocationFields(row);
			normalizeHistory(row);
			row.itemStatus = normalizeStatus(row.itemStatus);

			if (action === "edit") {
				const itemDescription = prompt("Item Description", row.itemDescription || "");
				if (itemDescription === null) return;
				const propertyNumber = prompt("Property No.", row.propertyNumber || "");
				if (propertyNumber === null) return;
				const assetNumber = prompt("Asset Number", row.assetNumber || "");
				if (assetNumber === null) return;
				const acquisitionCost = prompt("Acquisition Cost", String(row.acquisitionCost || "0"));
				if (acquisitionCost === null) return;
				const mrNo = prompt("MR No.", row.mrNo || "");
				if (mrNo === null) return;
				const amount = Number(String(acquisitionCost).replace(/[^\d.-]/g, ""));
				if (!Number.isFinite(amount) || amount < 0) {
					alert("Acquisition Cost must be a valid non-negative number.");
					return;
				}
				row.itemDescription = itemDescription.trim();
				row.propertyNumber = propertyNumber.trim();
				row.assetNumber = assetNumber.trim();
				row.acquisitionCost = amount;
				row.mrNo = mrNo.trim();
				addHistory(row, "update", "Asset record edited", "Core row details were updated from table actions.");
				saveRows();
				render();
				return;
			}

			if (action === "delete") {
				const confirmed = confirm(`Delete property no. ${row.propertyNumber || row.itemId}?`);
				if (!confirmed) return;
				state.rows = state.rows.filter((item) => item.itemId !== itemId);
				saveRows();
				render();
			}
		}

		document.querySelectorAll("th.sortable").forEach((th) => {
			th.addEventListener("click", () => sortBy(th.dataset.sort));
		});

		refs.searchInput.addEventListener("input", () => {
			state.search = refs.searchInput.value;
			state.page = 1;
			render();
		});

		refs.employeeFilter.addEventListener("change", () => {
			state.employeeFilter = refs.employeeFilter.value;
			state.page = 1;
			render();
		});

		refs.clearSearch.addEventListener("click", () => {
			refs.searchInput.value = "";
			if (refs.employeeFilter) refs.employeeFilter.value = "";
			state.search = "";
			state.employeeFilter = "";
			state.page = 1;
			render();
		});

		refs.prevPage.addEventListener("click", () => {
			if (state.page > 1) {
				state.page -= 1;
				render();
			}
		});

		refs.nextPage.addEventListener("click", () => {
			state.page += 1;
			render();
		});


		refs.tableBody.addEventListener("click", (event) => {
			const button = event.target.closest("button[data-action]");
			if (button) {
				const action = button.dataset.action;
				const itemId = button.dataset.id;
				if (!action || !itemId) return;
				runAction(action, itemId);
				return;
			}
		});

		refs.uploadPdfBtn.addEventListener("click", async () => {
			const file = refs.pdfUploadInput.files && refs.pdfUploadInput.files[0];
			if (!file) {
				alert("Select a PDF file first.");
				return;
			}
			try {
				let parsed = await extractPdfTableRows(file);
				let usedOcrFallback = false;
				let usedLegacyTextFallback = false;
				if (!parsed.length) {
					parsed = await extractPdfTableRowsViaOcr(file);
					usedOcrFallback = true;
				}
				if (!parsed.length) {
					const text = await extractPdfText(file);
					parsed = parseLegacyPdfTextToItems(text);
					usedLegacyTextFallback = true;
				}
				if (!parsed.length) {
					alert("No valid rows found in the PDF. Ensure the PDF includes columns: Property No., Asset Number, Item Description, Acquisition Cost, and MR No.");
					return;
				}

				const validation = validateParsedPdfRows(parsed, state.rows);
				if (!validation.validRows.length) {
					const preview = validation.errors.slice(0, 10).join("\n");
					const suffix = validation.errors.length > 10
						? `\n...and ${validation.errors.length - 10} more issue(s).`
						: "";
					alert(`Upload blocked to protect inventory integrity.\nFix these rows and re-upload:\n${preview}${suffix}`);
					return;
				}

				const created = [];
				validation.validRows.forEach((entry) => {
					const item = createItemFromParsed(entry);
					if (item) created.push(item);
				});
				if (!created.length) {
					alert("No rows imported. Missing text values are auto-filled as N/A, but rows must still look like valid table rows in the PDF.");
					return;
				}
				state.rows.unshift(...created);
				saveRows();
				render();
				refs.pdfUploadInput.value = "";
				const skipped = Math.max(0, parsed.length - created.length);
				alert(`PDF upload complete. Detected ${parsed.length} row(s), imported ${created.length} item(s)${skipped ? `, skipped ${skipped} empty/invalid layout row(s)` : ""}. Missing text values were filled with N/A.${usedOcrFallback ? " OCR fallback was used." : ""}${usedLegacyTextFallback ? " Legacy text fallback was used." : ""}`);
			} catch (error) {
				alert(`PDF upload failed: ${error && error.message ? error.message : "Unable to parse file."}`);
			}
		});

		async function initializePage() {
			await loadRows();
			state.rows = state.rows.map((row) => repairMisplacedPdfFields(row));
			state.rows = state.rows.map((row) => ({
				...row,
				mrNo: String(row.mrNo || row.parIcsNumber || "").trim()
			}));
			state.rows = state.rows.map((row) => normalizeLocationFields(row));
			state.rows = state.rows.map((row) => normalizeAllocationFields(row));
			state.rows = state.rows.map((row) => normalizeHistory(row));
			state.rows = state.rows.map((row) => ({ ...row, itemStatus: normalizeStatus(row.itemStatus) }));
			saveRows();
			render();
		}

		initializePage();
	</script>
</body>
</html>
