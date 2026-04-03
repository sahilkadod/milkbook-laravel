<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class VercelApi
{
    const BASE_URL = 'https://milk-app-api.vercel.app';

    // ── Auth ──────────────────────────────────────────────────────────────────

    public static function login(string $phone, string $password): array
    {
        $res = Http::post(self::BASE_URL . '/auth/login', [
            'phone'    => $phone,
            'password' => $password,
        ]);
        return $res->json();
    }

    public static function register(string $name, string $phone, string $password, string $dob): array
    {
        $res = Http::post(self::BASE_URL . '/auth/register', [
            'name'     => $name,
            'phone'    => $phone,
            'password' => $password,
            'dob'      => $dob,
        ]);
        return $res->json();
    }

    public static function forgotPassword(string $phone, string $dob, string $password): array
    {
        $res = Http::post(self::BASE_URL . '/auth/forgot-password', [
            'phone'    => $phone,
            'dob'      => $dob,
            'password' => $password,
        ]);
        return $res->json();
    }

    // ── Customers ─────────────────────────────────────────────────────────────

    public static function getCustomers(string $token): array
    {
        $res = Http::withToken($token)->get(self::BASE_URL . '/customers');
        return $res->json() ?? [];
    }

    public static function createCustomer(string $token, array $data): array
    {
        $res = Http::withToken($token)->post(self::BASE_URL . '/customers', $data);
        return $res->json();
    }

    public static function updateCustomer(string $token, string $id, array $data): array
    {
        $res = Http::withToken($token)->put(self::BASE_URL . '/customers/' . $id, $data);
        return $res->json();
    }

    public static function deleteCustomer(string $token, string $id): array
    {
        $res = Http::withToken($token)->delete(self::BASE_URL . '/customers/' . $id);
        return $res->json();
    }

    // ── Milk Entries ──────────────────────────────────────────────────────────

    public static function getMilkEntries(string $token, string $customerId, int $month, int $year): array
    {
        $res = Http::withToken($token)->get(self::BASE_URL . '/milk', [
            'customerId' => $customerId,
            'month'      => $month,
            'year'       => $year,
        ]);
        return $res->json() ?? [];
    }

    public static function saveMilkEntry(string $token, array $data): array
    {
        $res = Http::withToken($token)->post(self::BASE_URL . '/milk', $data);
        return $res->json();
    }

    public static function updateMilkEntry(string $token, string $id, array $data): array
    {
        $res = Http::withToken($token)->put(self::BASE_URL . '/milk/' . $id, $data);
        return $res->json();
    }

    public static function deleteMilkEntry(string $token, string $id): array
    {
        $res = Http::withToken($token)->delete(self::BASE_URL . '/milk/' . $id);
        return $res->json();
    }

    // ── Rates ─────────────────────────────────────────────────────────────────

    public static function getRates(string $token, string $customerId, int $month, int $year): array
    {
        $res = Http::withToken($token)->get(self::BASE_URL . '/rates', [
            'customerId' => $customerId,
            'month'      => $month,
            'year'       => $year,
        ]);
        return $res->json() ?? [];
    }

    public static function saveRate(string $token, array $data): array
    {
        $res = Http::withToken($token)->post(self::BASE_URL . '/rates', $data);
        return $res->json();
    }
}
