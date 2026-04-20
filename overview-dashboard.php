<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>PNOC Staff | Overview Dashboard</title>
	<meta name="description" content="Simple dashboard for document status and trends." />
	<link rel="icon" type="image/png" href="qw.png" />
	<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
	<style>
		:root {
			--pnoc-blue: #285f90;
			--pnoc-blue-2: #3f7aa8;
			--pnoc-dark: #172433;
			--pnoc-bg: #eaf3fb;
			--pnoc-card: #ffffff;
			--pnoc-border: #d3e0eb;
			--pnoc-muted: #536271;
			--pnoc-shadow: 0 14px 34px rgba(23, 36, 51, 0.09);
		}

		* {
			box-sizing: border-box;
			margin: 0;
			padding: 0;
		}

		body {
			font-family: "Manrope", "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
			background:
				radial-gradient(circle at 10% 0%, rgba(40, 95, 144, 0.12), transparent 36%),
				radial-gradient(circle at 96% 100%, rgba(63, 122, 168, 0.12), transparent 42%),
				var(--pnoc-bg);
			color: var(--pnoc-dark);
			line-height: 1.45;
		}

		.container {
			width: min(1420px, 96vw);
			margin: 0 auto;
		}

		header {
			background: rgba(255, 255, 255, 0.88);
			backdrop-filter: blur(8px);
			border-bottom: 1px solid var(--pnoc-border);
			position: sticky;
			top: 0;
			z-index: 20;
		}

		.nav {
			display: flex;
			align-items: center;
			justify-content: space-between;
			padding: 0.9rem 0;
			gap: 1rem;
		}

		.brand {
			display: inline-flex;
			align-items: center;
			gap: 0.65rem;
			font-weight: 700;
			text-decoration: none;
			color: var(--pnoc-dark);
		}

		.brand img {
			width: 40px;
			height: 40px;
			object-fit: contain;
		}

		.nav .actions {
			display: flex;
			gap: 0.6rem;
			align-items: center;
		}

		.nav a.link {
			text-decoration: none;
			color: var(--pnoc-blue);
			font-weight: 600;
		}

		.nav a.icon-back {
			display: inline-flex;
			align-items: center;
			justify-content: center;
			gap: 0.35rem;
			padding: 0 0.95rem;
			height: 2.3rem;
			border-radius: 999px;
			background: linear-gradient(135deg, var(--pnoc-blue), var(--pnoc-blue-2));
			color: #fff;
			font-size: 0.92rem;
			font-weight: 700;
			line-height: 1;
			box-shadow: 0 10px 20px rgba(40, 95, 144, 0.28);
		}

		main {
			padding: 1.35rem 0 2.3rem;
		}

		.panel {
			background: var(--pnoc-card);
			border: 1px solid var(--pnoc-border);
			border-radius: 18px;
			padding: 1.15rem;
			box-shadow: var(--pnoc-shadow);
		}

		.page-head {
			display: grid;
			grid-template-columns: 1.15fr minmax(280px, 0.85fr);
			gap: 1rem;
			align-items: stretch;
			margin-bottom: 1.1rem;
		}

		.page-head h1 {
			font-family: "Playfair Display", Georgia, serif;
			font-size: clamp(1.35rem, 2.8vw, 2rem);
			margin-bottom: 0.5rem;
		}

		.page-head p {
			color: var(--pnoc-muted);
			max-width: 72ch;
			margin-bottom: 0.85rem;
		}

		.page-copy {
			padding: 1.05rem;
			border-radius: 14px;
			background: linear-gradient(165deg, #ffffff 0%, #f7fbff 100%);
			border: 1px solid #d9e6f2;
			display: flex;
			flex-direction: column;
			justify-content: center;
		}

		.page-visual {
			position: relative;
			border-radius: 14px;
			overflow: hidden;
			min-height: 220px;
			border: 1px solid #cfdeeb;
			background: #dfeaf5;
		}

		.page-visual img {
			width: 100%;
			height: 100%;
			object-fit: cover;
			display: block;
		}

		.page-visual::after {
			content: "";
			position: absolute;
			inset: 0;
			background: linear-gradient(180deg, rgba(15, 29, 45, 0.02), rgba(15, 29, 45, 0.42));
		}

		.quick-action {
			display: inline-flex;
			align-items: center;
			justify-content: center;
			padding: 0.6rem 0.9rem;
			border-radius: 10px;
			background: linear-gradient(135deg, var(--pnoc-blue), var(--pnoc-blue-2));
			color: #fff;
			text-decoration: none;
			font-weight: 700;
			white-space: nowrap;
			width: fit-content;
			box-shadow: 0 10px 20px rgba(40, 95, 144, 0.24);
		}

		.filters {
			display: grid;
			grid-template-columns: 1fr 1fr 180px auto;
			gap: 0.7rem;
			margin-bottom: 1.05rem;
			padding: 0.8rem;
			border: 1px solid #d9e6f2;
			border-radius: 12px;
			background: #f8fbff;
		}

		.filter-tabs {
			display: flex;
			flex-wrap: wrap;
			gap: 0.55rem;
			margin-bottom: 0.8rem;
		}

		.filter-tab {
			border: 1px solid #cfe0ee;
			background: #ffffff;
			color: #32526f;
			font: inherit;
			font-size: 0.84rem;
			font-weight: 700;
			padding: 0.42rem 0.82rem;
			border-radius: 999px;
			cursor: pointer;
			letter-spacing: 0.02em;
			transition: all .2s ease;
		}

		.filter-tab:hover {
			border-color: #b7cde0;
			box-shadow: 0 8px 16px rgba(40, 95, 144, 0.12);
		}

		.filter-tab.active {
			border-color: transparent;
			color: #ffffff;
			background: linear-gradient(135deg, var(--pnoc-blue), var(--pnoc-blue-2));
			box-shadow: 0 10px 18px rgba(40, 95, 144, 0.24);
		}

		.filters input,
		.filters button {
			width: 100%;
			padding: 0.62rem 0.7rem;
			border-radius: 10px;
			border: 1px solid var(--pnoc-border);
			font: inherit;
		}

		.filters input::placeholder {
			color: #6b7680;
		}

		.filters button {
			background: linear-gradient(135deg, var(--pnoc-blue), var(--pnoc-blue-2));
			color: #fff;
			font-weight: 700;
			cursor: pointer;
			border-color: transparent;
		}

		.kpis {
			display: grid;
			grid-template-columns: repeat(3, minmax(0, 1fr));
			gap: 0.85rem;
			margin-bottom: 1.1rem;
		}

		.kpi {
			background: linear-gradient(165deg, #ffffff 0%, #f4f9ff 100%);
			border: 1px solid #d6e3ef;
			border-radius: 14px;
			padding: 0.95rem 1rem;
			box-shadow: 0 8px 22px rgba(23, 36, 51, 0.06);
		}

		.kpi-label {
			font-size: 0.82rem;
			color: var(--pnoc-muted);
			font-weight: 600;
			margin-bottom: 0.2rem;
		}

		.kpi-value {
			font-size: 1.65rem;
			font-weight: 700;
			line-height: 1.2;
			letter-spacing: -0.02em;
		}

		.grid {
			display: grid;
			grid-template-columns: 1.3fr 1fr;
			gap: 1rem;
			margin-bottom: 1.1rem;
		}

		.section-title {
			font-size: 1.03rem;
			font-weight: 700;
			margin-bottom: 0.75rem;
		}

		.recent-columns {
			display: grid;
			grid-template-columns: 1fr 1fr;
			gap: 0.7rem;
		}

		.recent-list {
			border: 1px solid #d7e3ef;
			border-radius: 12px;
			overflow: hidden;
			background: #fff;
		}

		.recent-list h3 {
			font-size: 0.85rem;
			background: linear-gradient(180deg, #f8fbff 0%, #ecf4fb 100%);
			padding: 0.58rem 0.7rem;
			border-bottom: 1px solid var(--pnoc-border);
			letter-spacing: 0.01em;
		}

		.recent-list ul {
			list-style: none;
		}

		.recent-list li {
			padding: 0.62rem 0.7rem;
			border-bottom: 1px solid #edf2f7;
			font-size: 0.9rem;
		}

		.recent-list li:last-child {
			border-bottom: 0;
		}

		#issuedChart {
			width: 100%;
			height: 270px;
			display: block;
			background: #fff;
			border: 1px solid #d7e3ef;
			border-radius: 12px;
		}

		.table-wrap {
			overflow: auto;
			border: 1px solid #d7e3ef;
			border-radius: 14px;
			background: #fff;
		}

		table {
			width: 100%;
			border-collapse: collapse;
			min-width: 920px;
			background: #fff;
		}

		th,
		td {
			border: 1px solid #d7e3ef;
			padding: 0.6rem 0.68rem;
			font-size: 0.9rem;
			text-align: left;
		}

		thead th {
			position: sticky;
			top: 0;
			z-index: 1;
			background: linear-gradient(180deg, #f8fbff 0%, #edf4fb 100%);
			white-space: nowrap;
			font-size: 0.78rem;
			text-transform: uppercase;
			letter-spacing: 0.04em;
			color: #2f4f70;
		}

		tr:nth-child(even) td {
			background: #fafcff;
		}

		tbody tr:hover td {
			background: #f2f8ff;
		}

		.badge {
			display: inline-block;
			padding: 0.24rem 0.52rem;
			font-size: 0.75rem;
			border-radius: 999px;
			font-weight: 700;
			border: 1px solid #c6d9ec;
			background: #eef6ff;
			color: #284a6a;
		}

		.muted {
			color: var(--pnoc-muted);
			font-size: 0.86rem;
			margin-top: 0.6rem;
		}

		@media (max-width: 980px) {
			.page-head {
				grid-template-columns: 1fr;
			}

			.page-visual {
				min-height: 190px;
			}

			.filters {
				grid-template-columns: 1fr 1fr;
			}

			.kpis,
			.grid,
			.recent-columns {
				grid-template-columns: 1fr;
			}
		}
	</style>
</head>
<body>
	<header>
		<div class="container nav">
			<a class="brand" href="index.html" aria-label="PNOC Home">
				<img src="qw.png" alt="PNOC logo" />
				<span>PNOC Staff</span>
			</a>
			<div class="actions">
				<a class="link icon-back" href="staff.php" aria-label="Back to Staff Tools" title="Back to Staff Tools">← Back</a>
			</div>

			</div>
		</header>

	<main class="container">
		<section class="panel" aria-label="Document monitoring dashboard overview">
			<div class="page-head">
				<div class="page-copy">
					<h1>Document Overview</h1>
					<p>
						Track records, status, and monthly activity.
					</p>
					<a class="quick-action" href="add-data-create.php" aria-label="Add Record">Add Record</a>
				</div>
				<div class="page-visual" aria-hidden="true">
					<img src="as.jpg" alt="Dashboard visual" onerror="this.style.display='none'" />
				</div>
			</div>

			<div class="filters" aria-label="Search and date filters">
				<input id="docNumberFilter" type="text" placeholder="Doc number" />
				<input id="holderFilter" type="text" placeholder="Copy holder" />
				<input id="monthFilter" type="month" aria-label="Filter by issuance month" />
				<button type="button" id="clearFilters">Clear</button>
			</div>
			<div class="filter-tabs" aria-label="Quick status filters">
				<button type="button" class="filter-tab active" data-tab="all">All</button>
				<button type="button" class="filter-tab" data-tab="issued">Issued</button>
				<button type="button" class="filter-tab" data-tab="retrieved">Retrieved</button>
				<button type="button" class="filter-tab" data-tab="revised">Revised</button>
			</div>
			<p class="muted" id="apiStatus">Loading records...</p>

			<div class="kpis" aria-label="Dashboard cards">
				<article class="kpi">
					<div class="kpi-label">Documents</div>
					<div class="kpi-value" id="totalDocuments">0</div>
				</article>
				<article class="kpi">
					<div class="kpi-label">Active Copies</div>
					<div class="kpi-value" id="totalCopies">0</div>
				</article>
				<article class="kpi">
					<div class="kpi-label">With Revisions</div>
					<div class="kpi-value" id="totalRevisions">0</div>
				</article>
			</div>

			<div class="grid">
				<section class="panel">
					<h2 class="section-title">Recent Records</h2>
					<div class="recent-columns">
						<div class="recent-list">
							<h3>Recently Issued</h3>
							<ul id="recentIssued"></ul>
						</div>
						<div class="recent-list">
							<h3>Recently Retrieved</h3>
							<ul id="recentRetrieved"></ul>
						</div>
					</div>
				</section>
				<section class="panel">
					<h2 class="section-title">Monthly Issued</h2>
					<canvas id="issuedChart" width="460" height="270" aria-label="Graph summary for issued documents per month"></canvas>
					<p class="muted">Based on issuance date.</p>
				</section>
			</div>

			<section>
				<h2 class="section-title">Records</h2>
				<div class="table-wrap">
					<table aria-label="Recent records table">
						<thead>
							<tr>
								<th>Doc Number</th>
								<th>Copy Holder</th>
								<th>Title</th>
								<th>Issued</th>
								<th>Retrieved</th>
								<th>Revision</th>
								<th>Status</th>
							</tr>
						</thead>
						<tbody id="recordsTableBody"></tbody>
					</table>
				</div>
			</section>
		</section>
	</main>

	<script>
		const API_URL = "./api/staff.php";
		const state = {
			allRecords: [],
			filteredRecords: [],
			activeTab: "all"
		};

		const refs = {
			docNumberFilter: document.getElementById("docNumberFilter"),
			holderFilter: document.getElementById("holderFilter"),
			monthFilter: document.getElementById("monthFilter"),
			clearFilters: document.getElementById("clearFilters"),
			filterTabs: Array.from(document.querySelectorAll(".filter-tab")),
			apiStatus: document.getElementById("apiStatus"),
			totalDocuments: document.getElementById("totalDocuments"),
			totalCopies: document.getElementById("totalCopies"),
			totalRevisions: document.getElementById("totalRevisions"),
			recentIssued: document.getElementById("recentIssued"),
			recentRetrieved: document.getElementById("recentRetrieved"),
			recordsTableBody: document.getElementById("recordsTableBody"),
			issuedChart: document.getElementById("issuedChart")
		};

		const toDate = (value) => {
			if (!value) return null;
			const date = new Date(value);
			return Number.isNaN(date.getTime()) ? null : date;
		};

		const formatDate = (value) => {
			const date = toDate(value);
			if (!date) return "-";
			return date.toLocaleDateString("en-PH", { year: "numeric", month: "short", day: "2-digit" });
		};

		const escapeHtml = (value) =>
			String(value ?? "")
				.replace(/&/g, "&amp;")
				.replace(/</g, "&lt;")
				.replace(/>/g, "&gt;")
				.replace(/\"/g, "&quot;")
				.replace(/'/g, "&#39;");

		function normalizeRecord(record) {
			const retrievalDate = record.retrievalDate || record.retrieval_date || "";
			const inferredStatus = retrievalDate ? "Retrieved" : "Issued";
			return {
				id: record.id || record.record_id || "",
				documentNumber: record.documentNumber || record.document_number || "",
				copyNumber: record.copyNumber || record.copy_number || "",
				copyHolder: record.copyHolder || record.copy_holder || "",
				documentTitle: record.documentTitle || record.document_title || "",
				issuanceDate: record.issuanceDate || record.issuance_date || "",
				retrievalDate,
				revisionNumber: record.revisionNumber || record.revision_number || "0",
				retrievedRevision: record.retrievedRevision || record.retrieved_revision || "",
				status: record.status || inferredStatus
			};
		}

		async function loadRecords() {
			try {
				const response = await fetch(API_URL, { method: "GET" });
				const rawText = await response.text();
				let payload = {};
				try {
					payload = rawText ? JSON.parse(rawText) : {};
				} catch {
					payload = {};
				}

				if (!response.ok || !payload.success) {
					const fallbackMessage = response.status === 404
						? "Staff API endpoint not found."
						: "Unable to connect to Staff API.";
					throw new Error(payload.error || payload.message || fallbackMessage);
				}

				const rows = Array.isArray(payload.data) ? payload.data : [];
				state.allRecords = rows.map(normalizeRecord);
				refs.apiStatus.textContent = `Loaded ${state.allRecords.length} record(s).`;
			} catch (error) {
				state.allRecords = [];
				refs.apiStatus.textContent = `Can’t load records: ${error.message || "API unavailable."}`;
			}
			applyFilters();
		}

		function applyFilters() {
			const docQuery = refs.docNumberFilter.value.trim().toLowerCase();
			const holderQuery = refs.holderFilter.value.trim().toLowerCase();
			const monthValue = refs.monthFilter.value;

			state.filteredRecords = state.allRecords.filter((record) => {
				const matchDoc = !docQuery || (record.documentNumber || "").toLowerCase().includes(docQuery);
				const matchHolder = !holderQuery || (record.copyHolder || "").toLowerCase().includes(holderQuery);
				const issuanceDate = toDate(record.issuanceDate);
				const issuanceMonth = issuanceDate ? `${issuanceDate.getFullYear()}-${String(issuanceDate.getMonth() + 1).padStart(2, "0")}` : "";
				const matchMonth = !monthValue || issuanceMonth === monthValue;

				const normalizedStatus = (record.status || "issued").trim().toLowerCase();
				const isRetrieved = normalizedStatus === "retrieved" || Boolean(toDate(record.retrievalDate));
				const hasRevision = Number(record.revisionNumber) > 0;

				let matchTab = true;
				if (state.activeTab === "issued") {
					matchTab = !isRetrieved;
				} else if (state.activeTab === "retrieved") {
					matchTab = isRetrieved;
				} else if (state.activeTab === "revised") {
					matchTab = hasRevision;
				}

				return matchDoc && matchHolder && matchMonth && matchTab;
			});

			renderDashboard();
		}

		function setActiveTab(tabName) {
			state.activeTab = tabName;
			for (const tab of refs.filterTabs) {
				tab.classList.toggle("active", tab.dataset.tab === tabName);
			}
			applyFilters();
		}

		function renderDashboard() {
			renderKpis();
			renderRecentLists();
			renderTable();
			renderChart();
		}

		function renderKpis() {
			const totalDocuments = new Set(state.filteredRecords.map((record) => record.documentNumber).filter(Boolean)).size;
			const totalCopies = state.filteredRecords.filter((record) => (record.status || "").toLowerCase() !== "retrieved").length;
			const totalRevisions = state.filteredRecords.filter((record) => Number(record.revisionNumber) > 0).length;

			refs.totalDocuments.textContent = String(totalDocuments);
			refs.totalCopies.textContent = String(totalCopies);
			refs.totalRevisions.textContent = String(totalRevisions);
		}

		function renderRecentLists() {
			const sortedByIssue = [...state.filteredRecords].sort((a, b) => (toDate(b.issuanceDate)?.getTime() || 0) - (toDate(a.issuanceDate)?.getTime() || 0));
			const sortedByRetrieve = [...state.filteredRecords]
				.filter((record) => toDate(record.retrievalDate))
				.sort((a, b) => (toDate(b.retrievalDate)?.getTime() || 0) - (toDate(a.retrievalDate)?.getTime() || 0));

			const recentIssued = sortedByIssue.slice(0, 5);
			const recentRetrieved = sortedByRetrieve.slice(0, 5);

			refs.recentIssued.innerHTML = recentIssued.length
				? recentIssued
						.map((record) => `<li><strong>${escapeHtml(record.documentNumber)}</strong> - ${escapeHtml(record.copyHolder)} <span class="muted">(${formatDate(record.issuanceDate)})</span></li>`)
						.join("")
				: "<li>No issued records.</li>";

			refs.recentRetrieved.innerHTML = recentRetrieved.length
				? recentRetrieved
						.map((record) => `<li><strong>${escapeHtml(record.documentNumber)}</strong> - ${escapeHtml(record.copyHolder)} <span class="muted">(${formatDate(record.retrievalDate)})</span></li>`)
						.join("")
				: "<li>No retrieved records.</li>";
		}

		function renderTable() {
			const sorted = [...state.filteredRecords].sort((a, b) => {
				const aTime = toDate(a.issuanceDate)?.getTime() || 0;
				const bTime = toDate(b.issuanceDate)?.getTime() || 0;
				return bTime - aTime;
			});

			const rows = sorted.slice(0, 12).map((record) => {
				const status = (record.status || "Issued").trim() || "Issued";
				return `
					<tr>
						<td>${escapeHtml(record.documentNumber)}</td>
						<td>${escapeHtml(record.copyHolder)}</td>
						<td>${escapeHtml(record.documentTitle)}</td>
						<td>${formatDate(record.issuanceDate)}</td>
						<td>${formatDate(record.retrievalDate)}</td>
						<td>${escapeHtml(record.revisionNumber)}</td>
						<td><span class="badge">${escapeHtml(status)}</span></td>
					</tr>
				`;
			});

			refs.recordsTableBody.innerHTML = rows.join("") || "<tr><td colspan='7'>No records match filters.</td></tr>";
		}

		function renderChart() {
			const canvas = refs.issuedChart;
			const context = canvas.getContext("2d");
			if (!context) return;

			const monthMap = new Map();
			for (const record of state.filteredRecords) {
				const issueDate = toDate(record.issuanceDate);
				if (!issueDate) continue;
				const key = `${issueDate.getFullYear()}-${String(issueDate.getMonth() + 1).padStart(2, "0")}`;
				monthMap.set(key, (monthMap.get(key) || 0) + 1);
			}

			const entries = [...monthMap.entries()].sort(([left], [right]) => left.localeCompare(right)).slice(-8);
			const labels = entries.map(([key]) => {
				const [year, month] = key.split("-");
				return new Date(Number(year), Number(month) - 1, 1).toLocaleDateString("en-PH", { month: "short", year: "2-digit" });
			});
			const values = entries.map((entry) => entry[1]);

			context.clearRect(0, 0, canvas.width, canvas.height);
			context.fillStyle = "#ffffff";
			context.fillRect(0, 0, canvas.width, canvas.height);

			if (!values.length) {
				context.fillStyle = "#5f6b76";
				context.font = "14px Segoe UI";
				context.fillText("No monthly data.", 18, 28);
				return;
			}

			const chartLeft = 40;
			const chartTop = 24;
			const chartWidth = canvas.width - 56;
			const chartHeight = canvas.height - 62;
			const maxValue = Math.max(...values, 1);
			const step = chartWidth / values.length;
			const barWidth = Math.max(18, step * 0.55);

			context.strokeStyle = "#c8d6e5";
			context.lineWidth = 1;
			context.beginPath();
			context.moveTo(chartLeft, chartTop + chartHeight);
			context.lineTo(chartLeft + chartWidth, chartTop + chartHeight);
			context.stroke();

			for (let i = 0; i < values.length; i += 1) {
				const value = values[i];
				const height = (value / maxValue) * (chartHeight - 8);
				const x = chartLeft + i * step + (step - barWidth) / 2;
				const y = chartTop + chartHeight - height;

				context.fillStyle = "#2f5d8a";
				context.fillRect(x, y, barWidth, height);

				context.fillStyle = "#1b2430";
				context.font = "11px Segoe UI";
				context.textAlign = "center";
				context.fillText(String(value), x + barWidth / 2, y - 6);
				context.fillStyle = "#4e5b66";
				context.fillText(labels[i], x + barWidth / 2, chartTop + chartHeight + 14);
			}

			context.textAlign = "start";
		}

		refs.docNumberFilter.addEventListener("input", applyFilters);
		refs.holderFilter.addEventListener("input", applyFilters);
		refs.monthFilter.addEventListener("change", applyFilters);
		for (const tab of refs.filterTabs) {
			tab.addEventListener("click", () => setActiveTab(tab.dataset.tab || "all"));
		}
		refs.clearFilters.addEventListener("click", () => {
			refs.docNumberFilter.value = "";
			refs.holderFilter.value = "";
			refs.monthFilter.value = "";
			setActiveTab("all");
		});

		loadRecords();
	</script>
</body>
</html>
