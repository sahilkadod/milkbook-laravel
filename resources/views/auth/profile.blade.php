@extends('layouts.app')
@section('title', 'My Profile')
@section('topbar-title', 'My Profile')

@section('content')
<div class="page-card" style="max-width:480px;margin:0 auto;">
    <div class="profile-avatar-wrap">
        <div class="profile-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
        <div class="profile-name">{{ $user->name }}</div>
        <div class="profile-phone">{{ $user->phone }}</div>
    </div>

    <form method="POST" action="{{ route('profile.update') }}">
        @csrf
        <div class="form-group">
            <label class="form-label">Full Name</label>
            <input type="text" name="name" class="form-input" value="{{ old('name', $user->name) }}" required>
        </div>
        <div class="form-group">
            <label class="form-label">Phone Number</label>
            <input type="tel" name="phone" class="form-input" value="{{ old('phone', $user->phone) }}" required>
        </div>
        <hr class="divider">
        <p class="section-hint">Leave password fields blank to keep current password</p>
        <div class="form-group">
            <label class="form-label">New Password</label>
            <input type="password" name="password" class="form-input" placeholder="New password (optional)" minlength="6">
        </div>
        <div class="form-group">
            <label class="form-label">Confirm New Password</label>
            <input type="password" name="password_confirmation" class="form-input" placeholder="Confirm new password">
        </div>
        <button type="submit" class="btn btn-primary btn-block">Save Changes</button>
    </form>
</div>
@endsection
