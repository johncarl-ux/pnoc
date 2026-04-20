<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>PNOC Staff | Tools</title>
	<meta name="description" content="Simple tools for sorting, filtering, and bulk actions." />
	<link rel="icon" type="image/png" href="qw.png" />
	<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
	<style>
		:root {
			--pnoc-blue: #2b638f;
			--pnoc-blue-2: #4a7ea8;
			--pnoc-dark: #1b2430;
			--pnoc-bg: #ecf3fa;
			--pnoc-card: #ffffff;
			--pnoc-border: #d3e0eb;
			--pnoc-muted: #5f6b76;
			--pnoc-shadow: 0 14px 34px rgba(27, 36, 48, 0.08);
		}

		* {
			box-sizing: border-box;
			margin: 0;
			padding: 0;
		}

		body {
			font-family: "Manrope", "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
			background:
				radial-gradient(circle at 8% 0%, rgba(43, 99, 143, 0.10), transparent 34%),
				radial-gradient(circle at 96% 100%, rgba(76, 128, 169, 0.12), transparent 40%),
				var(--pnoc-bg);
			color: var(--pnoc-dark);
			line-height: 1.45;
		}

		.container {
			width: min(1500px, 98vw);
			margin: 0 auto;
		}

		header {
			background: rgba(255, 255, 255, 0.90);
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
			padding: 0.8rem 0;
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
			gap: 0.7rem;
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
			padding: 0 0.9rem;
			height: 2.25rem;
			border-radius: 999px;
			background: linear-gradient(135deg, var(--pnoc-blue), var(--pnoc-blue-2));
			color: #fff;
			font-size: 0.92rem;
			font-weight: 700;
			line-height: 1;
			box-shadow: 0 10px 20px rgba(43, 99, 143, 0.26);
		}

		main {
			padding: 1.35rem 0 2.4rem;
		}

		.panel {
			background: var(--pnoc-card);
			border: 1px solid var(--pnoc-border);
			border-radius: 16px;
			padding: 1.1rem;
			box-shadow: var(--pnoc-shadow);
		}

		.head {
			display: grid;
			grid-template-columns: 1.15fr minmax(260px, 0.85fr);
			gap: 1rem;
			align-items: stretch;
			margin-bottom: 0.85rem;
		}

		.hero-copy {
			padding: 1rem;
			border-radius: 14px;
			border: 1px solid #d8e4ef;
			background: linear-gradient(165deg, #ffffff 0%, #f7fbff 100%);
			display: flex;
			flex-direction: column;
			justify-content: center;
		}

		.head h1 {
			font-family: "Playfair Display", Georgia, serif;
			font-size: clamp(1.2rem, 2.6vw, 1.8rem);
			margin-bottom: 0.3rem;
			font-weight: 700;
			letter-spacing: 0.01em;
		}

		.head p {
			color: var(--pnoc-muted);
			max-width: 68ch;
			font-size: 0.95rem;
			margin-bottom: 0.55rem;
		}

		.hero-note {
			display: inline-flex;
			align-items: center;
			gap: 0.42rem;
			width: fit-content;
			padding: 0.33rem 0.62rem;
			font-size: 0.76rem;
			font-weight: 700;
			border-radius: 999px;
			color: #2f5575;
			border: 1px solid #cbddea;
			background: #eef6ff;
		}

		.hero-media {
			position: relative;
			border-radius: 14px;
			overflow: hidden;
			border: 1px solid #cfdeeb;
			min-height: 210px;
			box-shadow: 0 14px 28px rgba(27, 36, 48, 0.10);
		}

		.hero-media img {
			width: 100%;
			height: 100%;
			object-fit: cover;
			display: block;
		}

		.hero-media::after {
			content: "";
			position: absolute;
			inset: 0;
			background: linear-gradient(180deg, rgba(20, 34, 50, 0.08), rgba(20, 34, 50, 0.44));
		}

		.section-label {
			font-size: 0.78rem;
			font-weight: 700;
			color: var(--pnoc-muted);
			margin-bottom: 0.28rem;
			text-transform: uppercase;
			letter-spacing: 0.04em;
		}

		.utility-grid {
			display: grid;
			grid-template-columns: 1.05fr 1fr 1.2fr 0.9fr 180px 150px;
			gap: 0.72rem;
			margin-bottom: 0.78rem;
			padding: 0.75rem;
			border: 1px solid #d8e4ef;
			border-radius: 12px;
			background: #f7fbff;
		}

		.utility-grid select,
		.utility-grid input,
		.utility-grid button {
			width: 100%;
			padding: 0.58rem 0.68rem;
			border-radius: 9px;
			border: 1px solid var(--pnoc-border);
			font: inherit;
			font-size: 0.9rem;
		}

		.utility-grid input::placeholder {
			color: #6b7680;
		}

		.utility-grid input[type="month"] {
			min-height: 42px;
		}

		.utility-grid button {
			background: linear-gradient(135deg, var(--pnoc-blue), var(--pnoc-blue-2));
			border-color: transparent;
			color: #fff;
			font-weight: 700;
			cursor: pointer;
		}

		.bulk-actions {
			display: grid;
			grid-template-columns: repeat(6, minmax(140px, 1fr));
			gap: 0.72rem;
			margin-bottom: 0.85rem;
		}

		.active-filters {
			display: flex;
			flex-wrap: wrap;
			gap: 0.5rem;
			margin-bottom: 0.85rem;
		}

		.filter-chip {
			display: inline-flex;
			align-items: center;
			gap: 0.4rem;
			padding: 0.32rem 0.6rem;
			font: inherit;
			font-size: 0.8rem;
			font-weight: 700;
			border-radius: 999px;
			border: 1px solid #c9dbea;
			background: #eef6ff;
			color: #2c4f6f;
			cursor: pointer;
		}

		.filter-chip:hover {
			border-color: #b2cce1;
		}

		.filter-chip-empty {
			font-size: 0.84rem;
			color: var(--pnoc-muted);
		}

		.bulk-actions button {
			display: inline-flex;
			align-items: center;
			justify-content: center;
			gap: 0.42rem;
			padding: 0.58rem 0.68rem;
			border-radius: 9px;
			border: 1px solid var(--pnoc-border);
			background: #fff;
			font: inherit;
			font-size: 0.9rem;
			font-weight: 700;
			cursor: pointer;
			transition: transform .15s ease, box-shadow .2s ease, border-color .2s ease;
		}

		.bulk-actions button:hover:not(:disabled) {
			transform: translateY(-1px);
			border-color: #b9ccdf;
			box-shadow: 0 8px 18px rgba(27, 36, 48, 0.08);
		}

		.bulk-actions button:disabled {
			opacity: 0.55;
			cursor: not-allowed;
		}

		.bulk-actions .danger {
			border-color: #c76b6b;
			color: #8e2a2a;
			background: #fff5f5;
		}

		.bulk-actions .neutral {
			background: #f7fbff;
			border-color: #c8d9e8;
			color: #2c4f6f;
		}

		.bulk-actions .primary {
			background: linear-gradient(135deg, var(--pnoc-blue), var(--pnoc-blue-2));
			border-color: transparent;
			color: #fff;
		}

		.meta {
			font-size: 0.9rem;
			color: var(--pnoc-muted);
			margin-bottom: 0.58rem;
		}

		.table-wrap {
			overflow: auto;
			border: 1px solid var(--pnoc-border);
			border-radius: 14px;
			background: #fff;
		}

		table {
			width: 100%;
			border-collapse: collapse;
			table-layout: fixed;
			min-width: 1080px;
			background: #fff;
		}

		th,
		td {
			border: 1px solid #d7e0ea;
			padding: 0.66rem 0.74rem;
			font-size: 0.9rem;
			text-align: left;
			vertical-align: top;
			word-break: break-word;
			overflow-wrap: anywhere;
		}

		thead th {
			position: sticky;
			top: 0;
			z-index: 1;
			background: linear-gradient(180deg, #f8fbff, #edf4fb);
			white-space: normal;
			font-size: 0.78rem;
			text-transform: uppercase;
			letter-spacing: 0.05em;
			color: #35516f;
		}

		.th-sort {
			width: 100%;
			display: inline-flex;
			align-items: center;
			justify-content: space-between;
			gap: 0.42rem;
			font: inherit;
			font-size: inherit;
			font-weight: inherit;
			letter-spacing: inherit;
			text-transform: inherit;
			color: inherit;
			border: 0;
			background: transparent;
			padding: 0;
			cursor: pointer;
		}

		.th-sort .sort-mark {
			font-size: 0.72rem;
			opacity: 0.55;
		}

		.th-sort.active .sort-mark {
			opacity: 1;
			color: #21486b;
		}

		tbody td:nth-child(1),
		tbody td:nth-child(5),
		tbody td:nth-child(6),
		tbody td:nth-child(7) {
			white-space: nowrap;
		}

		tbody td:nth-child(6) {
			text-align: center;
		}

		tr:nth-child(even) td {
			background: #fafcff;
		}

		tbody tr:hover td {
			background: #f3f9ff;
		}

		.badge {
			display: inline-block;
			padding: 0.22rem 0.5rem;
			font-size: 0.75rem;
			border-radius: 999px;
			border: 1px solid #c8d7e6;
			background: #f3f8fd;
			color: #284a6a;
			font-weight: 700;
		}

		@media (max-width: 1100px) {
			.head {
				grid-template-columns: 1fr;
			}

			.hero-media {
				min-height: 180px;
			}

			.utility-grid,
			.bulk-actions {
				grid-template-columns: repeat(2, minmax(0, 1fr));
			}

			table {
				min-width: 980px;
			}
		}

		@media (max-width: 640px) {
			.utility-grid,
			.bulk-actions {
				grid-template-columns: 1fr;
			}

			.panel {
				padding: 0.88rem;
			}

			.hero-copy {
				padding: 0.85rem;
			}

			table {
				min-width: 880px;
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
		<section class="panel" aria-label="Tools and utilities section">
			<div class="head">
				<div class="hero-copy">
					<h1>Tools</h1>
					<p>
						Sort, filter, then run quick actions.
					</p>
					<span class="hero-note">Workflow Console</span>
				</div>
				<div class="hero-media" aria-hidden="true">
					<img src="as.jpg" alt="Tools dashboard visual" onerror="this.style.display='none'" />
				</div>
			</div>

			<div class="section-label">Filters</div>
			<div class="utility-grid" aria-label="Filters and sort controls">
				<select id="sortMode" aria-label="Sort records">
					<option value="doc-asc">Doc Number (A–Z)</option>
					<option value="doc-desc">Doc Number (Z–A)</option>
					<option value="date-asc">Date (Oldest)</option>
					<option value="date-desc" selected>Date (Newest)</option>
					<option value="rev-asc">Revision (Low–High)</option>
					<option value="rev-desc">Revision (High–Low)</option>
				</select>
				<input id="holderFilter" list="holderNames" type="text" placeholder="Filter holder" aria-label="Filter by holder name" />
				<datalist id="holderNames"></datalist>
				<input id="titleSearch" type="text" placeholder="Search title" aria-label="Search documents by title" />
				<select id="statusFilter" aria-label="Filter by status">
					<option value="all" selected>All Status</option>
					<option value="retrieved">Retrieved</option>
					<option value="revised">Revised</option>
				</select>
				<input id="monthFilter" type="month" aria-label="Filter by month" />
				<button type="button" id="clearFilters">Clear</button>
			</div>
			<div class="active-filters" id="activeFilters" aria-live="polite"></div>

			<div class="section-label">Actions</div>
			<div class="bulk-actions" aria-label="Quick actions">
				<button type="button" id="selectAll" class="primary" title="Select all visible rows">Select Visible</button>
				<button type="button" id="clearSelection" class="neutral" title="Uncheck all selected rows">Clear Selection</button>
				<button type="button" id="bulkUpdate" disabled title="Increase revision by 1 for selected rows">Revise +1</button>
				<button type="button" id="bulkDelete" class="danger" disabled title="Delete selected rows">Delete Selected</button>
				<button type="button" id="exportSelected" disabled title="Export selected rows as CSV">Export Selected</button>
				<button type="button" id="exportFiltered" class="neutral" title="Export all currently filtered rows">Export Filtered</button>
			</div>

			<div class="meta" id="apiStatus">Loading records...</div>

			<div class="meta" id="meta">0 shown • 0 selected</div>

			<div class="table-wrap">
				<table aria-label="Tools records table">
					<colgroup>
						<col style="width: 56px" />
						<col style="width: 14%" />
						<col style="width: 18%" />
						<col style="width: 36%" />
						<col style="width: 12%" />
						<col style="width: 8%" />
						<col style="width: 12%" />
					</colgroup>
					<thead>
						<tr>
							<th><input id="masterCheck" type="checkbox" aria-label="Select all rows" /></th>
							<th><button type="button" class="th-sort" data-sort-base="doc">Doc Number <span class="sort-mark">↕</span></button></th>
							<th>Copy Holder</th>
							<th>Title</th>
							<th><button type="button" class="th-sort" data-sort-base="date">Issued <span class="sort-mark">↕</span></button></th>
							<th><button type="button" class="th-sort" data-sort-base="rev">Revision <span class="sort-mark">↕</span></button></th>
							<th>Status</th>
						</tr>
					</thead>
					<tbody id="tableBody"></tbody>
				</table>
			</div>
		</section>
	</main>

	<script>
		const API_URL = "./api/staff.php";

		const state = {
			allRecords: [],
			visibleRecords: [],
			selectedIds: new Set()
		};

		const refs = {
			sortMode: document.getElementById("sortMode"),
			holderFilter: document.getElementById("holderFilter"),
			titleSearch: document.getElementById("titleSearch"),
			statusFilter: document.getElementById("statusFilter"),
			monthFilter: document.getElementById("monthFilter"),
			clearFilters: document.getElementById("clearFilters"),
			selectAll: document.getElementById("selectAll"),
			clearSelection: document.getElementById("clearSelection"),
			bulkUpdate: document.getElementById("bulkUpdate"),
			bulkDelete: document.getElementById("bulkDelete"),
			exportSelected: document.getElementById("exportSelected"),
			exportFiltered: document.getElementById("exportFiltered"),
			masterCheck: document.getElementById("masterCheck"),
			tableBody: document.getElementById("tableBody"),
			apiStatus: document.getElementById("apiStatus"),
			meta: document.getElementById("meta"),
			holderNames: document.getElementById("holderNames"),
			activeFilters: document.getElementById("activeFilters"),
			sortButtons: Array.from(document.querySelectorAll(".th-sort"))
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

		function normalizeRecord(record, index) {
			const retrievalDate = record.retrievalDate || record.retrieval_date || "";
			const inferredStatus = retrievalDate ? "Retrieved" : "Issued";
			return {
				id: String(record.id || record.record_id || `api-${index + 1}`),
				documentNumber: record.documentNumber || record.document_number || "",
				copyNumber: record.copyNumber || record.copy_number || "",
				copyHolder: record.copyHolder || record.copy_holder || "",
				documentTitle: record.documentTitle || record.document_title || "",
				issuanceDate: record.issuanceDate || record.issuance_date || "",
				revisionNumber: record.revisionNumber || record.revision_number || "0",
				retrievalDate,
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
				state.allRecords = rows.map((record, index) => normalizeRecord(record, index));
				refs.apiStatus.textContent = `Loaded ${state.allRecords.length} record(s).`;
			} catch (error) {
				state.allRecords = [];
				refs.apiStatus.textContent = `Can’t load records: ${error.message || "API unavailable."}`;
			}

			populateHolderNames();
			state.selectedIds.clear();
			applyUtilityFilters();
		}

		async function apiRequest(method, body = null, query = "") {
			const options = { method };
			if (body !== null) {
				options.headers = { "Content-Type": "application/json" };
				options.body = JSON.stringify(body);
			}

			const response = await fetch(`${API_URL}${query}`, options);
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
			return payload;
		}

		function populateHolderNames() {
			const holders = [...new Set(state.allRecords.map((record) => record.copyHolder).filter(Boolean))].sort((left, right) => left.localeCompare(right));
			refs.holderNames.innerHTML = holders.map((holder) => `<option value="${escapeHtml(holder)}"></option>`).join("");
		}

		function applyUtilityFilters() {
			const holderQuery = refs.holderFilter.value.trim().toLowerCase();
			const titleQuery = refs.titleSearch.value.trim().toLowerCase();
			const statusValue = refs.statusFilter.value;
			const monthValue = refs.monthFilter.value;

			const filtered = state.allRecords.filter((record) => {
				const matchHolder = !holderQuery || record.copyHolder.toLowerCase().includes(holderQuery);
				const matchTitle = !titleQuery || record.documentTitle.toLowerCase().includes(titleQuery);

				const normalizedStatus = (record.status || "Issued").trim().toLowerCase();
				const isRetrieved = normalizedStatus === "retrieved" || Boolean(toDate(record.retrievalDate));
				const isRevised = Number(record.revisionNumber) > 0;

				let matchStatus = true;
				if (statusValue === "retrieved") {
					matchStatus = isRetrieved;
				} else if (statusValue === "revised") {
					matchStatus = isRevised;
				}

				const issuanceDate = toDate(record.issuanceDate);
				const issuanceMonth = issuanceDate
					? `${issuanceDate.getFullYear()}-${String(issuanceDate.getMonth() + 1).padStart(2, "0")}`
					: "";
				const matchMonth = !monthValue || issuanceMonth === monthValue;

				return matchHolder && matchTitle && matchStatus && matchMonth;
			});

			state.visibleRecords = sortRecords(filtered, refs.sortMode.value);
			renderActiveFilterChips();
			updateSortButtons();
			renderTable();
		}

		function renderActiveFilterChips() {
			const chips = [];
			const holder = refs.holderFilter.value.trim();
			const title = refs.titleSearch.value.trim();
			const status = refs.statusFilter.value;
			const month = refs.monthFilter.value;

			if (holder) chips.push({ key: "holder", label: `Holder: ${escapeHtml(holder)}` });
			if (title) chips.push({ key: "title", label: `Title: ${escapeHtml(title)}` });
			if (status !== "all") chips.push({ key: "status", label: `Status: ${escapeHtml(status)}` });
			if (month) chips.push({ key: "month", label: `Month: ${escapeHtml(month)}` });

			if (!chips.length) {
				refs.activeFilters.innerHTML = "<span class='filter-chip-empty'>No active filters.</span>";
				return;
			}

			refs.activeFilters.innerHTML = chips
				.map((chip) => `<button type='button' class='filter-chip' data-filter-key='${chip.key}' title='Clear ${chip.key} filter'>${chip.label} ×</button>`)
				.join("");
		}

		function clearSingleFilter(filterKey) {
			if (filterKey === "holder") refs.holderFilter.value = "";
			if (filterKey === "title") refs.titleSearch.value = "";
			if (filterKey === "status") refs.statusFilter.value = "all";
			if (filterKey === "month") refs.monthFilter.value = "";
			applyUtilityFilters();
		}

		function updateSortButtons() {
			for (const button of refs.sortButtons) {
				const base = button.dataset.sortBase || "";
				const activeAsc = refs.sortMode.value === `${base}-asc`;
				const activeDesc = refs.sortMode.value === `${base}-desc`;
				button.classList.toggle("active", activeAsc || activeDesc);
				const mark = button.querySelector(".sort-mark");
				if (mark) {
					mark.textContent = activeAsc ? "↑" : activeDesc ? "↓" : "↕";
				}
			}
		}

		function toggleSortBy(base) {
			const current = refs.sortMode.value;
			let nextValue = `${base}-asc`;
			if (base === "date" && !current.startsWith("date-")) {
				nextValue = "date-desc";
			} else if (current === `${base}-asc`) {
				nextValue = `${base}-desc`;
			} else if (current === `${base}-desc`) {
				nextValue = `${base}-asc`;
			}
			refs.sortMode.value = nextValue;
			applyUtilityFilters();
		}

		function sortRecords(records, mode) {
			const rows = [...records];
			switch (mode) {
				case "doc-asc":
					rows.sort((a, b) => a.documentNumber.localeCompare(b.documentNumber));
					break;
				case "doc-desc":
					rows.sort((a, b) => b.documentNumber.localeCompare(a.documentNumber));
					break;
				case "date-asc":
					rows.sort((a, b) => (toDate(a.issuanceDate)?.getTime() || 0) - (toDate(b.issuanceDate)?.getTime() || 0));
					break;
				case "rev-asc":
					rows.sort((a, b) => Number(a.revisionNumber) - Number(b.revisionNumber));
					break;
				case "rev-desc":
					rows.sort((a, b) => Number(b.revisionNumber) - Number(a.revisionNumber));
					break;
				case "date-desc":
				default:
					rows.sort((a, b) => (toDate(b.issuanceDate)?.getTime() || 0) - (toDate(a.issuanceDate)?.getTime() || 0));
					break;
			}
			return rows;
		}

		function getSelectedRecords() {
			return state.visibleRecords.filter((record) => state.selectedIds.has(record.id));
		}

		function updateActionLabels(selectedVisibleCount) {
			refs.selectAll.textContent = `Select Visible (${state.visibleRecords.length})`;
			refs.bulkUpdate.textContent = selectedVisibleCount > 0
				? `Revise +1 (${selectedVisibleCount})`
				: "Revise +1";
			refs.bulkDelete.textContent = selectedVisibleCount > 0
				? `Delete Selected (${selectedVisibleCount})`
				: "Delete Selected";
			refs.exportSelected.textContent = selectedVisibleCount > 0
				? `Export Selected (${selectedVisibleCount})`
				: "Export Selected";
			refs.exportFiltered.textContent = `Export Filtered (${state.visibleRecords.length})`;
		}

		function renderTable() {
			const rows = state.visibleRecords.map((record) => {
				const checked = state.selectedIds.has(record.id) ? "checked" : "";
				return `
					<tr>
						<td><input class="row-check" type="checkbox" data-id="${escapeHtml(record.id)}" ${checked} /></td>
						<td>${escapeHtml(record.documentNumber)}</td>
						<td>${escapeHtml(record.copyHolder)}</td>
						<td>${escapeHtml(record.documentTitle)}</td>
						<td>${formatDate(record.issuanceDate)}</td>
						<td>${escapeHtml(record.revisionNumber)}</td>
						<td><span class="badge">${escapeHtml(record.status || "Issued")}</span></td>
					</tr>
				`;
			});

			refs.tableBody.innerHTML = rows.join("") || "<tr><td colspan='7'>No records match filters.</td></tr>";

			const selectedVisibleCount = getSelectedRecords().length;
			refs.meta.textContent = `${state.visibleRecords.length} shown • ${selectedVisibleCount} selected`;
			refs.masterCheck.checked = state.visibleRecords.length > 0 && selectedVisibleCount === state.visibleRecords.length;
			const hasSelectedRows = selectedVisibleCount > 0;
			updateActionLabels(selectedVisibleCount);
			refs.bulkUpdate.disabled = !hasSelectedRows;
			refs.bulkDelete.disabled = !hasSelectedRows;
			refs.exportSelected.disabled = !hasSelectedRows;
			refs.clearSelection.disabled = !hasSelectedRows;
			refs.exportFiltered.disabled = state.visibleRecords.length === 0;
		}

		function onTableCheckChange(event) {
			const checkbox = event.target;
			if (!(checkbox instanceof HTMLInputElement) || !checkbox.classList.contains("row-check")) return;
			const rowId = checkbox.dataset.id;
			if (!rowId) return;

			if (checkbox.checked) {
				state.selectedIds.add(rowId);
			} else {
				state.selectedIds.delete(rowId);
			}
			renderTable();
		}

		function clearFilters() {
			refs.sortMode.value = "date-desc";
			refs.holderFilter.value = "";
			refs.titleSearch.value = "";
			refs.statusFilter.value = "all";
			refs.monthFilter.value = "";
			state.selectedIds.clear();
			applyUtilityFilters();
		}

		function selectAllVisible() {
			for (const record of state.visibleRecords) {
				state.selectedIds.add(record.id);
			}
			renderTable();
		}

		function clearSelection() {
			state.selectedIds.clear();
			renderTable();
		}

		function toggleMasterSelection(checked) {
			if (checked) {
				for (const record of state.visibleRecords) {
					state.selectedIds.add(record.id);
				}
			} else {
				for (const record of state.visibleRecords) {
					state.selectedIds.delete(record.id);
				}
			}
			renderTable();
		}

		async function bulkUpdateSelected() {
			const selected = getSelectedRecords();
			if (!selected.length) {
				alert("No selected records.");
				return;
			}

			const persistable = selected.filter((record) => record.id && !String(record.id).startsWith("api-"));
			if (!persistable.length) {
				alert("Selected records have no valid API IDs.");
				return;
			}

			let updatedCount = 0;
			const failed = [];
			for (const record of persistable) {
				const nextRevision = String(Number(record.revisionNumber || 0) + 1);
				try {
					await apiRequest("PUT", {
						id: record.id,
						documentNumber: record.documentNumber || "",
						copyNumber: record.copyNumber || "",
						copyHolder: record.copyHolder || "",
						documentTitle: record.documentTitle || "",
						issuanceDate: record.issuanceDate || "",
						revisionNumber: nextRevision,
						retrievalDate: record.retrievalDate || "",
						retrievedRevision: record.retrievedRevision || ""
					});
					updatedCount += 1;
				} catch (error) {
					failed.push(record.documentNumber || String(record.id));
				}
			}

			await loadRecords();
			if (!failed.length) {
				alert(`${updatedCount} record(s) updated.`);
				return;
			}
			alert(`${updatedCount} updated. Failed: ${failed.length} (${failed.join(", ")}).`);
		}

		async function bulkDeleteSelected() {
			const selected = getSelectedRecords();
			if (!selected.length) {
				alert("No selected records.");
				return;
			}

			const persistable = selected.filter((record) => record.id && !String(record.id).startsWith("api-"));
			if (!persistable.length) {
				alert("Selected records have no valid API IDs.");
				return;
			}

			const selectedCount = persistable.length;
			const confirmed = confirm(`Delete ${selectedCount} selected record(s)?`);
			if (!confirmed) return;

			let deletedCount = 0;
			const failed = [];
			for (const record of persistable) {
				try {
					await apiRequest("DELETE", null, `?id=${encodeURIComponent(record.id)}`);
					deletedCount += 1;
				} catch (error) {
					failed.push(record.documentNumber || String(record.id));
				}
			}

			await loadRecords();
			if (!failed.length) {
				alert(`${deletedCount} record(s) deleted.`);
				return;
			}
			alert(`${deletedCount} deleted. Failed: ${failed.length} (${failed.join(", ")}).`);
		}

		function exportRowsToCsv(records, filePrefix) {
			if (!records.length) {
				alert("No selected records.");
				return;
			}

			const header = ["Doc Number", "Copy Holder", "Title", "Issued", "Revision", "Status"];
			const lines = [header.join(",")];
			for (const record of records) {
				const row = [
					record.documentNumber,
					record.copyHolder,
					record.documentTitle,
					record.issuanceDate,
					record.revisionNumber,
					record.status
				].map((value) => `"${String(value ?? "").replace(/"/g, '""')}"`);
				lines.push(row.join(","));
			}

			const blob = new Blob([lines.join("\n")], { type: "text/csv;charset=utf-8;" });
			const url = URL.createObjectURL(blob);
			const link = document.createElement("a");
			link.href = url;
			link.download = `${filePrefix}_${Date.now()}.csv`;
			document.body.appendChild(link);
			link.click();
			link.remove();
			URL.revokeObjectURL(url);
		}

		function exportSelectedRecords() {
			const selected = getSelectedRecords();
			if (!selected.length) {
				alert("No selected records.");
				return;
			}
			exportRowsToCsv(selected, "selected_tools_records");
		}

		function exportFilteredRecords() {
			if (!state.visibleRecords.length) {
				alert("No filtered records to export.");
				return;
			}
			exportRowsToCsv(state.visibleRecords, "filtered_tools_records");
		}

		refs.sortMode.addEventListener("change", applyUtilityFilters);
		refs.holderFilter.addEventListener("input", applyUtilityFilters);
		refs.titleSearch.addEventListener("input", applyUtilityFilters);
		refs.statusFilter.addEventListener("change", applyUtilityFilters);
		refs.monthFilter.addEventListener("change", applyUtilityFilters);
		refs.clearFilters.addEventListener("click", clearFilters);
		refs.selectAll.addEventListener("click", selectAllVisible);
		refs.clearSelection.addEventListener("click", clearSelection);
		refs.bulkUpdate.addEventListener("click", bulkUpdateSelected);
		refs.bulkDelete.addEventListener("click", bulkDeleteSelected);
		refs.exportSelected.addEventListener("click", exportSelectedRecords);
		refs.exportFiltered.addEventListener("click", exportFilteredRecords);
		refs.activeFilters.addEventListener("click", (event) => {
			const target = event.target;
			if (!(target instanceof HTMLButtonElement)) return;
			const filterKey = target.dataset.filterKey;
			if (!filterKey) return;
			clearSingleFilter(filterKey);
		});
		for (const button of refs.sortButtons) {
			button.addEventListener("click", () => {
				const base = button.dataset.sortBase;
				if (!base) return;
				toggleSortBy(base);
			});
		}
		refs.masterCheck.addEventListener("change", (event) => {
			const target = event.target;
			if (!(target instanceof HTMLInputElement)) return;
			toggleMasterSelection(target.checked);
		});
		refs.tableBody.addEventListener("change", onTableCheckChange);

		loadRecords();
	</script>
</body>
</html>
