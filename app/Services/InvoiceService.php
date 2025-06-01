<?php

namespace App\Services;

use Mpdf\Mpdf;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class InvoiceService
{
    private $seller;
    private $buyer;
    private $driver;
    private $items;
    private $invoice;
    private $order;
    private $currency;
    private $locale;

    public function __construct(
        array $seller,
        array $buyer,
        array $driver,
        array $items,
        array $invoice,
        array $order
    ) {
        $this->seller = $seller;
        $this->buyer = $buyer;
        $this->driver = $driver;
        $this->items = $items;
        $this->invoice = $invoice;
        $this->order = $order;
        $this->currency = 'DA';
        $this->locale = session('locale');
    }

    public function generatePdf()
    {
        //$isRTL = $this->locale === 'ar';
        $isRTL = false;
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

        $qr_code = QrCode::size(150)
        ->merge(public_path('assets/img/icons/brands/google-maps.png'),.3 , true)
        ->style("round")
        ->generate($this->order['location']);

        // Removing the xml tag
        $qr_code = substr($qr_code, 39);

        // Render the Blade template
        $html = View::make('pdf.invoice', [
            'seller' => $this->seller,
            'buyer' => $this->buyer,
            'driver' => $this->driver,
            'items' => $this->items,
            'invoice' => $this->invoice,
            'order' => $this->order,
            'qr_code' => $qr_code,
            'currency' => $this->currency,
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
