<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bill — {{ $customer->name }} — {{ date('F Y', mktime(0,0,0,$month,1,$year)) }}</title>
    <style>
        @page { margin: 8mm; size: A4 portrait; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 9px; color: #1B1B1B; background:#fff; }

        /* Header */
        .header { background: #2E7D32; color: white; padding: 8px 12px; border-radius: 6px; margin-bottom: 10px; display: flex; align-items: center; justify-content: space-between; }
        .header-left { display: flex; align-items: center; gap: 8px; }
        .logo-emoji { font-size: 28px; }
        .app-name  { font-size: 18px; font-weight: 900; }
        .bill-label { font-size: 9px; opacity: 0.8; text-transform: uppercase; letter-spacing: 1px; }
        .header-right { text-align: right; }
        .cust-name  { font-size: 14px; font-weight: 800; }
        .cust-meta  { font-size: 9px; opacity: 0.85; margin-top: 2px; }

        /* Summary cards */
        .summary { display: flex; gap: 5px; margin-bottom: 10px; flex-wrap: wrap; }
        .card { flex: 1; min-width: 60px; border: 1px solid #A5D6A7; background: #E8F5E9; border-radius: 5px; padding: 5px 4px; text-align: center; }
        .card.accent { background: #C8E6C9; }
        .card .clabel { font-size: 7px; color: #2E7D32; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 700; }
        .card .cval   { font-size: 11px; font-weight: 800; color: #2E7D32; margin-top: 2px; }

        /* Sub totals */
        .subtotals { display: flex; gap: 10px; margin-bottom: 10px; background:#f9f9f9; border:1px solid #e0e0e0; border-radius:5px; padding:8px 12px; }
        .stblock   { display: flex; align-items: center; gap: 8px; }
        .stlabel   { font-size: 8px; color:#888; text-transform:uppercase; font-weight:600; margin-bottom:2px; }
        .stval     { font-size: 10px; font-weight: 700; }

        /* Table */
        table { width: 100%; border-collapse: collapse; }
        th { background: #1B1B1B; color: white; padding: 4px 3px; font-size: 8px; text-transform: uppercase; letter-spacing: 0.3px; text-align: center; }
        td { padding: 3px 4px; border-bottom: 1px solid #F0F0F0; text-align: center; font-size: 8px; }
        tr:nth-child(even) td { background: #FAFAFA; }
        td.cell-date { font-weight: 700; }
        td.cell-amt  { text-align: right; color: #2E7D32; font-weight: 700; padding-right: 6px; }
        .tfoot-row td { background: #E8F5E9 !important; font-weight: 800; color: #2E7D32; border-top: 1.5px solid #A5D6A7; font-size: 9px; }

        /* Print controls (hidden on print) */
        .print-bar { padding: 12px; display: flex; gap: 10px; justify-content: flex-end; }
        @media print { .print-bar { display: none; } }
    </style>
</head>
<body>

<div class="print-bar">
    <button onclick="window.print()" style="background:#2E7D32;color:white;border:none;padding:8px 18px;border-radius:6px;font-size:13px;cursor:pointer;font-weight:700;">
        🖨 Print / Save PDF
    </button>
    <button onclick="window.close()" style="background:#eee;border:none;padding:8px 14px;border-radius:6px;font-size:13px;cursor:pointer;">
        Close
    </button>
</div>

@php
    $monthName  = date('F', mktime(0,0,0,$month,1,$year));
    $activeRows = array_filter($rows, fn($r) => $r['mL'] > 0 || $r['eL'] > 0);
@endphp

{{-- Header --}}
<div class="header">
    <div class="header-left">
        <span class="logo-emoji">🥛</span>
        <div>
            <div class="app-name">MilkBook</div>
            <div class="bill-label">Monthly Bill</div>
        </div>
    </div>
    <div class="header-right">
        <div class="cust-name">{{ $customer->name }}</div>
        <div class="cust-meta">{{ $monthName }} {{ $year }}</div>
        @if($customer->phone)
            <div class="cust-meta">📞 {{ $customer->phone }}</div>
        @endif
    </div>
</div>

{{-- Summary --}}
<div class="summary">
    <div class="card accent"><div class="clabel">Rate/Fat</div><div class="cval">₹{{ $rate }}</div></div>
    <div class="card"><div class="clabel">M.Liters</div><div class="cval">{{ number_format($totMorningLiter,1) }}</div></div>
    <div class="card"><div class="clabel">E.Liters</div><div class="cval">{{ number_format($totEveningLiter,1) }}</div></div>
    <div class="card"><div class="clabel">Total Liters</div><div class="cval">{{ number_format($totalLiter,1) }}</div></div>
    <div class="card"><div class="clabel">Total Fat</div><div class="cval">{{ number_format($totalFat,1) }}</div></div>
    <div class="card accent"><div class="clabel">Total Amount</div><div class="cval">₹{{ number_format($totalAmount,0) }}</div></div>
</div>

{{-- Table --}}
<table>
    <thead>
        <tr><th>Day</th><th>M.Lt</th><th>M.Fat</th><th>E.Lt</th><th>E.Fat</th><th>Amount</th></tr>
    </thead>
    <tbody>
        @foreach($activeRows as $row)
        <tr>
            <td class="cell-date">{{ $row['day'] }}</td>
            <td>{{ $row['mL'] > 0 ? $row['mL'] : '—' }}</td>
            <td>{{ $row['mF'] > 0 ? $row['mF'] : '—' }}</td>
            <td>{{ $row['eL'] > 0 ? $row['eL'] : '—' }}</td>
            <td>{{ $row['eF'] > 0 ? $row['eF'] : '—' }}</td>
            <td class="cell-amt">₹{{ number_format($row['dayTotal'],2) }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr class="tfoot-row">
            <td>TOTAL</td>
            <td>{{ number_format($totMorningLiter,1) }}</td>
            <td>{{ number_format($totMorningFat,1) }}</td>
            <td>{{ number_format($totEveningLiter,1) }}</td>
            <td>{{ number_format($totEveningFat,1) }}</td>
            <td class="cell-amt">₹{{ number_format($totalAmount,0) }}</td>
        </tr>
    </tfoot>
</table>

</body>
</html>
