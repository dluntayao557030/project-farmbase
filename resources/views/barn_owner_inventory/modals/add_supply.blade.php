@once
@push('styles')
<style>
    .fb-modal-backdrop {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(15, 40, 5, 0.55);
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

    .modal-hero {
        height: 90px;
        background: url('/images/backgrounds/BarnImage1.jpg') center 40%/cover no-repeat;
        position: relative;
        flex-shrink: 0;
    }

    .modal-hero::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(to bottom, rgba(20,50,8,0.3), rgba(20,50,8,0.7));
    }

    .modal-hero-logo {
        position: absolute;
        top: 50%; left: 50%;
        transform: translate(-50%,-50%);
        z-index: 2;
    }

    .modal-hero-logo img {
        height: 36px;
        mix-blend-mode: screen;
        filter: brightness(1.0);
    }

    .modal-body-wrap {
        padding: 1.5rem 1.8rem 0.8rem;
        overflow-y: auto;
        flex: 1;
    }

    .modal-title {
        font-family: var(--font);
        font-size: 1.2rem;
        font-weight: 800;
        color: var(--text-dark);
        text-align: center;
        margin-bottom: 1.2rem;
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
        padding: 0.5rem 0.75rem;
        color: var(--text-dark);
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .modal-form .form-control:focus,
    .modal-form .form-select:focus {
        border-color: var(--green-main);
        box-shadow: 0 0 0 3px rgba(78,154,48,0.15);
        outline: none;
    }

    .modal-footer-btns {
        display: flex;
        gap: 0.7rem;
        padding: 0.9rem 1.8rem 1.3rem;
        border-top: 1px solid var(--green-border);
        flex-shrink: 0;
    }

    .btn-modal-back {
        flex: 1;
        background: transparent;
        color: var(--green-dark);
        border: 1.5px solid var(--green-border);
        border-radius: 8px;
        padding: 0.55rem;
        font-family: var(--font);
        font-size: 0.9rem;
        font-weight: 700;
        cursor: pointer;
        transition: background 0.2s;
    }

    .btn-modal-back:hover { background: var(--green-pale); }

    .btn-modal-submit {
        flex: 2;
        background: var(--green-main);
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 0.55rem;
        font-family: var(--font);
        font-size: 0.9rem;
        font-weight: 700;
        cursor: pointer;
        transition: background 0.2s, transform 0.1s;
    }

    .btn-modal-submit:hover { background: var(--green-dark); transform: translateY(-1px); }
    .btn-modal-submit.danger { background: #c0392b; }
    .btn-modal-submit.danger:hover { background: #922b21; }
</style>
@endpush
@endonce

<div class="fb-modal-backdrop" id="addSupplyModal">
    <div class="fb-modal">

        <div class="modal-hero">
            <div class="modal-hero-logo">
                <img src="/images/logos/FarmbaseLogoOnly.png" alt="Farmbase">
            </div>
        </div>

        <div class="modal-body-wrap">
            <div class="modal-title">Add New Supply</div>

            <form action="{{ route('inventory.store') }}" 
                  method="POST" 
                  enctype="multipart/form-data"
                  class="modal-form"
                  id="addSupplyForm">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Supply Name <span style="color:#c0392b">*</span></label>
                    <input type="text" 
                           name="supply_name" 
                           class="form-control @error('supply_name') is-invalid @enderror" 
                           value="{{ old('supply_name') }}"
                           placeholder="e.g. Organic Chicken Feed"
                           required>
                    @error('supply_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Category <span style="color:#c0392b">*</span></label>
                    <select name="category_id" 
                            class="form-select @error('category_id') is-invalid @enderror"
                            required>
                        <option value="" disabled selected>Select category...</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->category_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Reorder Level <span style="color:#c0392b">*</span></label>
                    <input type="number" 
                           name="reorder_level" 
                           class="form-control @error('reorder_level') is-invalid @enderror" 
                           value="{{ old('reorder_level', 10) }}" 
                           min="0"
                           required>
                    @error('reorder_level')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-1">
                    <label class="form-label">Supply Image (Optional)</label>
                    <input type="file" 
                           name="supply_image" 
                           accept="image/*"
                           class="form-control">
                </div>
            </form>
        </div>

        <div class="modal-footer-btns">
            <button class="btn-modal-back" onclick="closeModal('addSupplyModal')">Back</button>
            <button class="btn-modal-submit" onclick="document.getElementById('addSupplyForm').submit()">
                Add Supply
            </button>
        </div>

    </div>
</div>