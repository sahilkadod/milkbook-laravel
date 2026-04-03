<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\VercelApi;

class MilkEntryController extends Controller
{
    public function store(Request $request, $customerId)
    {
        $request->validate(['date' => 'required|date']);

        $token = session('token');
        $month = (int) date('n', strtotime($request->date));
        $year  = (int) date('Y', strtotime($request->date));

        // Check if entry exists for this date
        $entries   = VercelApi::getMilkEntries($token, $customerId, $month, $year);
        $existing  = collect($entries)->firstWhere('date', $request->date);

        $payload = [
            'customerId'   => $customerId,
            'date'         => $request->date,
            'morningLiter' => (float)($request->morning_liter ?? 0),
            'morningFat'   => (float)($request->morning_fat   ?? 0),
            'eveningLiter' => (float)($request->evening_liter ?? 0),
            'eveningFat'   => (float)($request->evening_fat   ?? 0),
        ];

        if ($existing) {
            VercelApi::updateMilkEntry($token, $existing['_id'], $payload);
        } else {
            VercelApi::saveMilkEntry($token, $payload);
        }

        return redirect()->route('customers.show', ['id' => $customerId, 'month' => $month, 'year' => $year])
            ->with('success', 'Milk entry saved.');
    }

    public function update(Request $request, $customerId, $entryId)
    {
        $token = session('token');

        VercelApi::updateMilkEntry($token, $entryId, [
            'morningLiter' => (float)($request->morning_liter ?? 0),
            'morningFat'   => (float)($request->morning_fat   ?? 0),
            'eveningLiter' => (float)($request->evening_liter ?? 0),
            'eveningFat'   => (float)($request->evening_fat   ?? 0),
        ]);

        return back()->with('success', 'Entry updated.');
    }

    public function destroy($customerId, $entryId)
    {
        VercelApi::deleteMilkEntry(session('token'), $entryId);
        return back()->with('success', 'Entry deleted.');
    }
}
