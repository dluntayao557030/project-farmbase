<div class="fb-modal-backdrop" id="editStaffModal">
    <div class="fb-modal">

        <div class="modal-hero">
            <div class="modal-hero-logo">
                <img src="/images/logos/FarmbaseLogoOnly.png" alt="Farmbase">
            </div>
        </div>

        <div class="modal-body-wrap">
            <div class="modal-title">Edit Barn Staff</div>

            <form method="POST" class="modal-form" id="editStaffForm">
                @csrf
                @method('PUT')

                <input type="hidden" name="user_id" id="editStfUserId">

                <div class="mb-3">
                    <label class="form-label">ID</label>
                    <input type="text" class="form-control" id="editStfId" readonly>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <label class="form-label">First Name <span style="color:#c0392b">*</span></label>
                        <input type="text" name="first_name" id="editStfFirstName" class="form-control" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Last Name <span style="color:#c0392b">*</span></label>
                        <input type="text" name="last_name" id="editStfLastName" class="form-control" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Username <span style="color:#c0392b">*</span></label>
                    <input type="text" name="username" id="editStfUsername" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="staff_status" id="editStfStatus" class="form-select">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">
                        New Password 
                        <span style="color:#aaa; font-weight:400;">(leave blank to keep current)</span>
                    </label>
                    <div class="pw-wrap">
                        <input type="password" name="password" id="editStfPassword" class="form-control" placeholder="••••••••">
                        <button type="button" class="pw-toggle" onclick="togglePw('editStfPassword','editStfPwEye')">
                            <span id="editStfPwEye">👁</span>
                        </button>
                    </div>
                    <div class="pw-hint">Minimum 8 characters if changing</div>
                </div>

                <div class="mb-1">
                    <label class="form-label">Re-type Password</label>
                    <div class="pw-wrap">
                        <input type="password" name="password_confirmation" id="editStfConfirm" class="form-control" placeholder="••••••••">
                        <button type="button" class="pw-toggle" onclick="togglePw('editStfConfirm','editStfCfmEye')">
                            <span id="editStfCfmEye">👁</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="modal-footer-btns">
            <button class="btn-modal-back" onclick="closeModal('editStaffModal')">Back</button>
            <button class="btn-modal-submit" onclick="document.getElementById('editStaffForm').submit()">
                Update Barn Staff
            </button>
        </div>

    </div>
</div>