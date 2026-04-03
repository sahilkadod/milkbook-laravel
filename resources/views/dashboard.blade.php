@extends('layouts.app')
@section('title', 'Dashboard')
@section('topbar-title', 'MilkBook 🥛')

@section('topbar-actions')
    <a href="{{ route('customers.index') }}" class="btn btn-sm btn-outline-white">👥 Customers</a>
@endsection

@section('content')

@php
    $months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    $prevMonth = $month == 1 ? 12 : $month - 1;
    $prevYear  = $month == 1 ? $year - 1 : $year;
    $nextMonth = $month == 12 ? 1 : $month + 1;
    $nextYear  = $month == 12 ? $year + 1 : $year;
    $avatarColors = ['#2E7D32','#1565C0','#6A1B9A','#AD1457','#E65100','#00838F','#4527A0','#558B2F'];
@endphp

{{-- Month Picker --}}
<div class="month-picker">
    <a href="{{ route('dashboard', ['month'=>$prevMonth,'year'=>$prevYear]) }}" class="month-arrow">‹</a>
    <span class="month-label">{{ $months[$month-1] }} {{ $year }}</span>
    <a href="{{ route('dashboard', ['month'=>$nextMonth,'year'=>$nextYear]) }}" class="month-arrow">›</a>
</div>

{{-- Summary Bar --}}
<div class="summary-bar">
    <div class="summary-item">
        <div class="summary-val">{{ count($customers) }}</div>
        <div class="summary-label">Customers</div>
    </div>
    <div class="summary-divider"></div>
    <div class="summary-item">
        <div class="summary-val">{{ number_format($grandTotalLiter, 1) }}</div>
        <div class="summary-label">Total Liters</div>
    </div>
    <div class="summary-divider"></div>
    <div class="summary-item">
        <div class="summary-val">₹{{ number_format($grandTotalAmount, 0) }}</div>
        <div class="summary-label">Total Amount</div>
    </div>
</div>

<div class="section-label">Customers</div>

@if(empty($customers))
    <div class="empty-state">
        <div class="empty-icon">👥</div>
        <div class="empty-text">No customers yet</div>
        <a href="{{ route('customers.create') }}" class="btn btn-primary">Add Customer</a>
    </div>
@else
    <div class="customer-grid">
        @foreach($customers as $i => $customer)
            @php
                $id    = $customer['_id'];
                $s     = $stats[$id] ?? ['totalLiter'=>0,'totalFat'=>0,'totalAmount'=>0,'days'=>0];
                $color = $avatarColors[$i % count($avatarColors)];
            @endphp
            <a href="{{ route('customers.show', ['id'=>$id,'month'=>$month,'year'=>$year]) }}"
               class="customer-card">
                <div class="ccard-avatar" style="background:{{ $color }}">
                    {{ strtoupper(substr($customer['name'],0,1)) }}
                </div>
                <div class="ccard-name">{{ $customer['name'] }}</div>
                @if(!empty($customer['phone']))
                    <div class="ccard-phone">{{ $customer['phone'] }}</div>
                @endif
                <div class="ccard-divider"></div>
                <div class="ccard-stats">
                    <div class="ccard-stat">
                        <div class="ccard-stat-val">{{ number_format($s['totalLiter'],1) }}</div>
                        <div class="ccard-stat-label">Liters</div>
                    </div>
                    <div class="ccard-stat">
                        <div class="ccard-stat-val">{{ number_format($s['totalFat'],1) }}</div>
                        <div class="ccard-stat-label">Fat</div>
                    </div>
                </div>
                @if($s['totalAmount'] > 0)
                    <div class="ccard-amount" style="background:{{ $color }}22;color:{{ $color }}">
                        ₹{{ number_format($s['totalAmount'],0) }}
                    </div>
                @endif
                @if($s['days'] > 0)
                    <div class="ccard-days">{{ $s['days'] }} days</div>
                @endif
            </a>
        @endforeach
    </div>
@endif
@endsection
