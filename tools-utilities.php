<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>PNOC Staff | Tools</title>
	<meta name="description" content="Simple tools for sorting, filtering, and bulk actions." />
	<link rel="icon" type="image/png" href="qw.png" />
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
			width: min(1240px, 95vw);
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
			padding: 0.85rem;
		}

		.head {
			display: flex;
			justify-content: space-between;
			gap: 1rem;
			align-items: flex-start;
			margin-bottom: 0.65rem;
		}

		.head h1 {
			font-size: clamp(1.1rem, 2.4vw, 1.5rem);
			margin-bottom: 0.2rem;
		}

		.head p {
			color: var(--pnoc-muted);
			max-width: 62ch;
			font-size: 0.9rem;
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
			grid-template-columns: repeat(4, minmax(0, 1fr));
			gap: 0.5rem;
			margin-bottom: 0.55rem;
		}

		.utility-grid select,
		.utility-grid input,
		.utility-grid button {
			width: 100%;
			padding: 0.42rem 0.5rem;
			border-radius: 7px;
			border: 1px solid var(--pnoc-border);
			font: inherit;
			font-size: 0.88rem;
		}

		.utility-grid input::placeholder {
			color: #6b7680;
		}

		.utility-grid button {
			background: var(--pnoc-blue);
			border-color: var(--pnoc-blue);
			color: #fff;
			font-weight: 600;
			cursor: pointer;
		}

		.bulk-actions {
			display: grid;
			grid-template-columns: repeat(4, minmax(0, 1fr));
			gap: 0.5rem;
			margin-bottom: 0.65rem;
		}

		.bulk-actions button {
			padding: 0.42rem 0.5rem;
			border-radius: 7px;
			border: 1px solid var(--pnoc-border);
			background: #fff;
			font: inherit;
			font-size: 0.86rem;
			font-weight: 600;
			cursor: pointer;
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

		.bulk-actions .primary {
			background: var(--pnoc-blue);
			border-color: var(--pnoc-blue);
			color: #fff;
		}

		.meta {
			font-size: 0.82rem;
			color: var(--pnoc-muted);
			margin-bottom: 0.5rem;
		}

		.table-wrap {
			overflow: auto;
			border: 1px solid var(--pnoc-border);
			border-radius: 10px;
		}

		table {
			width: 100%;
			border-collapse: collapse;
			min-width: 860px;
			background: #fff;
		}

		th,
		td {
			border: 1px solid #d7e0ea;
			padding: 0.38rem 0.45rem;
			font-size: 0.84rem;
			text-align: left;
		}

		thead th {
			background: #edf2f7;
			white-space: nowrap;
		}

		tr:nth-child(even) td {
			background: #fafcff;
		}

		.badge {
			display: inline-block;
			padding: 0.16rem 0.4rem;
			font-size: 0.72rem;
			border-radius: 999px;
			border: 1px solid #c8d7e6;
			background: #f3f8fd;
			color: #284a6a;
			font-weight: 600;
		}

		@media (max-width: 1100px) {
			.utility-grid,
			.bulk-actions {
				grid-template-columns: 1fr 1fr;
			}
		}

		@media (max-width: 640px) {
			.utility-grid,
			.bulk-actions {
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
		<section class="panel" aria-label="Tools and utilities section">
			<div class="head">
				<div>
					<h1>Tools</h1>
					<p>
						Sort, filter, then run quick actions.
					</p>
				</div>
			</div>

			<div class="section-label">Filters</div>
			<div class="utility-grid" aria-label="Filters and sort controls">
				<select id="sortMode" aria-label="Sort records">
					<option value="doc-asc">Doc Number (A–Z)</option>
					<option value="doc-desc">Doc Number (Z–A)</option>
					<option value="date-asc">Issued (Oldest)</option>
					<option value="date-desc" selected>Issued (Newest)</option>
					<option value="rev-asc">Revision (Low–High)</option>
					<option value="rev-desc">Revision (High–Low)</option>
				</select>
				<input id="holderFilter" list="holderNames" type="text" placeholder="Filter holder" aria-label="Filter by holder name" />
				<datalist id="holderNames"></datalist>
				<input id="titleSearch" type="text" placeholder="Search title" aria-label="Search documents by title" />
				<button type="button" id="clearFilters">Clear</button>
			</div>

			<div class="section-label">Actions</div>
			<div class="bulk-actions" aria-label="Quick actions">
				<button type="button" id="selectAll" class="primary">Select Visible</button>
				<button type="button" id="bulkUpdate" disabled>Revise +1</button>
				<button type="button" id="bulkDelete" class="danger" disabled>Delete Selected</button>
				<button type="button" id="exportSelected" disabled>Export Selected</button>
			</div>

			<div class="meta" id="apiStatus">Loading records...</div>

			<div class="meta" id="meta">0 shown • 0 selected</div>

			<div class="table-wrap">
				<table aria-label="Tools records table">
					<thead>
						<tr>
							<th><input id="masterCheck" type="checkbox" aria-label="Select all rows" /></th>
							<th>Doc Number</th>
							<th>Copy Holder</th>
							<th>Title</th>
							<th>Issued</th>
							<th>Revision</th>
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
			clearFilters: document.getElementById("clearFilters"),
			selectAll: document.getElementById("selectAll"),
			bulkUpdate: document.getElementById("bulkUpdate"),
			bulkDelete: document.getElementById("bulkDelete"),
			exportSelected: document.getElementById("exportSelected"),
			masterCheck: document.getElementById("masterCheck"),
			tableBody: document.getElementById("tableBody"),
			apiStatus: document.getElementById("apiStatus"),
			meta: document.getElementById("meta"),
			holderNames: document.getElementById("holderNames")
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

			const filtered = state.allRecords.filter((record) => {
				const matchHolder = !holderQuery || record.copyHolder.toLowerCase().includes(holderQuery);
				const matchTitle = !titleQuery || record.documentTitle.toLowerCase().includes(titleQuery);
				return matchHolder && matchTitle;
			});

			state.visibleRecords = sortRecords(filtered, refs.sortMode.value);
			renderTable();
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
			refs.bulkUpdate.disabled = !hasSelectedRows;
			refs.bulkDelete.disabled = !hasSelectedRows;
			refs.exportSelected.disabled = !hasSelectedRows;
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
			state.selectedIds.clear();
			applyUtilityFilters();
		}

		function selectAllVisible() {
			for (const record of state.visibleRecords) {
				state.selectedIds.add(record.id);
			}
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

		function exportSelectedRecords() {
			const selected = getSelectedRecords();
			if (!selected.length) {
				alert("No selected records.");
				return;
			}

			const header = ["Doc Number", "Copy Holder", "Title", "Issued", "Revision", "Status"];
			const lines = [header.join(",")];
			for (const record of selected) {
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
			link.download = `selected_tools_records_${Date.now()}.csv`;
			document.body.appendChild(link);
			link.click();
			link.remove();
			URL.revokeObjectURL(url);
		}

		refs.sortMode.addEventListener("change", applyUtilityFilters);
		refs.holderFilter.addEventListener("input", applyUtilityFilters);
		refs.titleSearch.addEventListener("input", applyUtilityFilters);
		refs.clearFilters.addEventListener("click", clearFilters);
		refs.selectAll.addEventListener("click", selectAllVisible);
		refs.bulkUpdate.addEventListener("click", bulkUpdateSelected);
		refs.bulkDelete.addEventListener("click", bulkDeleteSelected);
		refs.exportSelected.addEventListener("click", exportSelectedRecords);
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
