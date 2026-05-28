<?php

namespace App\Modules\Plans\Services;

use App\Models\Plan;
use Illuminate\Support\Collection;

class PlanService
{
    /**
     * All active plans with config features merged in.
     * This is what the pricing page / plan listing endpoint returns.
     */
    public function all(): Collection
    {
        return Plan::where('is_active', true)
            ->orderBy('price')
            ->get()
            ->map(fn (Plan $plan) => $this->format($plan));
    }

    /**
     * Single plan by slug with config features merged in.
     */
    public function bySlug(string $slug): ?array
    {
        $plan = Plan::where('slug', $slug)->where('is_active', true)->first();

        return $plan ? $this->format($plan) : null;
    }

    /**
     * Get just the limits for a slug — used by PlanGate checks.
     * Returns null if slug not found in config (unknown/legacy plan).
     */
    public function limitsFor(string $slug): ?array
    {
        return config("plans.{$slug}.limits");
    }

    /**
     * Check a single limit for a slug.
     *
     * Returns the limit value (int, bool, string, null).
     * null means unlimited.
     * false means feature not available.
     *
     * Example:
     *   $service->limit('starter', 'invoices_per_month') // 100
     *   $service->limit('enterprise', 'invoices_per_month') // null (unlimited)
     *   $service->limit('starter', 'mail_settings') // false (not available)
     */
    public function limit(string $slug, string $key): mixed
    {
        return config("plans.{$slug}.limits.{$key}");
    }

    // -------------------------------------------------------------------------
    // Private
    // -------------------------------------------------------------------------

    private function format(Plan $plan): array
    {
        return [
            'id'       => $plan->id,
            'name'     => $plan->name,
            'slug'     => $plan->slug,
            'price'    => $plan->price,
            'currency' => 'ZAR',
            'features' => config("plans.{$plan->slug}.features", []),
            'is_active' => $plan->is_active,
        ];
    }
}