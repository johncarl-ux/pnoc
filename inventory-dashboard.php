<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>PNOC Inventory | Dashboard</title>
    <meta name="description" content="Inventory dashboard with quick statistics and chart insights." />
    <link rel="icon" type="image/png" href="qw.png" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary: #4f46e5;
            --primary-light: #6366f1;
            --primary-glow: rgba(79, 70, 229, 0.15);
            --bg-body: #f8f9fa;
            --card-bg: #ffffff;
            --text-dark: #111827;
            --text-muted: #6b7280;
            --text-light: #9ca3af;
            --sidebar-width: 260px;
            --shadow-sm: 0 1px 2px rgba(0,0,0,0.04);
            --shadow-md: 0 4px 12px rgba(0,0,0,0.06);
            --shadow-lg: 0 8px 24px rgba(0,0,0,0.08);
            --shadow-hover: 0 12px 28px rgba(0,0,0,0.12);
            --radius-md: 12px;
            --radius-lg: 16px;
            --emerald: #10b981;
            --rose: #f43f5e;
            --amber: #f59e0b;
            --sky: #0ea5e9;
            --violet: #8b5cf6;
            --slate: #64748b;
            --border-color: #e2e8f0;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background: var(--bg-body);
            color: var(--text-dark);
            line-height: 1.5;
            min-height: 100vh;
            font-weight: 400;
        }

        .app-layout { display: flex; min-height: 100vh; }

        /* Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--card-bg);
            box-shadow: var(--shadow-sm);
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            z-index: 100;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            font-size: 14px;
        }

        .sidebar-header {
            padding: 1.5rem 1.25rem;
            border-bottom: 1px solid #f1f3f4;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: var(--text-dark);
        }

        .sidebar-brand img { width: 32px; height: 32px; object-fit: contain; }
        .sidebar-brand-text { font-weight: 700; font-size: 0.95rem; }
        .sidebar-brand-sub { font-size: 0.65rem; color: var(--text-muted); font-weight: 400; }

        /* Sidebar text color override: make all sidebar text black */
        .sidebar { color: #000; }
        .sidebar .sidebar-brand-text,
        .sidebar .sidebar-brand-sub,
        .sidebar .nav-link,
        .sidebar .nav-section-title { color: #000; }

        .sidebar-nav { padding: 1.25rem 0.75rem; }
        .nav-section { margin-bottom: 1.75rem; }
        .nav-section-title {
            font-size: 0.65rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--text-light);
            margin-bottom: 0.5rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.6rem 0.75rem;
            border-radius: 10px;
            text-decoration: none;
            color: var(--text-muted);
            font-size: 0.85rem;
            font-weight: 500;
            transition: all 0.2s ease;
            margin-bottom: 0.15rem;
        }

        .nav-link:hover { 
            background: var(--bg-body); 
            color: var(--text-dark);
        }
        
        .nav-link.active { 
            background: var(--primary-glow); 
            color: var(--primary); 
        }
        
        .nav-link.active .nav-icon { 
            background: var(--primary); 
            color: white;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
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
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            padding: 2rem 2.5rem;
        }

        .page-header { margin-bottom: 2rem; }
        .page-title { 
            font-size: 1.65rem; 
            font-weight: 700; 
            color: var(--text-dark); 
            margin-bottom: 0.35rem;
            letter-spacing: -0.02em;
        }
            .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
            .home-btn {
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                padding: 0.45rem 0.75rem;
                border-radius: 8px;
                background: var(--card-bg);
                border: 1px solid var(--border-color);
                color: var(--text-dark);
                text-decoration: none;
                font-weight: 600;
                box-shadow: 0 6px 18px rgba(2,6,23,0.06);
                transition: transform 140ms ease, box-shadow 140ms ease;
            }
            .home-btn:hover { transform: translateY(-2px); box-shadow: 0 12px 28px rgba(2,6,23,0.08); }
        .page-subtitle { font-size: 0.875rem; color: var(--text-muted); }

        /* Content Cards */
        .content-card {
            background: var(--card-bg);
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: var(--shadow-md);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.25rem;
        }

        .card-title { font-size: 1rem; font-weight: 600; letter-spacing: -0.01em; }
        .card-subtitle { font-size: 0.75rem; color: var(--text-muted); margin-top: 0.15rem; }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(5, minmax(0, 1fr));
            gap: 1rem;
        }

        .stat-card {
            background: var(--card-bg);
            border-radius: var(--radius-md);
            padding: 1.25rem;
            box-shadow: var(--shadow-sm);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: default;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--primary), var(--primary-light));
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-hover);
        }

        .stat-card:hover::before { opacity: 1; }

        .stat-card .label {
            display: block;
            font-size: 0.75rem;
            font-weight: 500;
            color: var(--text-muted);
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .stat-card .value {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-dark);
            letter-spacing: -0.02em;
        }

        /* Skeleton Loading */
        .skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e8e8e8 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
            border-radius: 6px;
        }

        .skeleton-value {
            height: 2rem;
            width: 60%;
            margin-top: 0.5rem;
        }

        .skeleton-chart {
            height: 100%;
            width: 100%;
            border-radius: var(--radius-md);
        }

        @keyframes shimmer {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }

        /* Chart Grid */
        .chart-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 1.25rem;
        }

        .chart-card {
            background: var(--card-bg);
            border-radius: var(--radius-md);
            padding: 1.25rem;
            box-shadow: var(--shadow-sm);
            opacity: 0;
            transform: translateY(20px);
            animation: slideUp 0.5s ease-out forwards;
        }

        .chart-card:nth-child(1) { animation-delay: 0.1s; }
        .chart-card:nth-child(2) { animation-delay: 0.2s; }
        .chart-card:nth-child(3) { animation-delay: 0.3s; }

        @keyframes slideUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .chart-card h3 { 
            font-size: 0.85rem; 
            margin-bottom: 1rem; 
            font-weight: 600;
            color: var(--text-dark);
        }
        
        .chart-box { 
            height: 280px; 
            position: relative;
        }
        
        .chart-box canvas { width: 100% !important; height: 100% !important; }

        /* Doughnut Center Text */
        .doughnut-wrapper {
            position: relative;
        }

        .doughnut-center {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -60%);
            text-align: center;
            pointer-events: none;
        }

        .doughnut-center .center-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-dark);
            line-height: 1;
        }

        .doughnut-center .center-label {
            font-size: 0.7rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-top: 0.25rem;
        }

        /* Empty State */
        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            color: var(--text-muted);
            text-align: center;
            padding: 2rem;
        }

        .empty-state-icon {
            width: 48px;
            height: 48px;
            background: var(--bg-body);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 0.75rem;
            color: var(--text-light);
        }

        .empty-state-text {
            font-size: 0.85rem;
            font-weight: 500;
        }

        .empty-state-sub {
            font-size: 0.75rem;
            color: var(--text-light);
            margin-top: 0.25rem;
        }

        @media (max-width: 1200px) {
            .stats-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); }
            .chart-grid { grid-template-columns: 1fr; }
        }

        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .main-content { margin-left: 0; padding: 1.25rem; }
            .stats-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }
        /* Unified sidebar override (applies new compact animated UI) */

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

        .nav-link { display:flex; align-items:center; gap:0.75rem; padding:0.65rem; border-radius:10px; color:var(--text-muted); text-decoration:none; font-weight:600; transition:all .18s ease; margin-bottom:0.2rem }
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
                    <a href="inventory-dashboard.php" class="nav-link active"><span class="nav-icon">⌂</span><span class="nav-label">Dashboard</span></a>
                    <a href="bentaco-inventory.php" class="nav-link"><span class="nav-icon">☐</span><span class="nav-label">BENTACO Inventory</span></a>
                    <a href="iot-inventory.php" class="nav-link"><span class="nav-icon">◎</span><span class="nav-label">IOT Inventory</span></a>
                </div>
                <div class="nav-section">
                    <div class="nav-section-title">Management</div>
                    <a href="location-management.php" class="nav-link"><span class="nav-icon">⊕</span><span class="nav-label">Location Management</span></a>
                    <a href="item-status-monitoring.php" class="nav-link"><span class="nav-icon">◉</span><span class="nav-label">Item Status Monitoring</span></a>
                </div>
                        <!-- Item Allocation removed from sidebar -->
                <div class="nav-section">
                    <div class="nav-section-title">Analytics</div>
                    <a href="report-generation.php" class="nav-link"><span class="nav-icon">☰</span><span class="nav-label">Reports</span></a>
                </div>
            </nav>
        </aside>

        <main class="main-content">
            <div class="page-header">
                <div>
                    <h1 class="page-title">Inventory Dashboard</h1>
                    <p class="page-subtitle" id="metaInfo">Loading data...</p>
                </div>
                <a href="index.html" class="home-btn">Home</a>
            </div>

            <div class="content-card">
                <div class="card-header">
                    <div>
                        <h2 class="card-title">Quick Statistics</h2>
                        <p class="card-subtitle">Real-time inventory summary</p>
                    </div>
                </div>
                    <div class="stats-grid" id="statsGrid">
                        <div class="stat-card">
                            <span class="label">Total</span>
                            <div class="skeleton skeleton-value"></div>
                        </div>
                        <div class="stat-card">
                            <span class="label">Usable</span>
                            <div class="skeleton skeleton-value"></div>
                        </div>
                        <div class="stat-card">
                            <span class="label">Unusable</span>
                            <div class="skeleton skeleton-value"></div>
                        </div>
                        <div class="stat-card">
                            <span class="label">Allocated</span>
                            <div class="skeleton skeleton-value"></div>
                        </div>
                        <div class="stat-card">
                            <span class="label">Available</span>
                            <div class="skeleton skeleton-value"></div>
                        </div>
                    </div>
            </div>

            <div class="content-card">
                <div class="card-header">
                    <div>
                        <h2 class="card-title">Chart Insights</h2>
                            <p class="card-subtitle">Overview</p>
                    </div>
                </div>
                <div class="chart-grid">
                    <div class="chart-card">
                            <h3>Category</h3>
                        <div class="chart-box" id="categoryChartBox">
                            <div class="skeleton skeleton-chart"></div>
                        </div>
                    </div>
                    <div class="chart-card">
                            <h3>Location</h3>
                        <div class="chart-box" id="departmentChartBox">
                            <div class="skeleton skeleton-chart"></div>
                        </div>
                    </div>
                    <div class="chart-card">
                            <h3>Status</h3>
                        <div class="chart-box doughnut-wrapper" id="statusChartBox">
                            <div class="skeleton skeleton-chart"></div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        const STORAGE_KEYS = ["pnoc_inventory_bentaco_v1", "pnoc_inventory_iot_v1"];
        
        // Status-specific color palette
        const statusColors = {
            'Usable': '#10b981',           // Emerald
            'Not Usable': '#f43f5e',       // Rose
            'Unusable': '#f43f5e',         // Rose
            'Retired': '#ef4444',        // Red (formerly Defective/Damaged)
            'Allocated': '#f59e0b',        // Amber
            'Under Maintenance': '#0ea5e9', // Sky
            'Retired': '#64748b',          // Slate
            'No Status': '#9ca3af'         // Gray
        };
        
        const categoryPalette = ['#4f46e5', '#6366f1', '#8b5cf6', '#a855f7', '#d946ef', '#ec4899', '#f43f5e', '#f97316'];
        const charts = [];

        function loadRows() {
            const allRows = [];
            STORAGE_KEYS.forEach((key) => {
                try {
                    const raw = localStorage.getItem(key);
                    const rows = raw ? JSON.parse(raw) : [];
                    if (Array.isArray(rows)) allRows.push(...rows);
                } catch {}
            });
            return allRows;
        }

        function normalize(value) { return String(value || "").trim(); }
        function isUsable(status) { return normalize(status).toLowerCase() === "usable"; }
        function isUnusable(status) {
            const s = normalize(status).toLowerCase();
            return s === "not usable" || s === "retired" || s === "unusable";
        }
        function isAllocated(allocation) { return normalize(allocation).length > 0; }

        function countBy(rows, keyFn, emptyLabel) {
            return rows.reduce((acc, row) => {
                const key = normalize(keyFn(row)) || emptyLabel;
                acc[key] = (acc[key] || 0) + 1;
                return acc;
            }, {});
        }

        function topEntries(mapObj, limit = 8) {
            return Object.entries(mapObj).sort((a, b) => b[1] - a[1]).slice(0, limit);
        }

        function destroyCharts() { while (charts.length) { charts.pop().destroy(); } }

        function buildChart(canvasId, config) {
            const ctx = document.getElementById(canvasId);
            if (!ctx) return null;
            const chart = new Chart(ctx, config);
            charts.push(chart);
            return chart;
        }

        function showEmptyState(containerId, message = "No data available", subMessage = "Add items to see insights") {
            const container = document.getElementById(containerId);
            if (!container) return;
            container.innerHTML = `
                <div class="empty-state">
                    <div class="empty-state-icon">📊</div>
                    <div class="empty-state-text">${message}</div>
                    <div class="empty-state-sub">${subMessage}</div>
                </div>
            `;
        }

        function render() {
            // Simulate loading delay for skeleton effect
            setTimeout(() => {
                const rows = loadRows();
                const totalAssets = rows.length;
                const usableItems = rows.filter((r) => isUsable(r.itemStatus)).length;
                const unusableItems = rows.filter((r) => isUnusable(r.itemStatus)).length;
                const allocatedItems = rows.filter((r) => isAllocated(r.allocationTo)).length;
                const availableItems = Math.max(totalAssets - allocatedItems, 0);

                // Update stats with animation
                const statsGrid = document.getElementById("statsGrid");
                statsGrid.innerHTML = `
                    <div class="stat-card"><span class="label">Total</span><span class="value" id="totalAssets">${totalAssets}</span></div>
                    <div class="stat-card"><span class="label">Usable</span><span class="value" id="usableItems">${usableItems}</span></div>
                    <div class="stat-card"><span class="label">Unusable</span><span class="value" id="unusableItems">${unusableItems}</span></div>
                    <div class="stat-card"><span class="label">Allocated</span><span class="value" id="allocatedItems">${allocatedItems}</span></div>
                    <div class="stat-card"><span class="label">Available</span><span class="value" id="availableItems">${availableItems}</span></div>
                `;
                
                document.getElementById("metaInfo").textContent = totalAssets + " total items across BENTACO and IOT";

                const categoryEntries = topEntries(countBy(rows, (r) => r.itemDescription, "Uncategorized"));
                const departmentEntries = topEntries(countBy(rows, (r) => r.department, "No Department")).filter(e => e[0] !== "No Department");
                const statusEntries = topEntries(countBy(rows, (r) => r.itemStatus, "No Status"), 6);

                destroyCharts();

                // Category Chart - Horizontal Bar with tilted labels
                const categoryBox = document.getElementById("categoryChartBox");
                if (categoryEntries.length === 0) {
                    showEmptyState("categoryChartBox", "No categories", "Add inventory items");
                } else {
                    categoryBox.innerHTML = '<canvas id="categoryChart"></canvas>';
                    buildChart("categoryChart", {
                        type: "bar",
                        data: {
                            labels: categoryEntries.map((e) => e[0].length > 15 ? e[0].substring(0, 15) + '...' : e[0]),
                            datasets: [{
                                        label: "",
                                        data: categoryEntries.map((e) => e[1]),
                                        backgroundColor: categoryPalette.slice(0, categoryEntries.length),
                                        borderRadius: 8,
                                        borderSkipped: false
                                    }]
                        },
                        options: {
                            indexAxis: 'y',
                            responsive: true,
                            maintainAspectRatio: false,
                            animation: {
                                duration: 800,
                                easing: 'easeOutQuart'
                            },
                            plugins: { legend: { display: false }, tooltip: { callbacks: { label: (ctx) => String(ctx.raw || 0) } } },
                            scales: {
                                x: { 
                                    beginAtZero: true, 
                                    ticks: { precision: 0 },
                                    grid: { color: '#f1f5f9' }
                                },
                                y: {
                                    grid: { display: false },
                                    ticks: { font: { size: 11 } }
                                }
                            }
                        }
                    });
                }

                // Department Chart
                const departmentBox = document.getElementById("departmentChartBox");
                if (departmentEntries.length === 0) {
                    showEmptyState("departmentChartBox", "No department data", "Assign departments to items");
                } else {
                    departmentBox.innerHTML = '<canvas id="departmentChart"></canvas>';
                    buildChart("departmentChart", {
                        type: "bar",
                        data: {
                            labels: departmentEntries.map((e) => e[0]),
                            datasets: [{
                                        label: "",
                                        data: departmentEntries.map((e) => e[1]),
                                        backgroundColor: "#6366f1",
                                        borderRadius: 8,
                                        borderSkipped: false
                                    }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            animation: {
                                duration: 800,
                                easing: 'easeOutQuart',
                                delay: (context) => context.dataIndex * 100
                            },
                            plugins: { legend: { display: false }, tooltip: { callbacks: { label: (ctx) => String(ctx.raw || 0) } } },
                            scales: {
                                y: { 
                                    beginAtZero: true, 
                                    ticks: { precision: 0 },
                                    grid: { color: '#f1f5f9' }
                                },
                                x: {
                                    grid: { display: false },
                                    ticks: {
                                        maxRotation: 45,
                                        minRotation: 45,
                                        font: { size: 10 }
                                    }
                                }
                            }
                        }
                    });
                }

                // Status Doughnut Chart with center text
                const statusBox = document.getElementById("statusChartBox");
                if (statusEntries.length === 0) {
                    showEmptyState("statusChartBox", "No status data", "Set item statuses");
                } else {
                    statusBox.innerHTML = `
                        <canvas id="statusChart"></canvas>
                        <div class="doughnut-center">
                            <div class="center-value">${totalAssets}</div>
                            <div class="center-label">Total</div>
                        </div>
                    `;
                    
                    const statusChartColors = statusEntries.map(e => statusColors[e[0]] || '#9ca3af');
                    
                    buildChart("statusChart", {
                        type: "doughnut",
                        data: {
                            labels: statusEntries.map((e) => e[0]),
                            datasets: [{
                                data: statusEntries.map((e) => e[1]),
                                backgroundColor: statusChartColors,
                                borderWidth: 0,
                                hoverOffset: 8
                            }]
                        },
                        options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                cutout: '65%',
                                animation: {
                                    animateRotate: true,
                                    duration: 1000,
                                    easing: 'easeOutCirc'
                                },
                                plugins: {
                                    legend: {
                                        position: "bottom",
                                        labels: {
                                            padding: 16,
                                            usePointStyle: true,
                                            pointStyle: 'circle',
                                            font: { size: 11 }
                                        }
                                    },
                                    tooltip: { callbacks: { label: (ctx) => String(ctx.raw || 0) } }
                                }
                            }
                    });
                }
            }, 400);
        }

        render();
    </script>
</body>
</html>
