<div class="fb-modal-backdrop" id="stockOutModal">
    <div class="fb-modal">
        <div class="modal-body-wrap">
            <div class="stock-modal-title">Stock Out</div>
            <div class="stock-supply-name" id="soSupplyName">—</div>
            <div class="stock-img-wrap" id="soImgWrap"></div>

            <form action="{{ route('transactions.stockOut') }}" method="POST" class="modal-form" id="stockOutForm">
                @csrf
                <input type="hidden" name="supply_id" id="soHiddenId">

                <div class="mb-3">
                    <label class="form-label">Supply ID</label>
                    <input type="text" class="form-control" id="soSupplyId" readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label">Quantity <span style="color:#c0392b">*</span></label>
                    <input type="number" name="quantity" id="soQuantity" class="form-control" min="1" placeholder="Enter quantity to deduct" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Remarks <span style="color:#aaa;font-weight:400;">(optional)</span></label>
                    <input type="text" name="remarks" class="form-control" placeholder="e.g. Used for morning feeding">
                </div>
            </form>
        </div>

        <div class="modal-footer-btns">
            <button class="btn-modal-back" onclick="closeModal('stockOutModal')">Cancel</button>
            <button class="btn-modal-submit" style="background:#c0392b;" onclick="document.getElementById('stockOutForm').submit()">
                − Confirm Stock Out
            </button>
        </div>
    </div>
</div>