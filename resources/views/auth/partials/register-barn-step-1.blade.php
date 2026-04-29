<div class="step-panel active" id="panel-1">
    <h2>Create Account</h2>

    <div class="row g-3 mb-3">
        <div class="col-6">
            <label class="form-label">First Name</label>
            <input type="text" name="first_name" value="{{ old('first_name') }}" class="form-control @error('first_name') is-invalid @enderror" placeholder="Juan">
            @error('first_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-6">
            <label class="form-label">Last Name</label>
            <input type="text" name="last_name" value="{{ old('last_name') }}" class="form-control @error('last_name') is-invalid @enderror" placeholder="Dela Cruz">
            @error('last_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" placeholder="juan@farm.com">
        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Username</label>
        <input type="text" name="username" value="{{ old('username') }}" class="form-control @error('username') is-invalid @enderror" placeholder="juanfarm">
        @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Password</label>
        <div class="password-wrapper">
            <input type="password" name="password" id="pw1" class="form-control @error('password') is-invalid @enderror">
            <button type="button" class="password-toggle" onclick="togglePw('pw1','eye1')"><span id="eye1">👁</span></button>
        </div>
        @error('password') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
    </div>

    <div class="mb-1">
        <label class="form-label">Confirm Password</label>
        <div class="password-wrapper">
            <input type="password" name="password_confirmation" id="pw2" class="form-control">
            <button type="button" class="password-toggle" onclick="togglePw('pw2','eye2')"><span id="eye2">👁</span></button>
        </div>
    </div>

    <div class="btn-row">
        <a href="{{ route('login') }}" class="btn-back text-center text-decoration-none d-flex align-items-center justify-content-center">Back</a>
        <button type="button" class="btn-next" onclick="goToStep(2)">Next</button>
    </div>
</div>