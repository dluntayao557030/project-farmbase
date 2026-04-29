@push('styles')
<style>
    /* ── VIEW DETAIL GRID (shared across view modals) ── */
    .view-detail-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.9rem;
    }

    .view-detail-item {
        display: flex;
        flex-direction: column;
        gap: 3px;
    }

    .detail-label {
        font-size: 0.7rem;
        font-weight: 700;
        color: var(--text-mid);
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .detail-value {
        font-size: 0.9rem;
        color: var(--text-dark);
        font-weight: 600;
    }
</style>
@endpush

<div class="fb-modal-backdrop" id="viewSupplierModal">
    <div class="fb-modal" style="max-width: 420px;">

        <div class="modal-hero">
            <div class="modal-hero-logo">
                <img src="/images/logos/FarmbaseLogoOnly.png" alt="Farmbase">
            </div>
        </div>

        <div class="modal-body-wrap">
            <div class="modal-title" id="viewSupName">Supplier Details</div>

            <div class="view-detail-grid">

                <div class="view-detail-item">
                    <span class="detail-label">ID</span>
                    <span class="detail-value" id="viewSupId" style="color: var(--green-mid);"></span>
                </div>

                <div class="view-detail-item">
                    <span class="detail-label">Supplier Name</span>
                    <span class="detail-value" id="viewSupNameRepeat"></span>
                </div>

                <div class="view-detail-item">
                    <span class="detail-label">Supply Category</span>
                    <span class="detail-value" id="viewSupCategory"></span>
                </div>

                <div class="view-detail-item">
                    <span class="detail-label">Contact Number</span>
                    <span class="detail-value" id="viewSupContact"></span>
                </div>

                <div class="view-detail-item" style="grid-column: 1 / -1;">
                    <span class="detail-label">Status</span>
                    <span class="detail-value" id="viewSupStatus"></span>
                </div>

            </div>
        </div>

        <div class="modal-footer-btns">
            <button class="btn-modal-back"
                    style="flex: 1;"
                    onclick="closeModal('viewSupplierModal')">Close</button>
        </div>

    </div>
</div>

@push('scripts')
<script>
    // Sync the name into the repeated field too
    const _origOpenView = window.openViewSupplierModal;
    window.openViewSupplierModal = function(id) {
        _origOpenView(id);
        const s = suppliersData.find(x => x.id === id);
        if (s) document.getElementById('viewSupNameRepeat').textContent = s.supplier_name;
    };
</script>
@endpush