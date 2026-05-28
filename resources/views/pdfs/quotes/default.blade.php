<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'dejavusans', sans-serif;
            font-size: 12px;
            color: #1a1a2e;
            line-height: 2;
            background: #ffffff;
        }

        /* ─── PAGE WRAPPER ─── */
        .page-wrap {
            padding: 5px;
        }

        /* ─── HEADER ─── */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .header-table td {
            border: none;
            padding: 0;
            vertical-align: bottom;
        }

        .company-name {
            font-size: 22px;
            font-weight: bold;
            color: {{ $prefs['brand_color'] }};
            margin-bottom: 10px;
            letter-spacing: 0.5px;
        }

        .company-details {
            font-size: 10px;
            color: #888888;
            line-height: 2;
        }

        .quote-label {
            text-align: right;
        }

        .quote-title {
            font-size: 42px;
            font-weight: bold;
            color: {{ $prefs['brand_color'] }};
            text-transform: uppercase;
            letter-spacing: 6px;
            line-height: 2;
            margin-bottom: 10px;
        }

        .quote-number {
            font-size: 13px;
            color: #888888;
            margin-bottom: 10px;
            letter-spacing: 0.3px;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 14px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            background-color: {{ $prefs['brand_color'] }};
            color: #ffffff;
        }

        /* ─── DIVIDER ─── */
        .header-divider {
            width: 100%;
            border: none;
            border-top: 2.5px solid {{ $prefs['brand_color'] }};
            margin-bottom: 36px;
        }

        /* ─── META / BILL TO ─── */
        .meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 44px;
        }

        .meta-table td {
            border: none;
            padding: 0;
            vertical-align: top;
        }

        .section-label {
            font-size: 8.5px;
            text-transform: uppercase;
            letter-spacing: 1.8px;
            color: #bbbbbb;
            margin-bottom: 8px;
        }

        .customer-name {
            font-size: 15px;
            font-weight: bold;
            color: #1a1a2e;
            margin-bottom: 6px;
        }

        .customer-details {
            font-size: 10.5px;
            color: #777777;
            line-height: 2.9;
        }

        .meta-date-block {
            margin-bottom: 20px;
            text-align: right;
        }

        .meta-date-value {
            font-size: 13px;
            font-weight: bold;
            color: #1a1a2e;
        }

        /* ─── ITEMS TABLE ─── */
        table.items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
        }

        table.items thead tr {
            background-color: {{ $prefs['brand_color'] }};
        }

        table.items thead th {
            padding: 13px 14px;
            text-align: left;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: bold;
            color: #ffffff;
            border: none;
        }

        table.items thead th.align-right {
            text-align: right;
        }

        table.items tbody td {
            padding: 14px 14px;
            font-size: 11px;
            vertical-align: middle;
            border: none;
            border-bottom: 1px solid #efefef;
        }

        table.items tbody td.align-right {
            text-align: right;
        }

        table.items tbody tr.row-alt {
            background-color: #f8fafa;
        }

        .item-name {
            font-weight: bold;
            color: #1a1a2e;
            margin-bottom: 2px;
        }

        /* ─── TOTALS ─── */
        .totals-wrap {
            width: 100%;
            border-collapse: collapse;
            margin: 40px 0;
        }

        .totals-wrap td {
            border: none;
            padding: 0;
            vertical-align: top;
        }

        .totals-inner {
            width: 100%;
            border-collapse: collapse;
        }

        .totals-inner td {
            padding: 9px 0;
            font-size: 11px;
            border: none;
            border-bottom: 1px solid #efefef;
        }

        .totals-inner td.t-label {
            color: #888888;
        }

        .totals-inner td.t-value {
            text-align: right;
            color: #1a1a2e;
            font-weight: bold;
        }

        .totals-inner tr.t-grand td {
            border-bottom: none;
            border-top: 2px solid {{ $prefs['brand_color'] }};
            padding-top: 14px;
            padding-bottom: 4px;
            font-size: 14px;
            font-weight: bold;
        }

        .totals-inner tr.t-grand td.t-label {
            color: {{ $prefs['brand_color'] }};
        }

        .totals-inner tr.t-grand td.t-value {
            color: {{ $prefs['brand_color'] }};
        }

        /* ─── BANK DETAILS ─── */
        .bank-wrap {
            background-color: #f5f7f7;
            padding: 20px 22px;
            margin-bottom: 36px;
        }

        .bank-wrap .section-label {
            margin-bottom: 14px;
        }

        .bank-table {
            width: 100%;
            border-collapse: collapse;
        }

        .bank-table td {
            border: none;
            padding: 0 18px 0 0;
            vertical-align: top;
            font-size: 10.5px;
        }

        .bank-col-label {
            font-size: 8.5px;
            color: #aaaaaa;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 5px;
        }

        .bank-col-value {
            font-weight: bold;
            color: #1a1a2e;
            font-size: 11px;
        }

        /* ─── NOTES ─── */
        .notes-wrap {
            margin-bottom: 36px;
        }

        .notes-wrap p {
            font-size: 11px;
            color: #666666;
            margin-top: 8px;
            line-height: 2.8;
        }

        /* ─── FOOTER ─── */
        .footer {
            border-top: 1px solid #eeeeee;
            padding-top: 14px;
            text-align: center;
            font-size: 9px;
            color: #bbbbbb;
            letter-spacing: 0.3px;
        }

    </style>
</head>
<body>
<div class="page-wrap">

    {{-- ══════════════════════════════════
         HEADER
    ══════════════════════════════════ --}}
    <table class="header-table">
        <tr>
            <td style="width: 55%;">
                <div class="company-name">
                    {{ $quote->organization->name ?? $quote->organization->org_code }}
                </div>
                <div class="company-details">
                    @if($quote->organization->email){{ $quote->organization->email }}<br>@endif
                    @if($quote->organization->phone){{ $quote->organization->phone }}<br>@endif
                    @if($quote->organization->street_address)
                        {{ $quote->organization->street_address }},
                        {{ $quote->organization->suburb }},
                        {{ $quote->organization->city }},
                        {{ $quote->organization->province }}<br>
                    @endif
                    @if($quote->organization->tax_number)
                        {{ $tax['number_label'] }}: {{ $quote->organization->tax_number }}
                    @endif
                </div>
            </td>
            <td style="width: 45%;" class="quote-label">
                <div class="quote-title">Quote</div>
                <div class="quote-number">{{ $quote->quote_number }}</div>
                <div><span class="status-badge">{{ strtoupper($quote->status) }}</span></div>
            </td>
        </tr>
    </table>

    <hr class="header-divider">

    {{-- ══════════════════════════════════
         META / BILL TO
    ══════════════════════════════════ --}}
    <table class="meta-table">
        <tr>
            <td style="width: 55%;">
                <div class="section-label">Bill To</div>
                <div class="customer-name">
                    @if($quote->customer->company_name)
                        {{ $quote->customer->company_name }}
                    @else
                        {{ $quote->customer->first_name }} {{ $quote->customer->last_name }}
                    @endif
                </div>
                <div class="customer-details">
                    {{ $quote->customer->first_name }} {{ $quote->customer->last_name }}<br>
                    {{ $quote->customer->email }}<br>
                    @if($quote->customer->phone){{ $quote->customer->phone }}@endif
                </div>
            </td>
            <td style="width: 45%;">
                <div class="meta-date-block">
                    <div class="section-label">Issue Date</div>
                    <div class="meta-date-value">{{ \Carbon\Carbon::parse($quote->issue_date)->format('d M Y') }}</div>
                </div>
                <div class="meta-date-block">
                    <div class="section-label">Expiry Date</div>
                    <div class="meta-date-value">{{ \Carbon\Carbon::parse($quote->expires_at)->format('d M Y') }}</div>
                </div>
                @if($quote->title)
                <div class="meta-date-block">
                    <div class="section-label">Reference</div>
                    <div class="meta-date-value">{{ $quote->title }}</div>
                </div>
                @endif
            </td>
        </tr>
    </table>

    {{-- ══════════════════════════════════
         LINE ITEMS
    ══════════════════════════════════ --}}
    <table class="items">
        <thead>
            <tr>
                <th style="width: 38%;">Description</th>
                <th style="width: 9%;">Qty</th>
                <th style="width: 9%;">Unit</th>
                <th class="align-right" style="width: 16%;">Unit Price</th>
                <th class="align-right" style="width: 13%;">{{ $tax['label'] }}</th>
                <th class="align-right" style="width: 15%;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($quote->items as $i => $item)
            @php $rowClass = ($i % 2 === 1) ? 'row-alt' : ''; @endphp
            <tr class="{{ $rowClass }}">
                <td>
                    <div class="item-name">{{ $item->description }}</div>
                </td>
                <td>{{ number_format((float) $item->quantity, 2) }}</td>
                <td>{{ $item->unit ?? '—' }}</td>
                <td class="align-right">R {{ number_format((float) $item->unit_price, 2) }}</td>
                <td class="align-right">@php echo $item->is_taxable ? $item->tax_rate . '%' : 'Exempt'; @endphp</td>
                <td class="align-right">R {{ number_format((float) $item->line_total, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- ══════════════════════════════════
         TOTALS
    ══════════════════════════════════ --}}
    <table class="totals-wrap">
        <tr>
            <td style="width: 56%;"></td>
            <td style="width: 44%;">
                <table class="totals-inner">
                    <tr>
                        <td class="t-label">Subtotal</td>
                        <td class="t-value">R {{ number_format((float) $quote->subtotal, 2) }}</td>
                    </tr>
                    @if($quote->discount_amount > 0 || $quote->discount_percent > 0)
                    @php
                        $discountLabel = 'Discount';
                        if ($quote->discount_percent > 0) {
                            $discountLabel .= ' (' . $quote->discount_percent . '%)';
                        }
                    @endphp
                    <tr>
                        <td class="t-label">{{ $discountLabel }}</td>
                        <td class="t-value">- R {{ number_format((float) $quote->discount_amount, 2) }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td class="t-label">{{ $tax['label'] }} ({{ $tax['rate'] }}%)</td>
                        <td class="t-value">R {{ number_format((float) $quote->tax_total, 2) }}</td>
                    </tr>
                    <tr class="t-grand">
                        <td class="t-label">Total Due</td>
                        <td class="t-value">R {{ number_format((float) $quote->total, 2) }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    {{-- ══════════════════════════════════
         BANK DETAILS
    ══════════════════════════════════ --}}
    @if($quote->organization->bankAccount)
    <div class="bank-wrap">
        <div class="section-label">Banking Details</div>
        <table class="bank-table">
            <tr>
                <td>
                    <div class="bank-col-label">Bank</div>
                    <div class="bank-col-value">
                        {{ config('lookup.south_african_banks')[$quote->organization->bankAccount->bank_name] ?? $quote->organization->bankAccount->bank_name }}
                    </div>
                </td>
                <td>
                    <div class="bank-col-label">Account Holder</div>
                    <div class="bank-col-value">{{ $quote->organization->bankAccount->account_holder }}</div>
                </td>
                <td>
                    <div class="bank-col-label">Account Number</div>
                    <div class="bank-col-value">{{ $quote->organization->bankAccount->account_number }}</div>
                </td>
                <td>
                    <div class="bank-col-label">Branch Code</div>
                    <div class="bank-col-value">{{ $quote->organization->bankAccount->branch_code }}</div>
                </td>
                <td>
                    <div class="bank-col-label">Account Type</div>
                    <div class="bank-col-value">
                        {{ config('lookup.account_types')[$quote->organization->bankAccount->account_type] ?? $quote->organization->bankAccount->account_type }}
                    </div>
                </td>
            </tr>
        </table>
    </div>
    @endif

    {{-- ══════════════════════════════════
         NOTES
    ══════════════════════════════════ --}}
    @if($quote->notes)
    <div class="notes-wrap">
        <div class="section-label">Notes</div>
        <p>{{ $quote->notes }}</p>
    </div>
    @endif

    {{-- ══════════════════════════════════
         FOOTER
    ══════════════════════════════════ --}}
    <div class="footer">
        {{ $prefs['invoice_footer'] }}
    </div>

</div>
</body>
</html>