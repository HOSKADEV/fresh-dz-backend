<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Mpdf\Mpdf;

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

        // Create the mPDF instance with RTL support if needed
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => [210, 297], // A4 size in mm
            'default_font_size' => 12,
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 30,
            'margin_bottom' => 30,
            'default_font' => $isRTL ? 'XBRiyaz' : 'arial',
            'direction' => $isRTL ? 'rtl' : 'ltr',
        ]);

        // Render the Blade template
        $html = View::make('pdf.invoice', [
            'seller' => $this->seller,
            'buyer' => $this->buyer,
            'items' => $this->items,
            'invoice' => $this->invoice,
            'note' => $this->note,
            'currency' => $this->currency,
            'date' => $this->date,
            'isRTL' => $isRTL,
            'locale' => $this->locale
        ])->render();

        //$mpdf->autoLangToFont = true;

        $mpdf->WriteHTML($html);

        $filename = $this->invoice['order_id'] . '.pdf';

        $filepath = 'uploads/invoices/'. $filename;

        Storage::disk('upload')->put($filepath, $mpdf->Output('', 'S'));

        return $filepath;
    }
}
