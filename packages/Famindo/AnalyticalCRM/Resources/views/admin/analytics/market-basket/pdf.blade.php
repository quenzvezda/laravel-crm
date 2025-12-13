<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="{{ app()->getLocale() }}" dir="ltr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Analisis Asosiasi Produk</title>
    <style>
        @page {
            margin: 20px 20px 40px 20px; /* Top, Right, Bottom, Left */
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 16px;
            text-transform: uppercase;
        }
        .meta-info {
            width: 100%;
            margin-bottom: 20px;
            font-size: 11px;
        }
        .meta-info td {
            padding: 2px 0;
            vertical-align: top;
        }
        .meta-label {
            font-weight: bold;
            width: 150px;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
        }
        table.data-table th, table.data-table td {
            border: 1px solid #ccc;
            padding: 5px;
            text-align: left;
        }
        table.data-table th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
        }
        table.data-table thead {
            display: table-header-group;
        }
        table.data-table tbody {
            display: table-row-group;
        }
        tr {
            page-break-inside: avoid;
        }
        .text-center { text-align: center !important; }
        .text-right { text-align: right !important; }
        
        .footer-signature {
            page-break-inside: avoid;
            margin-top: 50px;
            float: right;
            width: 200px;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Laporan Analisis Asosiasi Produk (Market Basket Analysis)</h1>
    </div>

    <table class="meta-info">
        <tr>
            <td class="meta-label">Nama Rekap</td>
            <td>: {{ $run->name ?? '-' }}</td>
            <td class="meta-label">Dibuat Pada</td>
            <td>: {{ $run->created_at ? $run->created_at->format('d M Y H:i') : '-' }}</td>
        </tr>
        <tr>
            <td class="meta-label">Periode Data</td>
            <td>: {{ $run->period_start ? $run->period_start->format('d M Y') : '?' }} s/d {{ $run->period_end ? $run->period_end->format('d M Y') : '?' }}</td>
            <td class="meta-label">Total Transaksi</td>
            <td>: {{ number_format($run->transactions_count) }}</td>
        </tr>
        <tr>
            <td class="meta-label">Parameter</td>
            <td colspan="3">
                : Support ≥ {{ number_format($run->support_threshold, 3) }}, 
                  Confidence ≥ {{ number_format($run->confidence_threshold, 3) }}, 
                  Min Items = {{ $run->min_items }}
            </td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th width="35%">LHS (Jika membeli...)</th>
                <th width="35%">RHS (Maka akan membeli...)</th>
                <th width="10%">Support</th>
                <th width="10%">Confidence</th>
                <th width="10%">Lift</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rules as $rule)
                @php
                    $lhs = json_decode($rule->lhs, true);
                    $rhs = json_decode($rule->rhs, true);
                    $lhsStr = is_array($lhs) ? implode(', ', $lhs) : $rule->lhs;
                    $rhsStr = is_array($rhs) ? implode(', ', $rhs) : $rule->rhs;
                @endphp
                <tr>
                    <td>{{ $lhsStr }}</td>
                    <td>{{ $rhsStr }}</td>
                    <td class="text-center">{{ number_format($rule->support, 4) }}</td>
                    <td class="text-center">{{ number_format($rule->confidence, 4) }}</td>
                    <td class="text-center">{{ number_format($rule->lift, 4) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Tidak ada rules yang ditemukan pada analisis ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer-signature">
        <p style="margin-bottom: 10px;">Mengetahui,</p>
        
        @if ($user->signature_image)
            <div style="height: 100px; display: flex; align-items: center; justify-content: center;">
                <img src="{{ public_path('storage/' . $user->signature_image) }}" alt="Signature" style="max-width: 150px; max-height: 100px;">
            </div>
        @else
            <div style="height: 100px;"></div>
        @endif

        <p style="margin-top: 10px; font-weight: bold; border-top: 1px solid #000; display: inline-block; min-width: 150px; padding-top: 5px;">
            {{ $user->signature_name ?? $user->name }}
        </p>
    </div>

</body>
</html>
