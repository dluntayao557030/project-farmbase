@extends('layouts.app')

@section('title', 'Staff')

@section('hero-text')
    Add barn staff to do stock transactions. 🧑‍🌾
@endsection

@push('styles')
<style>
    .page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
    }

    .page-title {
        font-family: var(--font);
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--gold-mid);
    }

    .btn-add {
        background: var(--green-main);
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 0.5rem 1.1rem;
        font-family: var(--font);
        font-size: 0.9rem;
        font-weight: 700;
        cursor: pointer;
        transition: background 0.2s, transform 0.1s;
    }

    .btn-add:hover { background: var(--green-dark); transform: translateY(-1px); }

    .table-card {
        background: var(--cream);
        border-radius: 12px;
        border: 1px solid var(--green-border);
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(20,50,8,0.08);
    }

    .table-toolbar {
        display: flex;
        align-items: center;
        padding: 0.9rem 1.2rem;
        border-bottom: 1px solid var(--green-border);
        background: #f0f7e8;
    }

    .search-input {
        background: #fff;
        border: 1.5px solid var(--green-border);
        border-radius: 20px;
        padding: 0.35rem 0.9rem 0.35rem 2rem;
        font-family: var(--font);
        font-size: 0.82rem;
        color: var(--text-dark);
        outline: none;
        width: 220px;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='13' height='13' viewBox='0 0 24 24' fill='none' stroke='%234e9a30' stroke-width='2.5'%3E%3Ccircle cx='11' cy='11' r='8'/%3E%3Cpath d='m21 21-4.35-4.35'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: 0.6rem center;
    }

    .search-input:focus { border-color: var(--green-main); }

    .stf-table {
        width: 100%;
        border-collapse: collapse;
        font-family: var(--font);
        font-size: 0.8rem;
        table-layout: fixed;
    }

    .stf-table thead tr { background: var(--green-main); }
    .stf-table thead th {
        padding: 0.6rem 0.9rem;
        color: #fff;
        font-weight: 700;
        font-size: 0.75rem;
        letter-spacing: 0.03em;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .stf-table tbody tr {
        border-bottom: 1px solid #e8f0e0;
        transition: background 0.15s;
    }

    .stf-table tbody tr:hover { background: #f3faea; }
    .stf-table tbody td {
        padding: 0.65rem 0.9rem;
        color: var(--text-dark);
        vertical-align: middle;
    }

    .stf-id-tag {
        font-weight: 700;
        color: var(--green-mid);
        font-size: 0.72rem;
        letter-spacing: 0.04em;
    }

    .badge-active   { background: var(--green-pale); color: var(--green-dark); padding:0.2rem 0.6rem; border-radius:20px; font-size:0.7rem; font-weight:700; }
    .badge-inactive { background: #f0f0f0; color: #777; padding:0.2rem 0.6rem; border-radius:20px; font-size:0.7rem; font-weight:700; }

    .action-btns { display: flex; align-items: center; gap: 0.4rem; }

    .btn-action {
        width: 28px; height: 28px;
        border: none; border-radius: 6px;
        cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.85rem;
        transition: transform 0.1s;
    }

    .btn-action:hover { transform: scale(1.15); }

    .empty-row td {
        text-align: center;
        padding: 3rem;
        color: #aaa;
        font-size: 0.9rem;
    }
</style>
@endpush

@section('content')

<div class="page-header">
    <div class="page-title">Staff</div>
    <button class="btn-add" onclick="openModal('addStaffModal')">+ Add Barn Staff</button>
</div>

<div class="table-card">
    <div class="table-toolbar">
        <input type="text" class="search-input" id="searchInput" placeholder="Search staff..." oninput="filterTable()">
    </div>

    <div style="overflow-x:auto;">
        <table class="stf-table" id="stfTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($staffs as $staff)
                @php
                    $stfId = 'STF' . str_pad($staff->id, 3, '0', STR_PAD_LEFT);
                    $fullName = $staff->user->first_name . ' ' . $staff->user->last_name;
                    $username = $staff->user->username;
                    $isActive = $staff->staff_status === 'active';
                @endphp
                <tr data-name="{{ strtolower($fullName) }}" data-username="{{ strtolower($username) }}">
                    <td><span class="stf-id-tag">{{ $stfId }}</span></td>
                    <td><strong>{{ $fullName }}</strong></td>
                    <td>{{ $username }}</td>
                    <td>
                        <span class="badge-{{ $isActive ? 'active' : 'inactive' }}">
                            {{ ucfirst($staff->staff_status) }}
                        </span>
                    </td>
                    <td>
                        <div class="action-btns">
                            <button class="btn-action btn-view" title="View" onclick="openViewStaffModal({{ $staff->id }})">👁</button>
                            <button class="btn-action btn-edit" title="Edit" onclick="openEditStaffModal({{ $staff->id }})">✎</button>
                            
                            <button class="btn-action" 
                                    title="{{ $isActive ? 'Deactivate Staff' : 'Reactivate Staff' }}"
                                    style="background: {{ $isActive ? '#c0392b' : '#28a745' }}; color:white;"
                                    onclick="toggleStaffStatus({{ $staff->id }})">
                                {{ $isActive ? '🗑️' : '↺' }}
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr class="empty-row">
                    <td colspan="5">No staff assigned yet. Click <strong>+ Add Barn Staff</strong> to create an account.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@include('barn_owner_staffs.modals.add_staff')
@include('barn_owner_staffs.modals.edit_staff')
@include('barn_owner_staffs.modals.view_staff')
@include('barn_owner_staffs.modals.delete_staff')

<div class="fb-toast" id="fbToast"></div>

<script>
    const staffsData = {!! json_encode($staffsData ?? []) !!};
</script>

@endsection

@push('scripts')
<script>
    function openModal(id)  { document.getElementById(id).classList.add('show'); }
    function closeModal(id) { document.getElementById(id).classList.remove('show'); }

    document.querySelectorAll('.fb-modal-backdrop').forEach(b => {
        b.addEventListener('click', e => { if (e.target === b) b.classList.remove('show'); });
    });

    function openViewStaffModal(id) {
        const s = staffsData.find(x => x.id === id);
        if (!s) return;
        document.getElementById('viewStfTitle').textContent    = s.full_name;
        document.getElementById('viewStfId').textContent       = s.display_id;
        document.getElementById('viewStfName').textContent     = s.full_name;
        document.getElementById('viewStfUsername').textContent = s.username;
        document.getElementById('viewStfEmail').textContent    = s.email;
        const statusEl = document.getElementById('viewStfStatus');
        statusEl.textContent = s.staff_status.charAt(0).toUpperCase() + s.staff_status.slice(1);
        statusEl.className   = 'badge-' + (s.staff_status === 'active' ? 'active' : 'inactive');
        openModal('viewStaffModal');
    }

    function openEditStaffModal(id) {
        const s = staffsData.find(x => x.id === id);
        if (!s) return;
        document.getElementById('editStfId').value         = s.display_id;
        document.getElementById('editStfFirstName').value  = s.first_name;
        document.getElementById('editStfLastName').value   = s.last_name;
        document.getElementById('editStfUsername').value   = s.username;
        document.getElementById('editStfStatus').value     = s.staff_status;
        document.getElementById('editStfUserId').value     = s.user_id;
        document.getElementById('editStfPassword').value   = '';
        document.getElementById('editStfConfirm').value    = '';
        document.getElementById('editStaffForm').action    = s.update_url;
        openModal('editStaffModal');
    }

    // Toggle Staff Status - Same pattern as toggleSupplyStatus
    function toggleStaffStatus(id) {
        const staff = staffsData.find(s => s.id === id);
        if (!staff) return;

        const isActive = staff.staff_status === 'active';
        
        const title = isActive ? 'Deactivate Staff' : 'Reactivate Staff';
        const message = isActive 
            ? `You are about to deactivate <strong>${staff.full_name}</strong>.<br>This staff member will be marked as inactive.`
            : `You are about to reactivate <strong>${staff.full_name}</strong>.`;

        document.getElementById('deleteStfTitle').textContent = title;
        document.getElementById('deleteStfMessage').innerHTML = message;
        document.getElementById('deleteStaffSubmitBtn').textContent = isActive ? 'Yes, Deactivate' : 'Yes, Reactivate';

        const form = document.getElementById('deleteStaffForm');
        form.action = staff.delete_url;

        openModal('deleteStaffModal');
    }

    function filterTable() {
        const q = document.getElementById('searchInput').value.toLowerCase();
        document.querySelectorAll('#stfTable tbody tr:not(.empty-row)').forEach(row => {
            const name = row.dataset.name || '';
            const user = row.dataset.username || '';
            row.style.display = (!q || name.includes(q) || user.includes(q)) ? '' : 'none';
        });
    }

    @if($errors->any() && old('_token'))
        openModal('addStaffModal');
    @endif
</script>
@endpush