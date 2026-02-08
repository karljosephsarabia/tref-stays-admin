<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Admin Dashboard'); ?> - IVR Reservation System</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        :root {
            /* Tref Stays Brand Colors - Solid Blue Theme */
            --primary: #2563EB;
            --primary-dark: #1D4ED8;
            --primary-light: #3B82F6;
            --primary-lighter: #60A5FA;
            --secondary: #2563EB;
            --accent: #F59E0B;
            --dark: #0F172A;
            --dark-light: #1E293B;
            --gray-100: #F8FAFC;
            --gray-200: #E2E8F0;
            --gray-300: #CBD5E1;
            --gray-400: #94A3B8;
            --gray-500: #64748B;
            --gray-600: #475569;
            --success: #10B981;
            --warning: #F59E0B;
            --danger: #EF4444;
            --info: #0EA5E9;
            --sidebar-width: 260px;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--gray-100);
            color: var(--dark);
            min-height: 100vh;
        }
        
        /* Sidebar */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            width: var(--sidebar-width);
            background: linear-gradient(180deg, var(--dark) 0%, var(--dark-light) 100%);
            color: white;
            padding: 0;
            overflow-y: auto;
            z-index: 1000;
            transition: transform 0.3s ease;
        }
        
        .sidebar-header {
            padding: 20px 24px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            gap: 12px;
            background: var(--primary);
        }
        
        .sidebar-logo {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .sidebar-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        
        .sidebar-brand {
            font-size: 18px;
            font-weight: 700;
            color: white;
        }
        
        .sidebar-nav {
            padding: 16px 0;
        }
        
        .nav-section {
            padding: 8px 24px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            color: var(--gray-400);
            letter-spacing: 0.5px;
            margin-top: 16px;
        }
        
        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 24px;
            color: var(--gray-400);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }
        
        .nav-item:hover {
            background: rgba(255,255,255,0.05);
            color: white;
        }
        
        .nav-item.active {
            background: rgba(37, 99, 235, 0.15);
            color: var(--primary-light);
            border-left-color: var(--primary-light);
        }
        
        .nav-item i {
            width: 20px;
            text-align: center;
            font-size: 16px;
        }
        
        .nav-badge {
            margin-left: auto;
            background: var(--primary);
            color: white;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 11px;
            font-weight: 600;
        }
        
        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
        }
        
        /* Header */
        .header {
            background: white;
            padding: 16px 32px;
            border-bottom: 1px solid var(--gray-200);
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .header-left {
            display: flex;
            align-items: center;
            gap: 24px;
        }
        
        .menu-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
            color: var(--gray-600);
        }
        
        .page-title {
            font-size: 20px;
            font-weight: 600;
        }
        
        .header-search {
            display: flex;
            align-items: center;
            gap: 8px;
            background: var(--gray-100);
            padding: 10px 16px;
            border-radius: 24px;
            width: 300px;
        }
        
        .header-search input {
            border: none;
            background: none;
            outline: none;
            width: 100%;
            font-size: 14px;
        }
        
        .header-search i {
            color: var(--gray-400);
        }
        
        .header-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        
        .header-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--gray-100);
            color: var(--gray-600);
            cursor: pointer;
            position: relative;
            transition: all 0.2s;
        }
        
        .header-icon:hover {
            background: var(--gray-200);
        }
        
        .header-icon .badge {
            position: absolute;
            top: -2px;
            right: -2px;
            background: var(--primary);
            color: white;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            font-size: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }
        
        .user-menu {
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            padding: 8px 12px;
            border-radius: 24px;
            transition: background 0.2s;
        }
        
        .user-menu:hover {
            background: var(--gray-100);
        }
        
        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 14px;
        }
        
        .user-info {
            text-align: right;
        }
        
        .user-name {
            font-size: 14px;
            font-weight: 600;
        }
        
        .user-role {
            font-size: 12px;
            color: var(--gray-500);
        }
        
        /* Content Area */
        .content {
            padding: 32px;
        }
        
        /* Cards */
        .card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.04);
            overflow: hidden;
        }
        
        .card-header {
            padding: 20px 24px;
            border-bottom: 1px solid var(--gray-200);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .card-title {
            font-size: 16px;
            font-weight: 600;
        }
        
        .card-body {
            padding: 24px;
        }
        
        /* Stat Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 24px;
            margin-bottom: 32px;
        }
        
        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 24px;
            display: flex;
            align-items: flex-start;
            gap: 16px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.04);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }
        
        .stat-icon {
            width: 52px;
            height: 52px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
        }
        
        .stat-icon.primary { background: rgba(255,56,92,0.1); color: var(--primary); }
        .stat-icon.success { background: rgba(16,185,129,0.1); color: var(--success); }
        .stat-icon.warning { background: rgba(245,158,11,0.1); color: var(--warning); }
        .stat-icon.info { background: rgba(59,130,246,0.1); color: var(--info); }
        .stat-icon.secondary { background: rgba(0,166,153,0.1); color: var(--secondary); }
        
        .stat-content {
            flex: 1;
        }
        
        .stat-label {
            font-size: 13px;
            color: var(--gray-500);
            margin-bottom: 4px;
        }
        
        .stat-value {
            font-size: 28px;
            font-weight: 700;
            line-height: 1.2;
        }
        
        .stat-change {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 12px;
            margin-top: 8px;
            padding: 4px 8px;
            border-radius: 6px;
        }
        
        .stat-change.positive { background: rgba(16,185,129,0.1); color: var(--success); }
        .stat-change.negative { background: rgba(239,68,68,0.1); color: var(--danger); }
        
        /* Tables */
        .table-container {
            overflow-x: auto;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th {
            text-align: left;
            padding: 12px 16px;
            font-size: 12px;
            font-weight: 600;
            color: var(--gray-500);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            background: var(--gray-100);
            border-bottom: 1px solid var(--gray-200);
        }
        
        td {
            padding: 16px;
            border-bottom: 1px solid var(--gray-200);
            font-size: 14px;
        }
        
        tr:hover {
            background: var(--gray-100);
        }
        
        tr:last-child td {
            border-bottom: none;
        }
        
        /* Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .badge-success { background: rgba(16,185,129,0.1); color: var(--success); }
        .badge-warning { background: rgba(245,158,11,0.1); color: var(--warning); }
        .badge-danger { background: rgba(239,68,68,0.1); color: var(--danger); }
        .badge-info { background: rgba(59,130,246,0.1); color: var(--info); }
        .badge-primary { background: rgba(255,56,92,0.1); color: var(--primary); }
        .badge-secondary { background: var(--gray-200); color: var(--gray-600); }
        
        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
            text-decoration: none;
        }
        
        .btn-primary {
            background: var(--primary);
            color: white;
        }
        
        .btn-primary:hover {
            background: var(--primary-dark);
        }
        
        .btn-secondary {
            background: var(--gray-200);
            color: var(--gray-600);
        }
        
        .btn-secondary:hover {
            background: var(--gray-300);
        }
        
        .btn-success { background: var(--success); color: white; }
        .btn-warning { background: var(--warning); color: white; }
        .btn-danger { background: var(--danger); color: white; }
        .btn-info { background: var(--info); color: white; }
        
        .btn-sm {
            padding: 6px 12px;
            font-size: 12px;
        }
        
        .btn-icon {
            width: 32px;
            height: 32px;
            padding: 0;
            justify-content: center;
            border-radius: 8px;
        }
        
        /* Forms */
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 6px;
            color: var(--gray-600);
        }
        
        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid var(--gray-300);
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(255,56,92,0.1);
        }
        
        select.form-control {
            appearance: none;
            background: white url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23717171' d='M6 8L1 3h10z'/%3E%3C/svg%3E") no-repeat right 16px center;
        }
        
        /* Modal */
        .modal-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 2000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s;
        }
        
        .modal-backdrop.active {
            opacity: 1;
            visibility: visible;
        }
        
        .modal {
            background: white;
            border-radius: 16px;
            width: 100%;
            max-width: 560px;
            max-height: 90vh;
            overflow-y: auto;
            transform: translateY(20px);
            transition: transform 0.3s;
        }
        
        .modal-backdrop.active .modal {
            transform: translateY(0);
        }
        
        .modal-header {
            padding: 20px 24px;
            border-bottom: 1px solid var(--gray-200);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .modal-title {
            font-size: 18px;
            font-weight: 600;
        }
        
        .modal-close {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background 0.2s;
        }
        
        .modal-close:hover {
            background: var(--gray-100);
        }
        
        .modal-body {
            padding: 24px;
        }
        
        .modal-footer {
            padding: 16px 24px;
            border-top: 1px solid var(--gray-200);
            display: flex;
            justify-content: flex-end;
            gap: 12px;
        }
        
        /* Charts */
        .chart-container {
            position: relative;
            height: 300px;
        }
        
        /* Grid */
        .grid-2 {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 24px;
        }
        
        .grid-3 {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
        }
        
        /* Pagination */
        .pagination {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 24px;
            justify-content: center;
        }
        
        .pagination a, .pagination span {
            padding: 8px 14px;
            border-radius: 8px;
            font-size: 14px;
            text-decoration: none;
            color: var(--gray-600);
            background: white;
            border: 1px solid var(--gray-300);
        }
        
        .pagination a:hover {
            background: var(--gray-100);
        }
        
        .pagination .active span {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }
        
        /* Alerts */
        .alert {
            padding: 16px 20px;
            border-radius: 12px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .alert-success { background: rgba(16,185,129,0.1); color: var(--success); }
        .alert-danger { background: rgba(239,68,68,0.1); color: var(--danger); }
        .alert-warning { background: rgba(245,158,11,0.1); color: var(--warning); }
        .alert-info { background: rgba(59,130,246,0.1); color: var(--info); }
        
        /* Filters */
        .filters {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 24px;
            flex-wrap: wrap;
        }
        
        .filter-group {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .filter-group label {
            font-size: 13px;
            color: var(--gray-500);
        }
        
        .filter-group select, .filter-group input {
            padding: 8px 12px;
            border: 1px solid var(--gray-300);
            border-radius: 8px;
            font-size: 13px;
        }
        
        /* User Avatar in table */
        .user-cell {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .user-avatar-sm {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 13px;
        }
        
        .user-details {
            line-height: 1.3;
        }
        
        .user-details .name {
            font-weight: 500;
        }
        
        .user-details .email {
            font-size: 12px;
            color: var(--gray-500);
        }
        
        /* Property Card */
        .property-cell {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .property-thumb {
            width: 56px;
            height: 40px;
            border-radius: 8px;
            background: var(--gray-200);
            object-fit: cover;
        }
        
        /* Action buttons */
        .actions {
            display: flex;
            gap: 8px;
        }
        
        /* Responsive */
        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.active {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .menu-toggle {
                display: block;
            }
            
            .grid-2, .grid-3 {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 768px) {
            .header-search {
                display: none;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .content {
                padding: 16px;
            }
        }
        
        /* Toast notifications */
        .toast-container {
            position: fixed;
            top: 24px;
            right: 24px;
            z-index: 3000;
        }
        
        .toast {
            background: white;
            padding: 16px 20px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
            animation: slideIn 0.3s ease;
        }
        
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        .toast-success { border-left: 4px solid var(--success); }
        .toast-error { border-left: 4px solid var(--danger); }
        .toast-warning { border-left: 4px solid var(--warning); }
        
        /* Quick stats row */
        .quick-stats {
            display: flex;
            gap: 24px;
            margin-bottom: 8px;
        }
        
        .quick-stat {
            font-size: 13px;
            color: var(--gray-500);
        }
        
        .quick-stat strong {
            color: var(--dark);
        }
        
        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 48px 24px;
            color: var(--gray-500);
        }
        
        .empty-state i {
            font-size: 48px;
            margin-bottom: 16px;
            color: var(--gray-300);
        }
        
        .empty-state h3 {
            font-size: 18px;
            margin-bottom: 8px;
            color: var(--dark);
        }
        
        /* Dropdown menu */
        .dropdown {
            position: relative;
        }
        
        .dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            min-width: 200px;
            padding: 8px 0;
            opacity: 0;
            visibility: hidden;
            transform: translateY(10px);
            transition: all 0.2s;
            z-index: 100;
        }
        
        .dropdown:hover .dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        
        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 16px;
            color: var(--gray-600);
            text-decoration: none;
            font-size: 14px;
            transition: background 0.2s;
        }
        
        .dropdown-item:hover {
            background: var(--gray-100);
        }
        
        .dropdown-item.danger {
            color: var(--danger);
        }
        
        .dropdown-divider {
            height: 1px;
            background: var(--gray-200);
            margin: 8px 0;
        }
    </style>
    
    <?php echo $__env->yieldContent('styles'); ?>
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">
                <img src="<?php echo e(asset('images/logo.png')); ?>" alt="Logo">
            </div>
            <span class="sidebar-brand">Tref Stays</span>
        </div>
        
        <nav class="sidebar-nav">
            <a href="<?php echo e(route('admin.dashboard')); ?>" class="nav-item <?php echo e(request()->routeIs('admin.dashboard') ? 'active' : ''); ?>">
                <i class="fas fa-th-large"></i>
                <span>Dashboard</span>
            </a>
            
            <div class="nav-section">Management</div>
            
            <a href="<?php echo e(route('admin.users')); ?>" class="nav-item <?php echo e(request()->routeIs('admin.users*') ? 'active' : ''); ?>">
                <i class="fas fa-users"></i>
                <span>Users</span>
                <span class="nav-badge"><?php echo e(\App\RsUser::count()); ?></span>
            </a>
            
            <a href="<?php echo e(route('admin.properties')); ?>" class="nav-item <?php echo e(request()->routeIs('admin.properties*') ? 'active' : ''); ?>">
                <i class="fas fa-building"></i>
                <span>Properties</span>
            </a>
            
            <a href="<?php echo e(route('admin.reservations')); ?>" class="nav-item <?php echo e(request()->routeIs('admin.reservations*') ? 'active' : ''); ?>">
                <i class="fas fa-calendar-check"></i>
                <span>Reservations</span>
            </a>
            
            <div class="nav-section">Analytics</div>
            
            <a href="<?php echo e(route('admin.finances')); ?>" class="nav-item <?php echo e(request()->routeIs('admin.finances') ? 'active' : ''); ?>">
                <i class="fas fa-chart-line"></i>
                <span>Finances</span>
            </a>
            
            <a href="<?php echo e(route('admin.analytics')); ?>" class="nav-item <?php echo e(request()->routeIs('admin.analytics') ? 'active' : ''); ?>">
                <i class="fas fa-chart-bar"></i>
                <span>Analytics</span>
            </a>
            
            <div class="nav-section">System</div>
            
            <a href="<?php echo e(route('admin.settings')); ?>" class="nav-item <?php echo e(request()->routeIs('admin.settings') ? 'active' : ''); ?>">
                <i class="fas fa-cog"></i>
                <span>Settings</span>
            </a>
            
            <a href="<?php echo e(route('admin.logout')); ?>" class="nav-item">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </nav>
    </aside>
    
    <!-- Main Content -->
    <main class="main-content">
        <!-- Header -->
        <header class="header">
            <div class="header-left">
                <button class="menu-toggle" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                <h1 class="page-title"><?php echo $__env->yieldContent('page-title', 'Dashboard'); ?></h1>
            </div>
            
            <div class="header-right">
                <div class="header-icon">
                    <i class="fas fa-bell"></i>
                    <span class="badge">3</span>
                </div>
                
                <div class="dropdown">
                    <div class="user-menu">
                        <div class="user-avatar">
                            <?php echo e(strtoupper(substr(Auth::user()->first_name ?? 'A', 0, 1))); ?>

                        </div>
                        <div class="user-info">
                            <div class="user-name"><?php echo e(Auth::user()->first_name ?? 'Admin'); ?> <?php echo e(Auth::user()->last_name ?? ''); ?></div>
                            <div class="user-role">Administrator</div>
                        </div>
                        <i class="fas fa-chevron-down" style="font-size: 12px; color: var(--gray-400);"></i>
                    </div>
                    <div class="dropdown-menu">
                        <a href="<?php echo e(route('admin.settings')); ?>" class="dropdown-item">
                            <i class="fas fa-user"></i>
                            Profile Settings
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="<?php echo e(route('admin.logout')); ?>" class="dropdown-item danger">
                            <i class="fas fa-sign-out-alt"></i>
                            Logout
                        </a>
                    </div>
                </div>
            </div>
        </header>
        
        <!-- Content -->
        <div class="content">
            <?php if(session('success')): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>
            
            <?php if(session('error')): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <?php echo e(session('error')); ?>

                </div>
            <?php endif; ?>
            
            <?php echo $__env->yieldContent('content'); ?>
        </div>
    </main>
    
    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>
    
    <script>
        // Toggle sidebar
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
        }
        
        // Toast notification
        function showToast(message, type = 'success') {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className = `toast toast-${type}`;
            toast.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'exclamation-triangle'}"></i>
                <span>${message}</span>
            `;
            container.appendChild(toast);
            
            setTimeout(() => {
                toast.style.animation = 'slideIn 0.3s ease reverse';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }
        
        // CSRF token for AJAX requests
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Global fetch helper
        async function fetchApi(url, options = {}) {
            const defaultOptions = {
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            };
            
            const response = await fetch(url, { ...defaultOptions, ...options });
            return response.json();
        }
        
        // Format currency
        function formatCurrency(amount) {
            return new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'USD'
            }).format(amount);
        }
        
        // Format number
        function formatNumber(num) {
            return new Intl.NumberFormat('en-US').format(num);
        }
    </script>
    
    <?php echo $__env->yieldContent('scripts'); ?>
</body>
</html>
<?php /**PATH D:\Tref Website\Testing\ya last ha final ala - Copy\ivr\ivr-reservation-system-master\resources\views/admin/layouts/app.blade.php ENDPATH**/ ?>