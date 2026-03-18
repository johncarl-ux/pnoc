<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>PNOC Inventory | Location Management</title>
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
            --radius-md: 12px;
            --radius-lg: 16px;
            --emerald: #10b981;
            --rose: #f43f5e;
            --amber: #f59e0b;
            --sky: #0ea5e9;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background: var(--bg-body);
            color: var(--text-dark);
            line-height: 1.5;
            min-height: 100vh;
        }

        /* Layout */
        .app-layout {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
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

        .sidebar-brand img {
            width: 36px;
            height: 36px;
            object-fit: contain;
        }

        .sidebar-brand-text {
            font-weight: 700;
            font-size: 1rem;
        }

        .sidebar-brand-sub {
            font-size: 0.7rem;
            color: var(--text-muted);
            font-weight: 400;
        }

        .sidebar-nav {
            padding: 1rem 0.75rem;
        }

        .nav-section {
            margin-bottom: 1.5rem;
        }

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

        .nav-link:hover {
            background: var(--bg-body);
            color: var(--primary);
        }

        .nav-link.active {
            background: var(--primary);
            color: white;
            box-shadow: 0 4px 12px rgba(79,70,229,0.3);
        }

        .nav-link.active .nav-icon {
            background: rgba(255, 255, 255, 0.2);
            color: white;
        }

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

        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            padding: 1.5rem 2rem;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1.5rem;
        }

        .page-header-left {
            flex: 1;
        }

        .page-header-right {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .page-meta {
            font-size: 0.8rem;
            color: var(--text-muted);
        }

        .refresh-btn {
            padding: 0.5rem 1rem;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            background: var(--card-bg);
            font-size: 0.8rem;
            font-weight: 500;
            color: var(--text-dark);
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .refresh-btn:hover {
            border-color: var(--primary);
            color: var(--primary);
        }

        .page-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 0.25rem;
        }

        .page-subtitle {
            font-size: 0.875rem;
            color: var(--text-muted);
        }

        /* Map Controls */
        .map-controls {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .control-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            background: var(--card-bg);
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--text-dark);
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: var(--shadow-sm);
        }

        .control-btn:hover {
            border-color: var(--primary);
            color: var(--primary);
            box-shadow: var(--shadow-md);
        }

        .control-btn.primary {
            background: var(--primary);
            border-color: var(--primary);
            color: white;
        }

        .control-btn.primary:hover {
            background: var(--primary-light);
            box-shadow: 0 4px 12px rgba(79,70,229,0.3);
        }

        /* Content Layout */
        .content-grid {
            display: grid;
            grid-template-columns: 1fr 320px;
            gap: 1.5rem;
            align-items: start;
        }

        /* Visual Item Locator Card */
        .locator-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            padding: 1.25rem;
            box-shadow: var(--shadow-md);
        }

        .locator-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }

        .locator-header-left h2 {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.125rem;
        }

        .locator-header-left p {
            font-size: 0.8rem;
            color: var(--text-muted);
        }

        .locator-header-right {
            display: flex;
            gap: 0.5rem;
        }

        .filter-pills {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .filter-pill {
            padding: 0.4rem 0.85rem;
            border: 1px solid var(--border-color);
            border-radius: 999px;
            background: var(--card-bg);
            font-size: 0.8rem;
            font-weight: 500;
            color: var(--text-muted);
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .filter-pill:hover {
            border-color: var(--primary);
            color: var(--primary);
        }

        .filter-pill.active {
            background: var(--primary);
            border-color: var(--primary);
            color: white;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .card-title {
            font-size: 1rem;
            font-weight: 600;
            color: var(--text-dark);
        }

        .card-subtitle {
            font-size: 0.75rem;
            color: var(--text-muted);
            margin-top: 0.125rem;
        }

        /* Visual Map Grid */
        .visual-map-container {
            background: #f1f5f9;
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            padding: 1.25rem;
            position: relative;
            min-height: 480px;
        }

        .map-grid {
            display: grid;
            grid-template-columns: repeat(12, 1fr);
            grid-template-rows: repeat(10, 1fr);
            gap: 6px;
            height: 100%;
            min-height: 440px;
        }

        .map-zone {
            background: var(--card-bg);
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 0.7rem;
            font-weight: 500;
            color: var(--text-dark);
            text-align: center;
            padding: 0.35rem;
            position: relative;
        }

        .map-zone:hover {
            border-color: var(--primary);
            background: #f0f4ff;
            z-index: 10;
        }

        .map-zone.selected {
            border-color: var(--primary);
            background: var(--primary-glow);
            box-shadow: 0 0 0 2px var(--primary-glow);
        }

        .map-zone-label {
            background: #334155;
            color: white;
            font-size: 0.65rem;
            font-weight: 600;
            padding: 0.2rem 0.5rem;
            border-radius: 4px;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }

        /* Zone Types */
        .zone-warehouse {
            background: #e0e7ff;
            border-color: #a5b4fc;
        }

        .zone-openyard {
            background: #f1f5f9;
            border-color: #cbd5e1;
        }

        .zone-bunker {
            background: #fef3c7;
            border-color: #fbbf24;
        }

        .zone-pier {
            background: #d1fae5;
            border-color: #6ee7b7;
            transform: rotate(-15deg);
        }

        .zone-admin {
            background: #fce7f3;
            border-color: #f9a8d4;
        }

        /* Zone Count Badge */
        .zone-count-badge {
            position: absolute;
            top: 4px;
            right: 4px;
            background: #ef4444;
            color: white;
            font-size: 0.65rem;
            font-weight: 700;
            padding: 0.15rem 0.4rem;
            border-radius: 999px;
            min-width: 18px;
            text-align: center;
            box-shadow: 0 1px 3px rgba(0,0,0,0.2);
            animation: badgePop 0.3s ease;
        }

        @keyframes badgePop {
            0% { transform: scale(0); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }

        /* Map Tooltip */
        .map-tooltip {
            position: absolute;
            background: #1e293b;
            color: white;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            font-size: 0.8rem;
            z-index: 100;
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.15s ease;
            min-width: 180px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.2);
        }

        .map-tooltip.visible {
            opacity: 1;
        }

        .map-tooltip-title {
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .map-tooltip-meta {
            font-size: 0.7rem;
            color: #94a3b8;
        }

        .map-tooltip-hint {
            font-size: 0.65rem;
            color: #64748b;
            margin-top: 0.35rem;
        }

        /* Map section labels */
        .map-section-label {
            position: absolute;
            font-size: 0.65rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
            background: white;
            padding: 0.15rem 0.4rem;
            border-radius: 4px;
            border: 1px solid #e2e8f0;
        }

        /* Warehouse Map - hide old style */
        .warehouse-map {
            display: none;
        }

        .map-section {
            display: none;
        }

        /* Selected Area Panel */
        .selected-panel {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            padding: 1.25rem;
            position: sticky;
            top: 1.5rem;
            box-shadow: var(--shadow-md);
        }

        .panel-section-title {
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-muted);
            margin-bottom: 0.75rem;
        }

        .area-badge {
            display: inline-block;
            padding: 0.25rem 0.65rem;
            border: 1px solid var(--border-color);
            border-radius: 999px;
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .area-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 1.25rem;
        }

        .panel-header {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border-color);
        }

        .panel-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: var(--radius-md);
            background: var(--primary-glow);
            font-size: 1.25rem;
        }

        .panel-title {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--text-dark);
        }

        .panel-subtitle {
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid var(--border-color);
        }

        .info-row:last-of-type {
            border-bottom: none;
        }

        .info-label {
            font-size: 0.8rem;
            color: var(--text-muted);
        }

        .info-value {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--text-dark);
        }

        .panel-actions {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            margin-top: 1.25rem;
        }

        .action-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            border: none;
        }

        .action-btn.primary {
            background: var(--primary);
            color: white;
        }

        .action-btn.primary:hover {
            background: var(--primary-light);
            box-shadow: 0 4px 12px rgba(79,70,229,0.3);
        }

        .action-btn.secondary {
            background: var(--bg-body);
            color: var(--text-dark);
            border: 1px solid var(--border-color);
        }

        .action-btn.secondary:hover {
            background: #e2e8f0;
        }

        /* Map Controls - moved into locator card */
        .map-controls {
            display: none;
        }

        .control-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            background: var(--card-bg);
            font-size: 0.8rem;
            font-weight: 500;
            color: var(--text-dark);
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .control-btn:hover {
            border-color: var(--primary);
            color: var(--primary);
        }

        .control-btn.primary {
            background: #2563eb;
            border-color: #2563eb;
            color: white;
        }

        .control-btn.primary:hover {
            background: #1d4ed8;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 2rem 1rem;
            color: var(--text-muted);
        }

        .empty-state-icon {
            font-size: 2.5rem;
            margin-bottom: 0.75rem;
            opacity: 0.5;
        }

        .empty-state-text {
            font-size: 0.875rem;
        }

        /* Content Layout */
        .content-grid {
            display: grid;
            grid-template-columns: 1fr 280px;
            gap: 1.5rem;
            align-items: start;
        }

        /* Items Section */
        .items-section {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            padding: 1.25rem;
            margin-top: 1.5rem;
            box-shadow: var(--shadow-md);
        }

        .items-header {
            margin-bottom: 1rem;
        }

        .items-header h2 {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.75rem;
        }

        .items-tabs {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 0.75rem;
            position: relative;
        }

        .items-tab {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.45rem 0.85rem;
            border: 1px solid var(--border-color);
            border-radius: 999px;
            background: var(--card-bg);
            font-size: 0.8rem;
            font-weight: 500;
            color: var(--text-muted);
            cursor: pointer;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            transform: scale(1);
        }

        .items-tab:hover {
            border-color: var(--primary);
            color: var(--primary);
            transform: scale(1.02);
        }

        .items-tab:active {
            transform: scale(0.98);
        }

        .items-tab.active {
            background: #334155;
            border-color: #334155;
            color: white;
            box-shadow: 0 2px 8px rgba(51, 65, 85, 0.25);
        }

        .items-tab .count {
            background: rgba(0,0,0,0.1);
            padding: 0.1rem 0.4rem;
            border-radius: 999px;
            font-size: 0.7rem;
            font-weight: 600;
            transition: all 0.25s ease;
        }

        .items-tab.active .count {
            background: rgba(255,255,255,0.2);
        }

        .items-status {
            font-size: 0.8rem;
            color: var(--text-muted);
            padding: 0.5rem 0;
            border-bottom: 1px solid var(--border-color);
            margin-bottom: 1rem;
        }

        .items-toolbar {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .items-search {
            flex: 1;
            padding: 0.6rem 0.85rem;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 0.85rem;
            background: var(--card-bg);
            transition: all 0.2s ease;
        }

        .items-search:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--primary-glow);
        }

        .items-toolbar-btn {
            padding: 0.6rem 1rem;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            background: var(--card-bg);
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--text-dark);
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .items-toolbar-btn:hover {
            border-color: var(--primary);
            color: var(--primary);
        }

        .items-table-wrap {
            overflow: hidden;
            border-radius: 12px;
            min-height: 320px;
            position: relative;
            background: white;
            box-shadow: 0 6px 18px rgba(16,24,40,0.04);
            border: 1px solid rgba(15,23,42,0.06);
        }

        .items-table-wrap.loading::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255,255,255,0.7);
            z-index: 10;
        }

        .items-table tbody {
            transition: opacity 0.2s ease;
        }

        .items-table tbody.fade-out {
            opacity: 0;
        }

        .items-table tbody.fade-in {
            opacity: 1;
        }

        .items-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            min-width: 760px;
            font-size: 0.9rem;
            color: var(--text-dark);
        }

        .items-table th,
        .items-table td {
            padding: 0.75rem 1rem;
            text-align: left;
            border-bottom: 1px solid rgba(15,23,42,0.06);
            vertical-align: middle;
        }

        .items-table th {
            background: linear-gradient(180deg,#ffffff,#fbfdff);
            font-weight: 700;
            color: #1f2937;
            text-transform: none;
            font-size: 0.82rem;
            letter-spacing: 0.01em;
            position: sticky;
            top: 0;
            z-index: 5;
            box-shadow: 0 2px 0 rgba(15,23,42,0.02);
        }

        .items-table tbody tr:nth-child(even) {
            background: #fbfdff;
        }

        .items-table tbody tr:hover {
            background: #f1f5f9;
        }

        .items-table tbody tr:last-child td {
            border-bottom: none;
        }

        /* Compact badges and actions */
        .source-badge { background: #f8fafc; color: #334155; font-weight:600; }

        /* Area items (smaller table used in the right panel) */
        .area-items-table { width:100%; border-collapse: collapse; font-size:0.86rem; }
        .area-items-table th, .area-items-table td { padding:0.5rem 0.65rem; border-bottom:1px solid rgba(15,23,42,0.04); }
        .area-items-table th { font-weight:700; color:#475569; font-size:0.72rem; text-transform:none; }

        .source-badge {
            display: inline-block;
            padding: 0.2rem 0.5rem;
            background: #f1f5f9;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
            color: #475569;
        }

        .location-tag {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.28rem 0.7rem;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 8px;
            font-size: 0.78rem;
            font-weight: 600;
            color: #166534;
        }

        .location-tag .pin {
            font-size: 0.78rem;
            color: #16a34a;
        }

        .location-tag .remove {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 18px;
            height: 18px;
            border: 1px solid rgba(15,23,42,0.06);
            border-radius: 6px;
            background: #ffffff;
            color: #475569;
            font-size: 0.68rem;
            cursor: pointer;
            margin-left: 0.35rem;
            transition: all 0.12s ease;
        }

        .location-tag .remove:hover {
            background: #f8fafc;
            color: #111827;
            border-color: rgba(15,23,42,0.12);
        }

        .location-select {
            padding: 0.35rem 0.5rem;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            font-size: 0.8rem;
            color: var(--text-muted);
            background: var(--card-bg);
            cursor: pointer;
            min-width: 130px;
        }

        .location-select:focus {
            outline: none;
            border-color: var(--primary);
        }

        .not-assigned {
            font-size: 0.75rem;
            color: #94a3b8;
            font-style: italic;
        }

        .status-usable {
            color: var(--emerald);
            font-weight: 500;
        }

        .delete-btn {
            padding: 0.38rem 0.72rem;
            border: 1px solid rgba(15,23,42,0.06);
            border-radius: 8px;
            background: #ffffff;
            color: #374151;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.14s ease;
        }

        .delete-btn:hover {
            background: #fff1f2;
            border-color: rgba(220,38,38,0.12);
            color: #b91c1c;
        }

        .items-pagination {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 1rem;
            margin-top: 1rem;
            border-top: 1px solid var(--border-color);
        }

        .pagination-info {
            font-size: 0.8rem;
            color: var(--text-muted);
        }

        .pagination-controls {
            display: flex;
            gap: 0.35rem;
        }

        .pagination-btn {
            padding: 0.4rem 0.7rem;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            background: var(--card-bg);
            font-size: 0.8rem;
            color: var(--text-dark);
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .pagination-btn:hover {
            border-color: var(--primary);
            color: var(--primary);
        }

        .pagination-btn.active {
            background: var(--primary);
            border-color: var(--primary);
            color: white;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .content-grid {
                grid-template-columns: 1fr;
            }
            
            .selected-panel {
                position: static;
            }

            .page-header {
                flex-direction: column;
                gap: 0.75rem;
            }

            .page-header-right {
                width: 100%;
                justify-content: flex-start;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .main-content {
                margin-left: 0;
                padding: 1rem;
            }
            
            .map-grid {
                grid-template-columns: repeat(6, 1fr);
                min-height: 300px;
            }

            .locator-header {
                flex-direction: column;
                gap: 0.75rem;
            }

            .filter-pills {
                flex-wrap: wrap;
            }
        }
    </style>
</head>
<body>
    <div class="app-layout">
        <!-- Left Sidebar Navigation -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <a href="index.html" class="sidebar-brand">
                    <img src="qw.png" alt="PNOC Logo" />
                    <div>
                        <div class="sidebar-brand-text">PNOC Inventory</div>
                        <div class="sidebar-brand-sub">Management System</div>
                    </div>
                </a>
            </div>
            <nav class="sidebar-nav">
                <div class="nav-section">
                    <div class="nav-section-title">Main Menu</div>
                    <a href="inventory-dashboard.php" class="nav-link">
                        <span class="nav-icon">⌂</span>
                        <span>Dashboard</span>
                    </a>
                    <a href="bentaco-inventory.php" class="nav-link">
                        <span class="nav-icon">☐</span>
                        <span>BENTACO Inventory</span>
                    </a>
                    <a href="iot-inventory.php" class="nav-link">
                        <span class="nav-icon">◎</span>
                        <span>IOT Inventory</span>
                    </a>
                </div>
                <div class="nav-section">
                    <div class="nav-section-title">Management</div>
                    <a href="location-management.php" class="nav-link active">
                        <span class="nav-icon">⊕</span>
                        <span>Location Management</span>
                    </a>
                    <!-- Item Allocation link removed -->
                    <a href="item-status-monitoring.php" class="nav-link">
                        <span class="nav-icon">◉</span>
                        <span>Item Status Monitoring</span>
                    </a>
                </div>
                <div class="nav-section">
                    <div class="nav-section-title">Analytics</div>
                    <a href="report-generation.php" class="nav-link">
                        <span class="nav-icon">☰</span>
                        <span>Reports</span>
                    </a>
                </div>
            </nav>
        </aside>

        <!-- Main Content Area -->
        <main class="main-content">
            <div class="page-header">
                <div class="page-header-left">
                    <h1 class="page-title">Location Management</h1>
                </div>
                <div class="page-header-right">
                    <span class="page-meta" id="zoneMeta">2 active areas • 29 programmed zones</span>
                    <button class="refresh-btn" id="refreshBtn">Refresh</button>
                </div>
            </div>

            <!-- Content Grid -->
            <div class="content-grid">
                <!-- Visual Item Locator -->
                <div class="locator-card">
                    <div class="locator-header">
                        <div class="locator-header-left">
                            <h2>Visual Item Locator</h2>
                            <p>Select an area to view items.</p>
                        </div>
                        <div class="locator-header-right">
                            <button class="control-btn" id="resetZoomBtn">Reset Zoom</button>
                            <button class="control-btn primary" id="pickOnMapBtn">Pick on Map</button>
                        </div>
                    </div>

                    <div class="filter-pills">
                        <button class="filter-pill active">Live view</button>
                        <button class="filter-pill">Mapped areas</button>
                        <button class="filter-pill">Quick filter</button>
                    </div>

                    <!-- Visual Map -->
                    <div class="visual-map-container">
                        <div class="map-tooltip" id="mapTooltip">
                            <div class="map-tooltip-title">Warehouse 4</div>
                            <div class="map-tooltip-meta">0 items • warehouse-4</div>
                            <div class="map-tooltip-hint">Click to inspect mapped items</div>
                        </div>

                        <div class="map-grid">
                            <!-- Row 1: Warehouses -->
                            <div class="map-zone zone-warehouse" data-area="warehouse-1" style="grid-column: 1 / 3; grid-row: 1 / 3;" onclick="selectZone(this)">
                                <span class="map-zone-label">Warehouses</span>
                            </div>
                            <div class="map-zone zone-warehouse" data-area="warehouse-2" style="grid-column: 3 / 4; grid-row: 1 / 3;" onclick="selectZone(this)">
                                Warehouse 2
                            </div>
                            <div class="map-zone zone-warehouse" data-area="warehouse-3" style="grid-column: 4 / 5; grid-row: 1 / 3;" onclick="selectZone(this)">
                                Warehouse 3
                            </div>
                            <div class="map-zone zone-warehouse" data-area="warehouse-4" style="grid-column: 5 / 6; grid-row: 1 / 3;" onclick="selectZone(this)">
                                Warehouse 4
                            </div>

                            <!-- Open Yards Label & First Row -->
                            <div class="map-zone zone-openyard" data-area="open-yards" style="grid-column: 6 / 8; grid-row: 1 / 2;" onclick="selectZone(this)">
                                <span class="map-zone-label">Open Yards</span>
                            </div>
                            <div class="map-zone zone-openyard" data-area="oy-4" style="grid-column: 8 / 9; grid-row: 1 / 2;" onclick="selectZone(this)">
                                OY 4
                            </div>
                            <div class="map-zone zone-openyard" data-area="oy-9" style="grid-column: 9 / 10; grid-row: 1 / 2;" onclick="selectZone(this)">
                                OY 9
                            </div>
                            <div class="map-zone zone-openyard" data-area="oy-11a" style="grid-column: 10 / 11; grid-row: 1 / 2;" onclick="selectZone(this)">
                                OY 11-A
                            </div>
                            <div class="map-zone zone-openyard" data-area="oy-11b" style="grid-column: 11 / 12; grid-row: 2 / 3;" onclick="selectZone(this)">
                                OY 11-B
                            </div>

                            <!-- Row 2-3: Admin Areas & More OY -->
                            <div class="map-zone zone-admin" data-area="petron-area" style="grid-column: 1 / 3; grid-row: 3 / 5;" onclick="selectZone(this)">
                                Petron Area
                            </div>
                            <div class="map-zone zone-admin" data-area="pnoc-esb" style="grid-column: 3 / 5; grid-row: 3 / 5;" onclick="selectZone(this)">
                                PNOC-ESB Admin
                            </div>
                            <div class="map-zone zone-openyard" data-area="oy-3" style="grid-column: 8 / 9; grid-row: 2 / 4;" onclick="selectZone(this)">
                                OY 3
                            </div>
                            <div class="map-zone zone-openyard" data-area="oy-7" style="grid-column: 9 / 10; grid-row: 3 / 4;" onclick="selectZone(this)">
                                OY 7
                            </div>
                            <div class="map-zone zone-openyard" data-area="oy-6" style="grid-column: 10 / 11; grid-row: 3 / 4;" onclick="selectZone(this)">
                                OY 6
                            </div>

                            <!-- Row 4: Bunkers -->
                            <div class="map-zone zone-bunker" data-area="bunker-1" style="grid-column: 1 / 2; grid-row: 5 / 6;" onclick="selectZone(this)">
                                Bunker 1
                            </div>
                            <div class="map-zone zone-bunker" data-area="bunker-2" style="grid-column: 2 / 3; grid-row: 5 / 6;" onclick="selectZone(this)">
                                Bunker 2
                            </div>

                            <!-- OY 10 Series -->
                            <div class="map-zone zone-openyard" data-area="oy-10a" style="grid-column: 7 / 8; grid-row: 5 / 6;" onclick="selectZone(this)">
                                OY 10-A
                            </div>
                            <div class="map-zone zone-openyard" data-area="oy-10b" style="grid-column: 8 / 9; grid-row: 5 / 6;" onclick="selectZone(this)">
                                OY 10-B
                            </div>
                            <div class="map-zone zone-openyard" data-area="oy-10c" style="grid-column: 9 / 10; grid-row: 5 / 6;" onclick="selectZone(this)">
                                OY 10-C
                            </div>

                            <!-- Piers -->
                            <div class="map-zone zone-pier" data-area="triangular-pier" style="grid-column: 10 / 12; grid-row: 5 / 7;" onclick="selectZone(this)">
                                Triangular Pier
                            </div>
                            <div class="map-zone zone-pier" data-area="marginal-wharf" style="grid-column: 10 / 12; grid-row: 7 / 8;" onclick="selectZone(this)">
                                Marginal Wharf
                            </div>
                            <div class="map-zone zone-pier" data-area="roro-pier" style="grid-column: 9 / 11; grid-row: 8 / 9;" onclick="selectZone(this)">
                                RoRo Pier
                            </div>
                            <div class="map-zone zone-pier" data-area="petron-jetty" style="grid-column: 10 / 12; grid-row: 9 / 10;" onclick="selectZone(this)">
                                Petron Jetty
                            </div>

                            <!-- Center crosshair indicator -->
                            <div style="grid-column: 6 / 8; grid-row: 5 / 7; display: flex; align-items: center; justify-content: center; opacity: 0.3;">
                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="1.5">
                                    <circle cx="12" cy="12" r="10"/>
                                    <line x1="12" y1="2" x2="12" y2="6"/>
                                    <line x1="12" y1="18" x2="12" y2="22"/>
                                    <line x1="2" y1="12" x2="6" y2="12"/>
                                    <line x1="18" y1="12" x2="22" y2="12"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Selected Area Panel -->
                <div class="selected-panel">
                    <div class="panel-section-title">SELECTED AREA</div>
                    <div id="selectedAreaContent">
                        <span class="area-badge" id="areaBadge">-</span>
                        <h3 class="area-title" id="areaTitle">Select an area</h3>
                    </div>

                    <div id="areaInfo" style="display: none;">
                        <div class="info-row">
                            <span class="info-label">Total Items</span>
                            <span class="info-value" id="totalItems">-</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Available Capacity</span>
                            <span class="info-value" id="availableCapacity">-</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Zone Type</span>
                            <span class="info-value" id="zoneType">-</span>
                        </div>
                    </div>

                    <!-- Items in this area -->
                    <div id="areaItemsPanel" style="display:none; margin-top:1.25rem;">
                        <div style="font-size:0.85rem; font-weight:600; color:var(--text-dark); margin-bottom:0.5rem;">Items in this area</div>
                        <div id="areaItemsList" style="max-height:220px; overflow-y:auto; border:1px solid var(--border-color); border-radius:8px; background:#f8fafc;">
                            <!-- List will be rendered here -->
                        </div>
                    </div>
                    <div class="panel-actions" id="panelActions" style="display: none;">
                        <button class="action-btn primary" id="viewItemsBtn">
                            View Items
                        </button>
                        <button class="action-btn secondary" id="allocateItemBtn">
                            Allocate Item
                        </button>
                    </div>
                </div>
            </div>

            <!-- Items Section -->
            <div class="items-section">
                <div class="items-header">
                    <h2>Items</h2>
                    <div class="items-tabs">
                        <button class="items-tab active" data-tab="all">All <span class="count" id="countAll">0</span></button>
                        <button class="items-tab" data-tab="iot">IoT <span class="count" id="countIoT">0</span></button>
                        <button class="items-tab" data-tab="bentaco">BENTACO <span class="count" id="countBentaco">0</span></button>
                        <button class="items-tab" data-tab="mapped">Mapped <span class="count" id="countMapped">0</span></button>
                        <button class="items-tab" data-tab="unmapped">Unmapped <span class="count" id="countUnmapped">0</span></button>
                    </div>
                </div>
                <div class="items-status" id="itemsStatus">Ready.</div>
                <div class="items-toolbar">
                    <input type="text" class="items-search" id="itemsSearch" placeholder="Search items...">
                    <button class="items-toolbar-btn" id="clearSearchBtn">Clear</button>
                    <button class="items-toolbar-btn" id="refreshItemsBtn">Refresh</button>
                </div>
                <div class="items-table-wrap">
                    <table class="items-table">
                        <thead>
                            <tr>
                                <th>Source</th>
                                <th>Property Number</th>
                                <th>Asset Number</th>
                                <th>Item Description</th>
                                <th>Location</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="itemsTableBody">
                            <!-- Rows will be populated dynamically -->
                        </tbody>
                    </table>
                </div>
                <div class="items-pagination">
                    <div class="pagination-info" id="paginationInfo">Showing 1-20 of 0 items</div>
                    <div class="pagination-controls" id="paginationControls">
                        <!-- Pagination buttons will be added dynamically -->
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Zone data with item counts and capacity
        const zoneData = {
            'warehouse-1': { name: 'Warehouse 1', shortName: 'WH-1', items: 0, capacity: 100, type: 'Warehouse' },
            'warehouse-2': { name: 'Warehouse 2', shortName: 'WH-2', items: 0, capacity: 100, type: 'Warehouse' },
            'warehouse-3': { name: 'Warehouse 3', shortName: 'WH-3', items: 0, capacity: 100, type: 'Warehouse' },
            'warehouse-4': { name: 'Warehouse 4', shortName: 'WH-4', items: 0, capacity: 100, type: 'Warehouse' },
            'open-yards': { name: 'Open Yards', shortName: 'OY', items: 0, capacity: 200, type: 'Open Yard' },
            'oy-3': { name: 'OY 3', shortName: 'OY-3', items: 0, capacity: 50, type: 'Open Yard' },
            'oy-4': { name: 'OY 4', shortName: 'OY-4', items: 0, capacity: 50, type: 'Open Yard' },
            'oy-6': { name: 'OY 6', shortName: 'OY-6', items: 0, capacity: 50, type: 'Open Yard' },
            'oy-7': { name: 'OY 7', shortName: 'OY-7', items: 0, capacity: 50, type: 'Open Yard' },
            'oy-9': { name: 'OY 9', shortName: 'OY-9', items: 0, capacity: 50, type: 'Open Yard' },
            'oy-10a': { name: 'OY 10-A', shortName: 'OY-10A', items: 0, capacity: 30, type: 'Open Yard' },
            'oy-10b': { name: 'OY 10-B', shortName: 'OY-10B', items: 0, capacity: 30, type: 'Open Yard' },
            'oy-10c': { name: 'OY 10-C', shortName: 'OY-10C', items: 0, capacity: 30, type: 'Open Yard' },
            'oy-11a': { name: 'OY 11-A', shortName: 'OY-11A', items: 0, capacity: 30, type: 'Open Yard' },
            'oy-11b': { name: 'OY 11-B', shortName: 'OY-11B', items: 0, capacity: 30, type: 'Open Yard' },
            'petron-area': { name: 'Petron Area', shortName: 'PETRON', items: 0, capacity: 60, type: 'Admin Area' },
            'pnoc-esb': { name: 'PNOC-ESB Admin', shortName: 'PNOC-ESB', items: 0, capacity: 80, type: 'Admin Area' },
            'bunker-1': { name: 'Bunker 1', shortName: 'BNK-1', items: 0, capacity: 30, type: 'Bunker' },
            'bunker-2': { name: 'Bunker 2', shortName: 'BNK-2', items: 0, capacity: 30, type: 'Bunker' },
            'triangular-pier': { name: 'Triangular Pier', shortName: 'TRI-PIER', items: 0, capacity: 25, type: 'Pier' },
            'marginal-wharf': { name: 'Marginal Wharf', shortName: 'M-WHARF', items: 0, capacity: 40, type: 'Pier' },
            'roro-pier': { name: 'RoRo Pier', shortName: 'RORO', items: 0, capacity: 20, type: 'Pier' },
            'petron-jetty': { name: 'Petron Jetty', shortName: 'P-JETTY', items: 0, capacity: 25, type: 'Pier' }
        };

        // Mapping from location names to zone IDs
        const locationToZoneMap = {
            'Warehouse 1': 'warehouse-1',
            'Warehouse 2': 'warehouse-2',
            'Warehouse 3': 'warehouse-3',
            'Warehouse 4': 'warehouse-4',
            'OY 3': 'oy-3',
            'OY 4': 'oy-4',
            'OY 6': 'oy-6',
            'OY 7': 'oy-7',
            'OY 9': 'oy-9',
            'OY 10-A': 'oy-10a',
            'OY 10-B': 'oy-10b',
            'OY 10-C': 'oy-10c',
            'OY 11-A': 'oy-11a',
            'OY 11-B': 'oy-11b',
            'Petron Area': 'petron-area',
            'PNOC-ESB Admin': 'pnoc-esb',
            'Bunker 1': 'bunker-1',
            'Bunker 2': 'bunker-2',
            'Triangular Pier': 'triangular-pier',
            'Marginal Wharf': 'marginal-wharf',
            'RoRo Pier': 'roro-pier',
            'Petron Jetty': 'petron-jetty'
        };

        // Update zone counts from actual item data
        function updateZoneCounts() {
            // Reset all counts to 0
            Object.keys(zoneData).forEach(key => {
                zoneData[key].items = 0;
            });

            // Count items per location
            allItems.forEach(item => {
                const loc = item.location || '';
                if (loc.trim() !== '') {
                    const zoneId = locationToZoneMap[loc];
                    if (zoneId && zoneData[zoneId]) {
                        zoneData[zoneId].items++;
                    }
                }
            });

            // Update the map zone badges
            document.querySelectorAll('.map-zone').forEach(zone => {
                const areaId = zone.dataset.area;
                if (zoneData[areaId]) {
                    const count = zoneData[areaId].items;
                    let badge = zone.querySelector('.zone-count-badge');
                    if (count > 0) {
                        if (!badge) {
                            badge = document.createElement('span');
                            badge.className = 'zone-count-badge';
                            zone.appendChild(badge);
                        }
                        badge.textContent = count;
                    } else if (badge) {
                        badge.remove();
                    }
                }
            });

            // Update selected panel if a zone is selected
            if (selectedZoneId && zoneData[selectedZoneId]) {
                document.getElementById('totalItems').textContent = zoneData[selectedZoneId].items + ' items';
                document.getElementById('availableCapacity').textContent = 
                    (zoneData[selectedZoneId].capacity - zoneData[selectedZoneId].items) + ' slots';
            }
        }

        let selectedZoneId = null;
        const tooltip = document.getElementById('mapTooltip');

        // Tooltip handling
        document.querySelectorAll('.map-zone').forEach(zone => {
            zone.addEventListener('mouseenter', function(e) {
                const areaId = this.dataset.area;
                const data = zoneData[areaId];
                if (data) {
                    tooltip.querySelector('.map-tooltip-title').textContent = data.name;
                    tooltip.querySelector('.map-tooltip-meta').textContent = data.items + ' items • ' + areaId;
                    tooltip.querySelector('.map-tooltip-hint').textContent = 'Click to inspect mapped items';
                    tooltip.classList.add('visible');
                    updateTooltipPosition(e);
                }
            });

            zone.addEventListener('mousemove', function(e) {
                updateTooltipPosition(e);
            });

            zone.addEventListener('mouseleave', function() {
                tooltip.classList.remove('visible');
            });
        });

        function updateTooltipPosition(e) {
            const container = document.querySelector('.visual-map-container');
            const rect = container.getBoundingClientRect();
            const x = e.clientX - rect.left + 15;
            const y = e.clientY - rect.top - 10;
            tooltip.style.left = x + 'px';
            tooltip.style.top = y + 'px';
        }

        // Select zone function
        function selectZone(element) {
            // Remove previous selection
            document.querySelectorAll('.map-zone').forEach(zone => {
                zone.classList.remove('selected');
            });

            // Add selection to clicked element
            element.classList.add('selected');

            // Get zone data
            const areaId = element.dataset.area;
            selectedZoneId = areaId;
            const zone = zoneData[areaId];

            if (zone) {
                // Update panel
                document.getElementById('areaBadge').textContent = zone.shortName;
                document.getElementById('areaTitle').textContent = zone.name;
                // Show info
                document.getElementById('areaInfo').style.display = 'block';
                document.getElementById('panelActions').style.display = 'flex';
                // Update info values
                document.getElementById('totalItems').textContent = zone.items + ' items';
                document.getElementById('availableCapacity').textContent = (zone.capacity - zone.items) + ' slots';
                document.getElementById('zoneType').textContent = zone.type;

                // Show items in this area
                const areaItemsPanel = document.getElementById('areaItemsPanel');
                const areaItemsList = document.getElementById('areaItemsList');
                // Find items in this zone
                const itemsInZone = allItems.filter(item => {
                    const loc = item.location || '';
                    return locationToZoneMap[loc] === areaId;
                });
                if (itemsInZone.length > 0) {
                    areaItemsPanel.style.display = 'block';
                    areaItemsList.innerHTML = `<table class="area-items-table">
                        <thead><tr><th>Source</th><th>Property #</th><th>Asset #</th><th>Description</th></tr></thead>
                        <tbody>
                        ${itemsInZone.map(item => `
                            <tr>
                                <td>${item.source || '-'}</td>
                                <td>${item.propertyNumber || item.property_number || '-'}</td>
                                <td>${item.assetNumber || item.asset_number || '-'}</td>
                                <td>${item.itemDescription || item.item_description || item.description || '-'}</td>
                            </tr>
                        `).join('')}
                        </tbody>
                    </table>`;
                } else {
                    areaItemsPanel.style.display = 'block';
                    areaItemsList.innerHTML = `<div style='padding:1rem; color:#94a3b8; text-align:center;'>No items in this area.</div>`;
                }
            } else {
                document.getElementById('areaInfo').style.display = 'none';
                document.getElementById('panelActions').style.display = 'none';
                document.getElementById('areaItemsPanel').style.display = 'none';
            }
        }

        // Filter pills
        document.querySelectorAll('.filter-pill').forEach(pill => {
            pill.addEventListener('click', function() {
                document.querySelectorAll('.filter-pill').forEach(p => p.classList.remove('active'));
                this.classList.add('active');
            });
        });

        // Control buttons
        document.getElementById('resetZoomBtn').addEventListener('click', function() {
            // Reset selection
            document.querySelectorAll('.map-zone').forEach(zone => {
                zone.classList.remove('selected');
            });
            
            // Reset panel
            document.getElementById('areaBadge').textContent = '-';
            document.getElementById('areaTitle').textContent = 'Select an area';
            document.getElementById('areaInfo').style.display = 'none';
            document.getElementById('panelActions').style.display = 'none';
            
            selectedZoneId = null;
        });

        document.getElementById('pickOnMapBtn').addEventListener('click', function() {
            alert('Click on any zone on the map to select it.');
        });

        document.getElementById('refreshBtn').addEventListener('click', function() {
            location.reload();
        });

        // Panel action buttons
        document.getElementById('viewItemsBtn').addEventListener('click', function() {
            if (selectedZoneId) {
                const zone = zoneData[selectedZoneId];
                alert('Viewing items in ' + zone.name + '\n\nTotal: ' + zone.items + ' items');
            }
        });

        document.getElementById('allocateItemBtn').addEventListener('click', function() {
            if (selectedZoneId) {
                const zone = zoneData[selectedZoneId];
                alert('Allocate item to ' + zone.name + '\n\nAvailable capacity: ' + (zone.capacity - zone.items) + ' slots');
            }
        });

        // ========================================
        // Items Table Management
        // ========================================
        let allItems = [];
        let filteredItems = [];
        let currentTab = 'all';
        let currentPage = 1;
        const itemsPerPage = 20;

        // Location options for dropdown
        const locationOptions = [
            'Warehouse 1', 'Warehouse 2', 'Warehouse 3', 'Warehouse 4',
            'OY 3', 'OY 4', 'OY 6', 'OY 7', 'OY 9',
            'OY 10-A', 'OY 10-B', 'OY 10-C', 'OY 11-A', 'OY 11-B',
            'Petron Area', 'PNOC-ESB Admin',
            'Bunker 1', 'Bunker 2',
            'Triangular Pier', 'Marginal Wharf', 'RoRo Pier', 'Petron Jetty'
        ];

        // Load items from localStorage
        function loadItems() {
            allItems = [];
            
            // Load IoT items
            const iotData = localStorage.getItem('pnoc_inventory_iot_v1');
            if (iotData) {
                try {
                    const iotItems = JSON.parse(iotData);
                    iotItems.forEach(item => {
                        allItems.push({
                            ...item,
                            source: 'IoT'
                        });
                    });
                } catch (e) {
                    console.error('Error parsing IoT data:', e);
                }
            }

            // Load BENTACO items
            const bentacoData = localStorage.getItem('pnoc_inventory_bentaco_v1');
            if (bentacoData) {
                try {
                    const bentacoItems = JSON.parse(bentacoData);
                    bentacoItems.forEach(item => {
                        allItems.push({
                            ...item,
                            source: 'BENTACO'
                        });
                    });
                } catch (e) {
                    console.error('Error parsing BENTACO data:', e);
                }
            }

            updateCounts();
            updateZoneCounts();
            filterItems();
        }

        // Update tab counts
        function updateCounts() {
            const iotCount = allItems.filter(i => i.source === 'IoT').length;
            const bentacoCount = allItems.filter(i => i.source === 'BENTACO').length;
            const mappedCount = allItems.filter(i => i.location && i.location.trim() !== '').length;
            const unmappedCount = allItems.filter(i => !i.location || i.location.trim() === '').length;

            document.getElementById('countAll').textContent = allItems.length;
            document.getElementById('countIoT').textContent = iotCount;
            document.getElementById('countBentaco').textContent = bentacoCount;
            document.getElementById('countMapped').textContent = mappedCount;
            document.getElementById('countUnmapped').textContent = unmappedCount;
        }

        // Filter items based on current tab and search
        function filterItems() {
            const searchTerm = document.getElementById('itemsSearch').value.toLowerCase();
            
            filteredItems = allItems.filter(item => {
                // Tab filter
                if (currentTab === 'iot' && item.source !== 'IoT') return false;
                if (currentTab === 'bentaco' && item.source !== 'BENTACO') return false;
                if (currentTab === 'mapped' && (!item.location || item.location.trim() === '')) return false;
                if (currentTab === 'unmapped' && item.location && item.location.trim() !== '') return false;

                // Search filter
                if (searchTerm) {
                    const searchFields = [
                        item.propertyNumber || '',
                        item.property_number || '',
                        item.assetNumber || '',
                        item.asset_number || '',
                        item.itemDescription || '',
                        item.item_description || '',
                        item.description || '',
                        item.location || ''
                    ].join(' ').toLowerCase();
                    
                    if (!searchFields.includes(searchTerm)) return false;
                }

                return true;
            });

            currentPage = 1;
            renderTable();
            updatePagination();
            document.getElementById('itemsStatus').textContent = 'Showing ' + filteredItems.length + ' of ' + allItems.length + ' items.';
        }

        // Render table rows
        function renderTable() {
            const tbody = document.getElementById('itemsTableBody');
            const start = (currentPage - 1) * itemsPerPage;
            const end = start + itemsPerPage;
            const pageItems = filteredItems.slice(start, end);

            if (pageItems.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" style="text-align: center; padding: 2rem; color: #94a3b8;">No items found</td></tr>';
                return;
            }

            tbody.innerHTML = pageItems.map((item, idx) => {
                const propNum = item.propertyNumber || item.property_number || '-';
                const assetNum = item.assetNumber || item.asset_number || '-';
                const desc = item.itemDescription || item.item_description || item.description || '-';
                const loc = item.location || '';
                
                const globalIdx = start + idx;

                let locationHTML;
                if (loc && loc.trim() !== '') {
                    locationHTML = `
                        <span class="location-tag">
                            <span class="pin">📍</span>
                            ${loc}
                            <span class="remove" onclick="removeLocation(${globalIdx})" title="Remove location">×</span>
                        </span>
                    `;
                } else {
                    locationHTML = `
                        <select class="location-select" onchange="assignLocation(${globalIdx}, this.value)">
                            <option value="">Select Location...</option>
                            ${locationOptions.map(opt => `<option value="${opt}">${opt}</option>`).join('')}
                        </select>
                    `;
                }

                

                return `
                    <tr>
                        <td><span class="source-badge">${item.source}</span></td>
                        <td>${propNum}</td>
                        <td>${assetNum}</td>
                        <td>${desc}</td>
                        <td>${locationHTML}</td>
                        <td><button class="delete-btn" onclick="deleteItem(${globalIdx})">Delete</button></td>
                    </tr>
                `;
            }).join('');
        }

        // Update pagination
        function updatePagination() {
            const totalPages = Math.ceil(filteredItems.length / itemsPerPage);
            const start = (currentPage - 1) * itemsPerPage + 1;
            const end = Math.min(currentPage * itemsPerPage, filteredItems.length);
            
            document.getElementById('paginationInfo').textContent = 
                filteredItems.length > 0 
                    ? `Showing ${start}-${end} of ${filteredItems.length} items`
                    : 'No items';

            const controls = document.getElementById('paginationControls');
            if (totalPages <= 1) {
                controls.innerHTML = '';
                return;
            }

            let html = '';
            html += `<button class="pagination-btn" onclick="goToPage(${currentPage - 1})" ${currentPage === 1 ? 'disabled' : ''}>‹</button>`;
            
            for (let i = 1; i <= Math.min(totalPages, 5); i++) {
                html += `<button class="pagination-btn ${currentPage === i ? 'active' : ''}" onclick="goToPage(${i})">${i}</button>`;
            }
            
            if (totalPages > 5) {
                html += `<span style="padding: 0 0.5rem;">...</span>`;
                html += `<button class="pagination-btn ${currentPage === totalPages ? 'active' : ''}" onclick="goToPage(${totalPages})">${totalPages}</button>`;
            }
            
            html += `<button class="pagination-btn" onclick="goToPage(${currentPage + 1})" ${currentPage === totalPages ? 'disabled' : ''}>›</button>`;
            
            controls.innerHTML = html;
        }

        function goToPage(page) {
            const totalPages = Math.ceil(filteredItems.length / itemsPerPage);
            if (page < 1 || page > totalPages) return;
            currentPage = page;
            renderTable();
            updatePagination();
        }

        // Assign location to item
        function assignLocation(idx, location) {
            const item = filteredItems[idx];
            if (!item) return;

            item.location = location;
            saveItems();
            updateCounts();
            updateZoneCounts();
            filterItems();
        }

        // Remove location from item
        function removeLocation(idx) {
            const item = filteredItems[idx];
            if (!item) return;

            item.location = '';
            saveItems();
            updateCounts();
            updateZoneCounts();
            filterItems();
        }

        

        // Delete item
        function deleteItem(idx) {
            const item = filteredItems[idx];
            if (!item) return;

            if (!confirm('Are you sure you want to delete this item?')) return;

            // Remove from allItems
            const allIdx = allItems.indexOf(item);
            if (allIdx > -1) {
                allItems.splice(allIdx, 1);
            }

            saveItems();
            updateCounts();
            filterItems();
        }

        // Save items back to localStorage
        function saveItems() {
            const iotItems = allItems.filter(i => i.source === 'IoT').map(i => {
                const copy = { ...i };
                delete copy.source;
                return copy;
            });
            
            const bentacoItems = allItems.filter(i => i.source === 'BENTACO').map(i => {
                const copy = { ...i };
                delete copy.source;
                return copy;
            });

            localStorage.setItem('pnoc_inventory_iot_v1', JSON.stringify(iotItems));
            localStorage.setItem('pnoc_inventory_bentaco_v1', JSON.stringify(bentacoItems));
        }

        // Tab click handlers with smooth transition
        document.querySelectorAll('.items-tab').forEach(tab => {
            tab.addEventListener('click', function() {
                if (this.classList.contains('active')) return;
                
                document.querySelectorAll('.items-tab').forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                currentTab = this.dataset.tab;
                
                // Smooth transition
                const tbody = document.getElementById('itemsTableBody');
                tbody.classList.add('fade-out');
                
                setTimeout(() => {
                    filterItems();
                    tbody.classList.remove('fade-out');
                    tbody.classList.add('fade-in');
                    setTimeout(() => tbody.classList.remove('fade-in'), 200);
                }, 150);
            });
        });

        // Search handler
        document.getElementById('itemsSearch').addEventListener('input', function() {
            filterItems();
        });

        // Clear search
        document.getElementById('clearSearchBtn').addEventListener('click', function() {
            document.getElementById('itemsSearch').value = '';
            filterItems();
        });

        // Refresh items
        document.getElementById('refreshItemsBtn').addEventListener('click', function() {
            loadItems();
        });

        // Initialize items on page load
        loadItems();
    </script>
</body>
</html>
