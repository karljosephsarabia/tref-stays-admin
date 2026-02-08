@extends('admin.layouts.app')

@section('title', 'Users Management')
@section('page-title', 'Users Management')

@section('content')
<!-- Filters & Actions -->
<div class="card" style="margin-bottom: 24px;">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.users') }}" class="filters">
            <div class="filter-group">
                <input type="text" name="search" class="form-control" placeholder="Search users..." value="{{ request('search') }}" style="width: 250px;">
            </div>
            <div class="filter-group">
                <select name="role" class="form-control">
                    <option value="">All Roles</option>
                    @foreach($roles as $value => $label)
                        <option value="{{ $value }}" {{ request('role') == $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group">
                <select name="status" class="form-control">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <button type="submit" class="btn btn-secondary">
                <i class="fas fa-filter"></i> Filter
            </button>
            <a href="{{ route('admin.users') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> Clear
            </a>
            <div style="margin-left: auto;">
                <button type="button" class="btn btn-primary" onclick="openCreateModal()">
                    <i class="fas fa-plus"></i> Add User
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Users Table -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">All Users ({{ $users->total() }})</h3>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Phone</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr id="user-row-{{ $user->id }}">
                        <td>
                            <div class="user-cell">
                                <div class="user-avatar-sm" style="background: {{ ['admin' => '#8b5cf6', 'broker' => '#06b6d4', 'owner' => '#10b981', 'customer' => '#f59e0b'][$user->role_id] ?? '#6b7280' }};">
                                    {{ strtoupper(substr($user->first_name ?? 'U', 0, 1)) }}
                                </div>
                                <div class="user-details">
                                    <div class="name">{{ $user->first_name }} {{ $user->last_name }}</div>
                                    <div class="email">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $user->phone_number ?: 'N/A' }}</td>
                        <td>
                            <span class="badge badge-{{ ['admin' => 'primary', 'broker' => 'info', 'owner' => 'success', 'customer' => 'warning'][$user->role_id] ?? 'secondary' }}">
                                {{ ucfirst($user->role_id) }}
                            </span>
                        </td>
                        <td>
                            <label class="toggle-switch" style="display: inline-flex; align-items: center; cursor: pointer;">
                                <input type="checkbox" {{ ($user->active && $user->activated) ? 'checked' : '' }} onchange="toggleUserStatus({{ $user->id }}, this.checked)" style="display: none;">
                                <span class="badge {{ ($user->active && $user->activated) ? 'badge-success' : 'badge-danger' }}" id="status-badge-{{ $user->id }}">
                                    {{ ($user->active && $user->activated) ? 'Active' : 'Inactive' }}
                                </span>
                            </label>
                        </td>
                        <td>
                            @php
                                $rawDate = $user->getRawOriginal('created_at');
                                echo $rawDate ? date('M d, Y', strtotime($rawDate)) : 'N/A';
                            @endphp
                        </td>
                        <td>
                            <div class="actions">
                                <a href="{{ route('admin.users.details', $user->id) }}" class="btn btn-primary btn-icon" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <button class="btn btn-secondary btn-icon" onclick="editUser({{ $user->id }})" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                @if($user->id !== Auth::id())
                                <button class="btn btn-danger btn-icon" onclick="deleteUser({{ $user->id }})" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <i class="fas fa-users"></i>
                                <h3>No Users Found</h3>
                                <p>Try adjusting your filters or add a new user.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Pagination -->
@if($users->hasPages())
<div class="pagination">
    {{ $users->withQueryString()->links() }}
</div>
@endif

<!-- Create User Modal -->
<div class="modal-backdrop" id="createModal">
    <div class="modal">
        <div class="modal-header">
            <h3 class="modal-title">Add New User</h3>
            <div class="modal-close" onclick="closeModal('createModal')">
                <i class="fas fa-times"></i>
            </div>
        </div>
        <form id="createUserForm" onsubmit="submitCreateUser(event)">
            <div class="modal-body">
                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">First Name *</label>
                        <input type="text" name="first_name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Last Name *</label>
                        <input type="text" name="last_name" class="form-control" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Email Address *</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Password *</label>
                    <input type="password" name="password" class="form-control" minlength="6" required>
                </div>
                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Phone Number</label>
                        <input type="text" name="phone_number" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Role *</label>
                        <select name="role_id" class="form-control" required>
                            <option value="">Select Role</option>
                            @foreach($roles as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">PIN (Optional)</label>
                    <input type="text" name="pin" class="form-control" maxlength="10">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('createModal')">Cancel</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Create User
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit User Modal -->
<div class="modal-backdrop" id="editModal">
    <div class="modal">
        <div class="modal-header">
            <h3 class="modal-title">Edit User</h3>
            <div class="modal-close" onclick="closeModal('editModal')">
                <i class="fas fa-times"></i>
            </div>
        </div>
        <form id="editUserForm" onsubmit="submitEditUser(event)">
            <input type="hidden" name="user_id" id="edit_user_id">
            <div class="modal-body">
                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">First Name *</label>
                        <input type="text" name="first_name" id="edit_first_name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Last Name *</label>
                        <input type="text" name="last_name" id="edit_last_name" class="form-control" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Email Address *</label>
                    <input type="email" name="email" id="edit_email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Password (leave blank to keep current)</label>
                    <input type="password" name="password" class="form-control" minlength="6">
                </div>
                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Phone Number</label>
                        <input type="text" name="phone_number" id="edit_phone_number" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Role *</label>
                        <select name="role_id" id="edit_role_id" class="form-control" required>
                            @foreach($roles as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('editModal')">Cancel</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<!-- View User Modal -->
<div class="modal-backdrop" id="viewModal">
    <div class="modal">
        <div class="modal-header">
            <h3 class="modal-title">User Details</h3>
            <div class="modal-close" onclick="closeModal('viewModal')">
                <i class="fas fa-times"></i>
            </div>
        </div>
        <div class="modal-body" id="viewUserContent">
            <!-- Content loaded dynamically -->
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeModal('viewModal')">Close</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Store users data for edit - avoid Carbon serialization issues
    const usersData = [
        @foreach($users->items() as $user)
        {
            id: {{ $user->id }},
            first_name: "{{ $user->first_name ?? '' }}",
            last_name: "{{ $user->last_name ?? '' }}",
            email: "{{ $user->email ?? '' }}",
            phone_number: "{{ $user->phone_number ?? '' }}",
            role_id: {{ $user->role_id ?? 0 }},
            status: {{ $user->status ?? 1 }}
        }{{ !$loop->last ? ',' : '' }}
        @endforeach
    ];
    
    function openCreateModal() {
        document.getElementById('createUserForm').reset();
        document.getElementById('createModal').classList.add('active');
    }
    
    function closeModal(modalId) {
        document.getElementById(modalId).classList.remove('active');
    }
    
    function editUser(userId) {
        const user = usersData.find(u => u.id === userId);
        if (user) {
            document.getElementById('edit_user_id').value = user.id;
            document.getElementById('edit_first_name').value = user.first_name || '';
            document.getElementById('edit_last_name').value = user.last_name || '';
            document.getElementById('edit_email').value = user.email || '';
            document.getElementById('edit_phone_number').value = user.phone_number || '';
            document.getElementById('edit_role_id').value = user.role_id || '';
            document.getElementById('editModal').classList.add('active');
        }
    }
    
    function viewUser(userId) {
        const user = usersData.find(u => u.id === userId);
        if (user) {
            const content = `
                <div style="text-align: center; margin-bottom: 24px;">
                    <div class="user-avatar-sm" style="width: 80px; height: 80px; font-size: 32px; margin: 0 auto 16px; background: var(--primary);">
                        ${(user.first_name || 'U').charAt(0).toUpperCase()}
                    </div>
                    <h3 style="font-size: 20px; margin-bottom: 4px;">${user.first_name || ''} ${user.last_name || ''}</h3>
                    <p style="color: var(--gray-500);">${user.email}</p>
                </div>
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px;">
                    <div style="background: var(--gray-100); padding: 16px; border-radius: 12px;">
                        <div style="font-size: 12px; color: var(--gray-500); margin-bottom: 4px;">Role</div>
                        <div style="font-weight: 600; text-transform: capitalize;">${user.role_id}</div>
                    </div>
                    <div style="background: var(--gray-100); padding: 16px; border-radius: 12px;">
                        <div style="font-size: 12px; color: var(--gray-500); margin-bottom: 4px;">Status</div>
                        <div style="font-weight: 600;">${(user.active && user.activated) ? 'Active' : 'Inactive'}</div>
                    </div>
                    <div style="background: var(--gray-100); padding: 16px; border-radius: 12px;">
                        <div style="font-size: 12px; color: var(--gray-500); margin-bottom: 4px;">Phone</div>
                        <div style="font-weight: 600;">${user.phone_number || 'N/A'}</div>
                    </div>
                    <div style="background: var(--gray-100); padding: 16px; border-radius: 12px;">
                        <div style="font-size: 12px; color: var(--gray-500); margin-bottom: 4px;">PIN</div>
                        <div style="font-weight: 600;">${user.pin || 'N/A'}</div>
                    </div>
                </div>
            `;
            document.getElementById('viewUserContent').innerHTML = content;
            document.getElementById('viewModal').classList.add('active');
        }
    }
    
    async function submitCreateUser(e) {
        e.preventDefault();
        const form = e.target;
        const formData = new FormData(form);
        const data = Object.fromEntries(formData);
        
        try {
            const response = await fetch('{{ route("admin.users.create") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            });
            
            const result = await response.json();
            
            if (result.success) {
                showToast('User created successfully!', 'success');
                closeModal('createModal');
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast(result.message || 'Failed to create user', 'error');
            }
        } catch (error) {
            showToast('An error occurred', 'error');
        }
    }
    
    async function submitEditUser(e) {
        e.preventDefault();
        const form = e.target;
        const formData = new FormData(form);
        const data = Object.fromEntries(formData);
        const userId = data.user_id;
        
        try {
            const response = await fetch(`/admin/users/${userId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            });
            
            const result = await response.json();
            
            if (result.success) {
                showToast('User updated successfully!', 'success');
                closeModal('editModal');
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast(result.message || 'Failed to update user', 'error');
            }
        } catch (error) {
            showToast('An error occurred', 'error');
        }
    }
    
    async function toggleUserStatus(userId, isActive) {
        try {
            const response = await fetch(`/admin/users/${userId}/status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ active: isActive, activated: isActive })
            });
            
            const result = await response.json();
            
            if (result.success) {
                const badge = document.getElementById(`status-badge-${userId}`);
                badge.className = `badge ${isActive ? 'badge-success' : 'badge-danger'}`;
                badge.textContent = isActive ? 'Active' : 'Inactive';
                showToast('Status updated successfully!', 'success');
            } else {
                showToast('Failed to update status', 'error');
            }
        } catch (error) {
            showToast('An error occurred', 'error');
        }
    }
    
    async function deleteUser(userId) {
        if (!confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
            return;
        }
        
        try {
            const response = await fetch(`/admin/users/${userId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            });
            
            const result = await response.json();
            
            if (result.success) {
                showToast('User deleted successfully!', 'success');
                document.getElementById(`user-row-${userId}`).remove();
            } else {
                showToast(result.message || 'Failed to delete user', 'error');
            }
        } catch (error) {
            showToast('An error occurred', 'error');
        }
    }
</script>
@endsection
