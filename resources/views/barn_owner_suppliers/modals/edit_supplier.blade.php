<div class="fb-modal-backdrop" id="editSupplierModal">
    <div class="fb-modal">

        <div class="modal-hero">
            <div class="modal-hero-logo">
                <img src="/images/logos/FarmbaseLogoOnly.png" alt="Farmbase">
            </div>
        </div>

        <div class="modal-body-wrap">
            <div class="modal-title">Edit Supplier</div>

            <form method="POST"
                  class="modal-form"
                  id="editSupplierForm">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">ID</label>
                    <input type="text" class="form-control" id="editSupId" readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label">Supplier Name <span style="color:#c0392b">*</span></label>
                    <input type="text"
                           name="supplier_name"
                           id="editSupName"
                           class="form-control"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Category <span style="color:#c0392b">*</span></label>
                    <select name="category_id"
                            id="editSupCategoryId"
                            class="form-select">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->category_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Contact Number</label>
                    <input type="text"
                           name="contact_number"
                           id="editSupContact"
                           class="form-control"
                           placeholder="e.g. 09XXXXXXXXX">
                </div>

                <div class="mb-1">
                    <label class="form-label">Status</label>
                    <select name="supplier_status"
                            id="editSupStatus"
                            class="form-select">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

            </form>
        </div>

        <div class="modal-footer-btns">
            <button class="btn-modal-back"
                    onclick="closeModal('editSupplierModal')">Back</button>
            <button class="btn-modal-submit"
                    onclick="document.getElementById('editSupplierForm').submit()">
                Update Supplier
            </button>
        </div>

    </div>
</div>