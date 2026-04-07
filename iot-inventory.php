<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>PNOC Inventory | IOT Inventory</title>
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
			--radius-md: 12px;
			--radius-lg: 16px;
			--emerald: #10b981;
			--rose: #f43f5e;
			--amber: #f59e0b;
			--sky: #0ea5e9;
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
			.nav-section-title { font-size:0.65rem; font-weight:700; color:var(--text-dark); padding-left:0.5rem; margin-bottom:0.5rem; text-transform:uppercase; letter-spacing:0.06em }

				.nav-link { display:flex; align-items:center; gap:0.75rem; padding:0.65rem; border-radius:10px; color:var(--text-dark); text-decoration:none; font-weight:600; font-size:0.875rem; transition:all .18s ease; margin-bottom:0.2rem }
			.nav-icon { width:36px; height:36px; border-radius:8px; background:transparent; display:flex; align-items:center; justify-content:center; font-size:1.05rem }
			.nav-label { opacity:1; transform:translateX(0); white-space:nowrap }
			.nav-link:hover { background: var(--bg-body); color: var(--text-dark); }

			.nav-link.active { background: var(--primary-glow); color: var(--text-dark); position:relative }
			.nav-link.active::before { content: ''; position:absolute; left:0; top:8px; bottom:8px; width:4px; background:var(--primary); border-radius:4px }

			.main-content { flex: 1; margin-left: var(--sidebar-width); padding: 2rem 2.75rem; }

			@media (max-width: 768px) { .sidebar { transform: translateX(-100%); } .main-content { margin-left: 0; padding: 1rem; } }
		.page-header {
			display: flex;
			justify-content: space-between;
			align-items: center;
			margin-bottom: 2rem;
		}
		.home-btn { display:inline-flex; align-items:center; gap:0.5rem; padding:0.45rem 0.75rem; border-radius:8px; background:var(--card-bg); border:1px solid var(--border-color); color:var(--text-dark); text-decoration:none; font-weight:600; box-shadow:0 6px 18px rgba(2,6,23,0.06); transition:transform 140ms ease, box-shadow 140ms ease; }
		.home-btn:hover { transform: translateY(-2px); box-shadow: 0 12px 28px rgba(2,6,23,0.08); }
		   .page-title { font-size: 1.75rem; font-weight: 700; color: var(--text-dark); margin-bottom: 0.25rem; }
		   .page-subtitle { font-size: 0.875rem; color: var(--text-muted); }
		   .content-card {
			   background: var(--card-bg);
			   border: 1px solid var(--border-color);
			   border-radius: 12px;
			   padding: 1.6rem;
			   margin-bottom: 1.4rem;
		   }
		   .card-header {
			   display: flex;
			   justify-content: space-between;
			   align-items: center;
			   margin-bottom: 1rem;
		   }
		   .card-title { font-size: 1rem; font-weight: 600; }
		   .card-subtitle { font-size: 0.75rem; color: var(--text-muted); }
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
			grid-template-columns: 1fr 250px 190px auto;
			gap: 1rem;
			margin-bottom: 1.25rem;
		}
		.upload-bar {
			display: grid;
			grid-template-columns: 1.3fr auto;
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
		thead th {
			background: #f9fafb;
			white-space: nowrap;
			font-weight: 600;
		}
		th.sortable { cursor: pointer; user-select: none; }
		th.sortable:hover { background: #f3f4f6; }
		tr:nth-child(even) td { background: #fafbfc; }
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
			padding: 0.34rem 0.52rem;
			border-radius: 7px;
			border: 1px solid var(--border-color);
			background: #fff;
			font: inherit;
			font-size: 0.78rem;
			cursor: pointer;
		}
		td input[data-field] {
			width: 100%;
			padding: 0.34rem 0.4rem;
			border: 1px solid var(--border-color);
			border-radius: 6px;
			font: inherit;
			font-size: 0.82rem;
			background: #fff;
		}
		.data-row:hover td { background: #f3f8fd !important; }
		.pagination {
			display: flex;
			justify-content: space-between;
			align-items: center;
			gap: 0.6rem;
			margin-top: 1rem;
		}
		.page-controls { display: flex; gap: 0.4rem; }
		.selection-meta { font-size: 0.82rem; color: var(--text-muted); }
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
					<a href="iot-inventory.php" class="nav-link active"><span class="nav-icon">◎</span><span class="nav-label">IOT Inventory</span></a>
				</div>
				<div class="nav-section">
					<div class="nav-section-title">Management</div>
					<a href="location-management.php" class="nav-link"><span class="nav-icon">⊕</span><span class="nav-label">Location Management</span></a>
					   <!-- Item Allocation link removed -->
					<a href="item-status-monitoring.php" class="nav-link">
						<span class="nav-icon">◉</span>
						<span>Item Status Monitoring</span>
					</a>
				</div>
				<div class="nav-section">
					<div class="nav-section-title">Analytics</div>
					<a href="report-generation.php" class="nav-link">
						<span class="nav-icon">☰</span>
						<span>Reports</span>
					</a>
				</div>
			</nav>
		</aside>

		<main class="main-content">
			<div class="page-header">
				<div>
					<h1 class="page-title">IOT Inventory</h1>
					<p class="page-subtitle" id="metaInfo">0 results</p>
				</div>
				<a href="index.html" class="home-btn">Home</a>
			</div>

			<div class="hero" aria-label="IOT inventory hero">
				<div class="hero-grid">
					<div class="hero-copy">
						<h2>Organized IOT asset workspace</h2>
						<p>Search, filter, and monitor IOT assets in a cleaner and more user-friendly view.</p>
					</div>
					<img class="hero-image" src="as.jpg" alt="IOT inventory visual" />
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
					<h2>Search and Filters</h2>
					<span>Find assets quickly</span>
				</div>
				<div class="toolbar">
					<input id="searchInput" type="text" placeholder="Search item, property, asset, employee, department" />
					<select id="employeeFilter" aria-label="Filter by employee name">
						<option value="">All employees</option>
					</select>
					<select id="rowsPerPage">
						<option value="20">20 rows</option>
						<option value="25">25 rows</option>
						<option value="50" selected>50 rows</option>
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
					<table aria-label="IOT inventory table view">
						<thead>
							<tr>
								<th class="sortable" data-sort="itemNo">Item No</th>
								<th class="sortable" data-sort="propertyNumber">Property Number</th>
								<th class="sortable" data-sort="itemDescription">Item Description</th>
								<th class="sortable" data-sort="assignedEmployee">Employee Name</th>
								<th class="sortable" data-sort="acquisitionCost">Acquisition Cost</th>
								<th class="sortable" data-sort="parIcsNumber">PAR / ICS Number</th>
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
		const GROUP_NAME = "IOT";
		const STORAGE_KEY = "pnoc_inventory_iot_v1";
		const API_SOURCE = "iot";
		const INVENTORY_API_URL = "api/inventory.php";

		const state = {
			rows: [],
			search: "",
			employeeFilter: "",
			sortKey: "itemNo",
			sortDir: "asc",
			page: 1,
			pageSize: 50,
			editingItemId: ""
		};

		const refs = {
			searchInput: document.getElementById("searchInput"),
			employeeFilter: document.getElementById("employeeFilter"),
			rowsPerPage: document.getElementById("rowsPerPage"),
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
			return `IOT-${String(max + 1).padStart(5, "0")}`;
		}

		function parseItemNo(value) {
			const match = String(value || "").match(/\d+/);
			if (!match) return null;
			const num = Number(match[0]);
			if (!Number.isFinite(num) || num <= 0) return null;
			return num;
		}

		function nextItemNo() {
			const max = state.rows.reduce((acc, row) => {
				const itemNo = parseItemNo(row.itemNo);
				return itemNo ? Math.max(acc, itemNo) : acc;
			}, 0);
			return max + 1;
		}

		function ensureItemNumbers() {
			state.rows = state.rows.map((row, index) => ({
				...row,
				itemNo: index + 1
			}));
		}

		function todayISO() {
			const date = new Date();
			const year = date.getFullYear();
			const month = String(date.getMonth() + 1).padStart(2, "0");
			const day = String(date.getDate()).padStart(2, "0");
			return `${year}-${month}-${day}`;
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

		function assetCategory(row) {
			const explicit = String(row.assetCategory || "").trim();
			if (explicit) return explicit;
			const desc = String(row.itemDescription || "").toLowerCase();
			if (desc.includes("laptop")) return "Laptop";
			if (desc.includes("desktop")) return "Desktop";
			if (desc.includes("monitor")) return "Monitor";
			if (desc.includes("printer")) return "Printer";
			if (desc.includes("router") || desc.includes("switch")) return "Network Device";
			if (desc.includes("sensor")) return "Sensor";
			if (desc.includes("ups")) return "Power Backup";
			return "General";
		}

		function acquisitionYear(row) {
			const direct = String(row.acquisitionYear || "").trim();
			if (direct) return direct;
			const dateText = String(row.dateAdded || "");
			const match = dateText.match(/(19|20)\d{2}/);
			return match ? match[0] : "";
		}

		function allocationStatus(row) {
			return String(row.allocatedTo || row.allocationTo || "").trim() ? "Allocated" : "Available";
		}

		function normalizeStatus(value) {
			const text = String(value || "").trim().toLowerCase();
			if (text === "usable") return "Usable";
			if (text === "under maintenance" || text === "maintenance") return "Under Maintenance";
			if (text === "retired") return "Retired";
			if (text === "damaged" || text === "not usable" || text === "defective" || text === "unusable") return "Retired";
			if (text === "unusable") return "Retired";
			if (text === "not usable") return "Retired";
			return "Usable";
		}

		function normalizePdfHeader(text) {
			return String(text || "").toLowerCase().replace(/[^a-z0-9]/g, "");
		}

		function headerFieldFromCell(text) {
			const normalized = normalizePdfHeader(text);
			if (!normalized) return null;
			if (normalized.includes("itemno") || normalized.includes("itemnumber")) return "itemNo";
			if (normalized.includes("propertynumber")) return "propertyNumber";
			if (normalized.includes("assetnumber")) return "assetNumber";
			if (normalized.includes("itemdescription") || normalized === "description") return "itemDescription";
			if (
				normalized.includes("employeename")
				|| normalized.includes("assignedemployee")
				|| normalized.includes("assignedto")
				|| normalized.includes("enduser")
				|| normalized.includes("accountableofficer")
				|| normalized === "employee"
			) return "assignedEmployee";
			if (normalized.includes("acquisitioncost")) return "acquisitionCost";
			if (normalized.includes("paricsnumber") || normalized === "parnumber" || normalized === "icsnumber") return "parIcsNumber";
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
				const field = headerFieldFromCell(cell.text);
				if (field && indexByField[field] === undefined) {
					indexByField[field] = index;
				}
			});
			const required = ["itemNo", "propertyNumber", "itemDescription", "acquisitionCost", "parIcsNumber"];
			const validRequired = required.every((field) => indexByField[field] !== undefined);
			if (!validRequired) {
				return null;
			}

			const columns = Object.entries(indexByField)
				.map(([field, index]) => {
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
				return {
					field: column.field,
					start,
					end
				};
			});

			return { indexByField, columns, boundaries, ranges };
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
			return normalizePdfHeader(record.propertyNumber) === "propertynumber"
				|| normalizePdfHeader(record.itemDescription) === "itemdescription"
				|| normalizePdfHeader(record.parIcsNumber).includes("paricsnumber");
		}

		function isLikelyValidTableRow(record) {
			const propertyNumber = String(record.propertyNumber || "").trim();
			const itemDescription = String(record.itemDescription || "").trim();
			if (!propertyNumber || !itemDescription) return false;
			if (looksLikeHeaderRow(record)) return false;
			if (/^total\b/i.test(propertyNumber)) return false;
			if (!/[a-z0-9]/i.test(propertyNumber)) return false;
			return true;
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
			const seenInPdf = new Set();
			const existingPropertyNumbers = new Set(
				existingRows
					.map((row) => normalizeIntegrityToken(row.propertyNumber))
					.filter(Boolean)
			);

			parsedRows.forEach((row, index) => {
				const sourceRow = Number(row.__sourceLine || index + 1);
				const itemNoRaw = String(row.itemNo || "").trim();
				const propertyNumber = String(row.propertyNumber || "").trim();
				const itemDescription = String(row.itemDescription || "").trim();
				const parIcsNumber = String(row.parIcsNumber || "").trim();
				const acquisitionCost = Number(row.acquisitionCost || 0);
				const normalizedProperty = normalizeIntegrityToken(propertyNumber);

				if (!isLikelyValidTableRow(row)) {
					errors.push(`Row ${sourceRow}: missing or invalid required columns.`);
					return;
				}

				if (!itemNoRaw || !/^\d+$/.test(itemNoRaw)) {
					errors.push(`Row ${sourceRow}: Item No must be a whole number.`);
				}

				if (!itemDescription || itemDescription.length < 2) {
					errors.push(`Row ${sourceRow}: Item Description is invalid.`);
				}

				if (!parIcsNumber) {
					errors.push(`Row ${sourceRow}: PAR / ICS Number is required.`);
				}

				if (!Number.isFinite(acquisitionCost) || acquisitionCost < 0) {
					errors.push(`Row ${sourceRow}: Acquisition Cost must be a valid non-negative number.`);
				}

				if (!normalizedProperty) {
					errors.push(`Row ${sourceRow}: Property Number is required.`);
					return;
				}

				if (seenInPdf.has(normalizedProperty)) {
					errors.push(`Row ${sourceRow}: duplicate Property Number in uploaded PDF.`);
				}
				seenInPdf.add(normalizedProperty);

				if (existingPropertyNumbers.has(normalizedProperty)) {
					errors.push(`Row ${sourceRow}: Property Number already exists in inventory.`);
				}
			});

			return {
				isValid: errors.length === 0,
				errors
			};
		}

		function parsePdfTableLines(lines, options = {}) {
			let header = null;
			const parsed = [];
			let pending = null;
			const globalEmployee = String(options.globalEmployee || "").trim();

			const normalizeRow = (record, sourceLine) => ({
				itemNo: String(record.itemNo || "").trim(),
				propertyNumber: String(record.propertyNumber || "").trim(),
				assetNumber: String(record.assetNumber || record.propertyNumber || "").trim(),
				itemDescription: String(record.itemDescription || "").trim(),
				assignedEmployee: String(record.assignedEmployee || "").trim(),
				acquisitionCost: toAmount(record.acquisitionCost || ""),
				parIcsNumber: String(record.parIcsNumber || "").trim(),
				__sourceLine: Number(sourceLine || 0)
			});

			const appendParsedRow = (row) => {
				parsed.push(row);
			};

			lines.forEach((line, lineIndex) => {
				const cells = splitPdfLineToCells(line.items);
				if (!cells.length) return;

				const maybeHeader = mapHeader(cells);
				if (maybeHeader) {
					header = maybeHeader;
					return;
				}

				if (!header) return;

				const record = normalizeRow(assignLineToColumns(line.items, header), lineIndex + 1);
				if (looksLikeHeaderRow(record)) return;
				if (/^total\b/i.test(record.propertyNumber)) return;

				const hasProperty = Boolean(record.propertyNumber);
				const hasDescription = Boolean(record.itemDescription);

				if (hasProperty && hasDescription) {
					if (pending && pending.itemDescription) {
						appendParsedRow(pending);
					}
					pending = null;
					appendParsedRow(record);
					return;
				}

				if (hasProperty && !hasDescription) {
					if (pending && pending.itemDescription) {
						appendParsedRow(pending);
					}
					pending = record;
					return;
				}

				if (!hasProperty && hasDescription) {
					if (pending) {
						pending.itemDescription = `${pending.itemDescription} ${record.itemDescription}`.replace(/\s+/g, " ").trim();
						if (!pending.parIcsNumber) pending.parIcsNumber = record.parIcsNumber;
						if (!pending.acquisitionCost) pending.acquisitionCost = record.acquisitionCost;
						appendParsedRow(pending);
						pending = null;
					}
					return;
				}
			});

			if (pending && pending.propertyNumber && pending.itemDescription) {
				appendParsedRow(pending);
			}

			let lastEmployee = "";
			const withEmployeeCarry = parsed.map((row) => {
				const currentEmployee = String(row.assignedEmployee || "").trim();
				if (currentEmployee) {
					lastEmployee = currentEmployee;
					return row;
				}
				if (lastEmployee) {
					return { ...row, assignedEmployee: lastEmployee };
				}
				return row;
			});

			if (!globalEmployee) {
				return withEmployeeCarry;
			}

			return withEmployeeCarry.map((row) => {
				if (String(row.assignedEmployee || "").trim()) {
					return row;
				}
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

		function createItemFromParsed(data) {
			const stamp = new Date().toLocaleString("en-GB", {
				day: "2-digit",
				month: "short",
				year: "numeric",
				hour: "2-digit",
				minute: "2-digit"
			});

			const propertyNumber = String(data.propertyNumber || "").trim();
			const assetNumber = String(data.assetNumber || propertyNumber || "").trim();
			const itemDescription = String(data.itemDescription || "").trim();
			const parsedItemNo = parseItemNo(data.itemNo);
			if (!propertyNumber || !itemDescription) {
				return null;
			}

			const item = {
				itemId: nextItemId(),
				itemNo: parsedItemNo || nextItemNo(),
				propertyNumber,
				assetNumber,
				itemDescription,
				building: String(data.building || "").trim(),
				room: String(data.room || "").trim(),
				department: String(data.department || "").trim(),
				storageArea: String(data.storageArea || "").trim(),
				itemLocation: String(data.itemLocation || "").trim(),
				assignedEmployee: String(data.assignedEmployee || data.employeeName || "").trim(),
				acquisitionCost: Number(data.acquisitionCost || 0) || 0,
				parIcsNumber: String(data.parIcsNumber || "").trim(),
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

		function statusBadgeClass(status) {
			const normalized = normalizeStatus(status).toLowerCase();
			if (normalized === "usable") return "status-usable";
			if (normalized === "under maintenance") return "status-maintenance";
			if (normalized === "retired") return "status-retired";
			if (normalized === "retired") return "status-retired";
			return "status-retired";
		}

		function normalizedValue(row, key) {
			if (key === "itemNo") return Number(row.itemNo || 0);
			if (key === "allocationStatus") return allocationStatus(row).toLowerCase();
			if (key === "acquisitionCost") return Number(row.acquisitionCost || 0);
			if (key === "allocatedTo") return String(row.allocatedTo || row.allocationTo || "").toLowerCase();
			return String(row[key] || "").toLowerCase();
		}

		function filteredRows() {
			const text = state.search.trim().toLowerCase();
			const employee = state.employeeFilter.trim().toLowerCase();

			return state.rows.filter((row) => {
				const rowLocation = locationSummary(row);
				const rowDepartment = String(row.department || "").trim();
				const rowStatus = normalizeStatus(row.itemStatus);
				const rowAllocatedTo = String(row.allocatedTo || row.allocationTo || "").trim();
				const rowCategory = assetCategory(row);
				const rowYear = acquisitionYear(row);
				const rowEmployee = String(row.assignedEmployee || "").trim();

				if (employee && rowEmployee.toLowerCase() !== employee) return false;

				if (!text) return true;
				return [
				row.itemNo,
				row.itemId,
				row.propertyNumber,
				row.assetNumber,
				row.itemDescription,
				rowLocation,
				row.building,
				row.room,
				row.storageArea,
				row.assignedEmployee,
				rowDepartment,
				row.parIcsNumber,
				rowStatus,
				rowAllocatedTo,
				rowCategory,
				rowYear,
				row.allocationType,
				row.allocationDate,
				row.returnDate,
				row.allocationNotes,
				allocationStatus(row),
				row.dateAdded
				].some((value) => String(value || "").toLowerCase().includes(text));
			});
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
				const isEditing = state.editingItemId === row.itemId;
				if (isEditing) {
					return `
					<tr class="data-row" data-edit-id="${escapeHtml(row.itemId)}">
						<td>${escapeHtml(row.itemNo)}</td>
						<td><input type="text" data-field="propertyNumber" value="${escapeHtml(row.propertyNumber)}" /></td>
						<td><input type="text" data-field="itemDescription" value="${escapeHtml(row.itemDescription)}" /></td>
						<td><input type="text" data-field="assignedEmployee" value="${escapeHtml(row.assignedEmployee)}" /></td>
						<td><input type="number" data-field="acquisitionCost" min="0" step="0.01" value="${escapeHtml(Number(row.acquisitionCost || 0))}" /></td>
						<td><input type="text" data-field="parIcsNumber" value="${escapeHtml(row.parIcsNumber)}" /></td>
						<td><div class="row-actions"><button type="button" data-action="save" data-id="${escapeHtml(row.itemId)}">Save</button><button type="button" data-action="cancel" data-id="${escapeHtml(row.itemId)}">Cancel</button><button type="button" data-action="delete" data-id="${escapeHtml(row.itemId)}">Delete</button></div></td>
					</tr>`;
				}
				return `
				<tr class="data-row">
					<td>${escapeHtml(row.itemNo)}</td>
					<td>${escapeHtml(row.propertyNumber)}</td>
					<td>${escapeHtml(row.itemDescription)}</td>
					<td>${escapeHtml(row.assignedEmployee)}</td>
					<td>${escapeHtml(formatMoney(row.acquisitionCost))}</td>
					<td>${escapeHtml(row.parIcsNumber)}</td>
					<td><div class="row-actions"><button type="button" data-action="edit" data-id="${escapeHtml(row.itemId)}">Edit</button><button type="button" data-action="delete" data-id="${escapeHtml(row.itemId)}">Delete</button></div></td>
				</tr>`;
			}).join("");
		}

		function startEditItem(itemId) {
			if (!itemId) return;
			const row = state.rows.find((entry) => entry.itemId === itemId);
			if (!row) return;
			state.editingItemId = itemId;
			render();
		}

		function cancelEditItem(itemId) {
			if (!itemId || state.editingItemId !== itemId) return;
			state.editingItemId = "";
			render();
		}

		function getEditFieldValue(itemId, field) {
			const row = refs.tableBody.querySelector(`tr[data-edit-id="${itemId}"]`);
			if (!row) return "";
			const input = row.querySelector(`[data-field="${field}"]`);
			return input ? String(input.value || "") : "";
		}

		function saveEditedItem(itemId) {
			if (!itemId) return;
			const row = state.rows.find((entry) => entry.itemId === itemId);
			if (!row) return;

			const propertyNumber = getEditFieldValue(itemId, "propertyNumber").trim();
			const itemDescription = getEditFieldValue(itemId, "itemDescription").trim();
			const assignedEmployee = getEditFieldValue(itemId, "assignedEmployee").trim();
			const acquisitionCost = toAmount(getEditFieldValue(itemId, "acquisitionCost"));
			const parIcsNumber = getEditFieldValue(itemId, "parIcsNumber").trim();

			if (!propertyNumber || !itemDescription || !parIcsNumber) {
				alert("Property Number, Item Description, and PAR / ICS Number are required.");
				return;
			}

			if (!Number.isFinite(acquisitionCost) || acquisitionCost < 0) {
				alert("Acquisition Cost must be a valid non-negative number.");
				return;
			}

			const normalizedProperty = normalizeIntegrityToken(propertyNumber);
			const duplicateProperty = state.rows.some((entry) => (
				entry.itemId !== itemId
				&& normalizeIntegrityToken(entry.propertyNumber) === normalizedProperty
			));
			if (duplicateProperty) {
				alert("Property Number already exists in inventory.");
				return;
			}

			row.propertyNumber = propertyNumber;
			row.assetNumber = propertyNumber;
			row.itemDescription = itemDescription;
			row.assignedEmployee = assignedEmployee;
			row.acquisitionCost = acquisitionCost;
			row.parIcsNumber = parIcsNumber;
			row.lastUpdated = new Date().toLocaleString("en-GB", {
				day: "2-digit",
				month: "short",
				year: "numeric",
				hour: "2-digit",
				minute: "2-digit"
			});
			addHistory(row, "update", "Asset record edited", "Core row details were updated from table actions.");

			state.editingItemId = "";
			saveRows();
			render();
		}

		function deleteItem(itemId) {
			if (!itemId) return;
			const row = state.rows.find((entry) => entry.itemId === itemId);
			if (!row) return;
			const confirmed = confirm(`Delete item no. ${row.itemNo}?`);
			if (!confirmed) return;
			state.rows = state.rows.filter((entry) => entry.itemId !== itemId);
			if (state.editingItemId === itemId) {
				state.editingItemId = "";
			}
			ensureItemNumbers();
			saveRows();
			render();
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

		refs.rowsPerPage.addEventListener("change", () => {
			state.pageSize = Number(refs.rowsPerPage.value || 50);
			state.page = 1;
			render();
		});

		refs.clearSearch.addEventListener("click", () => {
			refs.searchInput.value = "";
			state.search = "";
			if (refs.employeeFilter) refs.employeeFilter.value = "";
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
			if (!button) return;
			const action = button.dataset.action;
			const itemId = button.dataset.id;
			if (action === "edit") {
				startEditItem(itemId);
				return;
			}
			if (action === "save") {
				saveEditedItem(itemId);
				return;
			}
			if (action === "cancel") {
				cancelEditItem(itemId);
				return;
			}
			deleteItem(itemId);
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
				if (!parsed.length) {
					parsed = await extractPdfTableRowsViaOcr(file);
					usedOcrFallback = true;
				}
				if (!parsed.length) {
					alert("No valid table rows found. Required headers: Item No, Property Number, Item Description, Acquisition Cost, PAR / ICS Number.");
					return;
				}

				const validation = validateParsedPdfRows(parsed, state.rows);
				if (!validation.isValid) {
					const preview = validation.errors.slice(0, 10).join("\n");
					const suffix = validation.errors.length > 10
						? `\n...and ${validation.errors.length - 10} more issue(s).`
						: "";
					alert(`Upload blocked to protect inventory integrity.\nFix these rows and re-upload:\n${preview}${suffix}`);
					return;
				}

				const created = [];
				parsed.forEach((entry) => {
					const item = createItemFromParsed(entry);
					if (item) {
						created.push(item);
					}
				});

				if (!created.length) {
					alert("No rows imported. Required table columns: Property Number and Item Description.");
					return;
				}

				state.rows.unshift(...created);
				ensureItemNumbers();
				saveRows();
				render();
				refs.pdfUploadInput.value = "";
				alert(`PDF upload complete. Imported ${created.length} item(s) with strict row-by-row validation.${usedOcrFallback ? " OCR fallback was used." : ""}`);
			} catch (error) {
				alert(`PDF upload failed: ${error && error.message ? error.message : "Unable to parse file."}`);
			}
		});

		async function initializePage() {
			await loadRows();
			state.rows = state.rows.map((row) => normalizeLocationFields(row));
			state.rows = state.rows.map((row) => normalizeAllocationFields(row));
			state.rows = state.rows.map((row) => normalizeHistory(row));
			state.rows = state.rows.map((row) => ({ ...row, itemStatus: normalizeStatus(row.itemStatus) }));
			ensureItemNumbers();
			saveRows();
			render();
		}

		initializePage();
	</script>
</body>
</html>
