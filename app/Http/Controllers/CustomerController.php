<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\VercelApi;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = VercelApi::getCustomers(session('token'));
        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:100']);

        $data = VercelApi::createCustomer(session('token'), [
            'name'    => $request->name,
            'phone'   => $request->phone ?? '',
            'address' => $request->address ?? '',
        ]);

        if (isset($data['error'])) {
            return back()->withErrors(['name' => $data['error']])->withInput();
        }

        return redirect()->route('customers.index')->with('success', 'Customer added successfully.');
    }

    public function show(Request $request, $id)
    {
        $token  = session('token');
        $month  = (int) $request->get('month', date('n'));
        $year   = (int) $request->get('year',  date('Y'));

        // Find customer from API
        $customers = VercelApi::getCustomers($token);
        $customer  = collect($customers)->firstWhere('_id', $id);
        if (!$customer) abort(404);

        $entries = VercelApi::getMilkEntries($token, $id, $month, $year);
        $rates   = VercelApi::getRates($token, $id, $month, $year);
        $rate    = !empty($rates) ? (float) $rates[0]['rate'] : 0;

        // Build full month grid
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $entryMap    = [];
        foreach ($entries as $e) {
            $entryMap[$e['date']] = $e;
        }

        $fullMonth   = [];
        $totals      = ['mL' => 0, 'mF' => 0, 'eL' => 0, 'eF' => 0];
        $totalAmount = 0;
        $totalSessions = 0;

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $dateStr = sprintf('%04d-%02d-%02d', $year, $month, $day);
            $e = $entryMap[$dateStr] ?? null;

            $row = [
                'id'             => $e['_id']          ?? null,
                'date'           => $dateStr,
                'morning_liter'  => (float)($e['morningLiter'] ?? 0),
                'morning_fat'    => (float)($e['morningFat']   ?? 0),
                'evening_liter'  => (float)($e['eveningLiter'] ?? 0),
                'evening_fat'    => (float)($e['eveningFat']   ?? 0),
            ];

            $mL = $row['morning_liter']; $mF = $row['morning_fat'];
            $eL = $row['evening_liter']; $eF = $row['evening_fat'];

            if ($mL > 0) $totalSessions++;
            if ($eL > 0) $totalSessions++;
            $totals['mL'] += $mL; $totals['mF'] += $mF;
            $totals['eL'] += $eL; $totals['eF'] += $eF;
            $totalAmount += $mL * $mF * $rate + $eL * $eF * $rate;

            $fullMonth[] = $row;
        }

        $totalLiter = $totals['mL'] + $totals['eL'];
        $totalFat   = $totals['mF'] + $totals['eF'];
        $avgFat     = $totalSessions > 0 ? $totalFat / $totalSessions : 0;

        return view('customers.show', compact(
            'customer', 'fullMonth', 'rate', 'month', 'year',
            'totals', 'totalAmount', 'totalLiter', 'totalFat', 'avgFat'
        ));
    }

    public function edit($id)
    {
        $customers = VercelApi::getCustomers(session('token'));
        $customer  = collect($customers)->firstWhere('_id', $id);
        if (!$customer) abort(404);
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, $id)
    {
        $request->validate(['name' => 'required|string|max:100']);

        VercelApi::updateCustomer(session('token'), $id, [
            'name'    => $request->name,
            'phone'   => $request->phone ?? '',
            'address' => $request->address ?? '',
        ]);

        return redirect()->route('customers.show', $id)->with('success', 'Customer updated.');
    }

    public function destroy($id)
    {
        VercelApi::deleteCustomer(session('token'), $id);
        return redirect()->route('customers.index')->with('success', 'Customer deleted.');
    }

    public function saveRate(Request $request, $customerId)
    {
        $request->validate(['rate' => 'required|numeric|min:0', 'month' => 'required|integer', 'year' => 'required|integer']);

        VercelApi::saveRate(session('token'), [
            'customerId' => $customerId,
            'month'      => (int) $request->month,
            'year'       => (int) $request->year,
            'rate'       => (float) $request->rate,
        ]);

        return back()->with('success', 'Rate saved successfully.');
    }
}
