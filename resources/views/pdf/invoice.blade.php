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
            line-height: 1.6;
            color: #333;
            padding: 20px;
        }

        /* Typography */
        h2 {
            font-size: 24px;
            margin-bottom: 10px;
        }

        h3 {
            font-size: 18px;
            margin-bottom: 15px;
            color: #444;
        }

        p {
            margin-bottom: 8px;
        }

        /* Layout components */
        .invoice-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .info-table {
            width: 100%;
            margin-bottom: 40px;
            border-collapse: separate;
            border-spacing: 10px;
        }

        .info-cell {
            width: 50%;
            vertical-align: top;
            background-color: #f8f8f8;
            border-radius: 5px;
            padding: 20px;
        }

        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .invoice-table th {
            background-color: #f4f4f4;
            padding: 12px;
            text-align: {{ $isRTL ? 'right' : 'left' }};
            border-bottom: 2px solid #ddd;
            font-weight: bold;
        }

        .invoice-table td {
            padding: 12px;
            text-align: {{ $isRTL ? 'right' : 'left' }};
            border-bottom: 1px solid #ddd;
        }

        .invoice-table .item-name {
            width: 40%;
        }

        .invoice-table .amount {
            width: 15%;
        }

        .total-row td {
            font-weight: bold;
            border-top: 2px solid #ddd;
        }

        .subtotal-row td {
            border-bottom: none;
            padding: 8px 12px;
        }

        .empty-row td {
            border-bottom: none;
            padding: 12px;
        }

        .total-title {
            font-weight: bold;
            text-align: {{ $isRTL ? 'left' : 'right' }};
        }

        .total-row {
            font-weight: bold;
            text-align: {{ $isRTL ? 'right' : 'left' }};
            margin-bottom: 20px;
        }

        .total-row p {
            padding: 5px 0;
        }

        /* Notes section */
        .note-section {
            margin-top: 40px;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 5px;
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

        .mb-2 {
            margin-bottom: 20px;
        }

        .text-primary {
            color: #2c3e50;
        }
    </style>
</head>

<body>
    <div class="invoice-header">
        <h2 class="text-primary">{{ __('Invoice') }} #<span class="number">{{ $invoice['order_id'] }}</span></h2>
        <p>{{ __('Date') }}: <span class="number">{{ $date->format('Y-m-d') }}</span></p>
    </div>

    <table class="info-table">
        <tr>
            <td class="info-cell">
                <h3 class="text-primary">{{ __('Buyer Information') }}</h3>
                <p>{{ __('Name') }}: {{ $buyer['name'] }}</p>
                <p>{{ __('Phone') }}: <span class="number">{{ $buyer['phone'] }}</span></p>
            </td>
            <td class="info-cell">
                <h3 class="text-primary">{{ __('Seller Information') }}</h3>
                <p>{{ __('Name') }}: {{ $seller['name'] }}</p>
                <p>{{ __('Phone') }}: <span class="number">{{ $seller['phone'] }}</span></p>
            </td>
        </tr>
    </table>

    <table class="invoice-table">
        <thead>
            <tr>
                <th class="item-name">{{ __('Product') }}</th>
                <th class="amount">{{ __('Price') }}</th>
                <th class="amount">{{ __('Quantity') }}</th>
                <th class="amount">{{ __('Discount') }}</th>
                <th class="amount">{{ __('Subtotal') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $item)
                <tr>
                    <td>{{ $item['name'] }}</td>
                    <td><span class="number">{{ number_format($item['price'], 2, '.', ',') }}
                            {{ $currency }}</span></td>
                    <td><span class="number">{{ $item['quantity'] }}</span></td>
                    <td><span class="number">{{ number_format($item['discount'], 2, '.', ',') }}
                            {{ $currency }}</span></td>
                    <td><span class="number">{{ number_format($item['subtotal'], 2, '.', ',') }}
                            {{ $currency }}</span></td>
                </tr>
            @endforeach

            <!-- Empty row for spacing -->
            <tr class="empty-row">
                <td colspan="5"></td>
            </tr>

            <!-- Totals rows -->
            <tr class="subtotal-row">
                <td colspan="2"></td>
                <td colspan="2" class="total-title">{{ __('Purchase Total') }}:</td>
                <td><span class="number">{{ number_format($invoice['purchase_amount'], 2, '.', ',') }} {{ $currency }}</span>
                </td>
            </tr>
            <tr class="subtotal-row">
                <td colspan="2"></td>
                <td colspan="2" class="total-title">{{ __('Tax Total') }}:</td>
                <td><span class="number">{{ number_format($invoice['tax_amount'], 2, '.', ',') }} {{ $currency }}</span></td>
            </tr>
            <tr class="subtotal-row">
                <td colspan="2"></td>
                <td colspan="2" class="total-title">{{ __('Discount Total') }}:</td>
                <td><span class="number">{{ number_format($invoice['discount_amount'], 2, '.', ',') }} {{ $currency }}</span>
                </td>
            </tr>
            <tr class="total-row">
                <td colspan="2"></td>
                <td colspan="2" class="total-title">{{ __('Total Amount') }}:</td>
                <td><span class="number">{{ number_format($invoice['total_amount'], 2, '.', ',') }} {{ $currency }}</span>
                </td>
            </tr>
        </tbody>
    </table>

    @if ($note)
        <div class="note-section">
            <h3 class="text-primary mb-2">{{ __('Note') }}</h3>
            <p>{{ $note }}</p>
        </div>
    @endif
</body>

</html>
