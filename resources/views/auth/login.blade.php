@extends('layouts.auth')
@section('title', 'Login')
@section('subtitle', 'Sign in to your account')

@section('content')
<form method="POST" action="{{ route('login.post') }}">
    @csrf
    <div class="form-group">
        <label class="form-label">Phone Number</label>
        <input type="tel" name="phone" class="form-input" placeholder="Enter your phone number"
               value="{{ old('phone') }}" required autofocus>
    </div>
    <div class="form-group">
        <label class="form-label">Password</label>
        <div class="input-password">
            <input type="password" name="password" class="form-input" id="passwordInput"
                   placeholder="Enter your password" required>
            <button type="button" class="btn-eye" onclick="togglePwd()">👁</button>
        </div>
    </div>
    <button type="submit" class="btn btn-primary btn-block">Login</button>
    <div class="auth-links">
        <a href="{{ route('forgot') }}">Forgot Password?</a>
        <span>·</span>
        <a href="{{ route('register') }}">Create account</a>
    </div>
</form>
<script>
function togglePwd() {
    const i = document.getElementById('passwordInput');
    i.type = i.type === 'password' ? 'text' : 'password';
}
</script>
@endsection
