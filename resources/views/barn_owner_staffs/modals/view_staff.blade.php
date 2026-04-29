@push('styles')
<style>
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

    .staff-avatar {
        width: 64px; 
        height: 64px;
        border-radius: 50%;
        background: var(--green-pale);
        border: 3px solid var(--green-border);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        overflow: hidden;
    }

    .staff-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
</style>
@endpush

<div class="fb-modal-backdrop" id="viewStaffModal">
    <div class="fb-modal" style="max-width: 420px;">

        <div class="modal-hero">
            <div class="modal-hero-logo">
                <img src="/images/logos/FarmbaseLogoOnly.png" alt="Farmbase">
            </div>
        </div>

        <div class="modal-body-wrap">
            <div class="staff-avatar">
                <img src="../images/icons/StaffGreen.png" alt="Staff">
            </div>
            <div class="modal-title" id="viewStfTitle">Staff Details</div>

            <div class="view-detail-grid">
                <div class="view-detail-item">
                    <span class="detail-label">Staff ID</span>
                    <span class="detail-value" id="viewStfId" style="color: var(--green-mid);"></span>
                </div>
                <div class="view-detail-item">
                    <span class="detail-label">Full Name</span>
                    <span class="detail-value" id="viewStfName"></span>
                </div>
                <div class="view-detail-item">
                    <span class="detail-label">Username</span>
                    <span class="detail-value" id="viewStfUsername"></span>
                </div>
                <div class="view-detail-item">
                    <span class="detail-label">Email</span>
                    <span class="detail-value" id="viewStfEmail" style="font-size:0.82rem;"></span>
                </div>
                <div class="view-detail-item" style="grid-column: 1 / -1;">
                    <span class="detail-label">Status</span>
                    <span class="detail-value" id="viewStfStatus"></span>
                </div>
            </div>
        </div>

        <div class="modal-footer-btns">
            <button class="btn-modal-back" style="flex: 1;" onclick="closeModal('viewStaffModal')">Close</button>
        </div>

    </div>
</div>