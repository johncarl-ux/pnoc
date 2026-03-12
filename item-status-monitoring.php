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
		.sidebar-brand-text { font-weight: 700; font-size: 1rem; }
		.sidebar-brand-sub { font-size: 0.7rem; color: var(--text-muted); font-weight: 400; }
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
			padding: 0.5rem 0.55rem;
			border-radius: 8px;
			border: 1px solid var(--border-color);
			font: inherit;
			font-size: 0.86rem;
			background: #fff;
			transition: all 0.2s ease;
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
		table { width: 100%; border-collapse: collapse; min-width: 1480px; background: #fff; }
		th, td {
			border: 1px solid #d7e0ea;
			padding: 0.4rem 0.46rem;
			font-size: 0.83rem;
			text-align: left;
			vertical-align: middle;
		}
		thead th { background: #edf2f7; white-space: nowrap; }
		th.sortable { cursor: pointer; user-select: none; }
		th.sortable:hover { background: #e4edf7; }
		tr:nth-child(even) td { background: #fafcff; }
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
		.badge.status-usable { background: rgba(16,185,129,0.1); border-color: rgba(16,185,129,0.3); color: var(--emerald); }
		.badge.status-maintenance { background: rgba(245,158,11,0.1); border-color: rgba(245,158,11,0.3); color: var(--amber); }
		.badge.status-damaged { background: rgba(244,63,94,0.1); border-color: rgba(244,63,94,0.3); color: var(--rose); }
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
	</style>
</head>
<body>
	<div class="app-layout">
		<aside class="sidebar">
			<div class="sidebar-header">
				<a href="index.html" class="sidebar-brand">
					<img src="qw.png" alt="PNOC Logo" />
					<div>
						<div class="sidebar-brand-text">PNOC Inventory</div>
						<div class="sidebar-brand-sub">Management System</div>
					</div>
				</a>
			</div>
			<nav class="sidebar-nav">
				<div class="nav-section">
					<div class="nav-section-title">Main Menu</div>
					<a href="inventory-dashboard.php" class="nav-link">
						<span class="nav-icon">⌂</span>
						<span>Dashboard</span>
					</a>
					<a href="bentaco-inventory.php" class="nav-link">
						<span class="nav-icon">☐</span>
						<span>BENTACO Inventory</span>
					</a>
					<a href="iot-inventory.php" class="nav-link">
						<span class="nav-icon">◎</span>
						<span>IOT Inventory</span>
					</a>
				</div>
				<div class="nav-section">
					<div class="nav-section-title">Management</div>
					<a href="location-management.php" class="nav-link">
						<span class="nav-icon">⊕</span>
						<span>Location Management</span>
					</a>
					   <!-- Item Allocation link removed -->
					<a href="item-status-monitoring.php" class="nav-link active">
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
				<h1 class="page-title">Item Status Monitoring</h1>
				<p class="page-subtitle" id="metaInfo">0 rows</p>
			</div>

			<div class="stats-row" aria-label="Status overview cards">
				<div class="stat-card">
					<div class="stat-label">Active Assets</div>
					<div class="stat-value" id="statActiveAssets">0</div>
					<div class="stat-note">Monitored items</div>
				</div>
				<div class="stat-card">
					<div class="stat-label">Winning Status</div>
					<div class="stat-value" id="statWinningStatus">-</div>
					<div class="stat-note">Most common condition</div>
				</div>
				<div class="stat-card">
					<div class="stat-label">Level of Usability</div>
					<div class="usability-wrap">
						<div class="usability-gauge" id="usabilityGauge"></div>
						<div class="usability-info">
							<div class="usability-percent" id="usabilityPercent">0%</div>
							<div class="usability-level" id="usabilityLevel">No data</div>
						</div>
					</div>
				</div>
				<div class="stat-card">
					<div class="stat-label">Allocated Items</div>
					<div class="stat-value" id="statAllocatedItems">0</div>
					<div class="stat-note">Assigned or in-use</div>
				</div>
			</div>

			<div class="section-card">
				<div class="toolbar">
					<input id="searchInput" type="text" placeholder="Search item, status, department, notes" />
					<select id="groupFilter">
						<option value="all">Group: All</option>
						<option value="BENTACO">BENTACO</option>
						<option value="IOT">IOT</option>
					</select>
					<select id="statusFilter">
						<option value="all">Status: All</option>
						<option value="Usable">Usable</option>
						<option value="Under Maintenance">Under Maintenance</option>
						<option value="Damaged">Damaged</option>
						<option value="Retired">Retired</option>
					</select>
					<select id="allocationFilter">
						<option value="all">Allocation: All</option>
						<option value="Allocated">Allocated</option>
						<option value="Overdue">Overdue Return</option>
						<option value="Available">Unallocated</option>
					</select>
					<button id="clearFilters" type="button">Clear</button>
				</div>

				<div class="table-wrap">
					<table aria-label="Status monitoring table">
						<thead>
							<tr>
								<th class="sortable" data-sort="group">Group</th>
								<th class="sortable" data-sort="itemId">Item No</th>
								<th class="sortable" data-sort="itemDescription">Item Description</th>
								<th class="sortable" data-sort="department">Department</th>
								<th class="sortable" data-sort="itemStatus">Item Status</th>
							</tr>
						</thead>
						<tbody id="tableBody">
							<tr><td colspan="5">No records.</td></tr>
						</tbody>
					</table>
				</div>

				<div class="pagination">
					<div class="meta" id="pageInfo">Page 1 of 1</div>
					<div class="page-controls">
						<button id="prevPage" type="button">Previous</button>
						<button id="nextPage" type="button">Next</button>
					</div>
				</div>
			</div>
		</main>
	</div>

	<script>
		const STORAGE_KEYS = {
			BENTACO: "pnoc_inventory_bentaco_v1",
			IOT: "pnoc_inventory_iot_v1"
		};

		const state = {
			rows: [],
			search: "",
			sortKey: "itemId",
			sortDir: "asc",
			page: 1,
			pageSize: 15
		};

		const refs = {
			searchInput: document.getElementById("searchInput"),
			groupFilter: document.getElementById("groupFilter"),
			statusFilter: document.getElementById("statusFilter"),
			allocationFilter: document.getElementById("allocationFilter"),
			clearFilters: document.getElementById("clearFilters"),
			tableBody: document.getElementById("tableBody"),
			metaInfo: document.getElementById("metaInfo"),
			pageInfo: document.getElementById("pageInfo"),
			prevPage: document.getElementById("prevPage"),
			nextPage: document.getElementById("nextPage"),
			statActiveAssets: document.getElementById("statActiveAssets"),
			statWinningStatus: document.getElementById("statWinningStatus"),
			statAllocatedItems: document.getElementById("statAllocatedItems"),
			usabilityGauge: document.getElementById("usabilityGauge"),
			usabilityPercent: document.getElementById("usabilityPercent"),
			usabilityLevel: document.getElementById("usabilityLevel")
		};

		const escapeHtml = (value) =>
			String(value ?? "")
				.replace(/&/g, "&amp;")
				.replace(/</g, "&lt;")
				.replace(/>/g, "&gt;")
				.replace(/\"/g, "&quot;")
				.replace(/'/g, "&#39;");

		function todayISO() {
			const date = new Date();
			const year = date.getFullYear();
			const month = String(date.getMonth() + 1).padStart(2, "0");
			const day = String(date.getDate()).padStart(2, "0");
			return `${year}-${month}-${day}`;
		}

		function normalizeStatus(value) {
			const text = String(value || "").trim().toLowerCase();
			if (text === "usable") return "Usable";
			if (text === "under maintenance" || text === "maintenance") return "Under Maintenance";
			if (text === "retired") return "Retired";
			if (text === "damaged" || text === "not usable" || text === "defective" || text === "unusable") return "Damaged";
			return "Usable";
		}

		function statusBadgeClass(status) {
			const normalized = normalizeStatus(status).toLowerCase();
			if (normalized === "usable") return "status-usable";
			if (normalized === "under maintenance") return "status-maintenance";
			if (normalized === "damaged") return "status-damaged";
			if (normalized === "retired") return "status-retired";
			return "status-retired";
		}

		function normalizeRowFields(row) {
			return {
				...row,
				itemStatus: normalizeStatus(row.itemStatus),
				allocationDate: String(row.allocationDate || "").trim(),
				returnDate: String(row.returnDate || "").trim(),
				allocationNotes: String(row.allocationNotes || "").trim(),
				historyLog: Array.isArray(row.historyLog) ? row.historyLog : []
			};
		}

		function saveGroupRows(group, rows) {
			localStorage.setItem(STORAGE_KEYS[group], JSON.stringify(rows));
		}

		function loadRows() {
			const merged = [];
			Object.entries(STORAGE_KEYS).forEach(([group, key]) => {
				try {
					const raw = localStorage.getItem(key);
					const parsed = raw ? JSON.parse(raw) : [];
					const list = Array.isArray(parsed) ? parsed.map((row) => normalizeRowFields(row)) : [];
					saveGroupRows(group, list);
					list.forEach((row) => merged.push({ ...row, group }));
				} catch {
				}
			});
			state.rows = merged;
		}

		function isOverdue(row) {
			if (!row.returnDate || !row.allocatedTo) return false;
			return row.returnDate < todayISO();
		}

		function allocationState(row) {
			if (isOverdue(row)) return "Overdue";
			return row.allocatedTo ? "Allocated" : "Available";
		}

		function allocationBadgeClass(stateLabel) {
			if (stateLabel === "Overdue") return "overdue";
			if (stateLabel === "Allocated") return "status-usable";
			return "status-retired";
		}

		function normalizedValue(row, key) {
			if (key === "allocationStatus") return allocationState(row).toLowerCase();
			if (key === "itemStatus") return normalizeStatus(row.itemStatus).toLowerCase();
			return String(row[key] || "").toLowerCase();
		}

		function getFilteredRows() {
			const query = state.search.trim().toLowerCase();
			const groupFilter = refs.groupFilter.value;
			const statusFilter = refs.statusFilter.value;
			const allocationFilter = refs.allocationFilter.value;

			return state.rows.filter((row) => {
				if (groupFilter !== "all" && row.group !== groupFilter) return false;
				if (statusFilter !== "all" && normalizeStatus(row.itemStatus) !== statusFilter) return false;
				if (allocationFilter !== "all" && allocationState(row) !== allocationFilter) return false;
				if (!query) return true;
				return [
					row.group,
					row.itemId,
					row.itemDescription,
					row.department,
					row.itemStatus,
					row.allocatedTo,
					row.allocationDate,
					row.returnDate,
					row.allocationNotes,
					allocationState(row)
				].some((value) => String(value || "").toLowerCase().includes(query));
			});
		}

		function getSortedRows(rows) {
			return [...rows].sort((a, b) => {
				const left = normalizedValue(a, state.sortKey);
				const right = normalizedValue(b, state.sortKey);
				if (left < right) return state.sortDir === "asc" ? -1 : 1;
				if (left > right) return state.sortDir === "asc" ? 1 : -1;
				return 0;
			});
		}

		function getPagedRows(rows) {
			const totalPages = Math.max(1, Math.ceil(rows.length / state.pageSize));
			if (state.page > totalPages) state.page = totalPages;
			const start = (state.page - 1) * state.pageSize;
			return { rows: rows.slice(start, start + state.pageSize), totalPages };
		}

		function getGroupList(group) {
			try {
				const raw = localStorage.getItem(STORAGE_KEYS[group]);
				const parsed = raw ? JSON.parse(raw) : [];
				return Array.isArray(parsed) ? parsed.map((row) => normalizeRowFields(row)) : [];
			} catch {
				return [];
			}
		}

		function persistOne(row) {
			const list = getGroupList(row.group);
			const index = list.findIndex((item) => item.itemId === row.itemId);
			if (index >= 0) {
				list[index] = {
					...list[index],
					itemStatus: row.itemStatus,
					allocationDate: row.allocationDate,
					returnDate: row.returnDate,
					allocatedTo: row.allocatedTo,
					allocationTo: row.allocatedTo,
					allocationNotes: row.allocationNotes,
					historyLog: Array.isArray(row.historyLog) ? row.historyLog : list[index].historyLog
				};
				saveGroupRows(row.group, list);
			}
		}

		function appendHistory(row, type, title, details) {
			if (!Array.isArray(row.historyLog)) row.historyLog = [];
			row.historyLog.unshift({ type, title, details, at: new Date().toLocaleString() });
		}

		function runAction(action, itemId, group) {
			const row = state.rows.find((entry) => entry.itemId === itemId && entry.group === group);
			if (!row) return;

			if (action === "view") {
				const historyLines = (row.historyLog || []).slice(0, 8).map((entry) => `${entry.at} - ${entry.title}`).join("\n");
				alert(`${row.group} ${row.itemId}\n${row.itemDescription || ""}\nStatus: ${row.itemStatus}\nAllocated To: ${row.allocatedTo || ""}\nAllocation State: ${allocationState(row)}\n\nHistory:\n${historyLines || "No history."}`);
				return;
			}

			if (action === "status") {
				const input = prompt("Set Status (Usable, Under Maintenance, Damaged, Retired)", row.itemStatus || "Usable");
				if (input === null) return;
				const normalized = normalizeStatus(input);
				row.itemStatus = normalized;
				appendHistory(row, "status", "Status changed", `Status set to ${normalized}.`);
				persistOne(row);
				render();
				return;
			}

			if (action === "maintenance") {
				const note = prompt("Maintenance Log", "");
				if (note === null) return;
				if (!note.trim()) return;
				row.itemStatus = "Under Maintenance";
				appendHistory(row, "maintenance", "Maintenance log added", note.trim());
				persistOne(row);
				render();
			}
		}

		function render() {
			loadRows();
			const filtered = getFilteredRows();
			const sorted = getSortedRows(filtered);
			const pager = getPagedRows(sorted);
			const rows = pager.rows;

			const statusCounts = filtered.reduce((acc, row) => {
				const status = normalizeStatus(row.itemStatus);
				acc[status] = (acc[status] || 0) + 1;
				return acc;
			}, {});
			const winningStatus = Object.entries(statusCounts).sort((a, b) => b[1] - a[1])[0]?.[0] || "No data";
			const allocatedCount = filtered.filter((row) => allocationState(row) !== "Available").length;
			const usableCount = filtered.filter((row) => normalizeStatus(row.itemStatus) === "Usable").length;
			const usabilityPct = filtered.length ? Math.round((usableCount / filtered.length) * 100) : 0;
			const usabilityLabel = usabilityPct >= 85
				? "Excellent"
				: usabilityPct >= 70
					? "Good"
					: usabilityPct >= 50
						? "Fair"
						: "Needs attention";

			refs.metaInfo.textContent = `${filtered.length} rows`;
			refs.pageInfo.textContent = `Page ${state.page} of ${pager.totalPages}`;
			if (refs.statActiveAssets) refs.statActiveAssets.textContent = String(filtered.length);
			if (refs.statWinningStatus) refs.statWinningStatus.textContent = winningStatus;
			if (refs.statAllocatedItems) refs.statAllocatedItems.textContent = String(allocatedCount);
			if (refs.usabilityPercent) refs.usabilityPercent.textContent = `${usabilityPct}%`;
			if (refs.usabilityLevel) refs.usabilityLevel.textContent = usabilityLabel;
			if (refs.usabilityGauge) refs.usabilityGauge.style.setProperty("--gauge-value", `${usabilityPct}%`);
			refs.prevPage.disabled = state.page <= 1;
			refs.nextPage.disabled = state.page >= pager.totalPages;

			if (!rows.length) {
				refs.tableBody.innerHTML = "<tr><td colspan='6'>No records.</td></tr>";
				return;
			}

			// Collect all unique statuses from all rows and always include 'Retired' and 'Damaged'
			const mustHaveStatuses = ["Retired", "Damaged"];
			const allStatuses = Array.from(new Set([
				...rows.map(r => normalizeStatus(r.itemStatus)),
				...mustHaveStatuses
			])).filter(Boolean);
			refs.tableBody.innerHTML = rows.map((row, idx) => {
				const status = normalizeStatus(row.itemStatus);
				// Status dropdown
				let statusHTML = `<select class="status-select" onchange="updateStatus(${idx}, this.value)">
					${allStatuses.map(opt => `<option value="${opt}"${opt === status ? ' selected' : ''}>${opt}</option>`).join('')}
				</select>`;
				return `
				<tr>
					<td>${escapeHtml(row.group)}</td>
					<td>${escapeHtml(row.itemId || "")}</td>
					<td>${escapeHtml(row.itemDescription || "")}</td>
					<td>${escapeHtml(row.location || "")}</td>
					<td>${statusHTML}</td>
				</tr>
				`;
			}).join("");
				// Update status handler for dropdown
				function updateStatus(idx, value) {
					// Find the correct row in the current page
					const filtered = getFilteredRows();
					const sorted = getSortedRows(filtered);
					const pager = getPagedRows(sorted);
					const rows = pager.rows;
					const row = rows[idx];
					if (!row) return;
					row.itemStatus = value;
					persistOne(row);
					render();
				}
		}

		function setSort(key) {
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
			th.addEventListener("click", () => setSort(th.dataset.sort));
		});

		refs.searchInput.addEventListener("input", () => {
			state.search = refs.searchInput.value;
			state.page = 1;
			render();
		});

		[refs.groupFilter, refs.statusFilter, refs.allocationFilter].forEach((el) => {
			el.addEventListener("change", () => {
				state.page = 1;
				render();
			});
		});

		refs.clearFilters.addEventListener("click", () => {
			refs.searchInput.value = "";
			state.search = "";
			refs.groupFilter.value = "all";
			refs.statusFilter.value = "all";
			refs.allocationFilter.value = "all";
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

		render();
	</script>
</body>
</html>
