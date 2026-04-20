<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>PNOC Staff | Document View</title>
	<meta name="description" content="Simple read-only document view." />
	<link rel="icon" type="image/png" href="qw.png" />
	<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
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
			grid-template-columns: 280px minmax(0, 1fr);
			gap: 1.1rem;
			align-items: start;
		}

		.sidebar {
			position: sticky;
			top: 1rem;
			align-self: start;
			background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
			border: 1px solid var(--pnoc-border);
			border-radius: 16px;
			padding: 1rem;
			box-shadow: 0 12px 28px rgba(27, 36, 48, 0.06);
		}

		.sidebar h2 {
			font-family: 'Playfair Display', Georgia, serif;
			font-size: 1.15rem;
			font-weight: 600;
			margin-bottom: 0.85rem;
			color: var(--pnoc-dark);
		}

		.feature-nav {
			display: grid;
			grid-template-columns: 1fr;
			gap: 0.7rem;
		}

		.feature-link {
			display: flex;
			align-items: center;
			gap: 0.75rem;
			padding: 0.9rem 0.95rem;
			border-radius: 12px;
			border: 1px solid #dbe4ee;
			background: #ffffff;
			text-decoration: none;
			color: var(--pnoc-dark);
			font-weight: 600;
			transition: transform .16s ease, box-shadow .2s ease, border-color .2s ease, background .2s ease;
		}

		.feature-link:hover {
			transform: translateY(-1px);
			border-color: #c7d6e6;
			background: #fbfdff;
			box-shadow: 0 10px 22px rgba(27, 36, 48, 0.08);
		}

		.feature-icon {
			width: 2.15rem;
			height: 2.15rem;
			border-radius: 10px;
			display: inline-flex;
			align-items: center;
			justify-content: center;
			background: linear-gradient(180deg, #eef5ff, #e4effc);
			flex: 0 0 auto;
		}

		.content-area {
			min-width: 0;
			display: grid;
			gap: 1rem;
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
			font-family: 'Playfair Display', Georgia, serif;
			font-size: clamp(1.2rem, 2.8vw, 1.8rem);
			margin-bottom: 0.35rem;
			font-weight: 600;
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
			cursor: default;
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

			.sidebar {
				padding: 0.9rem;
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
			<a class="link" href="index.html" aria-label="Back to home">← Back to Home</a>
		</div>
	</header>

	<main class="container">
		<div class="workspace">
			<aside class="sidebar" aria-label="Feature sidebar">
				<h2>Features</h2>
				<nav class="feature-nav">
					<a class="feature-link" href="overview-dashboard.php">
						<span class="feature-icon" aria-hidden="true">🏠</span>
						<span>Overview</span>
					</a>
					<a class="feature-link" href="tools-utilities.php">
						<span class="feature-icon" aria-hidden="true">🛠️</span>
						<span>Tools</span>
					</a>
					<a class="feature-link" href="records-management.php">
						<span class="feature-icon" aria-hidden="true">📋</span>
						<span>Records</span>
					</a>

					<a class="feature-link" href="add-data-create.php">
						<span class="feature-icon" aria-hidden="true">➕</span>
						<span>Add Data</span>
					</a>
					<a class="feature-link" href="export-reports.php">
						<span class="feature-icon" aria-hidden="true">📤</span>
						<span>Export</span>
					</a>
				</nav>
			</aside>

			<div class="content-area">
				<section class="hero" id="overview">
					<div class="hero-grid">
						<div class="hero-copy">
							<h1>Document View</h1>
							<p>
								Read-only table for quick document checks.
							</p>
						</div>
						<img src="as.jpg" alt="Document tracker preview" onerror="this.style.display='none'" />
					</div>
				</section>

				<section class="compiler" id="compiler-tools" aria-label="Staff document compiler tools">
			<div class="meta" id="meta">Loading records...</div>

			<div class="table-wrap" id="records-table">
				<table aria-label="Document compiler grid">
					<thead>
						<tr>
							<th>Doc Number</th>
							<th>Copy Number</th>
							<th>Copy Holder</th>
							<th>Title / Manual</th>
							<th>Issued</th>
							<th>Revision</th>
							<th>Retrieved</th>
							<th>Retrieved Rev.</th>
						</tr>
					</thead>
					<tbody id="tableBody">
						<tr>
							<td colspan="8">No records to display.</td>
						</tr>
					</tbody>
				</table>
			</div>
				</section>
			</div>
		</div>
	</main>

	<script>
		const API_URL = "./api/staff.php";
		const refs = {
			meta: document.getElementById("meta"),
			tableBody: document.getElementById("tableBody")
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

		function renderRows(records) {
			if (!records.length) {
				refs.tableBody.innerHTML = "<tr><td colspan='8'>No records to display.</td></tr>";
				refs.meta.textContent = "0 records";
				return;
			}

			const rows = records.map((record) => `
				<tr>
					<td>${escapeHtml(record.documentNumber)}</td>
					<td>${escapeHtml(record.copyNumber)}</td>
					<td>${escapeHtml(record.copyHolder)}</td>
					<td>${escapeHtml(record.documentTitle)}</td>
					<td>${escapeHtml(formatDate(record.issuanceDate))}</td>
					<td>${escapeHtml(record.revisionNumber)}</td>
					<td>${escapeHtml(formatDate(record.retrievalDate))}</td>
					<td>${escapeHtml(record.retrievedRevision)}</td>
				</tr>
			`);

			refs.tableBody.innerHTML = rows.join("");
			refs.meta.textContent = `${records.length} record(s)`;
		}

		async function loadRecords() {
			refs.meta.textContent = "Loading records...";
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
				renderRows(rows.map(normalizeRecord));
			} catch (error) {
				refs.tableBody.innerHTML = "<tr><td colspan='8'>No records to display.</td></tr>";
				refs.meta.textContent = `Can’t load records: ${error.message || "API unavailable."}`;
			}
		}

		loadRecords();
	</script>
</body>
</html>
