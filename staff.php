<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>PNOC Staff | Document Compiler</title>
	<meta name="description" content="PNOC staff document compiler with sorting, data entry, and Excel report generation." />
	<link rel="icon" type="image/png" href="qw.png" />
	<script defer src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
	<style>
		:root {
			--pnoc-blue: #2f5d8a;
			--pnoc-dark: #1b2430;
			--pnoc-bg: #f3f6f9;
			--pnoc-card: #ffffff;
			--pnoc-border: #d8e1ea;
			--pnoc-muted: #5f6b76;
		}

		* {
			box-sizing: border-box;
			margin: 0;
			padding: 0;
		}

		body {
			font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
			background: var(--pnoc-bg);
			color: var(--pnoc-dark);
			line-height: 1.45;
		}

		.container {
			width: min(1280px, 95vw);
			margin: 0 auto;
		}

		header {
			background: #fff;
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

		.nav a.link {
			text-decoration: none;
			color: var(--pnoc-blue);
			font-weight: 600;
		}

		main {
			padding: 1.2rem 0 2rem;
		}

		.workspace {
			display: grid;
			grid-template-columns: 250px 1fr;
			gap: 1rem;
			align-items: start;
		}

		.sidebar {
			background: var(--pnoc-card);
			border: 1px solid var(--pnoc-border);
			border-radius: 12px;
			padding: 0.85rem;
			position: sticky;
			top: 84px;
		}

		.sidebar h2 {
			font-size: 0.95rem;
			margin-bottom: 0.65rem;
		}

		.feature-nav {
			display: flex;
			flex-direction: column;
			gap: 0.4rem;
		}

		.feature-link {
			display: flex;
			align-items: center;
			gap: 0.55rem;
			text-decoration: none;
			color: var(--pnoc-dark);
			font-size: 0.9rem;
			padding: 0.45rem 0.55rem;
			border: 1px solid transparent;
			border-radius: 8px;
		}

		.feature-link:hover {
			background: #f7fafc;
			border-color: var(--pnoc-border);
		}

		.feature-icon {
			width: 1.25rem;
			height: 1.25rem;
			display: inline-grid;
			place-items: center;
			font-size: 0.85rem;
			border-radius: 6px;
			background: #edf2f7;
		}

		.content-area {
			min-width: 0;
		}

		.hero {
			background: var(--pnoc-card);
			border: 1px solid var(--pnoc-border);
			border-radius: 12px;
			overflow: hidden;
			margin-bottom: 1rem;
		}

		.hero-grid {
			display: grid;
			grid-template-columns: 1.25fr 1fr;
			gap: 1rem;
			align-items: center;
		}

		.hero-copy {
			padding: 1.25rem;
		}

		.hero-copy h1 {
			font-size: clamp(1.2rem, 2.8vw, 1.8rem);
			margin-bottom: 0.35rem;
		}

		.hero-copy p {
			color: var(--pnoc-muted);
		}

		.hero img {
			width: 100%;
			height: 170px;
			object-fit: cover;
			border-left: 1px solid var(--pnoc-border);
		}

		.compiler {
			background: var(--pnoc-card);
			border: 1px solid var(--pnoc-border);
			border-radius: 12px;
			padding: 1rem;
		}

		.tabs {
			display: flex;
			gap: 0.5rem;
			flex-wrap: wrap;
			margin-bottom: 0.8rem;
		}

		.tab {
			border: 1px solid var(--pnoc-border);
			background: #fff;
			padding: 0.45rem 0.75rem;
			border-radius: 999px;
			font-weight: 600;
			cursor: pointer;
		}

		.tab.active {
			background: var(--pnoc-blue);
			border-color: var(--pnoc-blue);
			color: #fff;
		}

		.tools {
			display: grid;
			grid-template-columns: repeat(6, minmax(0, 1fr));
			gap: 0.6rem;
			margin-bottom: 0.9rem;
		}

		.import-label {
			display: flex;
			align-items: center;
			justify-content: center;
			width: 100%;
			padding: 0.5rem 0.55rem;
			border-radius: 8px;
			border: 1px solid #17653e;
			background: #17653e;
			color: #fff;
			font: inherit;
			font-weight: 600;
			cursor: pointer;
			text-align: center;
			white-space: nowrap;
			overflow: hidden;
			text-overflow: ellipsis;
		}

		.import-label:hover {
			background: #115030;
			border-color: #115030;
		}

		.import-label input[type="file"] {
			display: none;
		}

		.tools select,
		.tools input,
		.tools button,
		.form-grid input,
		.form-grid select {
			width: 100%;
			padding: 0.5rem 0.55rem;
			border-radius: 8px;
			border: 1px solid var(--pnoc-border);
			font: inherit;
		}

		.tools button,
		#addRecord {
			background: var(--pnoc-blue);
			color: #fff;
			font-weight: 600;
			cursor: pointer;
			border-color: var(--pnoc-blue);
		}

		#exportExcel {
			background: #1f7a4f;
			border-color: #1f7a4f;
		}

		.table-wrap {
			overflow: auto;
			border: 1px solid var(--pnoc-border);
			border-radius: 10px;
		}

		table {
			width: 100%;
			border-collapse: collapse;
			min-width: 1060px;
			background: #fff;
		}

		thead th {
			position: sticky;
			top: 0;
			background: #edf2f7;
			z-index: 2;
			cursor: pointer;
			white-space: nowrap;
		}

		th,
		td {
			border: 1px solid #d7e0ea;
			padding: 0.45rem 0.5rem;
			font-size: 0.9rem;
			text-align: left;
			vertical-align: middle;
		}

		tbody tr:nth-child(even) {
			background: #fbfdff;
		}

		.meta {
			margin: 0.65rem 0 1rem;
			font-size: 0.9rem;
			color: var(--pnoc-muted);
		}

		.form-card {
			border: 1px solid var(--pnoc-border);
			border-radius: 10px;
			padding: 0.85rem;
			margin-top: 1rem;
		}

		.form-card h2 {
			font-size: 1.05rem;
			margin-bottom: 0.7rem;
		}

		.form-grid {
			display: grid;
			grid-template-columns: repeat(4, minmax(0, 1fr));
			gap: 0.55rem;
		}

		#addRecord {
			margin-top: 0.6rem;
		}

		.form-actions {
			display: flex;
			gap: 0.6rem;
			margin-top: 0.6rem;
		}

		#cancelEdit {
			margin-top: 0.6rem;
			background: #fff;
			color: var(--pnoc-blue);
			border-color: var(--pnoc-blue);
			font-weight: 600;
			cursor: pointer;
			display: none;
		}

		.row-actions {
			display: flex;
			gap: 0.45rem;
		}

		.row-actions button {
			border: 1px solid var(--pnoc-border);
			border-radius: 6px;
			padding: 0.35rem 0.5rem;
			font: inherit;
			font-size: 0.82rem;
			cursor: pointer;
		}

		.edit-btn {
			background: var(--pnoc-blue);
			border-color: var(--pnoc-blue);
			color: #fff;
		}

		.delete-btn {
			background: #fff;
			color: #a72222;
			border-color: #d8b0b0;
		}

		@media (max-width: 1080px) {
			.workspace {
				grid-template-columns: 1fr;
			}

			.sidebar {
				position: static;
			}

			.feature-nav {
				display: grid;
				grid-template-columns: repeat(2, minmax(0, 1fr));
			}

			.tools,
			.form-grid {
				grid-template-columns: repeat(2, minmax(0, 1fr));
			}

			.hero-grid {
				grid-template-columns: 1fr;
			}

			.hero img {
				height: 130px;
				border-left: 0;
				border-top: 1px solid var(--pnoc-border);
			}
		}

		@media (max-width: 640px) {
			.feature-nav {
				grid-template-columns: 1fr;
			}

			.tools,
			.form-grid {
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
			<a class="link" href="index.html">Back to Home</a>
		</div>
	</header>

	<main class="container">
		<div class="workspace">
			<aside class="sidebar" aria-label="Feature sidebar">
				<h2>Features</h2>
				<nav class="feature-nav">
					<a class="feature-link" href="#overview">
						<span class="feature-icon" aria-hidden="true">🏠</span>
						<span>Overview</span>
					</a>
					<a class="feature-link" href="#compiler-tools">
						<span class="feature-icon" aria-hidden="true">🛠️</span>
						<span>Tools</span>
					</a>
					<a class="feature-link" href="#records-table">
						<span class="feature-icon" aria-hidden="true">📋</span>
						<span>Records</span>
					</a>
					<a class="feature-link" href="#add-data">
						<span class="feature-icon" aria-hidden="true">➕</span>
						<span>Add Data</span>
					</a>
					<a class="feature-link" href="#importLabel">
						<span class="feature-icon" aria-hidden="true">📥</span>
						<span>Import</span>
					</a>
					<a class="feature-link" href="#exportExcel">
						<span class="feature-icon" aria-hidden="true">📤</span>
						<span>Export</span>
					</a>
				</nav>
			</aside>

			<div class="content-area">
				<section class="hero" id="overview">
					<div class="hero-grid">
						<div class="hero-copy">
							<h1>Document Compiler</h1>
							<p>
								Add and edit staff records, then review generated rows in the
								spreadsheet-style tracking grid.
							</p>
						</div>
						<img src="as.jpg" alt="Document tracker preview" onerror="this.style.display='none'" />
					</div>
				</section>

				<section class="compiler" id="compiler-tools" aria-label="Staff document compiler tools">
			<div class="tabs" id="tabs"></div>

			<div class="tools">
				<select id="sortField" aria-label="Sort field">
					<option value="documentNumber">Document Number</option>
					<option value="copyNumber">Copy Number</option>
					<option value="copyHolder">Copy Holder's Name</option>
					<option value="documentTitle">Document Title / Name of Manual</option>
					<option value="issuanceDate">Issuance Date</option>
					<option value="revisionNumber">Revision Number</option>
					<option value="retrievalDate">Retrieval Date</option>
				</select>
				<select id="sortDirection" aria-label="Sort direction">
					<option value="asc">Ascending</option>
					<option value="desc">Descending</option>
				</select>
				<input id="searchInput" type="text" placeholder="Search file or holder..." aria-label="Search records" />
				<button id="applySort" type="button">Apply Sort</button>
				<label class="import-label" id="importLabel" title="Import records from an Excel or CSV file">
					<span id="importLabelText">📥 Import Excel/CSV</span>
					<input id="importExcel" type="file" accept=".xlsx,.xls,.csv" aria-label="Import Excel file" />
				</label>
				<button id="exportExcel" type="button">📤 Generate Excel Report</button>
			</div>

			<div class="meta" id="meta">0 records</div>

			<div class="table-wrap" id="records-table">
				<table id="documentTable" aria-label="Document compiler grid">
					<thead>
						<tr>
							<th data-key="documentNumber">Document Number</th>
							<th data-key="copyNumber">Copy Number</th>
							<th data-key="copyHolder">Copy Holder's Name</th>
							<th data-key="documentTitle">Document Title / Name of Manual</th>
							<th data-key="issuanceDate">Issuance Date</th>
							<th data-key="revisionNumber">Revision Number</th>
							<th data-key="retrievalDate">Retrieval Date</th>
							<th data-key="retrievedRevision">Revision No. of Retrieved Doc</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>

			<div class="form-card" id="add-data">
				<h2>Add Data</h2>
				<div class="form-grid">
					<input id="documentNumber" type="text" placeholder="Document Number (e.g., SEC-01)" />
					<input id="copyNumber" type="text" placeholder="Copy Number (e.g., CC No. 14)" />
					<input id="copyHolder" type="text" placeholder="Copy Holder's Name" />
					<input id="documentTitle" type="text" placeholder="Document Title / Name of Manual" />
					<input id="issuanceDate" type="date" placeholder="Issuance Date" />
					<input id="revisionNumber" type="number" min="0" placeholder="Revision Number" />
					<input id="retrievalDate" type="date" placeholder="Retrieval Date" />
					<input id="retrievedRevision" type="text" placeholder="Revision No. of Retrieved Doc" />
				</div>
				<div class="form-actions">
					<button id="addRecord" type="button">Add Record</button>
					<button id="cancelEdit" type="button">Cancel Edit</button>
				</div>
			</div>
				</section>
			</div>
		</div>
	</main>

	<script>
		const API_URL = "api/staff.php";
		let records = [];

		const tbody = document.querySelector("#documentTable tbody");
		const meta = document.getElementById("meta");
		const tabsHost = document.getElementById("tabs");
		const sortField = document.getElementById("sortField");
		const sortDirection = document.getElementById("sortDirection");
		const searchInput = document.getElementById("searchInput");
		const addRecordButton = document.getElementById("addRecord");
		const cancelEditButton = document.getElementById("cancelEdit");
		const requestedEditId = Number(new URLSearchParams(window.location.search).get("editId") || 0);

		let activeTab = "ALL";
		let currentSort = { key: "documentNumber", direction: "asc" };
		let editingId = null;

		const escapeHtml = (value) => String(value ?? "")
			.replaceAll("&", "&amp;")
			.replaceAll("<", "&lt;")
			.replaceAll(">", "&gt;")
			.replaceAll('"', "&quot;")
			.replaceAll("'", "&#39;");

		const formatDate = (value) => {
			if (!value) {
				return "";
			}
			const date = new Date(value);
			if (Number.isNaN(date.getTime())) {
				return value;
			}
			return date.toLocaleDateString("en-GB", {
				day: "2-digit",
				month: "short",
				year: "numeric"
			});
		};

		const getTabTokens = (record) => {
			if (!record.documentNumber) {
				return [];
			}
			return record.documentNumber
				.split("/")
				.map((token) => token.trim().toUpperCase())
				.filter(Boolean);
		};

		const renderTabs = () => {
			const uniqueTabs = new Set(["ALL"]);
			records.forEach((record) => {
				getTabTokens(record).forEach((token) => uniqueTabs.add(token));
			});

			tabsHost.innerHTML = "";
			Array.from(uniqueTabs).forEach((tabName) => {
				const button = document.createElement("button");
				button.type = "button";
				button.className = `tab ${activeTab === tabName ? "active" : ""}`;
				button.textContent = tabName;
				button.addEventListener("click", () => {
					activeTab = tabName;
					renderTable();
					renderTabs();
				});
				tabsHost.appendChild(button);
			});
		};

		const compareValues = (a, b) => {
			const key = currentSort.key;
			const directionFactor = currentSort.direction === "asc" ? 1 : -1;
			const valueA = (a[key] || "").toString().trim();
			const valueB = (b[key] || "").toString().trim();

			if (key === "issuanceDate" || key === "retrievalDate") {
				return directionFactor * ((new Date(valueA).getTime() || 0) - (new Date(valueB).getTime() || 0));
			}

			if (key === "revisionNumber") {
				return directionFactor * ((Number(valueA) || 0) - (Number(valueB) || 0));
			}

			return directionFactor * valueA.localeCompare(valueB, undefined, { numeric: true, sensitivity: "base" });
		};

		const getVisibleRecords = () => {
			const query = searchInput.value.trim().toLowerCase();

			return records
				.filter((record) => {
					if (activeTab === "ALL") {
						return true;
					}
					return getTabTokens(record).includes(activeTab);
				})
				.filter((record) => {
					if (!query) {
						return true;
					}
					return Object.values(record).some((value) => String(value || "").toLowerCase().includes(query));
				})
				.sort(compareValues);
		};

		const renderTable = () => {
			const visibleRecords = getVisibleRecords();
			tbody.innerHTML = "";

			visibleRecords.forEach((record) => {
				const row = document.createElement("tr");
				row.innerHTML = `
					<td>${escapeHtml(record.documentNumber)}</td>
					<td>${escapeHtml(record.copyNumber)}</td>
					<td>${escapeHtml(record.copyHolder)}</td>
					<td>${escapeHtml(record.documentTitle)}</td>
					<td>${escapeHtml(formatDate(record.issuanceDate))}</td>
					<td>${escapeHtml(record.revisionNumber)}</td>
					<td>${escapeHtml(formatDate(record.retrievalDate))}</td>
					<td>${escapeHtml(record.retrievedRevision)}</td>
					<td>
						<div class="row-actions">
							<button type="button" class="edit-btn" data-action="edit" data-id="${record.id}">Edit</button>
							<button type="button" class="delete-btn" data-action="delete" data-id="${record.id}">Delete</button>
						</div>
					</td>
				`;
				tbody.appendChild(row);
			});

			meta.textContent = `${visibleRecords.length} record(s) shown • Total: ${records.length} • Tab: ${activeTab}`;
		};

		const getRecordFromForm = () => ({
			documentNumber: document.getElementById("documentNumber").value.trim(),
			copyNumber: document.getElementById("copyNumber").value.trim(),
			copyHolder: document.getElementById("copyHolder").value.trim(),
			documentTitle: document.getElementById("documentTitle").value.trim(),
			issuanceDate: document.getElementById("issuanceDate").value,
			revisionNumber: document.getElementById("revisionNumber").value.trim() || "0",
			retrievalDate: document.getElementById("retrievalDate").value,
			retrievedRevision: document.getElementById("retrievedRevision").value.trim()
		});

		const clearForm = () => {
			document.querySelectorAll(".form-grid input").forEach((input) => {
				input.value = "";
			});
		};

		const stopEditing = () => {
			editingId = null;
			addRecordButton.textContent = "Add Record";
			cancelEditButton.style.display = "none";
			clearForm();
		};

		const startEditing = (id) => {
			const record = records.find((item) => Number(item.id) === Number(id));
			if (!record) {
				return;
			}

			editingId = Number(record.id);
			document.getElementById("documentNumber").value = record.documentNumber || "";
			document.getElementById("copyNumber").value = record.copyNumber || "";
			document.getElementById("copyHolder").value = record.copyHolder || "";
			document.getElementById("documentTitle").value = record.documentTitle || "";
			document.getElementById("issuanceDate").value = record.issuanceDate || "";
			document.getElementById("revisionNumber").value = record.revisionNumber || "0";
			document.getElementById("retrievalDate").value = record.retrievalDate || "";
			document.getElementById("retrievedRevision").value = record.retrievedRevision || "";

			addRecordButton.textContent = "Update Record";
			cancelEditButton.style.display = "inline-block";
		};

		const apiRequest = async (method, body = null, query = "") => {
			const options = { method };
			if (body !== null) {
				options.headers = { "Content-Type": "application/json" };
				options.body = JSON.stringify(body);
			}

			const response = await fetch(`${API_URL}${query}`,
				options
			);
			const payload = await response.json().catch(() => ({}));
			if (!response.ok || !payload.success) {
				throw new Error(payload.error || "Request failed.");
			}
			return payload;
		};

		const loadRecords = async () => {
			meta.textContent = "Loading records...";
			try {
				const payload = await apiRequest("GET");
				records = Array.isArray(payload.data) ? payload.data : [];
				renderTabs();
				renderTable();

				if (requestedEditId > 0) {
					startEditing(requestedEditId);
					document.getElementById("add-data")?.scrollIntoView({ behavior: "smooth", block: "start" });
				}
			} catch (error) {
				records = [];
				renderTabs();
				renderTable();
				meta.textContent = `Unable to load records right now. ${error.message || "Please check API/DB."}`;
			}
		};

		const saveRecord = async () => {
			const record = getRecordFromForm();

			if (!record.documentNumber || !record.copyNumber || !record.copyHolder || !record.documentTitle) {
				alert("Please complete Document Number, Copy Number, Copy Holder's Name, and Document Title.");
				return;
			}

			addRecordButton.disabled = true;
			cancelEditButton.disabled = true;

			try {
				if (editingId === null) {
					await apiRequest("POST", record);
				} else {
					await apiRequest("PUT", { ...record, id: editingId });
				}

				stopEditing();
				activeTab = "ALL";
				await loadRecords();
			} catch (error) {
				alert(error.message || "Failed to save record.");
			} finally {
				addRecordButton.disabled = false;
				cancelEditButton.disabled = false;
			}
		};

		const deleteRecord = async (id) => {
			const confirmed = window.confirm("Delete this record?");
			if (!confirmed) {
				return;
			}

			try {
				await apiRequest("DELETE", null, `?id=${encodeURIComponent(id)}`);
				if (editingId === Number(id)) {
					stopEditing();
				}
				await loadRecords();
			} catch (error) {
				alert(error.message || "Failed to delete record.");
			}
		};

		const exportReport = () => {
			const visibleRecords = getVisibleRecords().map((record) => ({
				"Document Number": record.documentNumber,
				"Copy Number": record.copyNumber,
				"Copy Holder's Name": record.copyHolder,
				"Document Title / Name of Manual": record.documentTitle,
				"Issuance Date": formatDate(record.issuanceDate),
				"Revision Number": record.revisionNumber,
				"Retrieval Date": formatDate(record.retrievalDate),
				"Revision No. of Retrieved Doc": record.retrievedRevision
			}));

			if (!visibleRecords.length) {
				alert("No rows to export.");
				return;
			}

			if (window.XLSX) {
				const workbook = XLSX.utils.book_new();
				const worksheet = XLSX.utils.json_to_sheet(visibleRecords);
				XLSX.utils.book_append_sheet(workbook, worksheet, "Staff_Report");
				XLSX.writeFile(workbook, `staff_document_report_${Date.now()}.xlsx`);
				return;
			}

			const headers = Object.keys(visibleRecords[0]);
			const lines = [headers.join(",")];
			visibleRecords.forEach((row) => {
				lines.push(headers.map((header) => `"${String(row[header] || "").replaceAll('"', '""')}"`).join(","));
			});
			const blob = new Blob([lines.join("\n")], { type: "text/csv;charset=utf-8;" });
			const link = document.createElement("a");
			link.href = URL.createObjectURL(blob);
			link.download = `staff_document_report_${Date.now()}.csv`;
			link.click();
			URL.revokeObjectURL(link.href);
		};

		const parseImportDate = (value) => {
			if (value === null || value === undefined || String(value).trim() === "") {
				return "";
			}
			if (value instanceof Date) {
				return Number.isNaN(value.getTime()) ? "" : value.toISOString().slice(0, 10);
			}
			const str = String(value).trim();
			const date = new Date(str);
			if (!Number.isNaN(date.getTime())) {
				return date.toISOString().slice(0, 10);
			}
			return str;
		};

		const importFromExcel = async (file) => {
			if (!window.XLSX) {
				alert("XLSX library is not available. Please refresh the page and try again.");
				return;
			}

			const labelText = document.getElementById("importLabelText");
			const importInput = document.getElementById("importExcel");
			labelText.textContent = "Importing…";

			const reader = new FileReader();
			reader.onload = async (event) => {
				try {
					const data = new Uint8Array(event.target.result);
					const workbook = XLSX.read(data, { type: "array" });
					const sheet = workbook.Sheets[workbook.SheetNames[0]];
					const rows = XLSX.utils.sheet_to_json(sheet, {
						header: 1,
						raw: true,
						cellDates: true,
						defval: ""
					});

					if (rows.length < 2) {
						alert("The file is empty or has no data rows.");
						return;
					}

					const FIELD_MAP = {
						"document number": "documentNumber",
						"copy number": "copyNumber",
						"copy holder's name": "copyHolder",
						"copy holder": "copyHolder",
						"document title / name of manual": "documentTitle",
						"document title": "documentTitle",
						"issuance date": "issuanceDate",
						"revision number": "revisionNumber",
						"retrieval date": "retrievalDate",
						"revision no. of retrieved doc": "retrievedRevision",
						"retrieved revision": "retrievedRevision"
					};

					const headers = rows[0].map((header) => String(header).trim().toLowerCase());
					const fieldIndices = {};
					headers.forEach((header, idx) => {
						const field = FIELD_MAP[header];
						if (field && fieldIndices[field] === undefined) {
							fieldIndices[field] = idx;
						}
					});

					const dataRows = rows.slice(1).filter((row) =>
						row.some((cell) => String(cell).trim() !== "")
					);

					if (!dataRows.length) {
						alert("No data rows found in the file.");
						return;
					}

					let successCount = 0;
					let errorCount = 0;

					for (const row of dataRows) {
						const record = {
							documentNumber: String(row[fieldIndices.documentNumber] ?? "").trim(),
							copyNumber: String(row[fieldIndices.copyNumber] ?? "").trim(),
							copyHolder: String(row[fieldIndices.copyHolder] ?? "").trim(),
							documentTitle: String(row[fieldIndices.documentTitle] ?? "").trim(),
							issuanceDate: parseImportDate(row[fieldIndices.issuanceDate]),
							revisionNumber: String(row[fieldIndices.revisionNumber] ?? "0").trim(),
							retrievalDate: parseImportDate(row[fieldIndices.retrievalDate]),
							retrievedRevision: String(row[fieldIndices.retrievedRevision] ?? "").trim()
						};

						if (!record.documentNumber || !record.copyNumber || !record.copyHolder || !record.documentTitle) {
							errorCount++;
							continue;
						}

						try {
							await apiRequest("POST", record);
							successCount++;
						} catch {
							errorCount++;
						}
					}

					alert(`Import complete: ${successCount} record(s) imported, ${errorCount} skipped or failed.`);
					activeTab = "ALL";
					await loadRecords();
				} catch (error) {
					alert("Failed to read the file: " + (error.message || "Unknown error."));
				} finally {
					labelText.textContent = "📥 Import Excel/CSV";
					importInput.value = "";
				}
			};
			reader.readAsArrayBuffer(file);
		};

		document.getElementById("importExcel").addEventListener("change", (event) => {
			const file = event.target.files[0];
			if (file) {
				importFromExcel(file);
			}
		});

		document.getElementById("applySort").addEventListener("click", () => {
			currentSort = {
				key: sortField.value,
				direction: sortDirection.value
			};
			renderTable();
		});

		addRecordButton.addEventListener("click", saveRecord);
		cancelEditButton.addEventListener("click", stopEditing);
		document.getElementById("exportExcel").addEventListener("click", exportReport);
		searchInput.addEventListener("input", renderTable);

		tbody.addEventListener("click", async (event) => {
			const button = event.target.closest("button[data-action]");
			if (!button) {
				return;
			}

			const action = button.dataset.action;
			const id = Number(button.dataset.id);
			if (!Number.isInteger(id) || id <= 0) {
				return;
			}

			if (action === "edit") {
				startEditing(id);
				return;
			}

			if (action === "delete") {
				await deleteRecord(id);
			}
		});

		document.querySelectorAll("thead th[data-key]").forEach((headerCell) => {
			headerCell.addEventListener("click", () => {
				const key = headerCell.dataset.key;
				if (currentSort.key === key) {
					currentSort.direction = currentSort.direction === "asc" ? "desc" : "asc";
				} else {
					currentSort.key = key;
					currentSort.direction = "asc";
				}
				sortField.value = currentSort.key;
				sortDirection.value = currentSort.direction;
				renderTable();
			});
		});

		loadRecords();
	</script>
</body>
</html>
