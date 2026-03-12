<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>PNOC Staff | Export Reports</title>
	<meta name="description" content="Simple export and report page." />
	<link rel="icon" type="image/png" href="qw.png" />
	<script defer src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
	<script defer src="https://cdn.jsdelivr.net/npm/jspdf@2.5.1/dist/jspdf.umd.min.js"></script>
	<script defer src="https://cdn.jsdelivr.net/npm/jspdf-autotable@3.8.2/dist/jspdf.plugin.autotable.min.js"></script>
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

		.nav .actions {
			display: flex;
			gap: 0.65rem;
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
			padding: 0 0.75rem;
			height: 2.25rem;
			border-radius: 999px;
			background: var(--pnoc-blue);
			color: #fff;
			font-size: 0.92rem;
			font-weight: 700;
			line-height: 1;
		}

		main {
			padding: 1.2rem 0 2rem;
		}

		.panel {
			background: var(--pnoc-card);
			border: 1px solid var(--pnoc-border);
			border-radius: 12px;
			padding: 1rem;
		}

		.head h1 {
			font-size: clamp(1.2rem, 2.8vw, 1.8rem);
			margin-bottom: 0.35rem;
		}

		.head p {
			color: var(--pnoc-muted);
			margin-bottom: 0.9rem;
		}

		.filters {
			display: grid;
			grid-template-columns: repeat(4, minmax(0, 1fr));
			gap: 0.6rem;
			margin-bottom: 0.8rem;
		}

		.filters label {
			display: flex;
			flex-direction: column;
			gap: 0.25rem;
			font-size: 0.82rem;
			font-weight: 600;
			color: #2a3642;
		}

		.filters input {
			width: 100%;
			padding: 0.52rem 0.55rem;
			border-radius: 8px;
			border: 1px solid var(--pnoc-border);
			font: inherit;
		}

		.filters input::placeholder {
			color: #6b7680;
		}

		.actions-row {
			display: grid;
			grid-template-columns: repeat(5, minmax(0, 1fr));
			gap: 0.55rem;
			margin-bottom: 0.8rem;
		}

		.actions-row button {
			padding: 0.52rem 0.62rem;
			border-radius: 8px;
			border: 1px solid var(--pnoc-border);
			background: #fff;
			font: inherit;
			font-weight: 600;
			cursor: pointer;
		}

		.actions-row .primary {
			background: var(--pnoc-blue);
			border-color: var(--pnoc-blue);
			color: #fff;
		}

		.meta {
			font-size: 0.85rem;
			color: var(--pnoc-muted);
			margin-bottom: 0.7rem;
		}

		.report {
			display: none;
			border: 1px solid var(--pnoc-border);
			border-radius: 10px;
			padding: 0.9rem;
			background: #fff;
		}

		.report.show {
			display: block;
		}

		.report-header {
			border-bottom: 1px solid var(--pnoc-border);
			padding-bottom: 0.6rem;
			margin-bottom: 0.7rem;
		}

		.report-header h2 {
			font-size: 1.05rem;
			margin-bottom: 0.15rem;
		}

		.report-header p {
			font-size: 0.84rem;
			color: var(--pnoc-muted);
		}

		.summary-grid {
			display: grid;
			grid-template-columns: repeat(4, minmax(0, 1fr));
			gap: 0.55rem;
			margin-bottom: 0.75rem;
		}

		.summary-card {
			border: 1px solid var(--pnoc-border);
			border-radius: 8px;
			padding: 0.55rem;
			background: #fff;
		}

		.summary-card .label {
			font-size: 0.78rem;
			color: var(--pnoc-muted);
			font-weight: 600;
		}

		.summary-card .value {
			font-size: 1.15rem;
			font-weight: 700;
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
		}

		th,
		td {
			border: 1px solid #d7e0ea;
			padding: 0.42rem 0.5rem;
			font-size: 0.86rem;
			text-align: left;
		}

		thead th {
			background: #edf2f7;
			white-space: nowrap;
		}

		tr:nth-child(even) td {
			background: #fafcff;
		}

		@media (max-width: 980px) {
			.filters,
			.actions-row,
			.summary-grid {
				grid-template-columns: 1fr 1fr;
			}
		}

		@media (max-width: 640px) {
			.filters,
			.actions-row,
			.summary-grid {
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
		<section class="panel" aria-label="Export and reports panel">
			<div class="head">
				<h1>Export Reports</h1>
				<p>Generate and download record reports.</p>
			</div>

			<div class="filters" aria-label="Report filters">
				<label for="fromDate">From
					<input id="fromDate" type="date" />
				</label>
				<label for="toDate">To
					<input id="toDate" type="date" />
				</label>
				<label for="documentNumberFilter">Doc Number
					<input id="documentNumberFilter" type="text" placeholder="e.g., SEC-01" />
				</label>
				<label for="generatedDate">Generated
					<input id="generatedDate" type="text" readonly />
				</label>
			</div>

			<div class="actions-row">
				<button id="generateReport" class="primary" type="button">Generate</button>
				<button id="downloadReport" type="button">Download PDF</button>
				<button id="exportAllExcel" type="button">Export All Excel</button>
				<button id="exportFilteredExcel" type="button">Export Filtered Excel</button>
				<button id="clearFilters" type="button">Clear</button>
			</div>

			<div class="meta" id="apiStatus">Loading live records...</div>
			<div class="meta" id="reportMeta">No report yet.</div>

			<section id="reportSection" class="report" aria-label="Generated report section">
				<div class="report-header">
					<h2 id="reportTitle">Document Report</h2>
					<p id="reportGeneratedAt">Generated: -</p>
				</div>

				<div class="summary-grid" id="summaryGrid">
					<div class="summary-card">
						<div class="label">Total</div>
						<div class="value" id="summaryTotal">0</div>
					</div>
					<div class="summary-card">
						<div class="label">Issued</div>
						<div class="value" id="summaryIssued">0</div>
					</div>
					<div class="summary-card">
						<div class="label">Retrieved</div>
						<div class="value" id="summaryRetrieved">0</div>
					</div>
					<div class="summary-card">
						<div class="label">Revised</div>
						<div class="value" id="summaryRevised">0</div>
					</div>
				</div>

				<div class="table-wrap">
					<table aria-label="Generated report data table">
						<thead>
							<tr>
								<th>Doc Number</th>
								<th>Copy Number</th>
								<th>Copy Holder</th>
								<th>Title / Manual</th>
								<th>Issued</th>
								<th>Revision</th>
								<th>Retrieved</th>
							</tr>
						</thead>
						<tbody id="reportTableBody"></tbody>
					</table>
				</div>
			</section>
		</section>
	</main>

	<script>
		const API_URL = "./api/staff.php";

		const state = {
			records: [],
			generatedRows: []
		};

		const refs = {
			fromDate: document.getElementById("fromDate"),
			toDate: document.getElementById("toDate"),
			documentNumberFilter: document.getElementById("documentNumberFilter"),
			generatedDate: document.getElementById("generatedDate"),
			generateReport: document.getElementById("generateReport"),
			downloadReport: document.getElementById("downloadReport"),
			exportAllExcel: document.getElementById("exportAllExcel"),
			exportFilteredExcel: document.getElementById("exportFilteredExcel"),
			clearFilters: document.getElementById("clearFilters"),
			apiStatus: document.getElementById("apiStatus"),
			reportMeta: document.getElementById("reportMeta"),
			reportSection: document.getElementById("reportSection"),
			reportTitle: document.getElementById("reportTitle"),
			reportGeneratedAt: document.getElementById("reportGeneratedAt"),
			summaryTotal: document.getElementById("summaryTotal"),
			summaryIssued: document.getElementById("summaryIssued"),
			summaryRetrieved: document.getElementById("summaryRetrieved"),
			summaryRevised: document.getElementById("summaryRevised"),
			reportTableBody: document.getElementById("reportTableBody")
		};

		const toDate = (value) => {
			if (!value) return null;
			const date = new Date(value);
			return Number.isNaN(date.getTime()) ? null : date;
		};

		const formatDate = (value) => {
			const date = toDate(value);
			if (!date) return "";
			return date.toLocaleDateString("en-GB", { day: "2-digit", month: "short", year: "numeric" });
		};

		const escapeHtml = (value) =>
			String(value ?? "")
				.replace(/&/g, "&amp;")
				.replace(/</g, "&lt;")
				.replace(/>/g, "&gt;")
				.replace(/\"/g, "&quot;")
				.replace(/'/g, "&#39;");

		function normalizeRecord(record) {
			return {
				id: Number(record.id || record.record_id || 0),
				documentNumber: record.documentNumber || record.document_number || "",
				copyNumber: record.copyNumber || record.copy_number || "",
				copyHolder: record.copyHolder || record.copy_holder || "",
				documentTitle: record.documentTitle || record.document_title || "",
				issuanceDate: record.issuanceDate || record.issuance_date || "",
				revisionNumber: String(record.revisionNumber || record.revision_number || "0"),
				retrievalDate: record.retrievalDate || record.retrieval_date || ""
			};
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

		function getFilteredRows() {
			const fromDate = refs.fromDate.value ? new Date(`${refs.fromDate.value}T00:00:00`) : null;
			const toDateValue = refs.toDate.value ? new Date(`${refs.toDate.value}T23:59:59`) : null;
			const docQuery = refs.documentNumberFilter.value.trim().toLowerCase();

			return state.records.filter((record) => {
				const issueDate = toDate(record.issuanceDate);
				const matchDateFrom = !fromDate || (issueDate && issueDate >= fromDate);
				const matchDateTo = !toDateValue || (issueDate && issueDate <= toDateValue);
				const matchDoc = !docQuery || (record.documentNumber || "").toLowerCase().includes(docQuery);
				return matchDateFrom && matchDateTo && matchDoc;
			});
		}

		function buildSummary(rows) {
			const issued = rows.filter((row) => !row.retrievalDate).length;
			const retrieved = rows.filter((row) => !!row.retrievalDate).length;
			const revised = rows.filter((row) => Number(row.revisionNumber) > 0).length;
			return {
				total: rows.length,
				issued,
				retrieved,
				revised
			};
		}

		function renderReport(rows) {
			const generatedAt = new Date();
			const generatedLabel = generatedAt.toLocaleString("en-GB", {
				day: "2-digit",
				month: "short",
				year: "numeric",
				hour: "2-digit",
				minute: "2-digit"
			});

			const summary = buildSummary(rows);
			refs.reportSection.classList.add("show");
			refs.reportGeneratedAt.textContent = `Generated: ${generatedLabel}`;
			refs.summaryTotal.textContent = String(summary.total);
			refs.summaryIssued.textContent = String(summary.issued);
			refs.summaryRetrieved.textContent = String(summary.retrieved);
			refs.summaryRevised.textContent = String(summary.revised);
			refs.reportMeta.textContent = `Report: ${rows.length} record(s).`;

			const bodyRows = rows.map((row) => `
				<tr>
					<td>${escapeHtml(row.documentNumber)}</td>
					<td>${escapeHtml(row.copyNumber)}</td>
					<td>${escapeHtml(row.copyHolder)}</td>
					<td>${escapeHtml(row.documentTitle)}</td>
					<td>${escapeHtml(formatDate(row.issuanceDate))}</td>
					<td>${escapeHtml(row.revisionNumber)}</td>
					<td>${escapeHtml(formatDate(row.retrievalDate))}</td>
				</tr>
			`);
			refs.reportTableBody.innerHTML = bodyRows.join("") || "<tr><td colspan='7'>No records match filters.</td></tr>";
		}

		function toExportRows(rows) {
			return rows.map((row) => ({
				"Doc Number": row.documentNumber,
				"Copy Number": row.copyNumber,
				"Copy Holder": row.copyHolder,
				"Title / Manual": row.documentTitle,
				"Issued": formatDate(row.issuanceDate),
				"Revision": row.revisionNumber,
				"Retrieved": formatDate(row.retrievalDate)
			}));
		}

		function exportExcel(rows, fileName) {
			const exportRows = toExportRows(rows);
			if (!exportRows.length) {
				alert("No rows to export.");
				return;
			}

			if (window.XLSX) {
				const workbook = XLSX.utils.book_new();
				const worksheet = XLSX.utils.json_to_sheet(exportRows);
				XLSX.utils.book_append_sheet(workbook, worksheet, "Report");
				XLSX.writeFile(workbook, fileName);
				return;
			}

			const headers = Object.keys(exportRows[0]);
			const lines = [headers.join(",")];
			for (const row of exportRows) {
				lines.push(headers.map((header) => `"${String(row[header] || "").replaceAll('"', '""')}"`).join(","));
			}
			const blob = new Blob([lines.join("\n")], { type: "text/csv;charset=utf-8;" });
			const link = document.createElement("a");
			link.href = URL.createObjectURL(blob);
			link.download = fileName.replace(/\.xlsx$/i, ".csv");
			link.click();
			URL.revokeObjectURL(link.href);
		}

		function downloadPdfSummary(rows) {
			if (!rows.length) {
				alert("Generate a report first.");
				return;
			}
			if (!window.jspdf || typeof window.jspdf.jsPDF !== "function") {
				alert("PDF library unavailable.");
				return;
			}

			const summary = buildSummary(rows);
			const { jsPDF } = window.jspdf;
			const pdf = new jsPDF({ orientation: "landscape", unit: "pt", format: "a4" });

			pdf.setFont("helvetica", "bold");
			pdf.setFontSize(14);
			pdf.text("Document Report Summary", 40, 40);
			pdf.setFont("helvetica", "normal");
			pdf.setFontSize(10);
			pdf.text(`Generated: ${new Date().toLocaleString("en-GB")}`, 40, 58);
			pdf.text(`Total Records: ${summary.total} | Issued: ${summary.issued} | Retrieved: ${summary.retrieved} | Revised: ${summary.revised}`, 40, 74);

			const head = [[
				"Doc Number",
				"Copy Number",
				"Copy Holder",
				"Title / Manual",
				"Issued",
				"Revision",
				"Retrieved"
			]];
			const body = rows.map((row) => [
				row.documentNumber,
				row.copyNumber,
				row.copyHolder,
				row.documentTitle,
				formatDate(row.issuanceDate),
				row.revisionNumber,
				formatDate(row.retrievalDate)
			]);

			pdf.autoTable({
				head,
				body,
				startY: 90,
				theme: "grid",
				headStyles: { fillColor: [47, 93, 138] },
				styles: { fontSize: 8 }
			});

			pdf.save(`document_report_summary_${Date.now()}.pdf`);
		}

		async function loadRecords() {
			try {
				const payload = await apiRequest("GET");
				state.records = Array.isArray(payload.data) ? payload.data.map(normalizeRecord) : [];
				refs.apiStatus.textContent = `Loaded ${state.records.length} record(s).`;
			} catch (error) {
				state.records = [];
				refs.apiStatus.textContent = `Can’t load records: ${error.message || "API unavailable."}`;
			}
		}

		refs.generateReport.addEventListener("click", () => {
			state.generatedRows = getFilteredRows();
			renderReport(state.generatedRows);
		});

		refs.downloadReport.addEventListener("click", () => {
			downloadPdfSummary(state.generatedRows);
		});

		refs.exportAllExcel.addEventListener("click", () => {
			exportExcel(state.records, `all_document_records_${Date.now()}.xlsx`);
		});

		refs.exportFilteredExcel.addEventListener("click", () => {
			exportExcel(getFilteredRows(), `filtered_document_records_${Date.now()}.xlsx`);
		});

		refs.clearFilters.addEventListener("click", () => {
			refs.fromDate.value = "";
			refs.toDate.value = "";
			refs.documentNumberFilter.value = "";
			state.generatedRows = [];
			refs.reportSection.classList.remove("show");
			refs.reportMeta.textContent = "Filters cleared. No report yet.";
		});

		refs.generatedDate.value = new Date().toLocaleDateString("en-GB", {
			day: "2-digit",
			month: "short",
			year: "numeric"
		});

		loadRecords();
	</script>
</body>
</html>
