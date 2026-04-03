@extends('layouts.auth')
@section('title', 'Register')
@section('subtitle', 'Create your account')

@section('content')
<form method="POST" action="{{ route('register.post') }}">
    @csrf
    <div class="form-group">
        <label class="form-label">Full Name</label>
        <input type="text" name="name" class="form-input" placeholder="Enter your full name"
               value="{{ old('name') }}" required>
    </div>
    <div class="form-group">
        <label class="form-label">Phone Number</label>
        <input type="tel" name="phone" class="form-input" placeholder="Enter your phone number"
               value="{{ old('phone') }}" required>
    </div>
    <div class="form-group">
        <label class="form-label">Date of Birth <span class="hint">(used for password reset)</span></label>
        <input type="date" name="dob" class="form-input" value="{{ old('dob') }}" required>
    </div>
    <div class="form-group">
        <label class="form-label">Password <span class="hint">(min 6 characters)</span></label>
        <div class="input-password">
            <input type="password" name="password" id="pwd" class="form-input"
                   placeholder="Minimum 6 characters" required minlength="6">
            <button type="button" class="btn-eye" onclick="togglePwd()">👁</button>
        </div>
    </div>
    <div class="form-group">
        <label class="form-label">Confirm Password</label>
        <input type="password" name="password_confirmation" class="form-input"
               placeholder="Re-enter your password" required>
    </div>
    <button type="submit" class="btn btn-primary btn-block">Create Account</button>
    <div class="auth-links">
        <a href="{{ route('login') }}">Already have an account? Login</a>
    </div>
</form>
<script>
function togglePwd() {
    const i = document.getElementById('pwd');
    i.type = i.type === 'password' ? 'text' : 'password';
}
</script>
@endsection
