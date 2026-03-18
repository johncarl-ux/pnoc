<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>PNOC Inventory | Item Status Monitoring</title>
    <link rel="icon" href="qw.png" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <style>
        :root{--bg:#f6f8fb;--card:#fff;--muted:#64748b;--text:#0f172a;--accent:#2563eb;--green:#10b981;--yellow:#f59e0b;--red:#ef4444;--gray:#6b7280;--radius:12px;--sidebar:260px;--shadow:0 8px 30px rgba(2,6,23,0.06)}
        *{box-sizing:border-box}
        body{font-family:Inter,Segoe UI,system-ui,-apple-system,sans-serif;margin:0;background:var(--bg);color:var(--text)}
        .app{display:flex;min-height:100vh}
        .sidebar{width:var(--sidebar);background:var(--card);border-right:1px solid #e6edf3;padding:18px;position:fixed;inset:0 auto auto 0}
        .brand{display:flex;gap:10px;align-items:center;margin-bottom:12px}
        .brand img{width:36px;height:36px}
        .nav{margin-top:8px}
        .nav a{display:flex;align-items:center;gap:10px;padding:10px;border-radius:8px;color:var(--text);text-decoration:none;margin-bottom:6px}
        .nav a.active{background:linear-gradient(90deg,#eef4ff,#f6fbff);box-shadow:var(--shadow)}
        .main{flex:1;margin-left:var(--sidebar);padding:28px}
		.page-head{display:flex;justify-content:space-between;align-items:center}
		.page-head h1{margin:0;font-size:22px}
		.home-btn{display:inline-flex;align-items:center;gap:0.5rem;padding:8px 10px;border-radius:8px;background:#fff;border:1px solid #eef4fb;color:var(--text);text-decoration:none;font-weight:700}
		.home-btn:hover{transform:translateY(-2px);box-shadow:0 10px 30px rgba(2,6,23,0.06)}

        /* Summary cards */
        .cards{display:grid;grid-template-columns:repeat(5,1fr);gap:12px;margin-top:18px}
		.card[data-status]{cursor:pointer}
		.card.active{box-shadow:0 8px 30px rgba(2,6,23,0.08);border:2px solid rgba(37,99,235,0.12)}
        .card{background:var(--card);padding:14px;border-radius:var(--radius);box-shadow:var(--shadow);display:flex;gap:12px;align-items:center}
        .card .icon{width:46px;height:46;border-radius:10px;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700}
        .card .meta .num{font-size:18px;font-weight:700}
        .card .meta .label{font-size:12px;color:var(--muted)}

		/* layout */
		.layout{display:grid;grid-template-columns:1fr;gap:14px;margin-top:18px}
        .panel{background:var(--card);border-radius:12px;padding:14px;box-shadow:var(--shadow)}
        .filters{display:flex;gap:8px;align-items:center}
        .filters input,.filters select{padding:10px;border-radius:10px;border:1px solid #eef4fb;background:#fff}

        /* alerts and actions */
        .alerts{display:flex;gap:8px;margin-top:12px}
        .alert{padding:10px;border-radius:8px;border:1px solid #eef4fb;background:#fff;font-size:13px}

        /* table */
        .table-wrap{margin-top:14px}
        table{width:100%;border-collapse:collapse;background:transparent}
        thead th{position:sticky;top:0;background:var(--card);padding:12px;text-align:left;border-bottom:1px solid #eef4fb}
        tbody tr{background:var(--card);border-bottom:1px solid #f3f6fb}
        tbody tr:nth-child(even){background:#fbfdff}
        td{padding:12px;vertical-align:middle}
        tr:hover td{background:#fcfeff}
		.status-badge{display:inline-block;padding:6px 10px;border-radius:999px;color:#fff;font-weight:700;font-size:13px}
		.s-usable{background:linear-gradient(90deg,var(--green),#059669)}.s-repair{background:linear-gradient(90deg,var(--yellow),#d97706);color:#06121a}.s-retired{background:linear-gradient(90deg,var(--red),#dc2626)}.s-missing{background:#374151}
        .expand{display:none;background:#fbfdff;padding:12px;border-top:1px solid #eef4fb}
        .bulk{display:none;align-items:center;gap:12px;padding:10px;border-radius:8px;background:linear-gradient(90deg,#fff,#fbfdff);box-shadow:var(--shadow);margin-bottom:12px}

        /* responsiveness */
        @media(max-width:1100px){.cards{grid-template-columns:repeat(2,1fr)}.layout{grid-template-columns:1fr}}
		@media(max-width:700px){.cards{grid-template-columns:1fr}.filters{flex-direction:column;align-items:stretch}}

		/* Unified sidebar override: always-expanded */
		.sidebar {
			width: 260px;
			background: linear-gradient(180deg,#ffffff,#fbfdff);
			position: fixed;
			top: 0;
			left: 0;
			height: 100vh;
			font-family: 'Inter', system-ui, -apple-system, sans-serif;
			font-size: 14px;
			box-shadow: 0 6px 18px rgba(2,6,23,0.06);
			overflow: hidden;
			z-index: 200;
		}

		.sidebar .brand-link { display:flex; align-items:center; gap:0.75rem; padding:1rem; text-decoration:none; color:inherit; }
		.sidebar .brand-link img { width:36px; height:36px; border-radius:6px; object-fit:cover }
		.sidebar .brand-text { opacity:1; transform:translateX(0); white-space:nowrap; }

		.sidebar-nav { padding:0.75rem; }
		.nav-section { margin-top:0.75rem; padding-top:0.5rem; border-top:1px dashed rgba(0,0,0,0.04); }
		.nav-section-title { font-size:0.65rem; font-weight:700; color:var(--muted); padding-left:0.5rem; margin-bottom:0.5rem; text-transform:uppercase; letter-spacing:0.06em }

		.nav-link { display:flex; align-items:center; gap:0.75rem; padding:0.65rem; border-radius:10px; color:var(--text-muted); text-decoration:none; font-weight:600; transition:all .18s ease; margin-bottom:0.2rem }
		.nav-icon { width:36px; height:36px; border-radius:8px; background:transparent; display:flex; align-items:center; justify-content:center; font-size:1.05rem }
		.nav-label { opacity:1; transform:translateX(0); white-space:nowrap }
		.nav-link:hover { background: #fbfdff; color: var(--text-dark); }

		.nav-link.active { background: linear-gradient(90deg,var(--primary,#4f46e5),var(--primary-light,#6366f1)); color: white; position:relative }
		.nav-link.active::before { content: ''; position:absolute; left:0; top:8px; bottom:8px; width:4px; background:var(--primary,#4f46e5); border-radius:4px }

		@media (max-width: 768px) { .sidebar { transform: translateX(-100%); } .main { margin-left: 0; } }

	</style>
</head>
<body>
    <div class="app">
		<aside class="sidebar" aria-label="Primary Navigation">
			<div class="sidebar-header">
				<a href="index.html" class="sidebar-brand">
					<img src="qw.png" alt="PNOC Logo" />
					<div>
						<div class="sidebar-brand-text">PNOC Inventory</div>
						<div class="sidebar-brand-sub">Management System</div>
					</div>
				</a>
			</div>
			<nav class="sidebar-nav" role="navigation">
				<div class="nav-section">
					<div class="nav-section-title">Main Menu</div>
					<a href="inventory-dashboard.php" class="nav-link"><span class="nav-icon">⌂</span><span class="nav-label">Dashboard</span></a>
					<a href="bentaco-inventory.php" class="nav-link"><span class="nav-icon">☐</span><span class="nav-label">BENTACO Inventory</span></a>
					<a href="iot-inventory.php" class="nav-link"><span class="nav-icon">◎</span><span class="nav-label">IOT Inventory</span></a>
				</div>
				<div class="nav-section">
					<div class="nav-section-title">Management</div>
					<a href="location-management.php" class="nav-link"><span class="nav-icon">⌖</span><span class="nav-label">Location Management</span></a>
					<!-- Item Allocation removed from sidebar -->
					<a href="item-status-monitoring.php" class="nav-link active"><span class="nav-icon">◉</span><span class="nav-label">Item Status Monitoring</span></a>
				</div>
				<div class="nav-section">
					<div class="nav-section-title">Analytics</div>
					<a href="report-generation.php" class="nav-link"><span class="nav-icon">☰</span><span class="nav-label">Reports</span></a>
				</div>
			</nav>
		</aside>

        <main class="main">
			<div class="page-head">
				<div><h1>Item Status Monitoring</h1><div style="color:var(--muted);margin-top:6px">Overview and health of inventory</div></div>
				<div style="display:flex;gap:8px;align-items:center"><a href="index.html" class="home-btn">Home</a><button onclick="render()" style="padding:10px;border-radius:8px">Refresh</button><button style="background:var(--accent);color:#fff;padding:10px;border-radius:8px;border:0">New Item</button></div>
			</div>

			<div class="cards">
				<div class="card" data-status="all"><div class="icon" style="background:linear-gradient(90deg,#60a5fa,#3b82f6)">T</div><div class="meta"><div class="num" id="totalAssets">0</div><div class="label">Active Assets</div></div></div>
				<div class="card" data-status="Usable"><div class="icon" style="background:linear-gradient(90deg,#34d399,#10b981)">U</div><div class="meta"><div class="num" id="usableCount">0</div><div class="label">Usable</div></div></div>
				<div class="card" data-status="Needs Repair"><div class="icon" style="background:linear-gradient(90deg,#facc15,#f59e0b)">R</div><div class="meta"><div class="num" id="repairCount">0</div><div class="label">Needs Repair</div></div></div>
				<div class="card" data-status="Retired"><div class="icon" style="background:linear-gradient(90deg,#9ca3af,#6b7280)">R</div><div class="meta"><div class="num" id="retiredCount">0</div><div class="label">Retired</div></div></div>
				<div class="card" data-status="Missing"><div class="icon" style="background:linear-gradient(90deg,#94a3b8,#6b7280)">M</div><div class="meta"><div class="num" id="missingCount">0</div><div class="label">Missing</div></div></div>
			</div>

			<div class="layout">
				<div>
					<div class="panel" style="margin-bottom:12px">
                        <div style="display:flex;justify-content:space-between;align-items:center">
                            <div class="filters" style="flex:1">
                                <input id="search" placeholder="Search item, number, notes, serial" />
                                <select id="group"><option value="all">Group: All</option><option value="BENTACO">BENTACO</option><option value="IOT">IOT</option></select>
								<select id="status"><option value="all">Status: All</option><option value="Usable">Usable</option><option value="Needs Repair">Needs Repair</option><option value="Retired">Retired</option><option value="Missing">Missing</option></select>
								<!-- allocation filter removed (no longer used) -->
                                <button id="clear">Clear Filters</button>
                            </div>
                            <div style="display:flex;gap:8px;margin-left:12px">
                                <button id="exportCsv">Export CSV</button>
                                <button id="exportXls">Export Excel</button>
                                <button id="print">Print</button>
                            </div>
                        </div>
                    </div>

                    <div class="bulk" id="bulkBar"><div id="bulkCount">0 selected</div><div style="margin-left:auto;display:flex;gap:8px"><button onclick="bulkUpdate()">Update Status</button><button onclick="bulkAllocate()">Allocate</button><button onclick="bulkMove()">Move</button><button onclick="bulkExport()">Export</button></div></div>

                    <div class="panel table-wrap">
                        <table id="itemsTable">
                            <thead>
									<tr>
										<th style="width:36px"><input id="selectAll" type="checkbox"/></th>
										<th>Group</th>
										<th>Item Number</th>
										<th>Item Description</th>
										<th>Item Status</th>
										<th>Actions</th>
									</tr>
                            </thead>
							<tbody id="tbody">
								<tr><td colspan="6">Loading…</td></tr>
							</tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        const KEYS = ['pnoc_inventory_bentaco_v1','pnoc_inventory_iot_v1'];
        let all = [];
        let view = [];

        function load(){ all = []; KEYS.forEach(k=>{ try{ const r=localStorage.getItem(k); const arr=r?JSON.parse(r):[]; if(Array.isArray(arr)) arr.forEach(it=>all.push(Object.assign({},it,{source: k.includes('iot')? 'IOT':'BENTACO'}))); }catch(e){}}); }

		function summarize(){
			const total = all.length;
			const stat = { Usable:0, 'Needs Repair':0, Retired:0, Missing:0 };
			all.forEach(i=>{ const s = (i.itemStatus||i.status||'').toLowerCase(); if(s==='usable') stat.Usable++; else if(s.includes('repair')||s.includes('needs')) stat['Needs Repair']++; else if(s.includes('retire')||s.includes('defect')||s.includes('unusable')) stat.Retired++; else stat.Missing++; });
			document.getElementById('totalAssets').textContent = total; document.getElementById('usableCount').textContent = stat.Usable; document.getElementById('repairCount').textContent = stat['Needs Repair']; document.getElementById('retiredCount') && (document.getElementById('retiredCount').textContent = stat.Retired); document.getElementById('missingCount').textContent = stat.Missing;
		}

		// Chart display removed — renderChart no longer used

		function apply(){
			const q=document.getElementById('search').value.toLowerCase(); const g=document.getElementById('group').value; const s=document.getElementById('status').value;
            view = all.filter(it=>{ if(g!=='all' && (it.source||'')!==g) return false; if(s!=='all' && ((it.itemStatus||it.status||'') !== s)) return false; if(q){ const text = ((it.itemDescription||'')+' '+(it.propertyNumber||it.itemId||'')+' '+(it.serialNumber||it.serial||'')+' '+(it.notes||'')).toLowerCase(); if(text.indexOf(q)===-1) return false; } return true; });
			document.getElementById('tbody').innerHTML = view.length ? view.map((it,idx)=>`<tr><td><input class="sel" data-idx="${idx}" type="checkbox"/></td><td>${it.source||''}</td><td>${it.propertyNumber||it.itemId||''}</td><td>${(it.itemDescription||it.item_description||it.description||'')}</td><td><span class="status-badge ${statusClass(it)}">${escapeHtml(it.itemStatus||it.status||'Missing')}</span></td><td><select class="action-select" onchange="changeStatusFromSelect(${idx}, this.value)"><option value="">Set status</option><option value="Usable">Usable</option><option value="Retired">Retired</option><option value="Missing">Missing</option></select></td></tr><tr class="expand"><td colspan="6">${expandHtml(it)}</td></tr>`).join('') : '<tr><td colspan="6" style="padding:18px;text-align:center;color:var(--muted)">No items found.</td></tr>';
            attachSelectors(); document.getElementById('resultCount') && (document.getElementById('resultCount').textContent = view.length);
        }

		function statusClass(it){ const s=(it.itemStatus||it.status||'').toLowerCase(); if(s==='usable') return 's-usable'; if(s.includes('repair')||s.includes('needs')) return 's-repair'; if(s.includes('retire')||s.includes('defect')||s.includes('unusable')) return 's-retired'; return 's-missing'; }
        function escapeHtml(v){ return String(v||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;'); }
        function expandHtml(it){ return `<div style="display:grid;grid-template-columns:1fr 1fr 1fr 260px;gap:12px"><div><strong>Serial No</strong><div>${escapeHtml(it.serialNumber||it.serial_number||'-')}</div><strong style="margin-top:8px">Assigned</strong><div>${escapeHtml(it.assignedTo||it.user||'-')}</div></div><div><strong>Purchase Date</strong><div>${escapeHtml(it.purchaseDate||it.purchase_date||'-')}</div><strong style="margin-top:8px">Warranty</strong><div>${escapeHtml(it.warranty||'-')}</div></div><div><strong>Notes</strong><div style="color:var(--muted)">${escapeHtml((it.notes||'').substring(0,300))}</div></div><div><strong>Status History</strong><div style="color:var(--muted);margin-top:6px">${sampleHistory(it)}</div></div></div>` }
        function sampleHistory(it){ return `Apr 10 – ${it.itemStatus||'Usable'}<br>Apr 15 – Under Repair<br>Apr 20 – Usable`; }

        function attachSelectors(){ document.querySelectorAll('.sel').forEach(el=>el.onchange=onSel); document.getElementById('selectAll').onchange=function(){ document.querySelectorAll('.sel').forEach(s=>s.checked=this.checked); onSel(); } }
        function onSel(){ const n = document.querySelectorAll('.sel:checked').length; const bar=document.getElementById('bulkBar'); if(n) { bar.style.display='flex'; document.getElementById('bulkCount').textContent = n+' selected'; } else bar.style.display='none'; }

		function viewItem(idx){ const it=view[idx]; alert(`${it.source} ${it.propertyNumber||it.itemId}\n${it.itemDescription||''}\nStatus: ${it.itemStatus||it.status||'Missing'}`); }
		function changeStatus(idx){ const it=view[idx]; const v=prompt('Set status (Usable, Needs Repair, Retired, Missing)', it.itemStatus||it.status||'Usable'); if(v===null) return; it.itemStatus=v; persist(it); render(); }
		function changeStatusFromSelect(idx, value){ if(!value) return; const it = view[idx]; if(!it) return; it.itemStatus = value; persist(it); render(); }

		function setStatusTab(status){
			const s = (status||'').toString();
			document.querySelectorAll('.card[data-status]').forEach(c=>{
				const ds = c.getAttribute('data-status')||'all';
				if(ds.toLowerCase() === (s||'all').toLowerCase()) c.classList.add('active'); else c.classList.remove('active');
			});
			const statusEl = document.getElementById('status'); if(statusEl) statusEl.value = (s==='all'?'all':s);
		}

		function attachStatusTabs(){
			document.querySelectorAll('.card[data-status]').forEach(c=>{
				c.addEventListener('click', ()=>{
					const ds = c.getAttribute('data-status') || 'all';
					const isActive = c.classList.contains('active');
					if(isActive) {
						setStatusTab('all');
					} else {
						setStatusTab(ds);
					}
					apply();
				});
			});
		}

        function persist(it){ try{ const key = (it.source==='IOT')? KEYS[1] : KEYS[0]; const raw = localStorage.getItem(key); const arr = raw?JSON.parse(raw):[]; const idx = arr.findIndex(x=> (x.propertyNumber||x.itemId) === (it.propertyNumber||it.itemId)); if(idx>-1) arr[idx]=Object.assign({},arr[idx],it); else arr.push(it); localStorage.setItem(key,JSON.stringify(arr)); }catch(e){console.error(e)} }

        function bulkUpdate(){ alert('Bulk update flow (placeholder)'); }
        function bulkAllocate(){ alert('Bulk allocate (placeholder)'); }
        function bulkMove(){ alert('Bulk move (placeholder)'); }
        function bulkExport(){ alert('Bulk export (placeholder)'); }

		function exportCSV(){ const rows = view.length? view: all; const cols = ['Group','Item Number','Item Description','Item Status']; const csv = [cols.join(',')].concat(rows.map(r=>[r.source,(r.propertyNumber||r.itemId||''),(r.itemDescription||r.description||''),(r.itemStatus||r.status||'')].map(v=>`"${String(v||'').replace(/"/g,'""')}"`).join(','))).join('\n'); const b=new Blob([csv],{type:'text/csv'}); const url=URL.createObjectURL(b); const a=document.createElement('a'); a.href=url; a.download='items-export.csv'; a.click(); URL.revokeObjectURL(url); }

		document.getElementById('search').addEventListener('input',apply); document.getElementById('group').addEventListener('change',apply); document.getElementById('status').addEventListener('change',()=>{ setStatusTab(document.getElementById('status').value); apply(); }); document.getElementById('clear').addEventListener('click',()=>{document.getElementById('search').value='';document.getElementById('group').value='all';document.getElementById('status').value='all'; setStatusTab('all'); apply();});
        document.getElementById('exportCsv').addEventListener('click',exportCSV); document.getElementById('exportXls').addEventListener('click',()=>alert('Export Excel - placeholder')); document.getElementById('print').addEventListener('click',()=>window.print());

		function render(){ load(); summarize(); apply(); setStatusTab(document.getElementById('status')?document.getElementById('status').value:'all'); }
		attachStatusTabs();
		render();
    </script>
</body>
</html>
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
			--compact-padding: 0.28rem;
			--table-min-width: 900px;
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
			.sidebar-brand-text { font-weight: 700; font-size: 0.9rem; }
			.sidebar-brand-sub { font-size: 0.7rem; color: var(--text-muted); font-weight: 400; }

			/* Sidebar text color override: make all sidebar text black */
			.sidebar { color: #000; }
			.sidebar .sidebar-brand-text,
			.sidebar .sidebar-brand-sub,
			.sidebar .nav-link,
			.sidebar .nav-section-title { color: #000; }
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
			padding: 0.36rem 0.5rem;
			border-radius: 10px;
			border: 1px solid var(--border-color);
			font: inherit;
			font-size: 0.84rem;
			background: #fff;
			transition: all 0.16s ease;
			box-shadow: 0 1px 0 rgba(16,24,40,0.03);
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
		table { width: 100%; border-collapse: collapse; min-width: var(--table-min-width); background: #fff; }
		th, td {
			border-bottom: 1px solid #eef4fb;
			border-right: 1px solid #f3f6fb;
			padding: var(--compact-padding) 0.42rem;
			font-size: 0.80rem;
			text-align: left;
			vertical-align: middle;
		}
		thead th { background: linear-gradient(90deg, rgba(99,102,241,0.06), rgba(99,102,241,0.02)); white-space: nowrap; position: sticky; top: 0; z-index: 3; color: var(--text-dark); }
		th.sortable { cursor: pointer; user-select: none; }
		th.sortable:hover { background: rgba(99,102,241,0.04); }
		tr:nth-child(even) td { background: #fbfdff; }
		tr:hover td { background: #fcfeff; }
		/* Ensure each table row has at least 100px height */
		tbody tr { height: 100px; }
		tbody td { height: 100px; max-height: 140px; vertical-align: middle; }
		tbody td > * { display: inline-flex; align-items: center; gap: 0.4rem; }
		.badge {
			display: inline-block;
			padding: 0.12rem 0.36rem;
			border-radius: 999px;
			font-size: 0.72rem;
			font-weight: 700;
			border: 1px solid rgba(0,0,0,0.06);
			background: rgba(99,102,241,0.06);
			color: var(--primary);
		}
		.badge.status-usable { background: rgba(16,185,129,0.08); border-color: rgba(16,185,129,0.15); color: var(--emerald); }
		.badge.status-maintenance { background: rgba(245,158,11,0.08); border-color: rgba(245,158,11,0.15); color: var(--amber); }
		.badge.status-retired { background: #f1f3f5; border-color: #e6e9ec; color: #5f6872; }
		.badge.status-usable { background: rgba(16,185,129,0.1); border-color: rgba(16,185,129,0.3); color: var(--emerald); }
		.badge.status-maintenance { background: rgba(245,158,11,0.1); border-color: rgba(245,158,11,0.3); color: var(--amber); }
			/* status-damaged removed — damaged rows are remapped to Retired */
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

		/* Styled action select to visually match the summary cards / pills */
		.action-select {
			-webkit-appearance: none;
			appearance: none;
			padding: 0.36rem 0.6rem;
			border-radius: 999px;
			border: 1px solid var(--border-color);
			background: linear-gradient(90deg,#fff,#fbfdff);
			font-weight: 700;
			color: var(--text-dark);
			cursor: pointer;
			min-width: 140px;
			box-shadow: 0 2px 8px rgba(16,24,40,0.03);
		}
		.action-select:focus { outline: none; box-shadow: 0 0 0 4px rgba(99,102,241,0.08); border-color: var(--primary); }
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


		/* Unified sidebar override: always-expanded */
		.sidebar {
			width: 240px;
			background: linear-gradient(180deg,#ffffff,#fbfdff);
			position: fixed;
			top: 0;
			left: 0;
			height: 100vh;
			box-shadow: 0 6px 18px rgba(2,6,23,0.06);
			overflow: hidden;
			z-index: 200;
		}

		.sidebar .brand-link { display:flex; align-items:center; gap:0.75rem; padding:1rem; text-decoration:none; color:inherit; }
		.sidebar .brand-link img { width:36px; height:36px; border-radius:6px; object-fit:cover }
		.sidebar .brand-text { opacity:1; transform:translateX(0); white-space:nowrap; }

		.sidebar-nav { padding:0.75rem; }
		.nav-section { margin-top:0.75rem; padding-top:0.5rem; border-top:1px dashed rgba(0,0,0,0.04); }
		.nav-section-title { font-size:0.65rem; font-weight:700; color:var(--text-muted); padding-left:0.5rem; margin-bottom:0.5rem; text-transform:uppercase; letter-spacing:0.06em }

			.nav-link { display:flex; align-items:center; gap:0.75rem; padding:0.65rem; border-radius:10px; color:var(--text-muted); text-decoration:none; font-weight:600; font-size:0.82rem; transition:all .18s ease; margin-bottom:0.2rem }
		.nav-icon { width:36px; height:36px; border-radius:8px; background:transparent; display:flex; align-items:center; justify-content:center; font-size:1.05rem }
		.nav-label { opacity:1; transform:translateX(0); white-space:nowrap }
		.nav-link:hover { background: var(--bg-body); color: var(--text-dark); }

		.nav-link.active { background: var(--primary-glow); color: var(--primary); position:relative }
		.nav-link.active::before { content: ''; position:absolute; left:0; top:8px; bottom:8px; width:4px; background:var(--primary); border-radius:4px }

		@media (max-width: 768px) { .sidebar { transform: translateX(-100%); } .main-content { margin-left: 0; } }

	</style>
</head>
<body>
	<div class="app-layout">
		<aside class="sidebar" aria-label="Primary Navigation">
			<div>
				<a href="index.html" class="brand-link">
					<img src="qw.png" alt="PNOC" />
					<div class="brand-text">
						<div style="font-weight:700;">PNOC Inventory</div>
						<div style="font-size:0.75rem;color:var(--text-muted);">Management System</div>
					</div>
				</a>
			</div>
			<nav class="sidebar-nav" role="navigation">
				<div class="nav-section">
					<div class="nav-section-title">Main Menu</div>
					<a href="inventory-dashboard.php" class="nav-link"><span class="nav-icon">⌂</span><span class="nav-label">Dashboard</span></a>
					<a href="bentaco-inventory.php" class="nav-link"><span class="nav-icon">☐</span><span class="nav-label">BENTACO Inventory</span></a>
					<a href="iot-inventory.php" class="nav-link"><span class="nav-icon">◎</span><span class="nav-label">IOT Inventory</span></a>
				</div>
				<div class="nav-section">
					<div class="nav-section-title">Management</div>
					<a href="location-management.php" class="nav-link"><span class="nav-icon">⊕</span><span class="nav-label">Location Management</span></a>
					<!-- Item Allocation removed from sidebar -->
					<a href="item-status-monitoring.php" class="nav-link active"><span class="nav-icon">◉</span><span class="nav-label">Item Status Monitoring</span></a>
				</div>
				<div class="nav-section">
					<div class="nav-section-title">Analytics</div>
					<a href="report-generation.php" class="nav-link"><span class="nav-icon">☰</span><span class="nav-label">Reports</span></a>
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
					<div class="stat-label">Active</div>
					<div class="stat-value" id="statActiveAssets">0</div>
					<div class="stat-note">Monitored</div>
				</div>
				<div class="stat-card">
					<div class="stat-label">Top</div>
					<div class="stat-value" id="statWinningStatus">-</div>
					<div class="stat-note">Most common</div>
				</div>
				<div class="stat-card">
					<div class="stat-label">Usability</div>
					<div class="usability-wrap">
						<div class="usability-gauge" id="usabilityGauge"></div>
						<div class="usability-info">
							<div class="usability-percent" id="usabilityPercent">0%</div>
							<div class="usability-level" id="usabilityLevel">No data</div>
						</div>
					</div>
				</div>
				<div class="stat-card">
					<div class="stat-label">Allocated</div>
					<div class="stat-value" id="statAllocatedItems">0</div>
					<div class="stat-note">Assigned</div>
				</div>
			</div>

			<div class="section-card">
				<div class="toolbar">
					<input id="searchInput" type="text" placeholder="Search item, status, notes" />
					<select id="groupFilter">
						<option value="all">Group: All</option>
						<option value="BENTACO">BENTACO</option>
						<option value="IOT">IOT</option>
					</select>
					<select id="statusFilter">
						<option value="all">Status: All</option>
						<option value="Usable">Usable</option>
						<option value="Under Maintenance">Under Maintenance</option>
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
								<th class="sortable" data-sort="itemStatus">Item Status</th>
							</tr>
						</thead>
						<tbody id="tableBody">
							<tr><td colspan="4">No records.</td></tr>
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
			// Treat damaged/defective/unusable entries as Retired to remap legacy data
			if (text === "damaged" || text === "not usable" || text === "unusable" || text === "defective") return "Retired";
			return "Usable";
		}

		function statusBadgeClass(status) {
			const normalized = normalizeStatus(status).toLowerCase();
			if (normalized === "usable") return "status-usable";
			if (normalized === "under maintenance") return "status-maintenance";
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
				const input = prompt("Set Status (Usable, Under Maintenance, Retired)", row.itemStatus || "Usable");
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
				refs.tableBody.innerHTML = "<tr><td colspan='4'>No records.</td></tr>";
				return;
			}

			// Collect all unique statuses from all rows and always include 'Retired' (exclude 'Damaged' from options)
			const mustHaveStatuses = ["Retired"];
			const allStatuses = Array.from(new Set([
				...rows.map(r => normalizeStatus(r.itemStatus)),
				...mustHaveStatuses
			])).filter(Boolean).filter(s => String(s) !== 'Damaged');
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
