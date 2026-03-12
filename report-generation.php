<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>PNOC Inventory | Report Generation</title>
	<link rel="icon" type="image/png" href="qw.png" />
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
	<script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/jspdf@2.5.1/dist/jspdf.umd.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/jspdf-autotable@3.8.2/dist/jspdf.plugin.autotable.min.js"></script>
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
		.content-stack { display: grid; gap: 1rem; }
		.hero {
			background: var(--card-bg);
			border: 1px solid var(--border-color);
			border-radius: var(--radius-lg);
			overflow: hidden;
			box-shadow: var(--shadow-md);
		}
		.hero-grid {
			display: grid;
			grid-template-columns: 1.2fr 0.8fr;
			align-items: center;
			gap: 0.8rem;
		}
		.hero-copy { padding: 1rem; }
		.hero-copy h2 {
			font-size: clamp(1.05rem, 2.4vw, 1.35rem);
			margin-bottom: 0.28rem;
		}
		.hero-copy p { color: var(--text-muted); font-size: 0.9rem; }
		.hero-image {
			width: 100%;
			height: 160px;
			object-fit: cover;
			border-left: 1px solid var(--border-color);
			background: #eef4fa;
		}
		.section-card {
			background: var(--card-bg);
			border: 1px solid var(--border-color);
			border-radius: var(--radius-lg);
			padding: 1rem;
			box-shadow: var(--shadow-md);
		}
		.section-head {
			display: flex;
			justify-content: space-between;
			align-items: center;
			gap: 0.5rem;
			margin-bottom: 0.65rem;
		}
		.section-head h2 { font-size: 0.96rem; }
		.section-head span { font-size: 0.8rem; color: var(--text-muted); }
		.controls {
			display: grid;
			grid-template-columns: repeat(4, minmax(0, 1fr));
			gap: 0.5rem;
			margin-bottom: 0.7rem;
		}
		.controls select,
		.controls button,
		.export-bar button {
			width: 100%;
			padding: 0.5rem 0.55rem;
			border-radius: 8px;
			border: 1px solid var(--border-color);
			font: inherit;
			font-size: 0.86rem;
			background: #fff;
			transition: all 0.2s ease;
		}
		.controls select:focus {
			border-color: var(--primary);
			box-shadow: 0 0 0 3px var(--primary-glow);
			outline: none;
		}
		.controls button,
		.export-bar button { cursor: pointer; font-weight: 600; }
		.controls button:hover,
		.export-bar button:hover { border-color: var(--primary); color: var(--primary); }
		.controls .primary { background: var(--primary); border-color: var(--primary); color: #fff; }
		.controls .primary:hover { background: var(--primary-light); box-shadow: 0 4px 12px rgba(79,70,229,0.3); }
		.export-bar {
			display: grid;
			grid-template-columns: repeat(3, minmax(0, 1fr));
			gap: 0.5rem;
			margin-bottom: 0.65rem;
		}
		.table-wrap { overflow: auto; border: 1px solid var(--border-color); border-radius: var(--radius-md); box-shadow: var(--shadow-sm); }
		table { width: 100%; border-collapse: collapse; min-width: 820px; background: #fff; }
		th, td { border: 1px solid #d7e0ea; padding: 0.42rem 0.48rem; font-size: 0.84rem; text-align: left; }
		thead th { background: #edf2f7; }
		tr:nth-child(even) td { background: #fafcff; }
		@media (max-width: 1200px) {
			.hero-grid { grid-template-columns: 1fr; }
			.hero-image {
				height: 130px;
				border-left: 0;
				border-top: 1px solid var(--border-color);
			}
			.controls { grid-template-columns: 1fr 1fr; }
			.export-bar { grid-template-columns: 1fr; }
		}
		@media (max-width: 768px) {
			.sidebar { transform: translateX(-100%); }
			.main-content { margin-left: 0; padding: 1rem; }
			.controls { grid-template-columns: 1fr; }
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
					<a href="item-status-monitoring.php" class="nav-link">
						<span class="nav-icon">◉</span>
						<span>Item Status Monitoring</span>
					</a>
				</div>
				<div class="nav-section">
					<div class="nav-section-title">Analytics</div>
					<a href="report-generation.php" class="nav-link active">
						<span class="nav-icon">☰</span>
						<span>Reports</span>
					</a>
				</div>
			</nav>
		</aside>

		<main class="main-content">
			<div class="page-header">
				<h1 class="page-title">Report Generation</h1>
				<p class="page-subtitle">Generate inventory reports and export to Excel, CSV, or PDF. <span id="summaryText"></span></p>
			</div>

			<div class="content-stack">
				<div class="hero" aria-label="Report generation hero">
					<div class="hero-grid">
						<div class="hero-copy">
							<h2>Organized reporting workspace</h2>
							<p>Pick scope, generate a report, and export in one clean and user-friendly flow.</p>
						</div>
						<img class="hero-image" src="as.jpg" alt="Inventory reporting visual" />
					</div>
				</div>

				<div class="section-card" aria-label="Report controls">
					<div class="section-head">
						<h2>Report Controls</h2>
						<span>Generate and export</span>
					</div>
					<div class="controls">
						<select id="reportType">
							<option value="asset-summary">Asset Summary</option>
							<option value="department-assets">Department Assets</option>
							<option value="unusable-items">Unusable Items</option>
							<option value="allocated-assets">Allocated Assets</option>
						</select>
						<select id="scopeType">
							<option value="all">All Groups</option>
							<option value="BENTACO">BENTACO</option>
							<option value="IOT">IOT</option>
						</select>
						<select id="statusScope">
							<option value="all">Status: All</option>
							<option value="Usable">Usable</option>
							<option value="Under Maintenance">Under Maintenance</option>
							<option value="Damaged">Damaged</option>
							<option value="Retired">Retired</option>
						</select>
						<button id="generateBtn" class="primary" type="button">Generate Report</button>
					</div>

					<div class="export-bar">
						<button type="button" id="exportExcel">Export Excel</button>
						<button type="button" id="exportCsv">Export CSV</button>
						<button type="button" id="exportPdf">Export PDF</button>
					</div>
				</div>

				<div class="section-card" aria-label="Report preview section">
					<div class="section-head">
						<h2>Report Preview</h2>
						<span>Generated output</span>
					</div>
					<div class="table-wrap">
						<table>
							<thead><tr id="tableHeadRow"><th>Report</th></tr></thead>
							<tbody id="tableBody"><tr><td>Click Generate Report.</td></tr></tbody>
						</table>
					</div>
				</div>
			</div>
		</main>
	</div>

	<script>
		const refs = {
			reportType: document.getElementById("reportType"),
			scopeType: document.getElementById("scopeType"),
			statusScope: document.getElementById("statusScope"),
			generateBtn: document.getElementById("generateBtn"),
			exportExcel: document.getElementById("exportExcel"),
			exportCsv: document.getElementById("exportCsv"),
			exportPdf: document.getElementById("exportPdf"),
			tableHeadRow: document.getElementById("tableHeadRow"),
			tableBody: document.getElementById("tableBody"),
			summaryText: document.getElementById("summaryText")
		};

		const state = {
			headers: ["Report"],
			rows: []
		};

		const escapeHtml = (value) =>
			String(value ?? "")
				.replace(/&/g, "&amp;")
				.replace(/</g, "&lt;")
				.replace(/>/g, "&gt;")
				.replace(/\"/g, "&quot;")
				.replace(/'/g, "&#39;");

		function normalizeStatus(value) {
			const text = String(value || "").trim().toLowerCase();
			if (text === "usable") return "Usable";
			if (text === "under maintenance" || text === "maintenance") return "Under Maintenance";
			if (text === "retired") return "Retired";
			if (text === "damaged" || text === "not usable" || text === "defective" || text === "unusable") return "Damaged";
			return "Usable";
		}

		function loadRows() {
			const sources = [
				{ group: "BENTACO", key: "pnoc_inventory_bentaco_v1" },
				{ group: "IOT", key: "pnoc_inventory_iot_v1" }
			];
			const all = [];
			sources.forEach((source) => {
				try {
					const raw = localStorage.getItem(source.key);
					const rows = raw ? JSON.parse(raw) : [];
					if (Array.isArray(rows)) {
						rows.forEach((row) => all.push({ ...row, group: source.group }));
					}
				} catch {
				}
			});
			return all;
		}

		function allocationStatus(row) {
			return String(row.allocatedTo || row.allocationTo || "").trim() ? "Allocated" : "Available";
		}

		function getScopedRows() {
			const scope = refs.scopeType.value;
			const status = refs.statusScope.value;
			let rows = loadRows();
			if (scope !== "all") {
				rows = rows.filter((row) => row.group === scope);
			}
			if (status !== "all") {
				rows = rows.filter((row) => normalizeStatus(row.itemStatus) === status);
			}
			return rows;
		}

		function buildAssetSummary(rows) {
			const byGroup = ["BENTACO", "IOT"].map((group) => {
				const groupRows = rows.filter((row) => row.group === group);
				return {
					group,
					totalAssets: groupRows.length,
					usable: groupRows.filter((row) => normalizeStatus(row.itemStatus) === "Usable").length,
					unusable: groupRows.filter((row) => ["Damaged", "Retired"].includes(normalizeStatus(row.itemStatus))).length,
					allocated: groupRows.filter((row) => allocationStatus(row) === "Allocated").length
				};
			});
			state.headers = ["Group", "Total Assets", "Usable", "Unusable", "Allocated"];
			state.rows = byGroup.map((row) => [row.group, row.totalAssets, row.usable, row.unusable, row.allocated]);
		}

		function buildDepartmentAssets(rows) {
			const map = {};
			rows.forEach((row) => {
				const dept = String(row.department || "No Department").trim() || "No Department";
				map[dept] = (map[dept] || 0) + 1;
			});
			state.headers = ["Department", "Asset Count"];
			state.rows = Object.entries(map).sort((a, b) => b[1] - a[1]).map(([dept, count]) => [dept, count]);
		}

		function buildUnusableItems(rows) {
			const unusable = rows.filter((row) => ["Damaged", "Retired", "Under Maintenance"].includes(normalizeStatus(row.itemStatus)));
			state.headers = ["Group", "Item No", "Item Description", "Department", "Location", "Status"];
			state.rows = unusable.map((row) => [
				row.group,
				row.itemId || "",
				row.itemDescription || "",
				row.department || "",
				row.itemLocation || row.building || "",
				normalizeStatus(row.itemStatus)
			]);
		}

		function buildAllocatedAssets(rows) {
			const allocated = rows.filter((row) => allocationStatus(row) === "Allocated");
			state.headers = ["Group", "Item No", "Item Description", "Allocated To", "Allocation Date", "Return Date"];
			state.rows = allocated.map((row) => [
				row.group,
				row.itemId || "",
				row.itemDescription || "",
				row.allocatedTo || row.allocationTo || "",
				row.allocationDate || "",
				row.returnDate || ""
			]);
		}

		function renderTable() {
			refs.tableHeadRow.innerHTML = state.headers.map((head) => `<th>${escapeHtml(head)}</th>`).join("");
			if (!state.rows.length) {
				refs.tableBody.innerHTML = `<tr><td colspan="${state.headers.length}">No data for selected report.</td></tr>`;
				return;
			}
			refs.tableBody.innerHTML = state.rows.map((row) => `<tr>${row.map((cell) => `<td>${escapeHtml(cell)}</td>`).join("")}</tr>`).join("");
		}

		function buildReport() {
			const rows = getScopedRows();
			const reportType = refs.reportType.value;
			if (reportType === "asset-summary") buildAssetSummary(rows);
			if (reportType === "department-assets") buildDepartmentAssets(rows);
			if (reportType === "unusable-items") buildUnusableItems(rows);
			if (reportType === "allocated-assets") buildAllocatedAssets(rows);
			renderTable();
			refs.summaryText.textContent = `${state.rows.length} rows in report`;
		}

		function exportCsv() {
			const lines = [state.headers, ...state.rows].map((row) => row.map((cell) => `"${String(cell ?? "").replace(/"/g, '""')}"`).join(","));
			const blob = new Blob([lines.join("\n")], { type: "text/csv;charset=utf-8;" });
			const link = document.createElement("a");
			link.href = URL.createObjectURL(blob);
			link.download = "inventory_report.csv";
			link.click();
		}

		function exportExcel() {
			const aoa = [state.headers, ...state.rows];
			const worksheet = XLSX.utils.aoa_to_sheet(aoa);
			const workbook = XLSX.utils.book_new();
			XLSX.utils.book_append_sheet(workbook, worksheet, "Report");
			XLSX.writeFile(workbook, "inventory_report.xlsx");
		}

		function exportPdf() {
			const { jsPDF } = window.jspdf;
			const doc = new jsPDF({ orientation: "landscape" });
			doc.setFontSize(12);
			doc.text("PNOC Inventory Report", 14, 12);
			doc.autoTable({
				head: [state.headers],
				body: state.rows,
				startY: 18,
				styles: { fontSize: 8 }
			});
			doc.save("inventory_report.pdf");
		}

		refs.generateBtn.addEventListener("click", buildReport);
		refs.exportCsv.addEventListener("click", exportCsv);
		refs.exportExcel.addEventListener("click", exportExcel);
		refs.exportPdf.addEventListener("click", exportPdf);
		buildReport();
	</script>
</body>
</html>
