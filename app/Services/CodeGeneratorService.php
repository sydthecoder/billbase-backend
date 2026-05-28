<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Organization;
use App\Models\OrganizationPreference;
use App\Models\Quote;
use App\Models\Invoice;

class CodeGeneratorService
{
    public static function organization(): string
    {
        do {
            $code = 'PAY' . random_int(10000, 99999);
        } while (Organization::where('org_code', $code)->exists());

        return $code;
    }

    public static function customer(int $organizationId, ?string $prefix = null): string
    {
        $prefix = self::resolveCustomerPrefix($organizationId, $prefix);

        do {
            $code = $prefix . random_int(10000, 99999);
        } while (
            Customer::where('organization_id', $organizationId)
                    ->where('customer_code', $code)
                    ->exists()
        );

        return $code;
    }

    public static function quote(int $organizationId): string
    {
        $prefs    = OrganizationPreference::where('organization_id', $organizationId)->first();
        $prefix   = strtoupper($prefs?->quote_prefix ?? config('settings.organization_preferences.quote_prefix'));
        $starting = $prefs?->quote_starting_number ?? config('settings.organization_preferences.quote_starting_number');

        // Count existing quotes for this org to determine next number
        $count  = Quote::where('organization_id', $organizationId)->withTrashed()->count();
        $number = str_pad($starting + $count, 4, '0', STR_PAD_LEFT);

        $code = $prefix . '-' . $number;

        // Safety loop — if somehow that code exists, increment
        while (Quote::where('organization_id', $organizationId)->where('quote_number', $code)->exists()) {
            $count++;
            $number = str_pad($starting + $count, 4, '0', STR_PAD_LEFT);
            $code   = $prefix . '-' . $number;
        }

        return $code;
    }

    public static function invoice(int $organizationId): string
    {
        $prefs  = OrganizationPreference::where('organization_id', $organizationId)->first();
        $prefix = strtoupper($prefs?->invoice_prefix ?? config('settings.organization_preferences.invoice_prefix'));

        $last = Invoice::where('organization_id', $organizationId)
                    ->orderByDesc('id')
                    ->value('invoice_number');

        if ($last) {
            $lastNumber = (int) preg_replace('/[^0-9]/', '', $last);
            $next       = $lastNumber + 1;
        } else {
            $next = $prefs?->invoice_starting_number
                    ?? config('settings.organization_preferences.invoice_starting_number');
        }

        return $prefix . '-' . str_pad($next, 4, '0', STR_PAD_LEFT);
    }

    private static function resolveCustomerPrefix(int $organizationId, ?string $prefix): string
    {
        $trimmed = $prefix !== null ? trim($prefix) : null;

        if ($trimmed !== null && $trimmed !== '') {
            return strtoupper($trimmed);
        }

        $prefs    = OrganizationPreference::where('organization_id', $organizationId)->first();
        $fallback = config('settings.organization_preferences.customer_code_prefix');

        return strtoupper($prefs?->customer_code_prefix ?? $fallback);
    }
}