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

    .view-img {
        width: 100%;
        max-height: 350px;
        object-fit: cover;
        border-radius: 10px;
        border: 1px solid var(--green-border);
        margin-bottom: 1rem;
    }

    .view-img-placeholder {
        width: 100%;
        height: 80px;
        background: #e8f0e0;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: #bbb;
        margin-bottom: 1rem;
        border: 1px solid var(--green-border);
    }
</style>
@endpush

<div class="fb-modal-backdrop" id="viewSupplyModal">
    <div class="fb-modal" style="max-width: 440px;">

        <div class="modal-hero">
            <div class="modal-hero-logo">
                <img src="/images/logos/FarmbaseLogoOnly.png" alt="Farmbase">
            </div>
        </div>

        <div class="modal-body-wrap">
            <div class="modal-title" id="viewSupTitle">Supply Details</div>

            <div id="viewSupImgWrap"></div>

            <div class="view-detail-grid" id="viewSupGrid"></div>
        </div>

        <div class="modal-footer-btns">
            <button class="btn-modal-back" style="flex: 1;" onclick="closeModal('viewSupplyModal')">Close</button>
        </div>

    </div>
</div>