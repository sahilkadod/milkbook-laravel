@extends('layouts.app')
@section('title', 'Edit Customer')
@section('topbar-title', 'Edit Customer')

@section('content')
<div class="page-card" style="max-width:480px;margin:0 auto;">
    <form method="POST" action="{{ route('customers.update', $customer['_id']) }}">
        @csrf @method('PUT')
        <div class="form-group">
            <label class="form-label">Full Name <span class="required">*</span></label>
            <input type="text" name="name" class="form-input"
                   value="{{ old('name', $customer['name']) }}" required>
        </div>
        <div class="form-group">
            <label class="form-label">Phone Number</label>
            <input type="tel" name="phone" class="form-input"
                   value="{{ old('phone', $customer['phone'] ?? '') }}">
        </div>
        <div class="form-group">
            <label class="form-label">Address</label>
            <input type="text" name="address" class="form-input"
                   value="{{ old('address', $customer['address'] ?? '') }}">
        </div>
        <div style="display:flex;gap:10px;">
            <button type="submit" class="btn btn-primary" style="flex:1;">Save Changes</button>
            <a href="{{ route('customers.show', $customer['_id']) }}" class="btn btn-outline" style="flex:1;text-align:center;">Cancel</a>
        </div>
    </form>
</div>
@endsection
