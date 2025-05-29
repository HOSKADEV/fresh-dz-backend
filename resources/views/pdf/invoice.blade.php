{{-- resources/views/invoices/template.blade.php --}}
<!DOCTYPE html>
<html lang="{{ $locale }}" dir="{{ $isRTL ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <title>{{ __('Invoice') }}</title>
    <style>
        /* Reset and base styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: {{ $isRTL ? 'XBRiyaz' : 'Arial' }}, sans-serif;
            direction: {{ $isRTL ? 'rtl' : 'ltr' }};
            line-height: 1.4;
            color: #000;
            padding: 10px;
            background: #fff;
            max-width: 800px;
            margin: 0 auto;
        }

        /* Main container with border */
        .invoice-container {
            border: 3px solid #000;
            border-radius: 20px;
            padding: 15px;
            background: #fff;
        }

        /* Header section */
        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 20px;
        }

        .company-info {
            flex: 1;
        }

        .company-logo {
            font-size: 28px;
            font-weight: bold;
            color: #000;
            margin-bottom: 5px;
        }

        .company-details {
            font-size: 12px;
            color: #666;
        }

        .qr-section {
            width: 80px;
            height: 80px;
            border: 2px solid #000;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            text-align: center;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            border: 2px solid #000;
        }

        .info-table th {
            background-color: #000;
            color: #fff;
            padding: 10px 8px;
            text-align: center;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            border-right: 1px solid #fff;
            width: 50%;
        }

        .info-table td {
            padding: 10px 8px;
            text-align: center;
            border-bottom: 1px solid #000;
            border-right: 1px solid #000;
            font-size: 12px;
            width: 50%;
        }

        .info-table h3 {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
            text-transform: uppercase;
            background: #000;
            color: #fff;
            padding: 5px 10px;
            margin: -15px -15px 10px -15px;
        }

        .info-table p {
            font-size: 12px;
            margin-bottom: 5px;
        }

        /* Main table */
        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            border: 2px solid #000;
        }

        .invoice-table th {
            background-color: #000;
            color: #fff;
            padding: 10px 8px;
            text-align: center;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            border-right: 1px solid #fff;
        }

        .invoice-table th:last-child {
            border-right: none;
        }

        .invoice-table td {
            padding: 10px 8px;
            text-align: center;
            border-bottom: 1px solid #000;
            border-right: 1px solid #000;
            font-size: 12px;
        }

        .invoice-table td:last-child {
            border-right: none;
        }

        .invoice-table .item-name {
            text-align: {{ $isRTL ? 'right' : 'left' }};
            font-weight: 500;
            width: 50%;
        }

        /* Totals section */
        .totals-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 25px;
        }

        .shipping-section {
            flex: 1;
            padding-right: 20px;
        }

        .shipping-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #ddd;
            font-size: 12px;
        }

        .shipping-row:last-child {
            border-bottom: 2px solid #000;
            font-weight: bold;
        }

        .totals-box {
            width: 200px;
            border: 2px solid #000;
            padding: 15px;
            background: #f8f8f8;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            font-size: 12px;
            border-bottom: 1px solid #ddd;
        }

        .total-row:last-child {
            border-bottom: none;
            font-weight: bold;
            font-size: 14px;
            border-top: 2px solid #000;
            padding-top: 10px;
            margin-top: 5px;
        }

        .total-row .label {
            font-weight: bold;
            text-transform: uppercase;
        }

        /* Footer */
        .invoice-footer {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #000;
        }

        .footer-table{
            width: 100%;
        }

        .footer-table td {
            width: 30%;
            text-align: center;
        }

        .thank-you {
            font-size: 24px;
            font-style: italic;
            font-weight: bold;
        }

        .company-stamp {
            width: 80px;
            height: 80px;
            border: 3px solid #ff0000;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fff;
        }

        .stamp-content {
            text-align: center;
            font-size: 10px;
            font-weight: bold;
        }

        /* Notes section */
        .note-section {
            margin: 20px 0;
            padding: 15px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .note-section h3 {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        /* Utility classes */
        .number {
            direction: ltr;
            display: inline-block;
            font-family: Arial, sans-serif;
        }

        .text-bold {
            font-weight: bold;
        }

        .text-center {
            text-align: center;
        }
        .text-upper {
          text-transform: uppercase;
        }
    </style>
</head>

<body>
    <div class="invoice-container">
        <!-- Header -->
        <header class="invoice-header">
            @include('pdf.partials.logo-header')
        </header>

        <!-- Customer Information -->
        <table class="info-table">
            <thead>
                <tr>
                    <th>
                        <h3>Destinataire</h3>
                    </th>
                    <th>
                        <h3>Livreur</h3>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <h3>{{ $buyer['name'] }}</h3>
                        <h3><span class="number">{{ $buyer['phone'] }}</span></h3>
                    </td>
                    <td>
                        <h3>{{ $driver['name'] ?? '' }}</h3>
                        <h3><span class="number">{{ $driver['phone'] ?? '' }}</span></h3>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Products Table -->
        <table class="invoice-table">
            <thead>
                <tr>
                    <th class="text-bold text-upper">Description</th>
                    <th class="text-bold text-upper">Quantité</th>
                    <th class="text-bold text-upper">Recouvrement</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $item)
                    <tr>
                        <td class="item-name text-bold">{{ $item['name'] }}</td>
                        <td><span class="number">{{ $item['quantity'] }} ({{ $item['unit'] }})</span></td>
                        <td><span class="number">{{ number_format($item['subtotal'], 2, '.', ',') }}
                                {{ $currency }}</span></td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2" class="text-bold text-upper">Frais de Transport</td>
                    <td class="text-bold"><span class="number">{{ number_format($invoice['tax_amount'], 2, '.', ',') }}
                            {{ $currency }}</span></td>
                </tr>
                <tr>
                    <td colspan="2" class="text-bold text-upper">Total</td>
                    <td class="text-bold"><span
                            class="number">{{ number_format($invoice['total_amount'], 2, '.', ',') }}
                            {{ $currency }}</span></td>
                </tr>
            </tfoot>
        </table>

        {{-- @if ($note)
            <div class="note-section">
                <h3>{{ __('Note') }}</h3>
                <p>{{ $note }}</p>
            </div>
        @endif --}}

        <!-- Footer -->
        <footer class="invoice-footer">
            <table class="footer-table">
              <tr>
                <td><h3>G.P.S</h3>{!! $qr_code  !!}</td>
                <td>@include('pdf.partials.thank-you')</td>
                <td>@include('pdf.partials.stamp')</td>
              </tr>
            </table>
        </footer>
    </div>
</body>

</html>
