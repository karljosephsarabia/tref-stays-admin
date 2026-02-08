<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Search Properties - Tref Stays</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --tref-blue: #3b82f6;
            --tref-blue-hover: #2563eb;
            --tref-blue-light: rgba(59, 130, 246, 0.1);
            --bg: #ffffff;
            --bg-secondary: #f8fafc;
            --bg-glass: rgba(255, 255, 255, 0.8);
            --text: #0f172a;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --border-focus: #3b82f6;
            --radius: 0.75rem;
            --radius-lg: 1rem;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1);
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Inter', -apple-system, sans-serif; 
            background: var(--bg-secondary); 
            color: var(--text); 
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        
        /* Touch-friendly improvements */
        @media (hover: none) and (pointer: coarse) {
            .btn, .search-select, .search-input {
                min-height: 44px;
            }
            .filter-tag {
                min-height: 44px;
                padding: 0.625rem 1rem;
            }
        }
        
        /* Smooth Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        
        .property-card {
            animation: fadeIn 0.4s ease-out backwards;
        }
        
        .property-card:nth-child(1) { animation-delay: 0.05s; }
        .property-card:nth-child(2) { animation-delay: 0.1s; }
        .property-card:nth-child(3) { animation-delay: 0.15s; }
        .property-card:nth-child(4) { animation-delay: 0.2s; }
        .property-card:nth-child(5) { animation-delay: 0.25s; }
        .property-card:nth-child(6) { animation-delay: 0.3s; }
        
        /* Smooth scroll */
        html {
            scroll-behavior: smooth;
        }
        
        *:focus-visible {
            outline: 2px solid var(--tref-blue);
            outline-offset: 2px;
        }
        
        /* Header */
        .header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
            position: sticky; 
            top: 0; 
            z-index: 100;
            box-shadow: var(--shadow-sm);
        }
        .header-inner {
            display: flex; 
            justify-content: space-between; 
            align-items: center;
            padding: 1rem 1.5rem; 
            max-width: 1440px; 
            margin: 0 auto;
            gap: 1rem;
        }
        .logo { 
            font-size: 1.5rem; 
            font-weight: 800; 
            text-decoration: none; 
            display: flex; 
            align-items: center; 
            gap: 0.625rem;
            flex-shrink: 0;
            letter-spacing: -0.02em;
        }
        .logo-icon { 
            width: 2rem; 
            height: 2rem; 
            color: var(--tref-blue); 
        }
        .logo span { 
            color: var(--text); 
        }
        .logo span:first-of-type { 
            color: var(--tref-blue); 
        }
        .header-nav { 
            display: flex; 
            align-items: center; 
            gap: 1rem; 
            flex-wrap: wrap;
        }
        .header-nav a { 
            color: var(--text); 
            text-decoration: none; 
            font-size: 0.9375rem; 
            font-weight: 500; 
            transition: color 0.2s; 
            white-space: nowrap;
        }
        .header-nav a:hover { 
            color: var(--tref-blue); 
        }
        
        /* Responsive Navigation */
        @media (max-width: 1024px) {
            .header-inner {
                padding: 0.875rem 1.25rem;
            }
        }
        
        @media (max-width: 768px) {
            .header-inner {
                padding: 0.75rem 1rem;
            }
            .logo {
                font-size: 1.375rem;
            }
            .logo-icon {
                width: 1.75rem;
                height: 1.75rem;
            }
            .header-nav {
                gap: 0.625rem;
            }
            .header-nav a {
                font-size: 0.875rem;
                padding: 0.5rem 0.75rem;
            }
            .btn {
                padding: 0.625rem 1.125rem;
                font-size: 0.875rem;
            }
        }
        
        @media (max-width: 640px) {
            .header-inner {
                padding: 0.625rem 0.875rem;
            }
            .logo {
                font-size: 1.25rem;
            }
        }
        
        @media (max-width: 480px) {
            .header-inner {
                padding: 0.5rem 0.75rem;
            }
            .logo {
                font-size: 1.125rem;
            }
            .logo-icon {
                width: 1.5rem;
                height: 1.5rem;
            }
            .logo span:not(:first-of-type) {
                display: none;
            }
            .header-nav a:not(.btn) {
                display: none;
            }
            .btn {
                padding: 0.5rem 0.875rem;
                font-size: 0.8125rem;
            }
        }
        
        @media (max-width: 375px) {
            .header-inner {
                padding: 0.5rem;
            }
            .logo {
                font-size: 1rem;
            }
            .btn {
                padding: 0.5rem 0.75rem;
                font-size: 0.75rem;
            }
        }
        .btn {
            display: inline-flex; align-items: center; justify-content: center; gap: 0.625rem;
            padding: 0.875rem 1.75rem; font-size: 0.9375rem; font-weight: 600;
            border-radius: var(--radius-lg); border: none; cursor: pointer; 
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); 
            text-decoration: none;
            white-space: nowrap;
            letter-spacing: 0.01em;
        }
        .btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        .btn-outline { 
            background: white; 
            border: 2px solid var(--border); 
            color: var(--text);
            box-shadow: var(--shadow-sm);
        }
        .btn-outline:hover { 
            border-color: var(--tref-blue); 
            background: var(--tref-blue-light); 
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }
        .btn-primary { 
            background: linear-gradient(135deg, var(--tref-blue) 0%, var(--tref-blue-hover) 100%); 
            color: white; 
            min-width: 140px;
            box-shadow: var(--shadow-md);
        }
        .btn-primary:hover { 
            box-shadow: var(--shadow-lg), 0 0 0 4px var(--tref-blue-light); 
            transform: translateY(-2px);
        }
        .btn-primary:active { 
            transform: translateY(0);
            box-shadow: var(--shadow-md);
        }
        .btn-sm { padding: 0.625rem 1.25rem; font-size: 0.875rem; min-width: auto; }
        
        /* Date Input Styling */
        input[type="date"].search-select {
            position: relative;
            cursor: pointer;
            color: var(--text);
        }
        input[type="date"].search-select::-webkit-calendar-picker-indicator {
            cursor: pointer;
            opacity: 0.7;
        }
        input[type="date"].search-select::-webkit-calendar-picker-indicator:hover {
            opacity: 1;
        }
        input[type="date"].search-select::-webkit-datetime-edit-text {
            padding: 0 0.25rem;
        }
        input[type="date"].search-select::-webkit-datetime-edit-month-field,
        input[type="date"].search-select::-webkit-datetime-edit-day-field,
        input[type="date"].search-select::-webkit-datetime-edit-year-field {
            padding: 0 0.25rem;
        }
        
        /* Search Bar */
        .search-bar-wrapper { 
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border); 
            padding: 1.25rem 0; 
            position: sticky;
            top: 0;
            z-index: 99;
            box-shadow: var(--shadow-sm);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .search-bar {
            max-width: 1440px; 
            margin: 0 auto; 
            padding: 0 1.5rem;
            display: flex; 
            align-items: stretch; 
            gap: 0.875rem; 
            flex-wrap: wrap;
        }
        .search-input-group { 
            display: flex; 
            align-items: center; 
            gap: 0.75rem; 
            flex: 1; 
            min-width: 200px;
            background: white;
            border: 2px solid transparent;
            border-radius: var(--radius-lg);
            padding: 0 1rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: var(--shadow-sm);
            position: relative;
        }
        .search-input-group:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-1px);
        }
        .search-input-group:focus-within {
            border-color: var(--border-focus);
            box-shadow: var(--shadow-md), 0 0 0 4px var(--tref-blue-light);
            transform: translateY(-1px);
        }
        .search-input-group svg {
            flex-shrink: 0;
        }
        .search-input {
            flex: 1; 
            padding: 0.75rem 0; 
            font-size: 0.9375rem;
            font-weight: 500;
            border: none;
            background: transparent;
            min-width: 0;
            color: var(--text);
        }
        .search-input:focus { 
            outline: none; 
        }
        .search-input::placeholder {
            color: var(--text-muted);
            font-weight: 400;
        }
        .search-select {
            padding: 0.875rem 1rem; 
            font-size: 0.9375rem;
            font-weight: 500;
            border: 2px solid transparent; 
            border-radius: var(--radius-lg);
            background: white; 
            cursor: pointer; 
            min-width: 150px;
            flex-shrink: 0;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: var(--shadow-sm);
            color: var(--text);
        }
        .search-select:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-1px);
        }
        .search-select:focus { 
            outline: none; 
            border-color: var(--border-focus); 
            box-shadow: var(--shadow-md), 0 0 0 4px var(--tref-blue-light);
            transform: translateY(-1px);
        }
        
        /* Tablet Responsive (1024px and below) */
        @media (max-width: 1024px) {
            .search-bar {
                gap: 0.75rem;
            }
            .search-input-group {
                min-width: 240px;
            }
            .search-select {
                min-width: 140px;
                font-size: 0.875rem;
                padding: 0.75rem 0.875rem;
            }
        }
        
        /* Tablet Portrait (768px and below) */
        @media (max-width: 768px) {
            .search-bar-wrapper {
                padding: 1rem 0;
            }
            .search-bar {
                padding: 0 1rem;
                gap: 0.625rem;
            }
            .search-input-group {
                min-width: 100%;
                order: 1;
                padding: 0 0.875rem;
            }
            .search-input {
                font-size: 0.875rem;
                padding: 0.625rem 0;
            }
            .search-select {
                min-width: calc(50% - 0.3125rem);
                order: 2;
                font-size: 0.875rem;
                padding: 0.75rem 0.875rem;
            }
            .search-bar .btn-primary {
                width: 100%;
                order: 3;
                justify-content: center;
                padding: 0.875rem 1.5rem;
                font-size: 0.9375rem;
            }
            .search-bar input[type="date"] {
                min-width: calc(50% - 0.3125rem);
                order: 2;
            }
        }
        
        /* Mobile Landscape (640px and below) */
        @media (max-width: 640px) {
            .search-bar {
                gap: 0.5rem;
            }
            .search-input-group {
                padding: 0 0.75rem;
            }
            .search-select {
                padding: 0.625rem 0.75rem;
            }
        }
        
        /* Mobile Portrait (480px and below) */
        @media (max-width: 480px) {
            .search-bar-wrapper {
                padding: 0.875rem 0;
            }
            .search-bar {
                padding: 0 0.75rem;
            }
            .search-input-group {
                min-width: 100%;
                padding: 0 0.75rem;
            }
            .search-input {
                font-size: 0.875rem;
            }
            .search-select,
            .search-bar input[type="date"] {
                min-width: 100%;
                order: 2;
                padding: 0.75rem 0.875rem;
            }
            .search-bar .btn-primary {
                padding: 0.875rem 1.25rem;
            }
            .search-bar .btn-primary span {
                display: inline;
            }
        }
        
        /* Small Mobile (375px and below) */
        @media (max-width: 375px) {
            .search-bar {
                padding: 0 0.5rem;
            }
            .search-input-group {
                padding: 0 0.625rem;
            }
            .search-input,
            .search-select {
                font-size: 0.8125rem;
            }
        }
        
        /* Filter Tags */
        .filter-tags {
            display: flex; 
            align-items: center; 
            gap: 0.625rem; 
            padding: 1rem 1.5rem;
            max-width: 1440px; 
            margin: 0 auto; 
            flex-wrap: wrap;
            background: var(--bg-secondary);
        }
        .filter-tag {
            display: inline-flex; 
            align-items: center; 
            gap: 0.5rem;
            padding: 0.625rem 1rem; 
            background: white;
            border: 2px solid transparent; 
            border-radius: 9999px;
            font-size: 0.875rem; 
            font-weight: 500;
            color: var(--text);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            min-height: 38px;
            box-shadow: var(--shadow-sm);
        }
        .filter-tag:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-1px);
        }
        .filter-tag.active { 
            background: linear-gradient(135deg, var(--tref-blue) 0%, var(--tref-blue-hover) 100%); 
            color: white; 
            border-color: transparent;
            box-shadow: var(--shadow-md), 0 0 0 4px var(--tref-blue-light);
        }
        .filter-tag button,
        .filter-tag a {
            background: none; 
            border: none; 
            cursor: pointer;
            color: inherit; 
            display: flex; 
            align-items: center;
            padding: 0;
            text-decoration: none;
            min-width: 20px;
            min-height: 20px;
            justify-content: center;
            transition: all 0.2s;
        }
        .filter-tag button:hover,
        .filter-tag a:hover { 
            opacity: 0.7; 
            transform: scale(1.1);
        }
        
        /* Responsive Filter Tags */
        @media (max-width: 1024px) {
            .filter-tags {
                padding: 0.875rem 1rem;
            }
        }
        
        @media (max-width: 768px) {
            .filter-tags {
                padding: 0.75rem 1rem;
                gap: 0.5rem;
            }
            .filter-tag {
                font-size: 0.8125rem;
                padding: 0.5rem 0.875rem;
                min-height: 36px;
            }
        }
        
        @media (max-width: 480px) {
            .filter-tags {
                padding: 0.625rem 0.75rem;
                gap: 0.375rem;
            }
            .filter-tag {
                font-size: 0.75rem;
                padding: 0.4375rem 0.75rem;
                min-height: 34px;
            }
        }
        
        /* Main Layout */
        .main-layout { display: flex; max-width: 1440px; margin: 0 auto; }
        
        /* Sidebar Filters */
        .sidebar {
            width: 280px; padding: 1.5rem;
            background: white; height: calc(100vh - 140px);
            overflow-y: auto; border-right: 1px solid var(--border);
            position: sticky; top: 140px;
        }
        @media (max-width: 1024px) { 
            .sidebar { 
                width: 240px; 
                padding: 1.25rem; 
            } 
        }
        @media (max-width: 968px) { 
            .sidebar { 
                display: none; 
            } 
            .main-layout {
                display: block;
            }
        }
        .filter-section { margin-bottom: 1.5rem; padding-bottom: 1.5rem; border-bottom: 1px solid var(--border); }
        .filter-section:last-child { border-bottom: none; }
        .filter-title { font-size: 0.9375rem; font-weight: 600; margin-bottom: 1rem; }
        .filter-options { display: flex; flex-direction: column; gap: 0.75rem; }
        .filter-option { display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; cursor: pointer; }
        .filter-option input { width: 1rem; height: 1rem; accent-color: var(--tref-blue); }
        .price-range { display: flex; align-items: center; gap: 0.5rem; }
        .price-input {
            width: 100%; padding: 0.5rem; font-size: 0.875rem;
            border: 1px solid var(--border); border-radius: var(--radius);
        }
        .price-input:focus { outline: none; border-color: var(--tref-blue); }
        
        /* Results Area */
        .results-area { flex: 1; padding: 1.5rem; }
        .results-header {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;
        }
        
        /* Responsive Results Area */
        @media (max-width: 1024px) {
            .results-area { padding: 1.25rem; }
        }
        
        @media (max-width: 768px) {
            .results-area { 
                padding: 1rem; 
            }
            .results-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.75rem;
            }
            .results-header > div:last-child {
                width: 100%;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
        }
        
        @media (max-width: 480px) {
            .results-area { 
                padding: 0.75rem; 
            }
        }
        .results-count { font-size: 1rem; font-weight: 600; }
        .results-count span { color: var(--text-muted); font-weight: 400; }
        .sort-select {
            padding: 0.5rem 1rem; font-size: 0.875rem;
            border: 1px solid var(--border); border-radius: var(--radius);
            background: white;
        }
        .view-toggle { display: flex; gap: 0.25rem; }
        .view-btn {
            width: 2.5rem; height: 2.5rem; border: 1px solid var(--border);
            background: white; border-radius: var(--radius); cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            color: var(--text-muted);
        }
        .view-btn.active { background: var(--tref-blue); color: white; border-color: var(--tref-blue); }
        
        /* Responsive Controls */
        @media (max-width: 768px) {
            .results-count { font-size: 0.9375rem; }
            .sort-select {
                padding: 0.5rem 0.875rem;
                font-size: 0.8125rem;
                flex: 1;
            }
            .view-toggle { display: none; }
        }
        
        @media (max-width: 480px) {
            .results-count { font-size: 0.875rem; }
            .sort-select {
                width: 100%;
            }
        }
        
        /* Property Grid */
        .property-grid {
            display: grid; 
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
        }
        .property-grid.list { grid-template-columns: 1fr; }
        
        /* Responsive Property Grid */
        @media (max-width: 1200px) {
            .property-grid {
                grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
                gap: 1.25rem;
            }
        }
        
        @media (max-width: 968px) {
            .property-grid {
                grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
                gap: 1rem;
            }
        }
        
        @media (max-width: 768px) {
            .property-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }
        }
        
        @media (max-width: 640px) {
            .property-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 0.875rem;
            }
        }
        
        @media (max-width: 480px) {
            .property-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
        }
        
        /* Property Card */
        .property-card {
            background: white; 
            border-radius: var(--radius-lg);
            overflow: hidden; 
            box-shadow: var(--shadow-sm);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid var(--border);
        }
        .property-card:hover { 
            transform: translateY(-6px); 
            box-shadow: var(--shadow-xl);
            border-color: transparent;
        }
        .property-card.list-view { display: flex; }
        .property-card.list-view .property-image { width: 280px; height: auto; aspect-ratio: 4/3; }
        .property-card.list-view .property-content { flex: 1; }
        .property-image {
            position: relative; aspect-ratio: 4/3; overflow: hidden;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .property-image img { 
            width: 100%; 
            height: 100%; 
            object-fit: cover; 
            transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1); 
        }
        .property-card:hover .property-image img { transform: scale(1.08); }
        .property-badge {
            position: absolute; top: 0.875rem; left: 0.875rem;
            background: linear-gradient(135deg, var(--tref-blue) 0%, var(--tref-blue-hover) 100%); 
            color: white;
            padding: 0.375rem 0.75rem; 
            border-radius: 9999px;
            font-size: 0.6875rem; 
            font-weight: 600; 
            text-transform: uppercase;
            box-shadow: var(--shadow-md);
            backdrop-filter: blur(8px);
        }
        .property-price {
            position: absolute; bottom: 0.875rem; left: 0.875rem;
            background: rgba(15, 23, 42, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            color: white;
            padding: 0.5rem 0.875rem; 
            border-radius: var(--radius-lg);
            font-size: 0.9375rem; 
            font-weight: 700;
            box-shadow: var(--shadow-lg);
        }
        .property-price span { font-weight: 400; opacity: 0.85; font-size: 0.8125rem; }
        .property-favorite {
            position: absolute; top: 0.875rem; right: 0.875rem;
            width: 2.25rem; height: 2.25rem; 
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: none; border-radius: 50%; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            box-shadow: var(--shadow-md);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .property-favorite:hover { 
            background: white; 
            transform: scale(1.1);
            box-shadow: var(--shadow-lg);
        }
        .property-favorite svg { width: 1.125rem; height: 1.125rem; color: var(--text-muted); transition: all 0.3s; }
        .property-favorite:hover svg, .property-favorite.active svg { color: #ef4444; fill: #ef4444; }
        .kosher-badge {
            position: absolute; top: 0.875rem; left: auto; right: 3.5rem;
            background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%); 
            color: #15803d;
            padding: 0.375rem 0.625rem; 
            border-radius: 9999px;
            font-size: 0.6875rem; 
            font-weight: 700;
            display: flex; 
            align-items: center; 
            gap: 0.25rem;
            box-shadow: var(--shadow-md);
            backdrop-filter: blur(8px);
        }
        .property-content { padding: 1.25rem; }
        .property-type { font-size: 0.75rem; color: var(--tref-blue); font-weight: 600; text-transform: uppercase; margin-bottom: 0.25rem; }
        .property-title { font-size: 1rem; font-weight: 600; margin-bottom: 0.5rem; line-height: 1.4; }
        .property-title a { color: inherit; text-decoration: none; }
        .property-title a:hover { color: var(--tref-blue); }
        .property-location { font-size: 0.8125rem; color: var(--text-muted); display: flex; align-items: center; gap: 0.25rem; margin-bottom: 0.75rem; }
        .property-features { display: flex; gap: 1rem; font-size: 0.8125rem; color: var(--text-muted); }
        .property-feature { display: flex; align-items: center; gap: 0.25rem; }
        .property-rating { display: flex; align-items: center; gap: 0.25rem; margin-top: 0.75rem; font-size: 0.8125rem; }
        .property-rating svg { width: 0.875rem; height: 0.875rem; color: #facc15; fill: #facc15; }
        .property-rating span { font-weight: 600; }
        .property-rating .count { color: var(--text-muted); font-weight: 400; }
        
        /* Responsive Property Cards */
        @media (max-width: 768px) {
            .property-content { padding: 1rem; }
            .property-title { font-size: 0.9375rem; }
            .property-badge { 
                top: 0.625rem; 
                left: 0.625rem;
                padding: 0.25rem 0.625rem;
                font-size: 0.625rem;
            }
            .property-price { 
                bottom: 0.625rem; 
                left: 0.625rem;
                padding: 0.375rem 0.625rem;
                font-size: 0.875rem;
            }
            .property-favorite {
                top: 0.625rem;
                right: 0.625rem;
                width: 2rem;
                height: 2rem;
            }
            .kosher-badge {
                top: 0.625rem;
                right: 3rem;
                padding: 0.25rem 0.5rem;
                font-size: 0.625rem;
            }
        }
        
        @media (max-width: 480px) {
            .property-content { padding: 0.875rem; }
            .property-title { font-size: 0.875rem; }
            .property-location { font-size: 0.75rem; }
            .property-features { 
                gap: 0.75rem; 
                font-size: 0.75rem; 
            }
            .property-rating { font-size: 0.75rem; }
            .property-card:hover {
                transform: translateY(-3px);
            }
        }
        
        /* Pagination */
        .pagination { 
            display: flex; 
            justify-content: center; 
            gap: 0.625rem; 
            margin-top: 3rem; 
        }
        .pagination-btn {
            min-width: 2.75rem; 
            height: 2.75rem; 
            border: 2px solid transparent;
            background: white; 
            border-radius: var(--radius-lg); 
            cursor: pointer;
            display: flex; 
            align-items: center; 
            justify-content: center;
            font-size: 0.9375rem; 
            font-weight: 600; 
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: var(--shadow-sm);
            color: var(--text);
        }
        .pagination-btn:hover { 
            border-color: var(--tref-blue); 
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
        }
        .pagination-btn.active { 
            background: linear-gradient(135deg, var(--tref-blue) 0%, var(--tref-blue-hover) 100%); 
            color: white; 
            border-color: transparent;
            box-shadow: var(--shadow-md), 0 0 0 4px var(--tref-blue-light);
        }
        .pagination-btn:disabled { 
            opacity: 0.4; 
            cursor: not-allowed;
            transform: none;
        }
        .pagination-btn:disabled:hover {
            border-color: transparent;
            box-shadow: var(--shadow-sm);
        }
        
        /* Responsive Pagination */
        @media (max-width: 768px) {
            .pagination {
                gap: 0.375rem;
                margin-top: 2rem;
            }
            .pagination-btn {
                min-width: 2.5rem;
                height: 2.5rem;
                font-size: 0.875rem;
            }
        }
        
        @media (max-width: 480px) {
            .pagination {
                gap: 0.25rem;
            }
            .pagination-btn {
                min-width: 2.25rem;
                height: 2.25rem;
                font-size: 0.8125rem;
            }
        }
        
        /* Map Button */
        .btn-map {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            white-space: nowrap;
        }
        .btn-map svg {
            flex-shrink: 0;
        }
        
        /* Responsive Map Button */
        @media (max-width: 768px) {
            .btn-map {
                padding: 0.625rem 1rem;
            }
        }
        
        @media (max-width: 640px) {
            .btn-map span {
                display: none;
            }
            .btn-map {
                min-width: 2.5rem;
                padding: 0.625rem;
            }
        }
        
        /* Footer */
        .footer { background: var(--text); color: white; padding: 3rem 0 1.5rem; margin-top: 2rem; }
        .footer-content { max-width: 1440px; margin: 0 auto; padding: 0 1.5rem; }
        .footer-grid { display: grid; grid-template-columns: 2fr repeat(3, 1fr); gap: 2rem; margin-bottom: 2rem; }
        @media (max-width: 768px) { .footer-grid { grid-template-columns: 1fr 1fr; } }
        @media (max-width: 480px) { .footer-grid { grid-template-columns: 1fr; } }
        .footer-brand p { color: #94a3b8; margin-top: 0.75rem; font-size: 0.875rem; line-height: 1.6; }
        .footer-title { font-weight: 600; margin-bottom: 1rem; }
        .footer-links { list-style: none; }
        .footer-links li { margin-bottom: 0.5rem; }
        .footer-links a { color: #94a3b8; text-decoration: none; font-size: 0.875rem; transition: color 0.2s; }
        .footer-links a:hover { color: white; }
        .footer-bottom { text-align: center; padding-top: 1.5rem; border-top: 1px solid #334155; font-size: 0.875rem; color: #64748b; }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-inner">
            <a href="{{ url('/') }}" class="logo">
                <svg class="logo-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2L2 7v10c0 5.55 3.84 10.74 10 12 6.16-1.26 10-6.45 10-12V7L12 2zm0 4l6 3v7c0 3.87-2.64 7.47-6 8.47-3.36-1-6-4.6-6-8.47V9l6-3z"/></svg>
                <span>Tref</span><span>Stays</span>
            </a>
            <nav class="header-nav">
                <a href="{{ url('/') }}">Browse Rentals</a>
                <a href="#">List Property</a>
                @guest
                    <a href="{{ route('login') }}" class="btn btn-outline">Sign In</a>
                    <a href="{{ route('register') }}" class="btn btn-primary">Sign Up</a>
                @else
                    <a href="{{ url('/home') }}">Dashboard</a>
                @endguest
            </nav>
        </div>
    </header>
    
    <!-- Search Bar -->
    <div class="search-bar-wrapper">
        <form class="search-bar" method="GET" action="{{ url('/search') }}" id="searchForm">
            <div class="search-input-group">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--text-muted)" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input type="text" name="location" class="search-input" placeholder="Location, city, or zipcode..." value="{{ request('location') }}" autocomplete="off">
            </div>
            <select name="country" class="search-select" id="countrySelect" aria-label="Select country" onchange="handleCountryChange(this)">
                <option value="">🌍 All Countries</option>
                <option value="us" {{ request('country') == 'us' ? 'selected' : '' }}>🇺🇸 United States</option>
                <option value="uk" {{ request('country') == 'uk' ? 'selected' : '' }}>🇬🇧 United Kingdom</option>
                <option value="ca" {{ request('country') == 'ca' ? 'selected' : '' }}>🇨🇦 Canada</option>
                <option value="il" {{ request('country') == 'il' ? 'selected' : '' }}>🇮🇱 Israel</option>
                <option value="be" {{ request('country') == 'be' ? 'selected' : '' }}>🇧🇪 Belgium</option>
                <option disabled>────────</option>
                <option value="map">🗺️ Map View</option>
            </select>
            <select name="type" class="search-select" aria-label="Select property type">
                <option value="">🏠 All Types</option>
                <option value="house" {{ request('type') == 'house' ? 'selected' : '' }}>House</option>
                <option value="apartment" {{ request('type') == 'apartment' ? 'selected' : '' }}>Apartment</option>
                <option value="condo" {{ request('type') == 'condo' ? 'selected' : '' }}>Condo</option>
                <option value="villa" {{ request('type') == 'villa' ? 'selected' : '' }}>Villa</option>
                <option value="cottage" {{ request('type') == 'cottage' ? 'selected' : '' }}>Cottage</option>
                <option value="cabin" {{ request('type') == 'cabin' ? 'selected' : '' }}>Cabin</option>
            </select>
            <select name="rental_type" class="search-select" aria-label="Select rental type">
                <option value="">📅 Rental Type</option>
                <option value="short" {{ request('rental_type') == 'short' ? 'selected' : '' }}>Short Term</option>
                <option value="long" {{ request('rental_type') == 'long' ? 'selected' : '' }}>Long Term</option>
                <option value="vacation" {{ request('rental_type') == 'vacation' ? 'selected' : '' }}>Vacation</option>
            </select>
            <input type="date" name="checkin" class="search-select" value="{{ request('checkin') }}" aria-label="Check-in date">
            <input type="date" name="checkout" class="search-select" value="{{ request('checkout') }}" aria-label="Check-out date">
            <button type="submit" class="btn btn-primary">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <span>Search</span>
            </button>
        </form>
    </div>
    
    <!-- Active Filters -->
    <div class="filter-tags">
        @php
            $hasFilters = request('location') || request('country') || request('type') || request('rental_type') || request('checkin') || request('kosher_kitchen') || request('bedrooms') || request('max_price');
        @endphp
        @if($hasFilters)
        <span style="font-size:0.8125rem;color:var(--text-muted);margin-right:0.5rem;">Active Filters:</span>
        @if(request('location'))
        <span class="filter-tag active">
            📍 {{ request('location') }}
            <a href="{{ url('/search?' . http_build_query(array_diff_key(request()->all(), ['location' => '']))) }}" style="color:inherit;text-decoration:none;margin-left:0.25rem;">✕</a>
        </span>
        @endif
        @if(request('country'))
        <span class="filter-tag active">
            🌍 {{ strtoupper(request('country')) }}
            <a href="{{ url('/search?' . http_build_query(array_diff_key(request()->all(), ['country' => '']))) }}" style="color:inherit;text-decoration:none;margin-left:0.25rem;">✕</a>
        </span>
        @endif
        @if(request('type'))
        <span class="filter-tag active">
            {{ ucfirst(request('type')) }}
            <a href="{{ url('/search?' . http_build_query(array_diff_key(request()->all(), ['type' => '']))) }}" style="color:inherit;text-decoration:none;margin-left:0.25rem;">✕</a>
        </span>
        @endif
        @if(request('rental_type'))
        <span class="filter-tag active">
            {{ request('rental_type') == 'short' ? 'Short Term' : (request('rental_type') == 'long' ? 'Long Term' : 'Vacation') }}
            <a href="{{ url('/search?' . http_build_query(array_diff_key(request()->all(), ['rental_type' => '']))) }}" style="color:inherit;text-decoration:none;margin-left:0.25rem;">✕</a>
        </span>
        @endif
        @if(request('checkin') && request('checkout'))
        <span class="filter-tag active">
            📅 {{ \Carbon\Carbon::parse(request('checkin'))->format('M d') }} - {{ \Carbon\Carbon::parse(request('checkout'))->format('M d') }}
            <a href="{{ url('/search?' . http_build_query(array_diff_key(request()->all(), ['checkin' => '', 'checkout' => '']))) }}" style="color:inherit;text-decoration:none;margin-left:0.25rem;">✕</a>
        </span>
        @endif
        @if(request('kosher_kitchen'))
        <span class="filter-tag active">
            ✡️ Kosher Kitchen
            <a href="{{ url('/search?' . http_build_query(array_diff_key(request()->all(), ['kosher_kitchen' => '']))) }}" style="color:inherit;text-decoration:none;margin-left:0.25rem;">✕</a>
        </span>
        @endif
        @if(request('bedrooms'))
        <span class="filter-tag active">
            🛏️ {{ request('bedrooms') }}+ beds
            <a href="{{ url('/search?' . http_build_query(array_diff_key(request()->all(), ['bedrooms' => '']))) }}" style="color:inherit;text-decoration:none;margin-left:0.25rem;">✕</a>
        </span>
        @endif
        @if(request('max_price'))
        <span class="filter-tag active">
            💰 Up to ${{ request('max_price') }}
            <a href="{{ url('/search?' . http_build_query(array_diff_key(request()->all(), ['max_price' => '']))) }}" style="color:inherit;text-decoration:none;margin-left:0.25rem;">✕</a>
        </span>
        @endif
        <a href="{{ url('/search') }}" style="font-size:0.8125rem;color:var(--tref-blue);margin-left:0.5rem;">Clear All</a>
        @else
        <span style="font-size:0.8125rem;color:var(--text-muted);">Showing all properties. Use filters to narrow results.</span>
        @endif
    </div>
    
    <div class="main-layout">
        <!-- Sidebar Filters -->
        <aside class="sidebar">
            <div class="filter-section">
                <h3 class="filter-title">Price Range</h3>
                <div class="price-range">
                    <input type="number" class="price-input" placeholder="Min" name="min_price">
                    <span>-</span>
                    <input type="number" class="price-input" placeholder="Max" name="max_price">
                </div>
            </div>
            
            <div class="filter-section">
                <h3 class="filter-title">Property Type</h3>
                <div class="filter-options">
                    @foreach(['House', 'Apartment', 'Condo', 'Villa', 'Cottage', 'Cabin', 'Townhouse'] as $type)
                    <label class="filter-option">
                        <input type="checkbox" name="property_type[]" value="{{ strtolower($type) }}">
                        {{ $type }}
                    </label>
                    @endforeach
                </div>
            </div>
            
            <div class="filter-section">
                <h3 class="filter-title">Bedrooms</h3>
                <div class="filter-options" style="flex-direction:row;flex-wrap:wrap;gap:0.5rem;">
                    @foreach(['Any', '1', '2', '3', '4', '5+'] as $beds)
                    <button type="button" class="btn btn-outline btn-sm {{ $beds === 'Any' ? 'btn-primary' : '' }}" style="{{ $beds === 'Any' ? 'background:var(--tref-blue);color:white;border-color:var(--tref-blue);' : '' }}">{{ $beds }}</button>
                    @endforeach
                </div>
            </div>
            
            <div class="filter-section">
                <h3 class="filter-title">Bathrooms</h3>
                <div class="filter-options" style="flex-direction:row;flex-wrap:wrap;gap:0.5rem;">
                    @foreach(['Any', '1', '2', '3', '4+'] as $baths)
                    <button type="button" class="btn btn-outline btn-sm {{ $baths === 'Any' ? 'btn-primary' : '' }}" style="{{ $baths === 'Any' ? 'background:var(--tref-blue);color:white;border-color:var(--tref-blue);' : '' }}">{{ $baths }}</button>
                    @endforeach
                </div>
            </div>
            
            <div class="filter-section">
                <h3 class="filter-title">Kosher Amenities</h3>
                <div class="filter-options">
                    <label class="filter-option">
                        <input type="checkbox" name="kosher_kitchen" value="1" checked>
                        Kosher Kitchen
                    </label>
                    <label class="filter-option">
                        <input type="checkbox" name="shabbos_friendly" value="1">
                        Shabbos Friendly
                    </label>
                    <label class="filter-option">
                        <input type="checkbox" name="nearby_shul" value="1">
                        Nearby Shul
                    </label>
                    <label class="filter-option">
                        <input type="checkbox" name="nearby_mikva" value="1">
                        Nearby Mikva
                    </label>
                </div>
            </div>
            
            <div class="filter-section">
                <h3 class="filter-title">Amenities</h3>
                <div class="filter-options">
                    @foreach(['WiFi', 'Air Conditioning', 'Pool', 'Hot Tub', 'Kitchen', 'Washer/Dryer', 'Free Parking', 'Gym', 'Fireplace', 'Balcony'] as $amenity)
                    <label class="filter-option">
                        <input type="checkbox" name="amenities[]" value="{{ $amenity }}">
                        {{ $amenity }}
                    </label>
                    @endforeach
                </div>
            </div>
            
            <button class="btn btn-primary" style="width:100%;" onclick="document.getElementById('searchForm').submit();">Apply Filters</button>
        </aside>
        
        <!-- Results -->
        <main class="results-area">
            @php
            // Fetch real properties from database
            $dbPropertiesQuery = \App\SMD\RsProperty::with(['images'])
                ->where('active', true);
            
            // Apply location filter
            if (request('location')) {
                $searchLocation = request('location');
                $dbPropertiesQuery->where(function($q) use ($searchLocation) {
                    $q->where('city', 'LIKE', '%' . $searchLocation . '%')
                      ->orWhere('state', 'LIKE', '%' . $searchLocation . '%')
                      ->orWhere('zipcode', 'LIKE', '%' . $searchLocation . '%')
                      ->orWhere('map_address', 'LIKE', '%' . $searchLocation . '%');
                });
            }
            
            // Apply type filter
            if (request('type')) {
                $dbPropertiesQuery->where('property_type', request('type'));
            }
            
            // Apply bedroom filter
            if (request('bedrooms')) {
                $dbPropertiesQuery->where('bedroom_count', '>=', (int)request('bedrooms'));
            }
            
            // Apply price filter
            if (request('max_price')) {
                $dbPropertiesQuery->where('price', '<=', (int)request('max_price'));
            }
            
            // Apply kosher filter
            if (request('kosher_kitchen')) {
                $dbPropertiesQuery->whereRaw("json_extract(kosher_info, '$.kosher_kitchen') = true");
            }
            
            // Apply sorting
            if (request('sort') == 'price_asc') {
                $dbPropertiesQuery->orderBy('price', 'asc');
            } elseif (request('sort') == 'price_desc') {
                $dbPropertiesQuery->orderBy('price', 'desc');
            } elseif (request('sort') == 'newest') {
                $dbPropertiesQuery->orderBy('created_at', 'desc');
            } else {
                $dbPropertiesQuery->orderBy('created_at', 'desc');
            }
            
            $dbProperties = $dbPropertiesQuery->get();
            
            // Transform to array format for display
            $allProperties = $dbProperties->map(function($p) {
                $location = implode(', ', array_filter([$p->city, $p->state])) ?: 'Location';
                $kosherInfo = json_decode($p->kosher_info ?? '{}', true);
                $primaryImage = $p->images->where('is_primary', true)->first();
                $image = $primaryImage ? $primaryImage->image_url : ($p->images->first()->image_url ?? 'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=600');
                
                return [
                    'id' => $p->id,
                    'title' => $p->title,
                    'location' => $location,
                    'type' => $p->property_type ?? 'House',
                    'price' => $p->price ?? 0,
                    'currency' => $p->currency ?? 'USD',
                    'beds' => $p->bedroom_count ?? 1,
                    'baths' => $p->bathroom_count ?? 1,
                    'guests' => $p->guest_count ?? 1,
                    'rating' => 4.8,
                    'reviews' => 0,
                    'kosher' => $kosherInfo['kosher_kitchen'] ?? false,
                    'image' => $image,
                ];
            })->toArray();
            
            // If no properties in database, show sample properties
            if (empty($allProperties)) {
                $allProperties = [
                    ['id' => 1, 'title' => 'Beautiful Lakefront Cottage', 'location' => 'Lakewood, NJ', 'type' => 'House', 'price' => 275, 'currency' => 'USD', 'beds' => 4, 'baths' => 3, 'guests' => 8, 'rating' => 4.92, 'reviews' => 128, 'kosher' => true, 'image' => 'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=600'],
                    ['id' => 2, 'title' => 'Modern Downtown Apartment', 'location' => 'Lakewood, NJ', 'type' => 'Apartment', 'price' => 150, 'currency' => 'USD', 'beds' => 2, 'baths' => 1, 'guests' => 4, 'rating' => 4.85, 'reviews' => 94, 'kosher' => true, 'image' => 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=600'],
                    ['id' => 3, 'title' => 'Spacious Family Home', 'location' => 'Toms River, NJ', 'type' => 'House', 'price' => 320, 'currency' => 'USD', 'beds' => 5, 'baths' => 3, 'guests' => 10, 'rating' => 4.96, 'reviews' => 67, 'kosher' => true, 'image' => 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?w=600'],
                    ['id' => 4, 'title' => 'Cozy Beach Cottage', 'location' => 'Point Pleasant, NJ', 'type' => 'Cottage', 'price' => 195, 'currency' => 'USD', 'beds' => 3, 'baths' => 2, 'guests' => 6, 'rating' => 4.78, 'reviews' => 45, 'kosher' => false, 'image' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=600'],
                ];
            }
            
            $properties = $allProperties;
            $resultCount = count($properties);
            $locationText = request('location') ? request('location') : (request('country') ? strtoupper(request('country')) : 'All Locations');
            @endphp
            
            <div class="results-header">
                <div class="results-count">
                    {{ $resultCount }} {{ $resultCount == 1 ? 'property' : 'properties' }} <span>in {{ $locationText }}</span>
                </div>
                <div style="display:flex;align-items:center;gap:1rem;">
                    <select class="sort-select" name="sort" onchange="updateSort(this.value)">
                        <option value="recommended" {{ request('sort') == 'recommended' ? 'selected' : '' }}>Sort: Recommended</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                        <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Top Rated</option>
                    </select>
                    <div class="view-toggle">
                        <button class="view-btn active" onclick="setView('grid')" title="Grid View">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                        </button>
                        <button class="view-btn" onclick="setView('list')" title="List View">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="property-grid" id="propertyGrid">
                @if(count($properties) === 0)
                <div style="grid-column: 1/-1; text-align:center; padding:3rem;">
                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="var(--text-muted)" stroke-width="1.5" style="margin:0 auto 1rem;"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="8" y1="11" x2="14" y2="11"/></svg>
                    <h3 style="font-size:1.25rem;font-weight:600;margin-bottom:0.5rem;">No properties found</h3>
                    <p style="color:var(--text-muted);">Try adjusting your filters or search in a different area.</p>
                    <a href="{{ url('/search') }}" class="btn btn-primary" style="margin-top:1rem;">Clear Filters</a>
                </div>
                @endif
                
                @foreach($properties as $idx => $prop)
                <div class="property-card">
                    <div class="property-image">
                        <span class="property-badge">{{ $prop['type'] }}</span>
                        @if($prop['kosher'])
                        <span class="kosher-badge">
                            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
                            Kosher
                        </span>
                        @endif
                        <button class="property-favorite" onclick="this.classList.toggle('active')">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                        </button>
                        <img src="{{ $prop['image'] }}" alt="{{ $prop['title'] }}" loading="lazy">
                        <span class="property-price">{{ currency_symbol($prop['currency'] ?? 'USD') }}{{ $prop['price'] }}<span>/night</span></span>
                    </div>
                    <div class="property-content">
                        <div class="property-type">{{ $prop['type'] }}</div>
                        <h3 class="property-title">
                            <a href="{{ url('/property/' . $prop['id']) }}">{{ $prop['title'] }}</a>
                        </h3>
                        <div class="property-location">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            {{ $prop['location'] }}
                        </div>
                        <div class="property-features">
                            <span class="property-feature">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                                {{ $prop['guests'] }}
                            </span>
                            <span class="property-feature">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 4v16"/><path d="M2 8h18a2 2 0 0 1 2 2v10"/><path d="M2 17h20"/></svg>
                                {{ $prop['beds'] }}
                            </span>
                            <span class="property-feature">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 6 6.5 3.5a1.5 1.5 0 0 0-1-.5C4.683 3 4 3.683 4 4.5V17a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-5"/></svg>
                                {{ $prop['baths'] }}
                            </span>
                        </div>
                        <div class="property-rating">
                            <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                            <span>{{ $prop['rating'] }}</span>
                            <span class="count">({{ $prop['reviews'] }})</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="pagination">
                <button class="pagination-btn" disabled>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
                </button>
                <button class="pagination-btn active">1</button>
                <button class="pagination-btn">2</button>
                <button class="pagination-btn">3</button>
                <button class="pagination-btn">4</button>
                <button class="pagination-btn">...</button>
                <button class="pagination-btn">25</button>
                <button class="pagination-btn">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
                </button>
            </div>
        </main>
    </div>
    
    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-grid">
                <div class="footer-brand">
                    <a href="{{ url('/') }}" class="logo" style="color:white;">
                        <svg class="logo-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2L2 7v10c0 5.55 3.84 10.74 10 12 6.16-1.26 10-6.45 10-12V7L12 2zm0 4l6 3v7c0 3.87-2.64 7.47-6 8.47-3.36-1-6-4.6-6-8.47V9l6-3z"/></svg>
                        <span style="color:var(--tref-blue);">Tref</span><span>Stays</span>
                    </a>
                    <p>Find your perfect kosher-friendly vacation rental. Trusted by thousands of families worldwide.</p>
                </div>
                <div>
                    <h4 class="footer-title">Explore</h4>
                    <ul class="footer-links">
                        <li><a href="#">Browse Rentals</a></li>
                        <li><a href="#">Popular Destinations</a></li>
                        <li><a href="#">Kosher Properties</a></li>
                        <li><a href="#">Last Minute Deals</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="footer-title">Host</h4>
                    <ul class="footer-links">
                        <li><a href="#">List Your Property</a></li>
                        <li><a href="#">Host Resources</a></li>
                        <li><a href="#">Host Guidelines</a></li>
                        <li><a href="#">Community</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="footer-title">Support</h4>
                    <ul class="footer-links">
                        <li><a href="#">Help Center</a></li>
                        <li><a href="#">Contact Us</a></li>
                        <li><a href="#">Cancellation Policy</a></li>
                        <li><a href="#">Trust & Safety</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                © {{ date('Y') }} Tref Stays. All rights reserved.
            </div>
        </div>
    </footer>
    
    <script>
        function setView(view) {
            const grid = document.getElementById('propertyGrid');
            const cards = grid.querySelectorAll('.property-card');
            const btns = document.querySelectorAll('.view-btn');
            
            btns.forEach(btn => btn.classList.remove('active'));
            event.currentTarget.classList.add('active');
            
            if (view === 'list') {
                grid.classList.add('list');
                cards.forEach(card => card.classList.add('list-view'));
            } else {
                grid.classList.remove('list');
                cards.forEach(card => card.classList.remove('list-view'));
            }
        }
        
        function updateSort(value) {
            const url = new URL(window.location);
            url.searchParams.set('sort', value);
            window.location = url;
        }
        
        function toggleFilters() {
            const panel = document.querySelector('.filters-panel');
            const icon = document.querySelector('.toggle-icon');
            if (panel) {
                panel.classList.toggle('show');
                if (icon) {
                    icon.style.transform = panel.classList.contains('show') ? 'rotate(180deg)' : 'rotate(0)';
                }
            }
        }
        
        // Prevent form submission on enter in search input
        document.addEventListener('DOMContentLoaded', function() {
            const searchForm = document.getElementById('searchForm');
            const searchInputs = searchForm.querySelectorAll('input[type="text"], input[type="date"]');
            
            searchInputs.forEach(input => {
                input.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        searchForm.submit();
                    }
                });
            });
            
            // Auto-submit on select change for better UX (optional)
            const autoSubmitSelects = searchForm.querySelectorAll('select');
            autoSubmitSelects.forEach(select => {
                select.addEventListener('change', function() {
                    // Uncomment next line to enable auto-submit on select change
                    // searchForm.submit();
                });
            });
            
            // Sticky search bar effect
            let lastScroll = 0;
            const searchWrapper = document.querySelector('.search-bar-wrapper');
            
            window.addEventListener('scroll', function() {
                const currentScroll = window.pageYOffset;
                
                if (currentScroll > 100) {
                    searchWrapper.style.boxShadow = '0 4px 12px rgba(0,0,0,0.1)';
                } else {
                    searchWrapper.style.boxShadow = '0 2px 4px rgba(0,0,0,0.05)';
                }
                
                lastScroll = currentScroll;
            });
        });

        // Handle country dropdown change to detect map option
        function handleCountryChange(select) {
            if (select.value === 'map') {
                // Implement map view
                alert('Map view feature - implement based on your requirements');
                // Example: window.location.href = '/search/map';
                select.value = ''; // Reset to All Countries
            }
        }
    </script>
</body>
</html>
