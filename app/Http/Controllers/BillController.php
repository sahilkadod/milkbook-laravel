<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\VercelApi;

class BillController extends Controller
{
    private function buildBillData($token, $customerId, $month, $year): array
    {
        $customers = VercelApi::getCustomers($token);
        $customer  = collect($customers)->firstWhere('_id', $customerId);
        if (!$customer) abort(404);

        $entries = VercelApi::getMilkEntries($token, $customerId, $month, $year);
        $rates   = VercelApi::getRates($token, $customerId, $month, $year);
        $rate    = !empty($rates) ? (float) $rates[0]['rate'] : 0;

        // Build full month grid
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $entryMap    = [];
        foreach ($entries as $e) {
            $entryMap[$e['date']] = $e;
        }

        $rows = [];
        $totMorningLiter = $totEveningLiter = 0;
        $totMorningFat   = $totEveningFat   = 0;
        $totalAmount     = $totalDays       = 0;

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $dateStr = sprintf('%04d-%02d-%02d', $year, $month, $day);
            $e = $entryMap[$dateStr] ?? null;

            $mL = (float)($e['morningLiter'] ?? 0);
            $mF = (float)($e['morningFat']   ?? 0);
            $eL = (float)($e['eveningLiter']  ?? 0);
            $eF = (float)($e['eveningFat']    ?? 0);

            $morningAmt = $mL * $mF * $rate;
            $eveningAmt = $eL * $eF * $rate;
            $dayTotal   = $morningAmt + $eveningAmt;

            $totMorningLiter += $mL; $totEveningLiter += $eL;
            $totMorningFat   += $mF; $totEveningFat   += $eF;
            $totalAmount     += $dayTotal;
            if ($mL > 0 || $eL > 0) $totalDays++;

            $rows[] = compact('dateStr', 'day', 'mL', 'mF', 'eL', 'eF', 'morningAmt', 'eveningAmt', 'dayTotal');
        }

        $totalLiter = $totMorningLiter + $totEveningLiter;
        $totalFat   = $totMorningFat   + $totEveningFat;

        return compact(
            'customer', 'rate', 'rows', 'month', 'year',
            'totMorningLiter', 'totEveningLiter', 'totMorningFat', 'totEveningFat',
            'totalLiter', 'totalFat', 'totalAmount', 'totalDays'
        );
    }

    public function show(Request $request, $customerId)
    {
        $month = (int) $request->get('month', date('n'));
        $year  = (int) $request->get('year',  date('Y'));
        $data  = $this->buildBillData(session('token'), $customerId, $month, $year);
        return view('bills.show', $data);
    }

    public function pdf(Request $request, $customerId)
    {
        $month = (int) $request->get('month', date('n'));
        $year  = (int) $request->get('year',  date('Y'));
        $data  = $this->buildBillData(session('token'), $customerId, $month, $year);
        return view('bills.pdf', $data);
    }
}
