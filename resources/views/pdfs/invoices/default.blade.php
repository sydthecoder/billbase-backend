<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'dejavusans', sans-serif;
            font-size: 12px;
            color: #1a1a2e;
            line-height: 1.5;
        }

        .header {
            display: table;
            width: 100%;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid {{ $prefs['brand_color'] }};
        }

        .header-left { display: table-cell; vertical-align: top; width: 60%; }
        .header-right { display: table-cell; vertical-align: top; text-align: right; width: 40%; }

        .company-name {
            font-size: 22px;
            font-weight: bold;
            color: {{ $prefs['brand_color'] }};
        }

        .company-details { font-size: 11px; color: #555; margin-top: 5px; }

        .tax-invoice-label {
            font-size: 28px;
            font-weight: bold;
            color: {{ $prefs['brand_color'] }};
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .invoice-number { font-size: 14px; color: #555; margin-top: 4px; }

        .status-badge {
            display: inline-block;
            padding: 3px 10px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            background-color: {{ $prefs['brand_color'] }};
            color: #ffffff;
            margin-top: 6px;
        }

        .meta-section { display: table; width: 100%; margin-bottom: 30px; }
        .meta-left  { display: table-cell; width: 50%; vertical-align: top; }
        .meta-right { display: table-cell; width: 50%; vertical-align: top; text-align: right; }

        .meta-label {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #999;
            margin-bottom: 3px;
        }

        .meta-value { font-size: 12px; color: #1a1a2e; font-weight: bold; }

        .bill-to-name { font-size: 14px; font-weight: bold; color: #1a1a2e; }
        .bill-to-details { font-size: 11px; color: #555; margin-top: 2px; }

        table.items { width: 100%; border-collapse: collapse; margin-bottom: 20px; }

        table.items thead tr { background-color: {{ $prefs['brand_color'] }}; color: #ffffff; }

        table.items thead th {
            padding: 8px 10px;
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        table.items thead th.text-right { text-align: right; }
        table.items tbody tr { border-bottom: 1px solid #eee; }
        table.items tbody tr:nth-child(even) { background-color: #f9f9f9; }

        table.items tbody td {
            padding: 8px 10px;
            font-size: 11px;
            vertical-align: top;
        }

        table.items tbody td.text-right { text-align: right; }
        .item-description { font-weight: bold; color: #1a1a2e; }

        .totals-section { display: table; width: 100%; margin-bottom: 30px; }
        .totals-spacer  { display: table-cell; width: 55%; }
        .totals-box     { display: table-cell; width: 45%; vertical-align: top; }

        .totals-row        { display: table; width: 100%; padding: 5px 0; border-bottom: 1px solid #eee; }
        .totals-row-label  { display: table-cell; font-size: 11px; color: #555; }
        .totals-row-value  { display: table-cell; text-align: right; font-size: 11px; color: #1a1a2e; }

        .totals-row.total-final {
            border-top: 2px solid {{ $prefs['brand_color'] }};
            border-bottom: none;
            padding-top: 8px;
            margin-top: 5px;
        }

        .totals-row.total-final .totals-row-label,
        .totals-row.total-final .totals-row-value {
            font-size: 14px;
            font-weight: bold;
            color: {{ $prefs['brand_color'] }};
        }

        .bank-section {
            background-color: #f5f5f5;
            padding: 12px 15px;
            margin-bottom: 20px;
        }

        .bank-section .section-title {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #999;
            margin-bottom: 8px;
        }

        .bank-details { display: table; width: 100%; }
        .bank-col { display: table-cell; width: 20%; font-size: 11px; }
        .bank-col-label { color: #999; font-size: 10px; }
        .bank-col-value { color: #1a1a2e; font-weight: bold; }

        .notes-section { margin-bottom: 20px; }
        .notes-section .section-title {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #999;
            margin-bottom: 5px;
        }
        .notes-section p { font-size: 11px; color: #555; }

        .footer {
            border-top: 1px solid #eee;
            padding-top: 10px;
            text-align: center;
            font-size: 10px;
            color: #999;
        }

        .sars-notice {
            text-align: center;
            font-size: 9px;
            color: #aaa;
            margin-top: 5px;
        }
    </style>
</head>
<body>

    {{-- HEADER --}}
    <div class="header">
        <div class="header-left">
            <div class="company-name">
                {{ $invoice->organization->name ?? $invoice->organization->org_code }}
            </div>
            <div class="company-details">
                @if($invoice->organization->email)
                    {{ $invoice->organization->email }}<br>
                @endif
                @if($invoice->organization->phone)
                    {{ $invoice->organization->phone }}<br>
                @endif
                @if($invoice->organization->street_address)
                    {{ $invoice->organization->street_address }},
                    {{ $invoice->organization->suburb }},
                    {{ $invoice->organization->city }},
                    {{ $invoice->organization->province }}<br>
                @endif
                @if($invoice->organization->tax_number)
                    {{ $tax['number_label'] }}: {{ $invoice->organization->tax_number }}
                @endif
            </div>
        </div>

        <div class="header-right">
            {{-- SARS REQUIRED: Must say TAX INVOICE --}}
            <div class="tax-invoice-label">Tax Invoice</div>
            <div class="invoice-number">{{ $invoice->invoice_number }}</div>
            <div><span class="status-badge">{{ strtoupper($invoice->status) }}</span></div>
        </div>
    </div>

    {{-- DATES + BILL TO --}}
    <div class="meta-section">
        <div class="meta-left">
            <div class="meta-label">Bill To</div>
            <div class="bill-to-name">
                @if($invoice->billing_company)
                    {{ $invoice->billing_company }}
                @else
                    {{ $invoice->billing_name }}
                @endif
            </div>
            <div class="bill-to-details">
                @if($invoice->billing_company)
                    {{ $invoice->billing_name }}<br>
                @endif
                @if($invoice->billing_vat_number)
                    {{ $tax['number_label'] }}: {{ $invoice->billing_vat_number }}<br>
                @endif
                @if($invoice->billing_street_address)
                    {{ $invoice->billing_street_address }},
                    {{ $invoice->billing_suburb }},
                    {{ $invoice->billing_city }},
                    {{ $invoice->billing_province }},
                    {{ $invoice->billing_postal_code }}
                @endif
            </div>
        </div>

        <div class="meta-right">
            <div style="margin-bottom: 10px;">
                <div class="meta-label">Invoice Date</div>
                <div class="meta-value">{{ \Carbon\Carbon::parse($invoice->issue_date)->format('d M Y') }}</div>
            </div>
            <div style="margin-bottom: 10px;">
                <div class="meta-label">Due Date</div>
                <div class="meta-value">{{ \Carbon\Carbon::parse($invoice->due_date)->format('d M Y') }}</div>
            </div>
            <div>
                <div class="meta-label">Amount Due</div>
                <div class="meta-value" style="color: {{ $prefs['brand_color'] }}; font-size: 16px;">
                    R {{ number_format((float) $invoice->amount_due, 2) }}
                </div>
            </div>
        </div>
    </div>

    {{-- LINE ITEMS --}}
    <table class="items">
        <thead>
            <tr>
                <th style="width: 38%;">Description</th>
                <th style="width: 10%;">Qty</th>
                <th style="width: 10%;">Unit</th>
                <th class="text-right" style="width: 14%;">Unit Price</th>
                <th class="text-right" style="width: 10%;">{{ $tax['label'] }}</th>
                <th class="text-right" style="width: 8%;">Disc</th>
                <th class="text-right" style="width: 10%;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $item)
            <tr>
                <td><div class="item-description">{{ $item->description }}</div></td>
                <td>{{ number_format((float) $item->quantity, 2) }}</td>
                <td>{{ $item->unit ?? '—' }}</td>
                <td class="text-right">R {{ number_format((float) $item->unit_price, 2) }}</td>
                <td class="text-right">
                    @if($item->is_taxable) {{ $item->tax_rate }}% @else Exempt @endif
                </td>
                <td class="text-right">
                    @if($item->discount_amount > 0)
                        R {{ number_format((float) $item->discount_amount, 2) }}
                    @else — @endif
                </td>
                <td class="text-right">R {{ number_format((float) $item->line_total, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- TOTALS --}}
    <div class="totals-section">
        <div class="totals-spacer"></div>
        <div class="totals-box">
            <div class="totals-row">
                <div class="totals-row-label">Subtotal</div>
                <div class="totals-row-value">R {{ number_format((float) $invoice->subtotal, 2) }}</div>
            </div>
            @if($invoice->discount_amount > 0)
            <div class="totals-row">
                <div class="totals-row-label">
                    Discount @if($invoice->discount_percent > 0)({{ $invoice->discount_percent }}%)@endif
                </div>
                <div class="totals-row-value">- R {{ number_format((float) $invoice->discount_amount, 2) }}</div>
            </div>
            @endif
            <div class="totals-row">
                <div class="totals-row-label">{{ $tax['label'] }} ({{ $tax['rate'] }}%)</div>
                <div class="totals-row-value">R {{ number_format((float) $invoice->tax_total, 2) }}</div>
            </div>
            @if($invoice->amount_paid > 0)
            <div class="totals-row">
                <div class="totals-row-label">Amount Paid</div>
                <div class="totals-row-value">- R {{ number_format((float) $invoice->amount_paid, 2) }}</div>
            </div>
            @endif
            <div class="totals-row total-final">
                <div class="totals-row-label">Total Due</div>
                <div class="totals-row-value">R {{ number_format((float) $invoice->amount_due, 2) }}</div>
            </div>
        </div>
    </div>

    {{-- BANK DETAILS --}}
    @if($invoice->organization->bankAccount && $invoice->organization->bankAccount->is_active)
    <div class="bank-section">
        <div class="section-title">Banking Details — Please use invoice number as reference</div>
        <div class="bank-details">
            <div class="bank-col">
                <div class="bank-col-label">Bank</div>
                <div class="bank-col-value">
                    {{ config('lookup.south_african_banks')[$invoice->organization->bankAccount->bank_name] ?? $invoice->organization->bankAccount->bank_name }}
                </div>
            </div>
            <div class="bank-col">
                <div class="bank-col-label">Account Holder</div>
                <div class="bank-col-value">{{ $invoice->organization->bankAccount->account_holder }}</div>
            </div>
            <div class="bank-col">
                <div class="bank-col-label">Account Number</div>
                <div class="bank-col-value">{{ $invoice->organization->bankAccount->account_number }}</div>
            </div>
            <div class="bank-col">
                <div class="bank-col-label">Branch Code</div>
                <div class="bank-col-value">{{ $invoice->organization->bankAccount->branch_code }}</div>
            </div>
            <div class="bank-col">
                <div class="bank-col-label">Account Type</div>
                <div class="bank-col-value">
                    {{ config('lookup.account_types')[$invoice->organization->bankAccount->account_type] ?? $invoice->organization->bankAccount->account_type }}
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- NOTES --}}
    @if($invoice->notes)
    <div class="notes-section">
        <div class="section-title">Notes</div>
        <p>{{ $invoice->notes }}</p>
    </div>
    @endif

    {{-- FOOTER --}}
    <div class="footer">
        {{ $prefs['invoice_footer'] }}
    </div>
    <div class="sars-notice">
        This is a tax invoice for VAT purposes.
    </div>

</body>
</html>