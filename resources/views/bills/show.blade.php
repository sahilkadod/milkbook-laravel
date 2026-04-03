@extends('layouts.app')
@section('title', 'Bill — '.$customer->name)
@section('topbar-title', 'Monthly Bill')

@section('topbar-actions')
    <a href="{{ route('bill.pdf', ['customerId'=>$customer->id,'month'=>$month,'year'=>$year]) }}"
       target="_blank" class="btn btn-sm btn-outline-white">🖨 Print / PDF</a>
@endsection

@section('content')
@php
    $monthNames = ['January','February','March','April','May','June','July','August','September','October','November','December'];
    $prevMonth  = $month == 1  ? 12 : $month - 1;
    $prevYear   = $month == 1  ? $year - 1 : $year;
    $nextMonth  = $month == 12 ? 1  : $month + 1;
    $nextYear   = $month == 12 ? $year + 1 : $year;
    $activeRows = array_filter($rows, fn($r) => $r['mL'] > 0 || $r['eL'] > 0);
@endphp

{{-- ── Month Picker ────────────────────────────────────────────────────────── --}}
<div class="month-picker">
    <a href="{{ route('bill.show', ['customerId'=>$customer->id,'month'=>$prevMonth,'year'=>$prevYear]) }}" class="month-arrow">‹</a>
    <span class="month-label">{{ $monthNames[$month-1] }} {{ $year }}</span>
    <a href="{{ route('bill.show', ['customerId'=>$customer->id,'month'=>$nextMonth,'year'=>$nextYear]) }}" class="month-arrow">›</a>
</div>

{{-- ── Summary Cards ────────────────────────────────────────────────────────── --}}
<div class="bill-summary-grid">
    <div class="bill-card accent">
        <div class="bill-card-label">Rate / Fat</div>
        <div class="bill-card-val">₹{{ number_format($rate,2) }}</div>
    </div>
    <div class="bill-card accent">
        <div class="bill-card-label">Total Amount</div>
        <div class="bill-card-val">₹{{ number_format($totalAmount,0) }}</div>
    </div>
    <div class="bill-card">
        <div class="bill-card-label">Total Liters</div>
        <div class="bill-card-val">{{ number_format($totalLiter,2) }} L</div>
    </div>
    <div class="bill-card">
        <div class="bill-card-label">Total Fat</div>
        <div class="bill-card-val">{{ number_format($totalFat,2) }}</div>
    </div>
    <div class="bill-card">
        <div class="bill-card-label">Total Days</div>
        <div class="bill-card-val">{{ $totalDays }} days</div>
    </div>
</div>

{{-- ── Sub-totals ───────────────────────────────────────────────────────────── --}}
<div class="bill-subtotals">
    <div class="subtotal-block">
        <span class="subtotal-icon">🌅</span>
        <div>
            <div class="subtotal-label">Morning</div>
            <div class="subtotal-val">{{ number_format($totMorningLiter,2) }} L &nbsp;|&nbsp; {{ number_format($totMorningFat,2) }} F</div>
        </div>
    </div>
    <div class="subtotal-divider"></div>
    <div class="subtotal-block">
        <span class="subtotal-icon">🌇</span>
        <div>
            <div class="subtotal-label">Evening</div>
            <div class="subtotal-val">{{ number_format($totEveningLiter,2) }} L &nbsp;|&nbsp; {{ number_format($totEveningFat,2) }} F</div>
        </div>
    </div>
</div>

{{-- ── Table ────────────────────────────────────────────────────────────────── --}}
<div class="table-wrap" style="margin-top:16px;">
    <table class="milk-table bill-table">
        <thead>
            <tr>
                <th>Date</th><th>M.Lt</th><th>M.Fat</th><th>E.Lt</th><th>E.Fat</th><th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $row)
                @if($row['mL'] > 0 || $row['eL'] > 0)
                <tr>
                    <td class="cell-date">{{ $row['day'] }}</td>
                    <td>{{ $row['mL'] > 0 ? $row['mL'] : '—' }}</td>
                    <td>{{ $row['mF'] > 0 ? $row['mF'] : '—' }}</td>
                    <td>{{ $row['eL'] > 0 ? $row['eL'] : '—' }}</td>
                    <td>{{ $row['eF'] > 0 ? $row['eF'] : '—' }}</td>
                    <td class="cell-amt">{{ $row['dayTotal'] > 0 ? '₹'.number_format($row['dayTotal'],2) : '—' }}</td>
                </tr>
                @endif
            @endforeach
        </tbody>
        <tfoot>
            <tr class="tfoot-row">
                <td>Total</td>
                <td>{{ number_format($totMorningLiter,1) }}</td>
                <td>{{ number_format($totMorningFat,1) }}</td>
                <td>{{ number_format($totEveningLiter,1) }}</td>
                <td>{{ number_format($totEveningFat,1) }}</td>
                <td class="cell-amt">₹{{ number_format($totalAmount,0) }}</td>
            </tr>
        </tfoot>
    </table>
</div>

{{-- ── Chart ────────────────────────────────────────────────────────────────── --}}
@if(count($activeRows) > 0)
<div class="chart-wrap">
    <div class="chart-title">📊 Daily Liters & Fat</div>
    <canvas id="billChart" height="180"></canvas>
    <script>
    (function() {
        const rows = @json(array_values($activeRows));
        const labels = rows.map(r => parseInt(r.dateStr.slice(8)));
        const liters = rows.map(r => parseFloat(r.mL) + parseFloat(r.eL));
        const fats   = rows.map(r => parseFloat(r.mF) + parseFloat(r.eF));

        const ctx = document.getElementById('billChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels,
                datasets: [
                    {
                        label: 'Total Liters',
                        data: liters,
                        borderColor: '#2E7D32',
                        backgroundColor: 'rgba(46,125,50,0.1)',
                        borderWidth: 2.5,
                        pointRadius: 3,
                        tension: 0.3,
                        fill: true,
                        yAxisID: 'yL',
                    },
                    {
                        label: 'Total Fat',
                        data: fats,
                        borderColor: '#E65100',
                        backgroundColor: 'rgba(230,81,0,0.05)',
                        borderWidth: 2,
                        pointRadius: 3,
                        tension: 0.3,
                        borderDash: [6,3],
                        yAxisID: 'yF',
                    }
                ]
            },
            options: {
                responsive: true,
                interaction: { mode: 'index', intersect: false },
                plugins: { legend: { position: 'bottom' } },
                scales: {
                    yL: { position: 'left',  title: { display: true, text: 'Liters', color: '#2E7D32' } },
                    yF: { position: 'right', title: { display: true, text: 'Fat',    color: '#E65100' }, grid: { drawOnChartArea: false } },
                }
            }
        });
    })();
    </script>
</div>
@endif

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
@endsection
