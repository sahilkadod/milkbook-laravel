@extends('layouts.app')
@section('title', 'Add Customer')
@section('topbar-title', 'Add Customer')

@section('content')
<div class="page-card" style="max-width:480px;margin:0 auto;">
    <form method="POST" action="{{ route('customers.store') }}">
        @csrf
        <div class="form-group">
            <label class="form-label">Full Name <span class="required">*</span></label>
            <input type="text" name="name" class="form-input" placeholder="Customer name"
                   value="{{ old('name') }}" required autofocus>
        </div>
        <div class="form-group">
            <label class="form-label">Phone Number</label>
            <input type="tel" name="phone" class="form-input" placeholder="Optional"
                   value="{{ old('phone') }}">
        </div>
        <div class="form-group">
            <label class="form-label">Address</label>
            <input type="text" name="address" class="form-input" placeholder="Optional"
                   value="{{ old('address') }}">
        </div>
        <div style="display:flex;gap:10px;">
            <button type="submit" class="btn btn-primary" style="flex:1;">Add Customer</button>
            <a href="{{ route('customers.index') }}" class="btn btn-outline" style="flex:1;text-align:center;">Cancel</a>
        </div>
    </form>
</div>
@endsection
