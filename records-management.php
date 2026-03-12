<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>PNOC Staff | Records</title>
	<meta name="description" content="Simple records page for search, edit, delete, and paging." />
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

		.page-head {
			display: flex;
			justify-content: space-between;
			gap: 1rem;
			align-items: flex-start;
			margin-bottom: 0.85rem;
		}

		.page-head h1 {
			font-size: clamp(1.2rem, 2.8vw, 1.8rem);
			margin-bottom: 0.35rem;
		}

		.page-head p {
			color: var(--pnoc-muted);
			max-width: 72ch;
		}

		.section-label {
			font-size: 0.78rem;
			font-weight: 700;
			color: var(--pnoc-muted);
			margin-bottom: 0.3rem;
			text-transform: uppercase;
			letter-spacing: 0.04em;
		}

		.meta {
			font-size: 0.85rem;
			color: var(--pnoc-muted);
			margin-bottom: 0.7rem;
		}

		.meta small {
			margin-left: 0.5rem;
			font-size: 0.78rem;
		}

		.filters {
			display: grid;
			grid-template-columns: 1.4fr 1fr auto;
			gap: 0.55rem;
			margin-bottom: 0.75rem;
		}

		.filters input,
		.filters select,
		.filters button {
			width: 100%;
			padding: 0.5rem 0.55rem;
			border-radius: 8px;
			border: 1px solid var(--pnoc-border);
			font: inherit;
		}

		.filters input::placeholder {
			color: #6b7680;
		}

		.filters button {
			background: #fff;
			cursor: pointer;
			font-weight: 600;
		}

		.table-wrap {
			overflow: auto;
			border: 1px solid var(--pnoc-border);
			border-radius: 10px;
		}

		table {
			width: 100%;
			border-collapse: collapse;
			min-width: 1240px;
			background: #fff;
		}

		th,
		td {
			border: 1px solid #d7e0ea;
			padding: 0.45rem 0.5rem;
			font-size: 0.88rem;
			text-align: left;
		}

		thead th {
			background: #edf2f7;
			white-space: nowrap;
		}

		th.sortable {
			cursor: pointer;
			user-select: none;
		}

		th.sortable:hover {
			background: #e7eef6;
		}

		tr:nth-child(even) td {
			background: #fafcff;
		}

		.row-actions {
			display: flex;
			gap: 0.35rem;
		}

		.row-actions button,
		.modal-actions button,
		.pagination button {
			padding: 0.38rem 0.62rem;
			border-radius: 7px;
			border: 1px solid var(--pnoc-border);
			background: #fff;
			font: inherit;
			cursor: pointer;
		}

		button.primary {
			background: var(--pnoc-blue);
			border-color: var(--pnoc-blue);
			color: #fff;
			font-weight: 600;
		}

		button.danger {
			border-color: #c76b6b;
			color: #8e2a2a;
			background: #fff5f5;
			font-weight: 600;
		}

		.pagination {
			margin-top: 0.75rem;
			display: flex;
			align-items: center;
			gap: 0.6rem;
			flex-wrap: wrap;
		}

		.pagination label {
			font-size: 0.84rem;
			color: var(--pnoc-muted);
		}

		.pagination select {
			padding: 0.35rem 0.4rem;
			border-radius: 7px;
			border: 1px solid var(--pnoc-border);
			font: inherit;
			font-size: 0.84rem;
		}

		.pagination span {
			font-size: 0.88rem;
			color: var(--pnoc-muted);
		}

		.modal-backdrop {
			position: fixed;
			inset: 0;
			background: rgba(10, 16, 24, 0.45);
			display: none;
			place-items: center;
			padding: 1rem;
			z-index: 60;
		}

		.modal-backdrop.show {
			display: grid;
		}

		.modal {
			width: min(720px, 95vw);
			background: #fff;
			border-radius: 12px;
			border: 1px solid var(--pnoc-border);
			padding: 1rem;
		}

		.modal h2 {
			font-size: 1.05rem;
			margin-bottom: 0.45rem;
		}

		.modal p {
			color: var(--pnoc-muted);
			margin-bottom: 0.75rem;
		}

		.form-grid {
			display: grid;
			grid-template-columns: repeat(2, minmax(0, 1fr));
			gap: 0.55rem;
			margin-bottom: 0.75rem;
		}

		.form-grid input {
			width: 100%;
			padding: 0.5rem 0.55rem;
			border-radius: 8px;
			border: 1px solid var(--pnoc-border);
			font: inherit;
		}

		.modal-actions {
			display: flex;
			justify-content: flex-end;
			gap: 0.5rem;
		}

		@media (max-width: 760px) {
			.filters {
				grid-template-columns: 1fr;
			}

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
			<div class="actions">
				<a class="link icon-back" href="staff.php" aria-label="Back to Staff Tools" title="Back to Staff Tools">← Back</a>
			</div>
		</div>
	</header>

	<main class="container">
		<section class="panel" aria-label="Records section">
			<div class="page-head">
				<div>
					<h1>Records</h1>
					<p>
						Search, edit, and manage records.
					</p>
				</div>
			</div>
			<div class="meta"><span id="apiStatus">Loading records...</span><small id="lastUpdated"></small></div>
			<div class="meta" id="meta">0 shown</div>

			<div class="section-label">Filters</div>
			<div class="filters" aria-label="Record search and filter controls">
				<input id="searchInput" type="text" placeholder="Search doc, copy, holder, title" />
				<select id="holderFilter">
					<option value="">All holders</option>
				</select>
				<button id="clearFilters" type="button">Reset</button>
			</div>

			<div class="section-label">Table</div>
			<div class="table-wrap">
				<table aria-label="Records management table">
					<thead>
						<tr>
							<th class="sortable" data-sort-key="documentNumber">Doc Number</th>
							<th class="sortable" data-sort-key="copyNumber">Copy No.</th>
							<th class="sortable" data-sort-key="copyHolder">Copy Holder</th>
							<th class="sortable" data-sort-key="documentTitle">Title / Manual</th>
							<th class="sortable" data-sort-key="issuanceDate">Issued</th>
							<th class="sortable" data-sort-key="revisionNumber">Revision</th>
							<th class="sortable" data-sort-key="retrievalDate">Retrieved</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody id="tableBody"></tbody>
				</table>
			</div>

			<div class="pagination" aria-label="Pagination controls">
				<label for="rowsPerPage">Rows</label>
				<select id="rowsPerPage" aria-label="Rows per page">
					<option value="10" selected>10</option>
					<option value="25">25</option>
					<option value="50">50</option>
				</select>
				<button id="prevPage" type="button">Prev</button>
				<span id="pageInfo">1 / 1</span>
				<button id="nextPage" type="button">Next</button>
			</div>
		</section>
	</main>

	<div id="editModalBackdrop" class="modal-backdrop" aria-hidden="true">
		<div class="modal" role="dialog" aria-modal="true" aria-labelledby="editModalTitle">
			<h2 id="editModalTitle">Edit Record</h2>
			<p>Update record details.</p>
			<div class="form-grid">
				<input id="editDocumentNumber" type="text" placeholder="Doc Number" />
				<input id="editCopyNumber" type="text" placeholder="Copy Number" />
				<input id="editCopyHolder" type="text" placeholder="Copy Holder" />
				<input id="editDocumentTitle" type="text" placeholder="Title / Manual" />
				<input id="editIssuanceDate" type="date" />
				<input id="editRevisionNumber" type="number" min="0" placeholder="Revision" />
				<input id="editRetrievalDate" type="date" />
				<input id="editRetrievedRevision" type="text" placeholder="Retrieved Revision" />
			</div>
			<div class="modal-actions">
				<button type="button" id="cancelEdit">Cancel</button>
				<button type="button" id="saveEdit" class="primary">Save</button>
			</div>
		</div>
	</div>

	<div id="deleteModalBackdrop" class="modal-backdrop" aria-hidden="true">
		<div class="modal" role="dialog" aria-modal="true" aria-labelledby="deleteModalTitle">
			<h2 id="deleteModalTitle">Delete Record</h2>
			<p id="deleteMessage">Delete this record?</p>
			<div class="modal-actions">
				<button type="button" id="cancelDelete">Cancel</button>
				<button type="button" id="confirmDelete" class="danger">Delete</button>
			</div>
		</div>
	</div>

	<script>
		const API_URL = "./api/staff.php";

		const state = {
			records: [],
			currentPage: 1,
			rowsPerPage: 10,
			sort: { key: "documentNumber", direction: "asc" },
			editingRecordId: null,
			deletingRecordId: null
		};

		const refs = {
			apiStatus: document.getElementById("apiStatus"),
			lastUpdated: document.getElementById("lastUpdated"),
			meta: document.getElementById("meta"),
			searchInput: document.getElementById("searchInput"),
			holderFilter: document.getElementById("holderFilter"),
			clearFilters: document.getElementById("clearFilters"),
			rowsPerPage: document.getElementById("rowsPerPage"),
			tableBody: document.getElementById("tableBody"),
			headers: document.querySelectorAll("th.sortable"),
			prevPage: document.getElementById("prevPage"),
			nextPage: document.getElementById("nextPage"),
			pageInfo: document.getElementById("pageInfo"),
			editModalBackdrop: document.getElementById("editModalBackdrop"),
			deleteModalBackdrop: document.getElementById("deleteModalBackdrop"),
			cancelEdit: document.getElementById("cancelEdit"),
			saveEdit: document.getElementById("saveEdit"),
			cancelDelete: document.getElementById("cancelDelete"),
			confirmDelete: document.getElementById("confirmDelete"),
			deleteMessage: document.getElementById("deleteMessage"),
			editDocumentNumber: document.getElementById("editDocumentNumber"),
			editCopyNumber: document.getElementById("editCopyNumber"),
			editCopyHolder: document.getElementById("editCopyHolder"),
			editDocumentTitle: document.getElementById("editDocumentTitle"),
			editIssuanceDate: document.getElementById("editIssuanceDate"),
			editRevisionNumber: document.getElementById("editRevisionNumber"),
			editRetrievalDate: document.getElementById("editRetrievalDate"),
			editRetrievedRevision: document.getElementById("editRetrievedRevision")
		};

		const escapeHtml = (value) =>
			String(value ?? "")
				.replace(/&/g, "&amp;")
				.replace(/</g, "&lt;")
				.replace(/>/g, "&gt;")
				.replace(/\"/g, "&quot;")
				.replace(/'/g, "&#39;");

		const toDate = (value) => {
			if (!value) return null;
			const date = new Date(value);
			return Number.isNaN(date.getTime()) ? null : date;
		};

		const formatDate = (value) => {
			const date = toDate(value);
			if (!date) return "";
			return date.toLocaleDateString("en-GB", {
				day: "2-digit",
				month: "short",
				year: "numeric"
			});
		};

		const formatLastUpdated = (date) => date.toLocaleTimeString("en-GB", {
			hour: "2-digit",
			minute: "2-digit",
			second: "2-digit"
		});

		function normalizeRecord(record) {
			return {
				id: Number(record.id || record.record_id || 0),
				documentNumber: record.documentNumber || record.document_number || "",
				copyNumber: record.copyNumber || record.copy_number || "",
				copyHolder: record.copyHolder || record.copy_holder || "",
				documentTitle: record.documentTitle || record.document_title || "",
				issuanceDate: record.issuanceDate || record.issuance_date || "",
				revisionNumber: String(record.revisionNumber || record.revision_number || "0"),
				retrievalDate: record.retrievalDate || record.retrieval_date || "",
				retrievedRevision: record.retrievedRevision || record.retrieved_revision || ""
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

		function compareRecords(left, right) {
			const { key, direction } = state.sort;
			const factor = direction === "asc" ? 1 : -1;
			const leftValue = (left[key] || "").toString().trim();
			const rightValue = (right[key] || "").toString().trim();

			if (key === "issuanceDate" || key === "retrievalDate") {
				return factor * ((new Date(leftValue).getTime() || 0) - (new Date(rightValue).getTime() || 0));
			}
			if (key === "revisionNumber") {
				return factor * ((Number(leftValue) || 0) - (Number(rightValue) || 0));
			}

			return factor * leftValue.localeCompare(rightValue, undefined, {
				numeric: true,
				sensitivity: "base"
			});
		}

		function getFilteredRecords() {
			const searchQuery = refs.searchInput.value.trim().toLowerCase();
			const holderQuery = refs.holderFilter.value.trim().toLowerCase();

			return state.records.filter((record) => {
				const matchHolder = !holderQuery || (record.copyHolder || "").toLowerCase() === holderQuery;
				if (!matchHolder) {
					return false;
				}

				if (!searchQuery) {
					return true;
				}

				return [record.documentNumber, record.copyNumber, record.copyHolder, record.documentTitle]
					.some((value) => String(value || "").toLowerCase().includes(searchQuery));
			});
		}

		function getSortedRecords() {
			return [...getFilteredRecords()].sort(compareRecords);
		}

		function populateHolderFilter() {
			const holders = [...new Set(state.records.map((record) => record.copyHolder).filter(Boolean))]
				.sort((left, right) => left.localeCompare(right));

			refs.holderFilter.innerHTML = "<option value=''>All holders</option>";
			for (const holder of holders) {
				const option = document.createElement("option");
				option.value = holder;
				option.textContent = holder;
				refs.holderFilter.appendChild(option);
			}
		}

		function getPageCount(totalItems) {
			return Math.max(1, Math.ceil(totalItems / state.rowsPerPage));
		}

		function getCurrentPageRows(sortedRecords) {
			const pageCount = getPageCount(sortedRecords.length);
			if (state.currentPage > pageCount) {
				state.currentPage = pageCount;
			}
			const start = (state.currentPage - 1) * state.rowsPerPage;
			return sortedRecords.slice(start, start + state.rowsPerPage);
		}

		function renderSortIndicators() {
			for (const header of refs.headers) {
				const sortKey = header.dataset.sortKey;
				const baseText = header.textContent.replace(/\s*[▲▼]$/, "");
				if (sortKey === state.sort.key) {
					header.textContent = `${baseText} ${state.sort.direction === "asc" ? "▲" : "▼"}`;
				} else {
					header.textContent = baseText;
				}
			}
		}

		function renderTable() {
			const sorted = getSortedRecords();
			const pageRows = getCurrentPageRows(sorted);
			refs.tableBody.innerHTML = "";

			for (const record of pageRows) {
				const row = document.createElement("tr");
				row.innerHTML = `
					<td>${escapeHtml(record.documentNumber)}</td>
					<td>${escapeHtml(record.copyNumber)}</td>
					<td>${escapeHtml(record.copyHolder)}</td>
					<td>${escapeHtml(record.documentTitle)}</td>
					<td>${escapeHtml(formatDate(record.issuanceDate))}</td>
					<td>${escapeHtml(record.revisionNumber)}</td>
					<td>${escapeHtml(formatDate(record.retrievalDate))}</td>
					<td>
						<div class="row-actions">
							<button type="button" class="primary edit-btn" data-id="${record.id}">Edit</button>
							<button type="button" class="danger delete-btn" data-id="${record.id}" data-doc="${escapeHtml(record.documentNumber)}">Delete</button>
						</div>
					</td>
				`;
				refs.tableBody.appendChild(row);
			}

			if (!pageRows.length) {
				refs.tableBody.innerHTML = "<tr><td colspan='8'>No records.</td></tr>";
			}

			const pageCount = getPageCount(sorted.length);
			refs.meta.textContent = `${sorted.length} shown • ${state.records.length} total`;
			refs.pageInfo.textContent = `${state.currentPage} / ${pageCount}`;
			refs.prevPage.disabled = state.currentPage <= 1;
			refs.nextPage.disabled = state.currentPage >= pageCount;
			renderSortIndicators();
		}

		async function loadRecords() {
			try {
				const payload = await apiRequest("GET");
				state.records = Array.isArray(payload.data) ? payload.data.map(normalizeRecord) : [];
				refs.apiStatus.textContent = `Loaded ${state.records.length} records.`;
				refs.lastUpdated.textContent = `Last updated: ${formatLastUpdated(new Date())}`;
				populateHolderFilter();
			} catch (error) {
				state.records = [];
				refs.apiStatus.textContent = `Can’t load records: ${error.message || "API unavailable."}`;
				refs.lastUpdated.textContent = "";
				populateHolderFilter();
			}
			state.currentPage = 1;
			renderTable();
		}

		function openEditModal(recordId) {
			const record = state.records.find((item) => item.id === Number(recordId));
			if (!record) return;

			state.editingRecordId = record.id;
			refs.editDocumentNumber.value = record.documentNumber || "";
			refs.editCopyNumber.value = record.copyNumber || "";
			refs.editCopyHolder.value = record.copyHolder || "";
			refs.editDocumentTitle.value = record.documentTitle || "";
			refs.editIssuanceDate.value = record.issuanceDate || "";
			refs.editRevisionNumber.value = record.revisionNumber || "0";
			refs.editRetrievalDate.value = record.retrievalDate || "";
			refs.editRetrievedRevision.value = record.retrievedRevision || "";
			refs.editModalBackdrop.classList.add("show");
			refs.editModalBackdrop.setAttribute("aria-hidden", "false");
		}

		function closeEditModal() {
			state.editingRecordId = null;
			refs.editModalBackdrop.classList.remove("show");
			refs.editModalBackdrop.setAttribute("aria-hidden", "true");
		}

		async function saveEdit() {
			if (!state.editingRecordId) return;
			const payload = {
				id: state.editingRecordId,
				documentNumber: refs.editDocumentNumber.value.trim(),
				copyNumber: refs.editCopyNumber.value.trim(),
				copyHolder: refs.editCopyHolder.value.trim(),
				documentTitle: refs.editDocumentTitle.value.trim(),
				issuanceDate: refs.editIssuanceDate.value,
				revisionNumber: refs.editRevisionNumber.value.trim() || "0",
				retrievalDate: refs.editRetrievalDate.value,
				retrievedRevision: refs.editRetrievedRevision.value.trim()
			};

			if (!payload.documentNumber || !payload.copyNumber || !payload.copyHolder || !payload.documentTitle) {
				alert("Fill in Doc Number, Copy Number, Copy Holder, and Title.");
				return;
			}

			refs.saveEdit.disabled = true;
			try {
				await apiRequest("PUT", payload);
				closeEditModal();
				await loadRecords();
			} catch (error) {
				alert(error.message || "Failed to save changes.");
			} finally {
				refs.saveEdit.disabled = false;
			}
		}

		function openDeleteModal(recordId, documentNumber) {
			state.deletingRecordId = Number(recordId);
			refs.deleteMessage.textContent = `Delete ${documentNumber || "(unknown)"}? This cannot be undone.`;
			refs.deleteModalBackdrop.classList.add("show");
			refs.deleteModalBackdrop.setAttribute("aria-hidden", "false");
		}

		function closeDeleteModal() {
			state.deletingRecordId = null;
			refs.deleteModalBackdrop.classList.remove("show");
			refs.deleteModalBackdrop.setAttribute("aria-hidden", "true");
		}

		async function confirmDelete() {
			if (!state.deletingRecordId) return;
			refs.confirmDelete.disabled = true;
			try {
				await apiRequest("DELETE", null, `?id=${encodeURIComponent(state.deletingRecordId)}`);
				closeDeleteModal();
				await loadRecords();
			} catch (error) {
				alert(error.message || "Failed to delete record.");
			} finally {
				refs.confirmDelete.disabled = false;
			}
		}

		refs.headers.forEach((header) => {
			header.addEventListener("click", () => {
				const key = header.dataset.sortKey;
				if (!key) return;
				if (state.sort.key === key) {
					state.sort.direction = state.sort.direction === "asc" ? "desc" : "asc";
				} else {
					state.sort.key = key;
					state.sort.direction = "asc";
				}
				state.currentPage = 1;
				renderTable();
			});
		});

		refs.prevPage.addEventListener("click", () => {
			if (state.currentPage <= 1) return;
			state.currentPage -= 1;
			renderTable();
		});

		refs.nextPage.addEventListener("click", () => {
			const pageCount = getPageCount(getSortedRecords().length);
			if (state.currentPage >= pageCount) return;
			state.currentPage += 1;
			renderTable();
		});

		refs.searchInput.addEventListener("input", () => {
			state.currentPage = 1;
			renderTable();
		});

		refs.holderFilter.addEventListener("change", () => {
			state.currentPage = 1;
			renderTable();
		});

		refs.clearFilters.addEventListener("click", () => {
			refs.searchInput.value = "";
			refs.holderFilter.value = "";
			state.currentPage = 1;
			renderTable();
		});

		refs.rowsPerPage.addEventListener("change", () => {
			state.rowsPerPage = Number(refs.rowsPerPage.value) || 10;
			state.currentPage = 1;
			renderTable();
		});

		refs.tableBody.addEventListener("click", (event) => {
			const editButton = event.target.closest(".edit-btn");
			if (editButton) {
				openEditModal(editButton.dataset.id);
				return;
			}

			const deleteButton = event.target.closest(".delete-btn");
			if (deleteButton) {
				openDeleteModal(deleteButton.dataset.id, deleteButton.dataset.doc);
			}
		});

		refs.cancelEdit.addEventListener("click", closeEditModal);
		refs.saveEdit.addEventListener("click", saveEdit);
		refs.cancelDelete.addEventListener("click", closeDeleteModal);
		refs.confirmDelete.addEventListener("click", confirmDelete);

		refs.editModalBackdrop.addEventListener("click", (event) => {
			if (event.target === refs.editModalBackdrop) {
				closeEditModal();
			}
		});

		refs.deleteModalBackdrop.addEventListener("click", (event) => {
			if (event.target === refs.deleteModalBackdrop) {
				closeDeleteModal();
			}
		});

		loadRecords();
	</script>
</body>
</html>
