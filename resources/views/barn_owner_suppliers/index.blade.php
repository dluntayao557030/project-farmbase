@extends('layouts.app')

@section('title', 'Suppliers')

@section('hero-text')
    Manage the preferred suppliers in your farm. 🚚
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

    .btn-add:hover { 
        background: var(--green-dark); 
        transform: translateY(-1px); 
    }

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

    .sup-table {
        width: 100%;
        border-collapse: collapse;
        font-family: var(--font);
        font-size: 0.8rem;
        table-layout: fixed;
    }

    .sup-table thead tr { background: var(--green-main); }

    .sup-table thead th {
        padding: 0.6rem 0.9rem;
        color: #fff;
        font-weight: 700;
        font-size: 0.75rem;
        letter-spacing: 0.03em;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .sup-table tbody tr {
        border-bottom: 1px solid #e8f0e0;
        transition: background 0.15s;
    }

    .sup-table tbody tr:hover { background: #f3faea; }

    .sup-table tbody td {
        padding: 0.65rem 0.9rem;
        color: var(--text-dark);
        vertical-align: middle;
    }

    .sup-id-tag {
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
    <div class="page-title">Suppliers</div>
    <button class="btn-add" onclick="openModal('addSupplierModal')">+ Add Supplier</button>
</div>

<div class="table-card">
    <div class="table-toolbar">
        <input type="text" class="search-input" id="searchInput" placeholder="Search suppliers..." oninput="filterTable()">
    </div>

    <div style="overflow-x:auto;">
        <table class="sup-table" id="supTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Supplier Name</th>
                    <th>Supply Category</th>
                    <th>Contact Number</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($suppliers as $supplier)
                @php 
                    $supId = 'SUP' . str_pad($supplier->id, 3, '0', STR_PAD_LEFT); 
                    $isActive = $supplier->supplier_status === 'active';
                @endphp
                <tr data-name="{{ strtolower($supplier->supplier_name) }}"
                    data-category="{{ strtolower($supplier->category->category_name ?? '') }}">
                    <td><span class="sup-id-tag">{{ $supId }}</span></td>
                    <td><strong>{{ $supplier->supplier_name }}</strong></td>
                    <td>{{ $supplier->category->category_name ?? '—' }}</td>
                    <td>{{ $supplier->contact_number ?? '—' }}</td>
                    <td>
                        <span class="badge-{{ $isActive ? 'active' : 'inactive' }}">
                            {{ ucfirst($supplier->supplier_status) }}
                        </span>
                    </td>
                    <td>
                        <div class="action-btns">
                            <button class="btn-action btn-view" title="View" onclick="openViewSupplierModal({{ $supplier->id }})">👁</button>
                            <button class="btn-action btn-edit" title="Edit" onclick="openEditSupplierModal({{ $supplier->id }})">✎</button>
                            
                            <button class="btn-action" 
                                    title="{{ $isActive ? 'Deactivate Supplier' : 'Reactivate Supplier' }}"
                                    style="background: {{ $isActive ? '#c0392b' : '#28a745' }}; color:white;"
                                    onclick="toggleSupplierStatus({{ $supplier->id }})">
                                {{ $isActive ? '🗑️' : '↺' }}
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr class="empty-row">
                    <td colspan="6">No suppliers yet. Click <strong>+ Add Supplier</strong> to register one.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@include('barn_owner_suppliers.modals.add_supplier')
@include('barn_owner_suppliers.modals.edit_supplier')
@include('barn_owner_suppliers.modals.view_supplier')
@include('barn_owner_suppliers.modals.delete_supplier')

<div class="fb-toast" id="fbToast"></div>

<script>
    const suppliersData = {!! json_encode($suppliersData ?? []) !!};
</script>

@endsection

@push('scripts')
<script>
    function openModal(id)  { document.getElementById(id).classList.add('show'); }
    function closeModal(id) { document.getElementById(id).classList.remove('show'); }

    document.querySelectorAll('.fb-modal-backdrop').forEach(backdrop => {
        backdrop.addEventListener('click', e => {
            if (e.target === backdrop) backdrop.classList.remove('show');
        });
    });

    function openViewSupplierModal(id) {
        const s = suppliersData.find(x => x.id === id);
        if (!s) return;

        document.getElementById('viewSupName').textContent     = s.supplier_name;
        document.getElementById('viewSupId').textContent       = s.display_id;
        document.getElementById('viewSupCategory').textContent = s.category_name;
        document.getElementById('viewSupContact').textContent  = s.contact_number || '—';

        const statusEl = document.getElementById('viewSupStatus');
        statusEl.textContent = s.supplier_status === 'active' ? 'Active' : 'Inactive';
        statusEl.className   = 'badge-' + (s.supplier_status === 'active' ? 'active' : 'inactive');

        openModal('viewSupplierModal');
    }

    function openEditSupplierModal(id) {
        const s = suppliersData.find(x => x.id === id);
        if (!s) return;

        document.getElementById('editSupId').value         = s.display_id;
        document.getElementById('editSupName').value       = s.supplier_name;
        document.getElementById('editSupCategoryId').value = s.category_id;
        document.getElementById('editSupContact').value    = s.contact_number;
        document.getElementById('editSupStatus').value     = s.supplier_status;
        document.getElementById('editSupplierForm').action = s.update_url;

        openModal('editSupplierModal');
    }

    function toggleSupplierStatus(id) {
        const supplier = suppliersData.find(s => s.id === id);
        if (!supplier) return;

        const isActive = supplier.supplier_status === 'active';
        
        const title = isActive ? 'Deactivate Supplier' : 'Reactivate Supplier';
        const message = isActive 
            ? `You are about to deactivate <strong>${supplier.supplier_name}</strong>.<br>This supplier will be marked as inactive.`
            : `You are about to reactivate <strong>${supplier.supplier_name}</strong>.`;

        document.getElementById('deleteSupTitle').textContent = title;
        document.getElementById('deleteSupMessage').innerHTML = message;
        document.getElementById('deleteSupplierSubmitBtn').textContent = isActive ? 'Yes, Deactivate' : 'Yes, Reactivate';
        

        const form = document.getElementById('deleteSupplierForm');
        form.action = supplier.delete_url;

        openModal('deleteSupplierModal');
    }

    function filterTable() {
        const q = document.getElementById('searchInput').value.toLowerCase();
        document.querySelectorAll('#supTable tbody tr:not(.empty-row)').forEach(row => {
            const name = row.dataset.name || '';
            const cat  = row.dataset.category || '';
            row.style.display = (!q || name.includes(q) || cat.includes(q)) ? '' : 'none';
        });
    }

    @if($errors->any() && old('_token'))
        openModal('addSupplierModal');
    @endif
</script>
@endpush