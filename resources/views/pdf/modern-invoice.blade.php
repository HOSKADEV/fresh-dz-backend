<!DOCTYPE html>
<html lang="{{ $locale ?? 'ar' }}" dir="{{ $isRTL ?? 'rtl' }}">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>{{ __('Invoice') }}</title>
  <style>

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    :root {
      --primary-color: #69B24C;
      --secondary-color: #F89E2F;
      --light-primary: #e8f5e4;
      --light-secondary: #fff4e6;
      --dark-text: #333333;
      --light-text: #ffffff;
      --border-radius: 8px;
    }

    body {
      background-color: #f5f5f5;
      color: var(--dark-text);
      padding: 1rem;
    }

    #invoice {
      background-color: white;
      color: var(--dark-text);
      margin: 0 auto;
      padding: 1rem;
      position: relative;
      overflow: hidden;
      width: 210mm;
      height: 297mm;
      box-sizing: border-box;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .border-container {
      border: 2px solid var(--primary-color);
      border-radius: var(--border-radius);
      padding: 1.5rem;
      height: 100%;
      position: relative;
    }

    .border-container::before {
      content: '';
      position: absolute;
      top: 0;
      right: 0;
      width: 150px;
      height: 150px;
      background-color: var(--secondary-color);
      opacity: 0.1;
      clip-path: polygon(100% 0, 0 0, 100% 100%);
      z-index: 0;
    }

    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 0 1rem 1.5rem 1rem;
      border-bottom: 2px solid var(--light-primary);
      position: relative;
      z-index: 1;
    }

    .header-text {
      text-align: right;
    }

    .header-text h1 {
      font-weight: bold;
      font-size: 1.8rem;
      color: var(--primary-color);
      margin-bottom: 0.2rem;
    }

    .header-text p:first-of-type {
      font-size: 1.25rem;
      margin-bottom: 0.2rem;
    }

    .header-text p:last-of-type {
      font-size: 0.875rem;
      color: #666;
    }

    .main-content {
      display: flex;
      min-height: calc(100% - 400px);
      margin-top: 2rem;
      position: relative;
      z-index: 1;
    }

    .sidebar {
      width: 30%;
      padding-right: 0.75rem;
      position: relative;
    }

    .sidebar-section {
      margin-bottom: 1rem;
      transform: rotate(270deg);
      transform-origin: left top;
      position: absolute;
      width: 250px;
    }

    .sidebar-section.destinataire {
      top: 300px;
      left: 20px;
    }

    .sidebar-section.livreur {
      top: 600px;
      left: 20px;
    }

    .sidebar-title {
      background-color: var(--primary-color);
      color: white;
      text-align: center;
      font-weight: bold;
      padding: 0.4rem;
      border-radius: var(--border-radius) var(--border-radius) 0 0;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
      font-size: 0.8rem;
    }

    .sidebar-content {
      display: flex;
      flex-direction: column;
      gap: 0.3rem;
      text-align: right;
      border: 1px solid #ddd;
      border-top: none;
      padding: 0.6rem;
      border-radius: 0 0 var(--border-radius) var(--border-radius);
      font-size: 0.75rem;
      background-color: white;
      min-height: 80px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .table-container {
      width: 70%;
      border: 2px solid var(--secondary-color);
      font-size: 0.875rem;
      border-radius: var(--border-radius);
      overflow: hidden;
      box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    table {
      width: 100%;
      table-layout: fixed;
      border-collapse: collapse;
      height: 100%;
    }

    thead th {
      background-color: var(--secondary-color);
      color: white;
      padding: 0.75rem 0.5rem;
      text-align: left;
      font-weight: 600;
      border: none;
    }

    tbody td {
      padding: 0.5rem;
      border-bottom: 1px solid var(--light-secondary);
      border-right: 1px solid var(--light-secondary);
    }

    tbody tr:nth-child(even) {
      background-color: var(--light-primary);
    }

    tbody tr.align-top {
      vertical-align: top;
    }

    tbody tr.spacer {
      height: 100%;
      background-color: white;
    }

    tbody tr.spacer td {
      border-bottom: none;
    }

    tfoot {
      font-weight: bold;
    }

    tfoot tr td {
      padding: 0.75rem 0.5rem;
      border: none;
    }

    tfoot tr:first-child {
      background-color: #f5f5f5;
    }

    tfoot tr:first-child td:first-child {
      color: var(--secondary-color);
    }

    tfoot tr:last-child {
      background-color: var(--secondary-color);
    }

    tfoot tr:last-child td {
      color: white;
    }

    .footer {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 1rem;
      padding: 1.5rem;
      margin-top: 1.5rem;
      border-top: 2px solid var(--light-primary);
      position: relative;
      z-index: 1;
    }

    .footer-text {
      text-align: center;
      color: var(--primary-color);
      font-weight: 500;
    }

    .footer-logo {
      width: 6rem;
      height: 6rem;
      display: flex;
      align-items: center;
      justify-content: center;
      background-color: var(--light-primary);
      border-radius: 50%;
      padding: 1rem;
    }

    @media print {
      body * {
        visibility: hidden;
      }
      #invoice, #invoice * {
        visibility: visible;
      }
      #invoice {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        width: 210mm;
        height: 297mm;
        margin: 0;
        box-shadow: none;
      }

      @page {
        size: A4;
      }
    }
  </style>
</head>
<body>
  <div id="invoice">
    <div class="border-container">

      <div class="header">
        <img src="{{ public_path('assets/img/qr_code.svg') }}" alt="QR Code" style="height: 120px; width: auto;">
        <img src="{{ public_path('assets/img/Layer_x0020_2.svg') }}" alt="Fresh DZ Logo" style="height: 100px; width: auto;">
      </div>

      <div class="main-content">

        <div class="sidebar">

          <div class="sidebar-section destinataire">
            <h1 class="sidebar-title">DESTINATAIRE</h1>
            <div class="sidebar-content">
              <h1 class="text-sm">{{ $buyer['name'] ?? 'Nom du client' }}</h1>
              <h2 class="text-sm">{{ $buyer['address'] ?? 'Adresse du client' }}</h2>
              <h3 class="text-sm">{{ $buyer['phone'] ?? 'Téléphone du client' }}</h3>
            </div>
          </div>

          <div class="sidebar-section livreur">
            <h1 class="sidebar-title">LIVREUR</h1>
            <div class="sidebar-content">
              <h1 class="text-sm">{{ $driver['name'] ?? 'Nom du livreur' }}</h1>
              <h3 class="text-sm">{{ $driver['phone'] ?? 'Téléphone du livreur' }}</h3>
            </div>
          </div>
        </div>

        <div class="table-container">
          <table>

            <thead>
              <tr>
                <th style="width: 25%;">RECOUVREMENT</th>
                <th style="width: 25%;">QUANTITÉ</th>
                <th style="width: 50%;">DESCRIPTION DU CONTENU</th>
              </tr>
            </thead>

            <tbody dir="ltr">
              @foreach ($items as $item)
              <tr class="align-top">
                <td>{{ number_format($item['subtotal'], 2, '.', ',') }} {{ $currency ?? 'DA' }}</td>
                <td>{{ $item['quantity'] }} {{ $item['unit'] ?? 'kg' }}</td>
                <td style="font-weight: 600;">{{ $item['name'] }}</td>
              </tr>
              @endforeach

              <tr class="spacer">
                <td colspan="3"></td>
              </tr>
            </tbody>

            <tfoot dir="ltr">
              <tr>
                <td>{{ number_format($invoice['shipping_amount'] ?? 200.00, 2, '.', ',') }} {{ $currency ?? 'DA' }}</td>
                <td colspan="2">FRAIS DE TRANSPORT</td>
              </tr>
              <tr>
                <td>{{ number_format($invoice['total_amount'] ?? 11.200, 2, '.', ',') }} {{ $currency ?? 'DA' }}</td>
                <td colspan="2">TOTAL</td>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>

      <div class="footer">
        <div class="footer-logo">FD</div>
        <div class="footer-text">Merci pour votre confiance!</div>
        <div class="footer-logo" style="background-color: var(--light-secondary); color: var(--secondary-color);">QR</div>
      </div>
    </div>
  </div>
</body>
</html>
