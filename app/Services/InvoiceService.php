<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Spatie\LaravelPdf\Facades\Pdf;

class InvoiceService
{
  private $seller;
  private $buyer;
  private $driver;
  private $items;
  private $invoice;
  private $note;
  private $currency;
  private $date;
  private $locale;

  public function __construct(
    array $seller,
    array $buyer,
    array $driver,
    array $items,
    array $invoice,
    \DateTimeInterface $date,
    ?string $note = null,
  ) {
    $this->seller = $seller;
    $this->buyer = $buyer;
    $this->driver = $driver;
    $this->items = $items;
    $this->invoice = $invoice;
    $this->date = $date;
    $this->note = $note;
    $this->currency = __('Dzd');
    $this->locale = session('locale');
  }

  public function generatePdf()
  {
    $isRTL = $this->locale === 'ar';

    // Prepare data for the view
    $data = [
      'seller' => $this->seller,
      'buyer' => $this->buyer,
      'driver' => $this->driver,
      'items' => $this->items,
      'invoice' => $this->invoice,
      'note' => $this->note,
      'currency' => $this->currency,
      'date' => $this->date,
      'isRTL' => $isRTL,
      'locale' => $this->locale
    ];

    // Generate filename
    $filename = $this->invoice['order_id'] . '.pdf';
    $filepath = 'uploads/invoices/' . $filename;

    // Generate PDF using Spatie's Laravel-PDF
    Pdf::view('pdf.modern-invoice', $data)
        ->format('a4')
        ->save($filepath);

    return $filepath;
  }
}
