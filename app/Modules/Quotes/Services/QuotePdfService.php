<?php

namespace App\Modules\Quotes\Services;

use App\Models\OrganizationPreference;
use App\Models\Quote;
use App\Services\BasePdfService;

class QuotePdfService extends BasePdfService
{
    public function generate(Quote $quote): string
    {
        // Load all relationships needed for the PDF
        $quote->load([
            'customer',
            'items.product',
            'organization',
            'organization.bankAccount',
            'createdBy',
        ]);

        // Get org preferences for branding and template
        $prefs = OrganizationPreference::where('organization_id', $quote->organization_id)
            ->first();

        $resolvedPrefs = [
            'brand_color'      => $prefs?->brand_color      ?? config('settings.organization_preferences.brand_color'),
            'invoice_footer'   => $prefs?->invoice_footer   ?? config('settings.organization_preferences.invoice_footer'),
            'quote_template'   => $prefs?->quote_template   ?? config('settings.organization_preferences.quote_template'),
        ];

        $template = $resolvedPrefs['quote_template'];

        $html = view("pdfs.quotes.{$template}", [
            'quote' => $quote,
            'prefs' => $resolvedPrefs,
            'tax'   => config('settings.tax'),
        ])->render();

        $mpdf = $this->createMpdf();
        $mpdf->WriteHTML($html);

        // Return as string — controller handles the response
        return $mpdf->Output('', 'S');
    }
}