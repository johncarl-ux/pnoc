<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>PNOC Staff | Add Record</title>
	<meta name="description" content="Quick form to add a record." />
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
			width: min(960px, 95vw);
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

		.hint {
			font-size: 0.82rem;
			color: var(--pnoc-muted);
			margin-bottom: 0.7rem;
		}

		.form-grid {
			display: grid;
			grid-template-columns: repeat(2, minmax(0, 1fr));
			gap: 0.6rem;
			margin-bottom: 0.85rem;
		}

		.form-group {
			display: flex;
			flex-direction: column;
			gap: 0.3rem;
		}

		.form-group label {
			font-size: 0.84rem;
			font-weight: 600;
			color: #2a3642;
		}

		.form-group input {
			width: 100%;
			padding: 0.52rem 0.55rem;
			border-radius: 8px;
			border: 1px solid var(--pnoc-border);
			font: inherit;
		}

		.form-group small {
			font-size: 0.78rem;
			color: var(--pnoc-muted);
		}

		.form-group input::placeholder {
			color: #6b7680;
		}

		.form-group .error {
			color: #8e2a2a;
			min-height: 1.1em;
		}

		.actions {
			display: flex;
			gap: 0.55rem;
			justify-content: flex-end;
			flex-wrap: wrap;
		}

		.actions button {
			padding: 0.52rem 0.75rem;
			border-radius: 8px;
			border: 1px solid var(--pnoc-border);
			background: #fff;
			font: inherit;
			cursor: pointer;
			font-weight: 600;
		}

		.actions .primary {
			background: var(--pnoc-blue);
			border-color: var(--pnoc-blue);
			color: #fff;
		}

		.notice {
			display: none;
			margin-top: 0.85rem;
			padding: 0.62rem 0.7rem;
			border-radius: 8px;
			border: 1px solid #b7dfc8;
			background: #f2fcf6;
			color: #1f6a3f;
			font-size: 0.86rem;
			font-weight: 600;
		}

		.notice.show {
			display: block;
		}

		.bulk {
			margin-top: 1rem;
			border-top: 1px solid var(--pnoc-border);
			padding-top: 0.9rem;
		}

		.bulk h2 {
			font-size: 1rem;
			margin-bottom: 0.25rem;
		}

		.bulk p {
			font-size: 0.82rem;
			color: var(--pnoc-muted);
			margin-bottom: 0.55rem;
		}

		.bulk-row {
			display: grid;
			grid-template-columns: 1fr auto auto;
			gap: 0.5rem;
		}

		.bulk-row input,
		.bulk-row button {
			padding: 0.5rem 0.6rem;
			border-radius: 8px;
			border: 1px solid var(--pnoc-border);
			font: inherit;
		}

		.bulk-row button {
			background: #fff;
			font-weight: 600;
			cursor: pointer;
		}

		.bulk-row .primary {
			background: var(--pnoc-blue);
			border-color: var(--pnoc-blue);
			color: #fff;
		}

		.bulk-status {
			margin-top: 0.55rem;
			font-size: 0.82rem;
			color: var(--pnoc-muted);
			min-height: 1.2em;
		}

		@media (max-width: 760px) {
			.form-grid {
				grid-template-columns: 1fr;
			}

			.bulk-row {
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
		<section class="panel" aria-label="Add record form">
			<div class="head">
				<h1>Add Record</h1>
				<p>Fill in the fields, then save.</p>
			</div>
			<p class="hint">* Required: Doc Number, Copy Number, Copy Holder, Title, Issued</p>

			<form id="addDataForm" novalidate>
				<div class="form-grid">
					<div class="form-group">
						<label for="documentNumber">Doc Number</label>
						<input id="documentNumber" type="text" placeholder="SEC-01" required />
						<small class="error" id="errDocumentNumber"></small>
					</div>

					<div class="form-group">
						<label for="copyNumber">Copy Number</label>
						<input id="copyNumber" type="text" placeholder="CC No. 3" required />
						<small class="error" id="errCopyNumber"></small>
					</div>

					<div class="form-group">
						<label for="copyHolder">Copy Holder</label>
						<input id="copyHolder" type="text" placeholder="Copy Holder" required />
						<small class="error" id="errCopyHolder"></small>
					</div>

					<div class="form-group">
						<label for="documentTitle">Title</label>
						<input id="documentTitle" type="text" placeholder="Title" required />
						<small class="error" id="errDocumentTitle"></small>
					</div>

					<div class="form-group">
						<label for="issuanceDate">Issued</label>
						<input id="issuanceDate" type="date" required />
						<small id="issuanceDateFormatted">Formatted date: -</small>
						<small class="error" id="errIssuanceDate"></small>
					</div>

					<div class="form-group">
						<label for="revisionNumber">Revision</label>
						<input id="revisionNumber" type="number" min="0" value="0" />
						<small>Default: 0</small>
					</div>

					<div class="form-group">
						<label for="retrievalDate">Retrieved (Opt)</label>
						<input id="retrievalDate" type="date" />
						<small id="retrievalDateFormatted">Date: -</small>
					</div>
				</div>

				<div class="actions">
					<button id="resetButton" type="reset">Reset</button>
					<button id="submitButton" class="primary" type="submit">Save</button>
				</div>
			</form>

			<div id="successNotice" class="notice" role="status" aria-live="polite">Saved.</div>

			<section class="bulk" aria-label="Bulk add upload">
				<h2>Bulk Upload</h2>
				<p>CSV/XLSX columns: Doc Number, Copy Number, Copy Holder, Title, Issued.</p>
				<div class="bulk-row">
					<input id="bulkFile" type="file" accept=".csv,.xlsx,.xls" aria-label="Upload file" />
					<button id="downloadTemplate" type="button">Template</button>
					<button id="uploadBulk" class="primary" type="button">Upload</button>
				</div>
				<div id="bulkStatus" class="bulk-status" aria-live="polite"></div>
			</section>
		</section>
	</main>

	<script>
		const API_URL = "./api/staff.php";

		const refs = {
			form: document.getElementById("addDataForm"),
			documentNumber: document.getElementById("documentNumber"),
			copyNumber: document.getElementById("copyNumber"),
			copyHolder: document.getElementById("copyHolder"),
			documentTitle: document.getElementById("documentTitle"),
			issuanceDate: document.getElementById("issuanceDate"),
			revisionNumber: document.getElementById("revisionNumber"),
			retrievalDate: document.getElementById("retrievalDate"),
			issuanceDateFormatted: document.getElementById("issuanceDateFormatted"),
			retrievalDateFormatted: document.getElementById("retrievalDateFormatted"),
			errDocumentNumber: document.getElementById("errDocumentNumber"),
			errCopyNumber: document.getElementById("errCopyNumber"),
			errCopyHolder: document.getElementById("errCopyHolder"),
			errDocumentTitle: document.getElementById("errDocumentTitle"),
			errIssuanceDate: document.getElementById("errIssuanceDate"),
			submitButton: document.getElementById("submitButton"),
			successNotice: document.getElementById("successNotice"),
			bulkFile: document.getElementById("bulkFile"),
			downloadTemplate: document.getElementById("downloadTemplate"),
			uploadBulk: document.getElementById("uploadBulk"),
			bulkStatus: document.getElementById("bulkStatus")
		};

		const normalizeHeader = (value) => String(value || "").toLowerCase().replace(/[^a-z0-9]/g, "");

		const resolveField = (header) => {
			const key = normalizeHeader(header);
			if (["docnumber", "documentnumber", "docno"].includes(key)) return "documentNumber";
			if (["copynumber", "copyno"].includes(key)) return "copyNumber";
			if (["copyholder", "copyholdersname", "holder"].includes(key)) return "copyHolder";
			if (["title", "documenttitle", "manualname", "titlemanual", "documenttitlenameofmanual"].includes(key)) return "documentTitle";
			if (["issued", "issuance", "issuancedate"].includes(key)) return "issuanceDate";
			if (["revision", "revisionnumber"].includes(key)) return "revisionNumber";
			if (["retrieved", "retrieval", "retrievaldate"].includes(key)) return "retrievalDate";
			if (["retrievedrevision", "revisionofretrieveddoc", "revisionnoofretrieveddoc"].includes(key)) return "retrievedRevision";
			return "";
		};

		const toIsoDate = (value) => {
			const raw = String(value ?? "").trim();
			if (!raw) return "";

			if (/^\d+(\.\d+)?$/.test(raw)) {
				const serial = Number(raw);
				if (Number.isFinite(serial) && serial > 59) {
					const utcDays = Math.floor(serial - 25569);
					const utcValue = utcDays * 86400;
					const date = new Date(utcValue * 1000);
					if (!Number.isNaN(date.getTime())) {
						return date.toISOString().slice(0, 10);
					}
				}
			}

			if (/^\d{4}-\d{2}-\d{2}$/.test(raw)) return raw;

			const dmyMatch = raw.match(/^(\d{1,2})[\/-](\d{1,2})[\/-](\d{4})$/);
			if (dmyMatch) {
				const day = Number(dmyMatch[1]);
				const month = Number(dmyMatch[2]);
				const year = Number(dmyMatch[3]);
				if (day >= 1 && day <= 31 && month >= 1 && month <= 12) {
					return `${year}-${String(month).padStart(2, "0")}-${String(day).padStart(2, "0")}`;
				}
			}

			const parsed = new Date(raw);
			if (Number.isNaN(parsed.getTime())) return "";
			return parsed.toISOString().slice(0, 10);
		};

		const mapBulkRow = (row) => {
			const mapped = {
				documentNumber: "",
				copyNumber: "",
				copyHolder: "",
				documentTitle: "",
				issuanceDate: "",
				revisionNumber: "0",
				retrievalDate: "",
				retrievedRevision: ""
			};

			Object.entries(row).forEach(([header, cell]) => {
				const field = resolveField(header);
				if (!field) return;
				mapped[field] = String(cell ?? "").trim();
			});

			mapped.issuanceDate = toIsoDate(mapped.issuanceDate);
			mapped.retrievalDate = toIsoDate(mapped.retrievalDate);
			mapped.revisionNumber = String(Math.max(0, Number(mapped.revisionNumber || "0") || 0));
			return mapped;
		};

		const parseBulkFile = async (file) => {
			if (!window.XLSX) {
				throw new Error("File parser is unavailable.");
			}
			const data = await file.arrayBuffer();
			const workbook = XLSX.read(data, { type: "array" });
			const firstSheetName = workbook.SheetNames[0];
			if (!firstSheetName) {
				return [];
			}
			const sheet = workbook.Sheets[firstSheetName];
			const rows = XLSX.utils.sheet_to_json(sheet, { defval: "", raw: false });
			return rows.map(mapBulkRow);
		};

		const downloadTemplate = () => {
			const headers = ["Doc Number", "Copy Number", "Copy Holder", "Title", "Issued", "Revision", "Retrieved"];
			const sample = ["SEC-01", "CC No. 3", "Juan Dela Cruz", "Control Procedure", "2026-03-06", "0", ""];
			const csv = `${headers.join(",")}\n${sample.map((value) => `"${String(value).replace(/"/g, '""')}"`).join(",")}`;
			const blob = new Blob([csv], { type: "text/csv;charset=utf-8;" });
			const url = URL.createObjectURL(blob);
			const link = document.createElement("a");
			link.href = url;
			link.download = "bulk_add_template.csv";
			link.click();
			URL.revokeObjectURL(url);
		};

		const downloadFailedRowsCsv = (failedRows) => {
			if (!failedRows.length) {
				return;
			}

			const headers = ["Row", "Doc Number", "Copy Number", "Copy Holder", "Title", "Issued", "Reason"];
			const lines = [headers.join(",")];

			failedRows.forEach((item) => {
				const row = [
					item.row,
					item.data.documentNumber || "",
					item.data.copyNumber || "",
					item.data.copyHolder || "",
					item.data.documentTitle || "",
					item.data.issuanceDate || "",
					item.reason || ""
				].map((value) => `"${String(value ?? "").replace(/"/g, '""')}"`);

				lines.push(row.join(","));
			});

			const blob = new Blob([lines.join("\n")], { type: "text/csv;charset=utf-8;" });
			const url = URL.createObjectURL(blob);
			const link = document.createElement("a");
			link.href = url;
			link.download = `bulk_upload_failed_rows_${Date.now()}.csv`;
			link.click();
			URL.revokeObjectURL(url);
		};

		const uploadBulkRecords = async () => {
			refs.successNotice.classList.remove("show");
			refs.bulkStatus.textContent = "";
			const file = refs.bulkFile.files?.[0];
			if (!file) {
				refs.bulkStatus.textContent = "Choose a file first.";
				return;
			}

			refs.uploadBulk.disabled = true;
			refs.bulkStatus.textContent = "Reading file...";

			try {
				const rows = await parseBulkFile(file);
				if (!rows.length) {
					refs.bulkStatus.textContent = "No rows found.";
					return;
				}

				let saved = 0;
				const failedRows = [];

				for (let index = 0; index < rows.length; index += 1) {
					const row = rows[index];
					if (!row.documentNumber || !row.copyNumber || !row.copyHolder || !row.documentTitle || !row.issuanceDate) {
						failedRows.push({
							row: index + 2,
							data: row,
							reason: "Missing required fields"
						});
						continue;
					}

					try {
						await apiRequest("POST", row);
						saved += 1;
					} catch {
						failedRows.push({
							row: index + 2,
							data: row,
							reason: "API request failed"
						});
					}
				}

				if (!failedRows.length) {
					refs.bulkStatus.textContent = `Uploaded ${saved} record(s).`;
					alert(`File uploaded successfully. ${saved} record(s) added.`);
					refs.bulkFile.value = "";
					return;
				}

				downloadFailedRowsCsv(failedRows);

				const previewFailed = failedRows.slice(0, 8).map((item) => item.row).join(", ");
				const suffix = failedRows.length > 8 ? ", ..." : "";
				refs.bulkStatus.textContent = `Uploaded ${saved}. Failed rows: ${previewFailed}${suffix}`;
				if (saved > 0) {
					alert(`Upload completed. ${saved} record(s) added, ${failedRows.length} row(s) failed.`);
				} else {
					alert(`Upload failed. 0 record(s) added. Failed rows: ${previewFailed}${suffix}`);
				}
			} catch (error) {
				refs.bulkStatus.textContent = error.message || "Bulk upload failed.";
			} finally {
				refs.uploadBulk.disabled = false;
			}
		};

		const toReadableDate = (value) => {
			if (!value) return "-";
			const date = new Date(value);
			if (Number.isNaN(date.getTime())) return "-";
			return date.toLocaleDateString("en-GB", { day: "2-digit", month: "short", year: "numeric" });
		};

		const setDateFormatPreview = () => {
			refs.issuanceDateFormatted.textContent = `Date: ${toReadableDate(refs.issuanceDate.value)}`;
			refs.retrievalDateFormatted.textContent = `Date: ${toReadableDate(refs.retrievalDate.value)}`;
		};

		function clearErrors() {
			refs.errDocumentNumber.textContent = "";
			refs.errCopyNumber.textContent = "";
			refs.errCopyHolder.textContent = "";
			refs.errDocumentTitle.textContent = "";
			refs.errIssuanceDate.textContent = "";
		}

		function validateForm() {
			clearErrors();
			let isValid = true;

			if (!refs.documentNumber.value.trim()) {
				refs.errDocumentNumber.textContent = "Doc Number is required.";
				isValid = false;
			}
			if (!refs.copyNumber.value.trim()) {
				refs.errCopyNumber.textContent = "Copy Number is required.";
				isValid = false;
			}
			if (!refs.copyHolder.value.trim()) {
				refs.errCopyHolder.textContent = "Copy Holder is required.";
				isValid = false;
			}
			if (!refs.documentTitle.value.trim()) {
				refs.errDocumentTitle.textContent = "Title is required.";
				isValid = false;
			}
			if (!refs.issuanceDate.value) {
				refs.errIssuanceDate.textContent = "Issued date is required.";
				isValid = false;
			}

			return isValid;
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

		async function submitForm(event) {
			event.preventDefault();
			refs.successNotice.classList.remove("show");

			if (!validateForm()) {
				return;
			}

			const revisionValue = refs.revisionNumber.value.trim();
			const revisionNumber = revisionValue === "" ? "0" : String(Math.max(0, Number(revisionValue) || 0));
			refs.revisionNumber.value = revisionNumber;

			const recordPayload = {
				documentNumber: refs.documentNumber.value.trim(),
				copyNumber: refs.copyNumber.value.trim(),
				copyHolder: refs.copyHolder.value.trim(),
				documentTitle: refs.documentTitle.value.trim(),
				issuanceDate: refs.issuanceDate.value,
				revisionNumber,
				retrievalDate: refs.retrievalDate.value,
				retrievedRevision: ""
			};

			refs.submitButton.disabled = true;
			try {
				await apiRequest("POST", recordPayload);
				refs.form.reset();
				refs.revisionNumber.value = "0";
				setDateFormatPreview();
				clearErrors();
				refs.successNotice.textContent = "Saved.";
				refs.successNotice.classList.add("show");
			} catch (error) {
				alert(error.message || "Failed to save record.");
			} finally {
				refs.submitButton.disabled = false;
			}
		}

		refs.form.addEventListener("submit", submitForm);
		refs.form.addEventListener("reset", () => {
			setTimeout(() => {
				refs.revisionNumber.value = "0";
				clearErrors();
				setDateFormatPreview();
				refs.successNotice.classList.remove("show");
			}, 0);
		});
		refs.issuanceDate.addEventListener("change", setDateFormatPreview);
		refs.retrievalDate.addEventListener("change", setDateFormatPreview);
		refs.downloadTemplate.addEventListener("click", downloadTemplate);
		refs.uploadBulk.addEventListener("click", uploadBulkRecords);
		refs.bulkFile.addEventListener("change", () => {
			if (refs.bulkFile.files?.length) {
				uploadBulkRecords();
			}
		});

		setDateFormatPreview();
	</script>
</body>
</html>
