<div class="fb-modal-backdrop" id="editSupplyModal">
    <div class="fb-modal">

        <div class="modal-hero">
            <div class="modal-hero-logo">
                <img src="/images/logos/FarmbaseLogoOnly.png" alt="Farmbase">
            </div>
        </div>

        <div class="modal-body-wrap">
            <div class="modal-title">Edit Supply</div>

            <form method="POST" 
                  enctype="multipart/form-data"
                  class="modal-form"
                  id="editSupplyForm">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">ID</label>
                    <input type="text" class="form-control" id="editSupId" readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label">Supply Name <span style="color:#c0392b">*</span></label>
                    <input type="text" 
                           name="supply_name" 
                           id="editSupName"
                           class="form-control" 
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Category <span style="color:#c0392b">*</span></label>
                    <select name="category_id" id="editSupCategoryId" class="form-select" required>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->category_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Reorder Level <span style="color:#c0392b">*</span></label>
                    <input type="number" 
                           name="reorder_level" 
                           id="editSupReorderLevel"
                           class="form-control" 
                           min="0"
                           required>
                </div>

                <div class="mb-1">
                    <label class="form-label">Upload New Image (Optional)</label>
                    
                    <div id="editSupCurrentImg" style="margin-bottom: 0.8rem; display: none;">
                        <img id="editSupCurrentImgEl" src="" alt="Current Image" 
                             style="max-width:100%; border-radius:8px; max-height:180px; object-fit:cover; border: 1px solid var(--green-border);">
                        <div style="font-size:0.75rem; color:#888; margin-top: 4px;">
                            Current image — new upload will replace it
                        </div>
                    </div>

                    <input type="file" 
                           name="supply_image" 
                           accept="image/*"
                           id="editSupplyImage"
                           class="form-control"
                           onchange="previewFile(this, 'editSupPreview')">

                    <div id="editSupPreview" style="display:none; margin-top:0.5rem; font-size:0.82rem; color:var(--green-dark); font-weight:600;">
                    </div>
                </div>
            </form>
        </div>

        <div class="modal-footer-btns">
            <button class="btn-modal-back" onclick="closeModal('editSupplyModal')">Back</button>
            <button class="btn-modal-submit" onclick="document.getElementById('editSupplyForm').submit()">
                Update Supply
            </button>
        </div>

    </div>
</div>