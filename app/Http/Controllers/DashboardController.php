<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\VercelApi;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $token     = session('token');
        $month     = (int) $request->get('month', date('n'));
        $year      = (int) $request->get('year',  date('Y'));

        $customers = VercelApi::getCustomers($token);

        // Build stats per customer
        $stats            = [];
        $grandTotalLiter  = 0;
        $grandTotalAmount = 0;

        foreach ($customers as $customer) {
            $customerId = $customer['_id'];

            $entries = VercelApi::getMilkEntries($token, $customerId, $month, $year);
            $rates   = VercelApi::getRates($token, $customerId, $month, $year);
            $rate    = !empty($rates) ? (float) $rates[0]['rate'] : 0;

            $totalLiter = $totalFat = $totalAmount = $days = 0;

            foreach ($entries as $e) {
                $mL = (float)($e['morningLiter'] ?? 0);
                $mF = (float)($e['morningFat']   ?? 0);
                $eL = (float)($e['eveningLiter']  ?? 0);
                $eF = (float)($e['eveningFat']    ?? 0);

                if ($mL > 0 || $eL > 0) $days++;
                $totalLiter  += $mL + $eL;
                $totalFat    += $mF + $eF;
                $totalAmount += $mL * $mF * $rate + $eL * $eF * $rate;
            }

            $stats[$customerId] = compact('totalLiter', 'totalFat', 'totalAmount', 'days', 'rate');
            $grandTotalLiter  += $totalLiter;
            $grandTotalAmount += $totalAmount;
        }

        return view('dashboard', compact(
            'customers', 'stats', 'month', 'year',
            'grandTotalLiter', 'grandTotalAmount'
        ));
    }
}
