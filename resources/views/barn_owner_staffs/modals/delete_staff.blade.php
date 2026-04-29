@push('styles')
<style>
    .delete-warning {
        text-align: center;
        padding: 0.5rem 0 0.8rem;
    }

    .delete-warning .warn-icon {
        font-size: 2.8rem;
        display: block;
        margin-bottom: 0.5rem;
    }

    .delete-warning p {
        font-size: 0.88rem;
        color: var(--text-mid);
        line-height: 1.7;
    }

    .delete-warning strong { color: #c0392b; }
</style>
@endpush

<div class="fb-modal-backdrop" id="deleteStaffModal">
    <div class="fb-modal" style="max-width: 400px;">

        <div class="modal-body-wrap">
            <div class="delete-warning">
                <span class="warn-icon">⚠️</span>
                <div class="modal-title" id="deleteStfTitle" style="margin-bottom: 0.6rem;">Remove Barn Staff</div>
                <p id="deleteStfMessage"></p>
            </div>

            <form method="POST" id="deleteStaffForm">
                @csrf
                @method('DELETE')
            </form>
        </div>

        <div class="modal-footer-btns">
            <button class="btn-modal-back" onclick="closeModal('deleteStaffModal')">Cancel</button>
            <button class="btn-modal-submit danger" id="deleteStaffSubmitBtn"
                    onclick="document.getElementById('deleteStaffForm').submit()">
                Yes, Deactivate
            </button>
        </div>

    </div>
</div>