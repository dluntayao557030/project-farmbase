@once
@push('styles')
<style>
    .fb-modal-backdrop {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(15, 40, 5, 0.6);
        z-index: 9000;
        align-items: center;
        justify-content: center;
        padding: 1rem;
    }

    .fb-modal-backdrop.show { display: flex; }

    .fb-modal {
        background: var(--cream);
        border-radius: 16px;
        width: 100%;
        max-width: 480px;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(15,40,5,0.35);
        border: 1px solid var(--green-border);
        animation: modalPop 0.28s cubic-bezier(0.34,1.56,0.64,1) both;
        max-height: 92vh;
        display: flex;
        flex-direction: column;
    }

    @keyframes modalPop {
        from { transform: scale(0.88) translateY(20px); opacity: 0; }
        to   { transform: scale(1)    translateY(0);    opacity: 1; }
    }

    .modal-body-wrap {
        padding: 1.8rem 1.5rem 0.8rem;
        overflow-y: auto;
        flex: 1;
    }

    .stock-modal-title {
        font-family: var(--font);
        font-size: 1.3rem;
        font-weight: 800;
        color: var(--text-dark);
        text-align: center;
        margin-bottom: 0.2rem;
    }

    .stock-supply-name {
        font-family: var(--font);
        font-size: 1rem;
        font-weight: 700;
        color: var(--green-mid);
        text-align: center;
        margin-bottom: 1.2rem;
    }

    .stock-img-wrap {
        display: flex;
        justify-content: center;
        margin-bottom: 1.3rem;
    }

    .modal-form .form-label {
        font-family: var(--font);
        font-size: 0.8rem;
        font-weight: 700;
        color: var(--text-mid);
        margin-bottom: 0.25rem;
    }

    .modal-form .form-control,
    .modal-form .form-select {
        font-family: var(--font);
        font-size: 0.88rem;
        background: #fff;
        border: 1.5px solid var(--green-border);
        border-radius: 7px;
        padding: 0.55rem 0.8rem;
        color: var(--text-dark);
        width: 100%;
        transition: border-color 0.2s;
    }

    .modal-form .form-control:focus,
    .modal-form .form-select:focus {
        border-color: var(--green-main);
        box-shadow: 0 0 0 3px rgba(78,154,48,0.15);
    }

    .modal-form .form-control[readonly] {
        background: #f0f7e8;
        color: var(--text-mid);
    }

    .modal-footer-btns {
        display: flex;
        gap: 0.7rem;
        padding: 1rem 1.5rem 1.3rem;
        border-top: 1px solid var(--green-border);
        flex-shrink: 0;
    }

    .btn-modal-back {
        flex: 1;
        background: transparent;
        color: var(--green-dark);
        border: 1.5px solid var(--green-border);
        border-radius: 8px;
        padding: 0.6rem;
        font-family: var(--font);
        font-size: 0.9rem;
        font-weight: 700;
        cursor: pointer;
    }

    .btn-modal-back:hover { background: var(--green-pale); }

    .btn-modal-submit {
        flex: 2;
        background: var(--green-main);
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 0.6rem;
        font-family: var(--font);
        font-size: 0.9rem;
        font-weight: 700;
        cursor: pointer;
        transition: background 0.2s, transform 0.1s;
    }

    .btn-modal-submit:hover { background: var(--green-dark); transform: translateY(-1px); }
</style>
@endpush
@endonce

<div class="fb-modal-backdrop" id="stockInModal">
    <div class="fb-modal">
        <div class="modal-body-wrap">
            <div class="stock-modal-title">Stock In</div>
            <div class="stock-supply-name" id="siSupplyName">—</div>
            <div class="stock-img-wrap" id="siImgWrap"></div>

            <form action="{{ route('transactions.stockIn') }}" method="POST" class="modal-form" id="stockInForm">
                @csrf
                <input type="hidden" name="supply_id" id="siHiddenId">

                <div class="mb-3">
                    <label class="form-label">Supply ID</label>
                    <input type="text" class="form-control" id="siSupplyId" readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label">Supplier <span style="color:#aaa;font-weight:400;">(optional)</span></label>
                    <select name="supplier_id" id="siSupplierId" class="form-select">
                        <option value="">— Select Supplier —</option>
                        <!-- Populated dynamically by JavaScript -->
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Quantity <span style="color:#c0392b">*</span></label>
                    <input type="number" name="quantity" id="siQuantity" class="form-control" min="1" placeholder="Enter quantity to add" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Unit Cost (₱) <span style="color:#c0392b">*</span></label>
                    <input type="number" name="unit_cost" id="siUnitCost" class="form-control" min="0" step="0.01" placeholder="0.00" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Remarks <span style="color:#aaa;font-weight:400;">(optional)</span></label>
                    <input type="text" name="remarks" class="form-control" placeholder="e.g. Weekly restock from supplier">
                </div>
            </form>
        </div>

        <div class="modal-footer-btns">
            <button class="btn-modal-back" onclick="closeModal('stockInModal')">Cancel</button>
            <button class="btn-modal-submit" onclick="document.getElementById('stockInForm').submit()">
                ＋ Confirm Stock In
            </button>
        </div>
    </div>
</div>