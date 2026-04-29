<div class="step-panel" id="panel-3">
    <h2>Finalize your setup</h2>

    <div class="mb-3">
        <label class="form-label">Business Permit Number</label>
        <input type="text" name="permit_number" value="{{ old('permit_number') }}" class="form-control @error('permit_number') is-invalid @enderror" placeholder="e.g. BP-2026-00123">
        @error('permit_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="mb-1">
        <label class="form-label">Upload Permit Document</label>
        <div class="upload-zone" id="uploadZone">
            <input type="file" name="permit_doc" accept="image/*,.pdf" id="permitFile" onchange="handleFileSelect(this)">
            <span class="upload-icon">📄</span>
            <span class="upload-label">Add Image / PDF</span>
            <span class="upload-hint">Click or drag file here</span>
        </div>
        <div id="uploadPreview"></div>
    </div>

    <div class="btn-row">
        <button type="button" class="btn-back" onclick="goToStep(2)">Back</button>
        <button type="submit" class="btn-next" id="createBarnBtn">Create Barn</button>
    </div>
</div>