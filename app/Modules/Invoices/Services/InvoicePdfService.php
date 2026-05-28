<?php

namespace App\Modules\Invoices\Services;

use App\Models\Invoice;
use App\Models\OrganizationPreference;
use App\Services\BasePdfService;
use Illuminate\Support\Facades\Storage;

class InvoicePdfService extends BasePdfService
{
    public function generate(Invoice $invoice): string
    {
        $invoice->load([
            'customer',
            'items',
            'organization',
            'organization.bankAccount',
            'createdBy',
            'payments',
        ]);

        $prefs = OrganizationPreference::where('organization_id', $invoice->organization_id)->first();

        $resolvedPrefs = [
            'brand_color'      => $prefs?->brand_color      ?? config('settings.organization_preferences.brand_color'),
            'invoice_footer'   => $prefs?->invoice_footer   ?? config('settings.organization_preferences.invoice_footer'),
            'invoice_template' => $prefs?->invoice_template ?? config('settings.organization_preferences.invoice_template'),
        ];

        $template = $resolvedPrefs['invoice_template'];

        $html = view("pdfs.invoices.{$template}", [
            'invoice' => $invoice,
            'prefs'   => $resolvedPrefs,
            'tax'     => config('settings.tax'),
        ])->render();

        $mpdf = $this->createMpdf();
        $mpdf->WriteHTML($html);

        return $mpdf->Output('', 'S');
    }

    public function generateAndStore(Invoice $invoice): string
    {
        $pdf      = $this->generate($invoice);
        $filename = 'invoices/' . $invoice->organization->org_code . '/' . $invoice->invoice_number . '.pdf';

        // Store permanently — paid invoices are frozen legal documents
        Storage::disk('local')->put($filename, $pdf);

        return $filename;
    }
}