<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Organization;
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
        $resolver = OrganizationSettingsResolver::for($organizationId);

        $prefix   = strtoupper($resolver->get('quote_prefix'));
        $starting = (int) $resolver->get('quote_starting_number');

        $count  = Quote::where('organization_id', $organizationId)->withTrashed()->count();
        $number = str_pad($starting + $count, 4, '0', STR_PAD_LEFT);
        $code   = $prefix . '-' . $number;

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
        $resolver = OrganizationSettingsResolver::for($organizationId);

        $prefix = strtoupper($resolver->get('invoice_prefix'));

        $last = Invoice::where('organization_id', $organizationId)
                    ->orderByDesc('id')
                    ->value('invoice_number');

        if ($last) {
            $next = (int) preg_replace('/[^0-9]/', '', $last) + 1;
        } else {
            $next = (int) $resolver->get('invoice_starting_number');
        }

        return $prefix . '-' . str_pad($next, 4, '0', STR_PAD_LEFT);
    }

    private static function resolveCustomerPrefix(int $organizationId, ?string $prefix): string
    {
        $trimmed = $prefix !== null ? trim($prefix) : null;

        if ($trimmed !== null && $trimmed !== '') {
            return strtoupper($trimmed);
        }

        return strtoupper(
            OrganizationSettingsResolver::for($organizationId)->get('customer_code_prefix')
        );
    }
}