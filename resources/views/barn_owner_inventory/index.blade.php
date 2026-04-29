@extends('layouts.app')

@section('title', 'Inventory')

@section('hero-text')
    Register your farm's supplies in the barn inventory. 📦
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
        justify-content: space-between;
        padding: 0.9rem 1.2rem;
        border-bottom: 1px solid var(--green-border);
        background: #ffffff;
    }

    .tbl-title {
        font-family: var(--font);
        font-size: 0.95rem;
        font-weight: 800;
        color: var(--text-dark);
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
        width: 200px;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='13' height='13' viewBox='0 0 24 24' fill='none' stroke='%234e9a30' stroke-width='2.5'%3E%3Ccircle cx='11' cy='11' r='8'/%3E%3Cpath d='m21 21-4.35-4.35'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: 0.6rem center;
    }

    .search-input:focus { border-color: var(--green-main); }

    .btn-filter {
        background: #fff;
        border: 1.5px solid var(--green-border);
        border-radius: 7px;
        padding: 0.35rem 0.8rem;
        font-family: var(--font);
        font-size: 0.82rem;
        font-weight: 700;
        color: var(--green-mid);
        cursor: pointer;
    }

    .inv-table {
        width: 100%;
        height: 100%;
        border-collapse: collapse;
        font-family: var(--font);
        font-size: 0.8rem;
        table-layout: auto;
    }

    .inv-table thead tr { background: var(--green-main); }
    .inv-table thead th {
        padding: 0.6rem 0.8rem;
        color: #fff;
        font-weight: 700;
        font-size: 0.75rem;
        letter-spacing: 0.03em;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .inv-table td:nth-child(6),
    .inv-table td:nth-child(7) { 
        text-align: right;
    }

    .inv-table tbody tr {
        border-bottom: 1px solid #e8f0e0;
        transition: background 0.15s;
    }

    .inv-table th:nth-child(1), .inv-table td:nth-child(1) { width: 68px; text-align: center; }   /* Image */
    .inv-table th:nth-child(2), .inv-table td:nth-child(2) { width: 95px; text-align: center; }   /* ID */
    .inv-table th:nth-child(5), .inv-table td:nth-child(5) { text-align: right; width: 90px; }    /* Stock */
    .inv-table th:nth-child(6), .inv-table td:nth-child(6) { text-align: right; width: 110px; }   /* Reorder Level */
    .inv-table th:nth-child(7), .inv-table td:nth-child(7) { text-align: center; }                /* Status */
    .inv-table th:nth-child(8), .inv-table td:nth-child(8) { text-align: center; width: 110px; }  /* Actions */

    .badge-status {
        display: inline-block;
        padding: 0.25rem 0.7rem;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 700;
        white-space: nowrap;
    }

    .inv-table tbody tr:hover { background: #f3faea; }
    .inv-table tbody td {
        padding: 0.6rem 0.8rem;
        color: var(--text-dark);
        vertical-align: middle;
    }

    .supply-thumb {
        width: 44px; 
        height: 44px;
        object-fit: cover;
        border-radius: 6px;
        border: 1px solid var(--green-border);
    }

    .supply-thumb-placeholder {
        width: 44px; 
        height: 44px;
        border-radius: 6px;
        background: #e8f0e0;
        display: flex; 
        align-items: center; 
        justify-content: center;
        font-size: 1.2rem; 
        color: #aaa;
        border: 1px solid var(--green-border);
    }

    .supply-id-tag {
        font-weight: 700;
        color: var(--green-mid);
        font-size: 0.72rem;
        letter-spacing: 0.04em;
    }

    .badge-status {
        display: inline-block;
        padding: 0.2rem 0.6rem;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 700;
    }

    .badge-low      { background: #fde8e8; color: #856404; }
    .badge-ok       { background: var(--green-pale); color: var(--green-dark); }
    .badge-out      { background: #fff3cd; color: #c0392b;; }
    .badge-inactive { background: #f0f0f0; color: #777; }

    .action-btns { display: flex; align-items: center; gap: 0.4rem; }

    .btn-action {
        width: 28px; 
        height: 28px;
        border: none; 
        border-radius: 6px;
        cursor: pointer;
        display: flex; 
        align-items: center; 
        justify-content: center;
        font-size: 0.85rem;
        transition: transform 0.1s;
    }

    .btn-action:hover { transform: scale(1.15); }

    .empty-row td {
        text-align: center !important;
        color: #888;
        font-size: 0.96rem;
        border: none !important;
        background: #f8fbf4;
    }

    .barn-name-highlight {
        font-weight: 700;
        color: #e8b820;
        text-shadow: 0 1px 4px rgba(0,0,0,0.5);
    }

    .filter-dropdown {
        display: none;
        position: absolute;
        background: #fff;
        border: 1px solid var(--green-border);
        border-radius: 8px;
        padding: 1rem;
        box-shadow: 0 4px 15px rgba(20,50,8,0.15);
        z-index: 100;
        margin-top: 5px;
        width: 180px;
    }

    .filter-dropdown.show { display: block; }

    /* Toast Notification */
    .fb-toast {
        position: fixed;
        bottom: 24px;
        right: 24px;
        background: #2e7d32;
        color: white;
        padding: 14px 20px;
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        font-family: var(--font);
        font-size: 0.95rem;
        font-weight: 600;
        display: none;
        align-items: center;
        gap: 8px;
        z-index: 10000;
        min-width: 280px;
        transition: all 0.3s ease;
    }

    .fb-toast.show {
        display: flex;
    }

    .fb-toast.error {
        background: #c62828;
    }
</style>
@endpush

@section('content')

<div class="page-header">
    <div class="page-title">Inventory</div>
    <button class="btn-add" onclick="openModal('addSupplyModal')">+ Add Supply</button>
</div>

<div class="table-card">
    <div class="table-toolbar">
        <span class="tbl-title">All Supplies</span>
        <div class="toolbar-right" style="position:relative;">
            <input type="text" class="search-input" id="searchInput" placeholder="Search supplies..." oninput="filterTable()">
            <button class="btn-filter" onclick="toggleFilter()">▼ Filter</button>
            <div class="filter-dropdown" id="filterDropdown">
                <div class="mb-2">
                    <label style="font-size:0.75rem;font-weight:700;color:var(--text-mid);">Category</label>
                    <select class="form-select form-select-sm" id="filterCategory" onchange="filterTable()">
                        <option value="">All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ strtolower($cat->category_name) }}">{{ $cat->category_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="font-size:0.75rem;font-weight:700;color:var(--text-mid);">Status</label>
                    <select class="form-select form-select-sm" id="filterStatus" onchange="filterTable()">
                        <option value="">All Status</option>
                        <option value="low stock">Low Stock</option>
                        <option value="in stock">In Stock</option>
                        <option value="out of stock">Out of Stock</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

   <div style="overflow-x:auto; min-height: 400px;">
    <table class="inv-table" id="invTable">
        <thead>
            <tr>
                <th>Image</th>
                <th>ID</th>
                <th>Supply</th>
                <th>Category</th>
                <th>Stock</th>
                <th>Reorder Level</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($supplies as $supply)
                @php
                    $catName = $supply->category->category_name ?? 'N/A';
                    $supId   = strtoupper(substr($catName, 0, 3)) . str_pad($supply->id, 4, '0', STR_PAD_LEFT);
                    $isLow   = $supply->stock <= $supply->reorder_level;
                    $isOut   = $supply->stock == 0;
                    $badgeClass = $isOut ? 'badge-out' : ($isLow ? 'badge-low' : 'badge-ok');
                    $badgeLabel = $isOut ? 'Out of Stock' : ($isLow ? 'Low Stock' : 'In Stock');
                    $isActive = $supply->supply_status === 'active';
                @endphp
                <tr data-name="{{ strtolower($supply->supply_name) }}"
                    data-category="{{ strtolower($catName) }}"
                    data-status="{{ strtolower($badgeLabel) }}"
                    class="{{ !$isActive ? 'table-secondary opacity-75' : '' }}">

                    <td>
                        @if($supply->supply_img_path)
                            <img src="{{ asset('storage/' . $supply->supply_img_path) }}" 
                                 class="supply-thumb" alt="{{ $supply->supply_name }}">
                        @else
                            <div class="supply-thumb-placeholder">📦</div>
                        @endif
                    </td>
                    <td><span class="supply-id-tag">{{ $supId }}</span></td>
                    <td><strong>{{ $supply->supply_name }}</strong></td>
                    <td>{{ $catName }}</td>
                    <td>{{ $supply->stock }}</td>
                    <td>{{ $supply->reorder_level }}</td>
                    <td>
                        <span class="badge-status {{ $badgeClass }}">{{ $badgeLabel }}</span>
                        @if(!$isActive)
                            <span class="badge-status badge-inactive ms-1">Inactive</span>
                        @endif
                    </td>
                    <td>
                        <div class="action-btns">
                            <button class="btn-action btn-view" title="View" onclick="openViewSupplyModal({{ $supply->id }})">👁</button>
                            <button class="btn-action btn-edit" title="Edit" onclick="openEditSupplyModal({{ $supply->id }})">✎</button>
                            
                            @php $isActive = $supply->supply_status === 'active'; @endphp
                            <button class="btn-action" 
                                    title="{{ $isActive ? 'Deactivate Supply' : 'Reactivate Supply' }}"
                                    style="background: {{ $isActive ? '#c0392b' : '#28a745' }}; color:white;"
                                    onclick="toggleSupplyStatus({{ $supply->id }})">
                                {{ $isActive ? '🗑️' : '↺' }}
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr class="empty-row">
                    <td colspan="8">
                        No supplies found. Click <strong>+ Add Supply</strong> to get started.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
</div>

@include('barn_owner_inventory.modals.add_supply')
@include('barn_owner_inventory.modals.edit_supply')
@include('barn_owner_inventory.modals.view_supply')
@include('barn_owner_inventory.modals.delete_supply')


<div class="fb-toast" id="fbToast"></div>

<script>
    const suppliesData = {!! json_encode($suppliesData ?? []) !!};
</script>

@endsection

@push('scripts')
<script>
    function openModal(id)  { document.getElementById(id).classList.add('show'); }
    function closeModal(id) { document.getElementById(id).classList.remove('show'); }

    document.querySelectorAll('.fb-modal-backdrop').forEach(b => {
        b.addEventListener('click', e => { if (e.target === b) b.classList.remove('show'); });
    });

    function openViewSupplyModal(id) {
        const s = suppliesData.find(x => x.id === id);
        if (!s) return;

        document.getElementById('viewSupTitle').textContent = s.supply_name;

        const imgWrap = document.getElementById('viewSupImgWrap');
        imgWrap.innerHTML = s.img_url 
            ? `<img src="${s.img_url}" class="view-img" alt="${s.supply_name}">` 
            : `<div class="view-img-placeholder">📦</div>`;

        const statusColor = s.status_label === 'In Stock' 
            ? 'var(--green-mid)' 
            : (s.status_label === 'Out of Stock' ? '#c0392b' : '#856404');

        document.getElementById('viewSupGrid').innerHTML = `
            <div class="view-detail-item">
                <span class="detail-label">ID</span>
                <span class="detail-value" style="color:var(--green-mid)">${s.display_id}</span>
            </div>
            <div class="view-detail-item">
                <span class="detail-label">Supply Name</span>
                <span class="detail-value">${s.supply_name}</span>
            </div>
            <div class="view-detail-item">
                <span class="detail-label">Category</span>
                <span class="detail-value">${s.category_name}</span>
            </div>
            <div class="view-detail-item">
                <span class="detail-label">Current Stock</span>
                <span class="detail-value" style="font-size:1.1rem;">${s.stock}</span>
            </div>
            <div class="view-detail-item">
                <span class="detail-label">Reorder Level</span>
                <span class="detail-value">${s.reorder_level}</span>
            </div>
            <div class="view-detail-item" style="grid-column:1/-1">
                <span class="detail-label">Status</span>
                <span class="detail-value" style="color:${statusColor}">${s.status_label}</span>
            </div>
        `;
        openModal('viewSupplyModal');
    }

    function openEditSupplyModal(id) {
    const s = suppliesData.find(x => x.id === id);
    if (!s) return;

    document.getElementById('editSupId').value           = s.display_id;
    document.getElementById('editSupName').value         = s.supply_name;
    document.getElementById('editSupCategoryId').value   = s.category_id;
    document.getElementById('editSupReorderLevel').value = s.reorder_level;

    const currentImgDiv = document.getElementById('editSupCurrentImg');
    const currentImgEl  = document.getElementById('editSupCurrentImgEl');
    
    if (s.img_url) {
        currentImgEl.src = s.img_url;
        currentImgDiv.style.display = 'block';
    } else {
        currentImgDiv.style.display = 'none';
    }

    document.getElementById('editSupPreview').style.display = 'none';

    document.getElementById('editSupplyForm').action = s.edit_url;

    openModal('editSupplyModal');
}

    function openDeleteSupplyModal(id, name) {
        const s = suppliesData.find(x => x.id === id);
        if (!s) return;
        document.getElementById('deleteSupplyName').textContent = name;
        document.getElementById('deleteSupplyForm').action = s.delete_url;
        openModal('deleteSupplyModal');
    }

    function toggleSupplyStatus(id) {
    const supply = suppliesData.find(s => s.id === id);
    if (!supply) return;

    const isActive = supply.supply_status === 'active';
    
    const title = isActive ? 'Deactivate Supply' : 'Reactivate Supply';
    const message = isActive 
        ? `You are about to deactivate <strong>${supply.supply_name}</strong>.<br>This supply will be marked as inactive.`
        : `You are about to reactivate <strong>${supply.supply_name}</strong>.`;

    document.getElementById('deleteSupplyTitle').textContent = title;
    document.getElementById('deleteSupplyMessage').innerHTML = message;
    document.getElementById('deleteSubmitBtn').textContent = isActive ? 'Yes, Deactivate' : 'Yes, Reactivate';

    const form = document.getElementById('deleteSupplyForm');
    form.action = supply.delete_url;

    openModal('deleteSupplyModal');
}

    function previewFile(input, previewId) {
        const el = document.getElementById(previewId);
        if (input.files && input.files[0]) {
            el.style.display = 'block';
            el.textContent = '✅ ' + input.files[0].name;
        }
    }

    function filterTable() {
        const q = document.getElementById('searchInput').value.toLowerCase();
        const catF = document.getElementById('filterCategory').value.toLowerCase();
        const statF = document.getElementById('filterStatus').value.toLowerCase();

        document.querySelectorAll('#invTable tbody tr:not(.empty-row)').forEach(row => {
            const name = (row.dataset.name || '').toLowerCase();
            const cat = (row.dataset.category || '').toLowerCase();
            const status = (row.dataset.status || '').toLowerCase();
            const matchQ = !q || name.includes(q) || cat.includes(q);
            const matchCat = !catF || cat === catF;
            const matchStat = !statF || status === statF;
            row.style.display = (matchQ && matchCat && matchStat) ? '' : 'none';
        });
    }

    function toggleFilter() {
        document.getElementById('filterDropdown').classList.toggle('show');
    }

    document.addEventListener('click', e => {
        const wrap = document.querySelector('.toolbar-right');
        if (wrap && !wrap.contains(e.target)) {
            document.getElementById('filterDropdown').classList.remove('show');
        }
    });

    @if($errors->any() && old('_token'))
        openModal('addSupplyModal');
    @endif
</script>
@endpush