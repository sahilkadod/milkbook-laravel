@extends('layouts.app')
@section('title', 'Customers')
@section('topbar-title', 'Customers')
@section('topbar-actions')
    <a href="{{ route('customers.create') }}" class="btn btn-sm btn-outline-white">+ Add</a>
@endsection

@section('content')
<div style="margin-bottom:12px;">
    <a href="{{ route('customers.create') }}" class="btn btn-primary btn-block">➕ Add Customer</a>
</div>

@if(empty($customers))
    <div class="empty-state">
        <div class="empty-icon">👥</div>
        <div class="empty-text">No customers yet</div>
        <a href="{{ route('customers.create') }}" class="btn btn-primary">Add First Customer</a>
    </div>
@else
    <div class="list-cards">
        @foreach($customers as $customer)
        @php $id = $customer['_id']; @endphp
        <div class="list-card">
            <a href="{{ route('customers.show', $id) }}" class="list-card-main">
                <div class="list-card-name">{{ $customer['name'] }}</div>
                @if(!empty($customer['phone']))
                    <div class="list-card-sub">📞 {{ $customer['phone'] }}</div>
                @endif
                @if(!empty($customer['address']))
                    <div class="list-card-sub">📍 {{ $customer['address'] }}</div>
                @endif
            </a>
            <div class="list-card-actions">
                <a href="{{ route('customers.edit', $id) }}" class="btn btn-sm btn-outline">Edit</a>
                <form method="POST" action="{{ route('customers.destroy', $id) }}"
                      onsubmit="return confirm('Delete {{ addslashes($customer['name']) }}?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
@endif
@endsection
