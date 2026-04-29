<div class="step-panel" id="panel-2">
    <h2>Set up your Barn</h2>

    <div class="mb-3">
        <label class="form-label">Barn Name</label>
        <input type="text" name="barn_name" value="{{ old('barn_name') }}" class="form-control @error('barn_name') is-invalid @enderror" placeholder="e.g. Greenhills Farm">
        @error('barn_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Country</label>
        <input type="text" name="country" value="{{ old('country', 'Philippines') }}" class="form-control @error('country') is-invalid @enderror">
        @error('country') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="row g-3 mb-3">
        <div class="col-6">
            <label class="form-label">Region</label>
            <input type="text" name="region" value="{{ old('region') }}" class="form-control @error('region') is-invalid @enderror" placeholder="e.g. Region XI">
            @error('region') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-6">
            <label class="form-label">City</label>
            <input type="text" name="city" value="{{ old('city') }}" class="form-control @error('city') is-invalid @enderror" placeholder="e.g. Davao City">
            @error('city') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="mb-1">
        <label class="form-label">Farm Type</label>
        <select name="farm_type" class="form-select @error('farm_type') is-invalid @enderror">
            <option value="" disabled {{ old('farm_type') ? '' : 'selected' }}>Select farm type...</option>
            <option value="Crop Farm" {{ old('farm_type') == 'Crop Farm' ? 'selected' : '' }}>Crop Farm</option>
            <option value="Livestock Farm" {{ old('farm_type') == 'Livestock Farm' ? 'selected' : '' }}>Livestock Farm</option>
            <option value="Poultry Farm" {{ old('farm_type') == 'Poultry Farm' ? 'selected' : '' }}>Poultry Farm</option>
            <option value="Mixed Farm" {{ old('farm_type') == 'Mixed Farm' ? 'selected' : '' }}>Mixed Farm</option>
            <option value="Aquaculture Farm" {{ old('farm_type') == 'Aquaculture Farm' ? 'selected' : '' }}>Aquaculture Farm</option>
            <option value="Organic Farm" {{ old('farm_type') == 'Organic Farm' ? 'selected' : '' }}>Organic Farm</option>
            <option value="Other" {{ old('farm_type') == 'Other' ? 'selected' : '' }}>Other</option>
        </select>
        @error('farm_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="btn-row">
        <button type="button" class="btn-back" onclick="goToStep(1)">Back</button>
        <button type="button" class="btn-next" onclick="goToStep(3)">Next</button>
    </div>
</div>