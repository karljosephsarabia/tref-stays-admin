

<?php $__env->startSection('title', 'Dashboard'); ?>
<?php $__env->startSection('page-title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<!-- Welcome Banner -->
<div class="card" style="background: linear-gradient(135deg, var(--primary) 0%, #e31c5f 100%); color: white; margin-bottom: 32px;">
    <div class="card-body" style="padding: 32px;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h2 style="font-size: 24px; font-weight: 600; margin-bottom: 8px;">Welcome back, <?php echo e(Auth::user()->first_name ?? 'Admin'); ?>! 👋</h2>
                <p style="opacity: 0.9; font-size: 15px;">Here's what's happening with your properties today.</p>
            </div>
            <div style="text-align: right;">
                <div style="font-size: 32px; font-weight: 700;">$<?php echo e(number_format($totalRevenue, 2)); ?></div>
                <div style="opacity: 0.9; font-size: 13px;">Total Revenue</div>
            </div>
        </div>
    </div>
</div>

<!-- Stats Grid -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon primary">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-content">
            <div class="stat-label">Total Users</div>
            <div class="stat-value"><?php echo e(number_format($totalUsers)); ?></div>
            <div class="quick-stats">
                <span class="quick-stat"><strong><?php echo e($activeUsers); ?></strong> active</span>
            </div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon secondary">
            <i class="fas fa-building"></i>
        </div>
        <div class="stat-content">
            <div class="stat-label">Properties</div>
            <div class="stat-value"><?php echo e(number_format($totalProperties)); ?></div>
            <div class="quick-stats">
                <span class="quick-stat"><strong><?php echo e($activeProperties); ?></strong> active</span>
            </div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon success">
            <i class="fas fa-calendar-check"></i>
        </div>
        <div class="stat-content">
            <div class="stat-label">Reservations</div>
            <div class="stat-value"><?php echo e(number_format($totalReservations)); ?></div>
            <div class="quick-stats">
                <span class="quick-stat"><strong><?php echo e($pendingReservations); ?></strong> pending</span>
            </div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon warning">
            <i class="fas fa-dollar-sign"></i>
        </div>
        <div class="stat-content">
            <div class="stat-label">This Month Revenue</div>
            <div class="stat-value">$<?php echo e(number_format($thisMonthRevenue, 0)); ?></div>
            <?php if($revenueGrowth != 0): ?>
            <span class="stat-change <?php echo e($revenueGrowth >= 0 ? 'positive' : 'negative'); ?>">
                <i class="fas fa-arrow-<?php echo e($revenueGrowth >= 0 ? 'up' : 'down'); ?>"></i>
                <?php echo e(abs($revenueGrowth)); ?>% from last month
            </span>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Secondary Stats -->
<div class="stats-grid" style="margin-bottom: 32px;">
    <div class="stat-card">
        <div class="stat-icon info">
            <i class="fas fa-user-shield"></i>
        </div>
        <div class="stat-content">
            <div class="stat-label">Admins</div>
            <div class="stat-value"><?php echo e($totalAdmins); ?></div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(6,182,212,0.1); color: #06b6d4;">
            <i class="fas fa-user-tie"></i>
        </div>
        <div class="stat-content">
            <div class="stat-label">Brokers</div>
            <div class="stat-value"><?php echo e($totalBrokers); ?></div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(16,185,129,0.1); color: #10b981;">
            <i class="fas fa-home"></i>
        </div>
        <div class="stat-content">
            <div class="stat-label">Property Owners</div>
            <div class="stat-value"><?php echo e($totalOwners); ?></div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(245,158,11,0.1); color: #f59e0b;">
            <i class="fas fa-user"></i>
        </div>
        <div class="stat-content">
            <div class="stat-label">Customers</div>
            <div class="stat-value"><?php echo e($totalCustomers); ?></div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="grid-2" style="margin-bottom: 32px;">
    <!-- Revenue Chart -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Revenue Overview</h3>
        </div>
        <div class="card-body">
            <div class="chart-container">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Reservation Status Chart -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Reservations by Status</h3>
        </div>
        <div class="card-body">
            <div class="chart-container">
                <canvas id="statusChart"></canvas>
            </div>
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; margin-top: 24px;">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <span style="width: 12px; height: 12px; border-radius: 3px; background: #f59e0b;"></span>
                    <span style="font-size: 13px;">Pending (<?php echo e($pendingReservations); ?>)</span>
                </div>
                <div style="display: flex; align-items: center; gap: 8px;">
                    <span style="width: 12px; height: 12px; border-radius: 3px; background: #10b981;"></span>
                    <span style="font-size: 13px;">Confirmed (<?php echo e($confirmedReservations); ?>)</span>
                </div>
                <div style="display: flex; align-items: center; gap: 8px;">
                    <span style="width: 12px; height: 12px; border-radius: 3px; background: #3b82f6;"></span>
                    <span style="font-size: 13px;">Completed (<?php echo e($completedReservations); ?>)</span>
                </div>
                <div style="display: flex; align-items: center; gap: 8px;">
                    <span style="width: 12px; height: 12px; border-radius: 3px; background: #ef4444;"></span>
                    <span style="font-size: 13px;">Cancelled (<?php echo e($cancelledReservations); ?>)</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity Tables -->
<div class="grid-2">
    <!-- Recent Users -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Recent Users</h3>
            <a href="<?php echo e(route('admin.users')); ?>" class="btn btn-secondary btn-sm">View All</a>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Role</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $recentUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <div class="user-cell">
                                    <div class="user-avatar-sm">
                                        <?php echo e(strtoupper(substr($user->first_name ?? 'U', 0, 1))); ?>

                                    </div>
                                    <div class="user-details">
                                        <div class="name"><?php echo e($user->first_name); ?> <?php echo e($user->last_name); ?></div>
                                        <div class="email"><?php echo e($user->email); ?></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-<?php echo e($user->role_id == 'admin' ? 'primary' : ($user->role_id == 'broker' ? 'info' : 'secondary')); ?>">
                                    <?php echo e(ucfirst($user->role_id)); ?>

                                </span>
                            </td>
                            <td>
                                <?php if($user->active && $user->activated): ?>
                                    <span class="badge badge-success">Active</span>
                                <?php else: ?>
                                    <span class="badge badge-danger">Inactive</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="3" class="empty-state">
                                <p>No users found</p>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Recent Reservations -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Recent Reservations</h3>
            <a href="<?php echo e(route('admin.reservations')); ?>" class="btn btn-secondary btn-sm">View All</a>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Property</th>
                            <th>Guest</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $recentReservations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reservation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($reservation->property->title ?? 'N/A'); ?></td>
                            <td><?php echo e($reservation->customer->first_name ?? 'N/A'); ?> <?php echo e($reservation->customer->last_name ?? ''); ?></td>
                            <td><strong>$<?php echo e(number_format($reservation->total_price, 2)); ?></strong></td>
                            <td>
                                <?php
                                    $statusLabels = [1 => 'Pending', 2 => 'Confirmed', 3 => 'Cancelled', 4 => 'Completed'];
                                    $statusColors = [1 => 'warning', 2 => 'success', 3 => 'danger', 4 => 'info'];
                                ?>
                                <span class="badge badge-<?php echo e($statusColors[$reservation->status] ?? 'secondary'); ?>">
                                    <?php echo e($statusLabels[$reservation->status] ?? 'Unknown'); ?>

                                </span>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="4" class="empty-state">
                                <p>No reservations found</p>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Recent Properties -->
<div class="card" style="margin-top: 32px;">
    <div class="card-header">
        <h3 class="card-title">Recent Properties</h3>
        <a href="<?php echo e(route('admin.properties')); ?>" class="btn btn-secondary btn-sm">View All</a>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Property</th>
                        <th>Owner</th>
                        <th>Price/Night</th>
                        <th>Capacity</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $recentProperties; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $property): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>
                            <div class="property-cell">
                                <div class="property-thumb" style="background: linear-gradient(135deg, var(--gray-200), var(--gray-300));"></div>
                                <div>
                                    <div style="font-weight: 500;"><?php echo e($property->title); ?></div>
                                    <div style="font-size: 12px; color: var(--gray-500);"><?php echo e($property->map_address ?? 'No address'); ?></div>
                                </div>
                            </div>
                        </td>
                        <td><?php echo e($property->owner->first_name ?? 'N/A'); ?> <?php echo e($property->owner->last_name ?? ''); ?></td>
                        <td><strong>$<?php echo e(number_format($property->price, 2)); ?></strong></td>
                        <td>
                            <span style="font-size: 13px; color: var(--gray-500);">
                                <i class="fas fa-user"></i> <?php echo e($property->guest_count); ?> · 
                                <i class="fas fa-bed"></i> <?php echo e($property->bedroom_count); ?> · 
                                <i class="fas fa-bath"></i> <?php echo e($property->bathroom_count); ?>

                            </span>
                        </td>
                        <td>
                            <?php if($property->active): ?>
                                <span class="badge badge-success">Active</span>
                            <?php else: ?>
                                <span class="badge badge-danger">Inactive</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="empty-state">
                            <p>No properties found</p>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    const revenueData = <?php echo json_encode($monthlyRevenue, 15, 512) ?>;
    
    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: revenueData.map(d => d.month),
            datasets: [{
                label: 'Revenue',
                data: revenueData.map(d => d.revenue),
                borderColor: '#FF385C',
                backgroundColor: 'rgba(255, 56, 92, 0.1)',
                fill: true,
                tension: 0.4,
                borderWidth: 2,
                pointBackgroundColor: '#FF385C',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#222',
                    padding: 12,
                    cornerRadius: 8,
                    callbacks: {
                        label: function(context) {
                            return '$' + context.raw.toLocaleString();
                        }
                    }
                }
            },
            scales: {
                x: { grid: { display: false }, ticks: { font: { size: 11 }, color: '#717171' } },
                y: { grid: { color: '#f0f0f0' }, ticks: { font: { size: 11 }, color: '#717171', callback: function(value) { return '$' + value.toLocaleString(); } } }
            }
        }
    });
    
    // Status Chart
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    const statusData = <?php echo json_encode($reservationsByStatus, 15, 512) ?>;
    
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: statusData.map(d => d.status),
            datasets: [{
                data: statusData.map(d => d.count),
                backgroundColor: statusData.map(d => d.color),
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: { legend: { display: false }, tooltip: { backgroundColor: '#222', padding: 12, cornerRadius: 8 } }
        }
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Tref Website\Testing\ya last ha final ala - Copy\ivr\ivr-reservation-system-master\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>