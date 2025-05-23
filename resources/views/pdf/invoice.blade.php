<!DOCTYPE html>
<html lang="{{ $locale ?? 'ar' }}" dir="{{ $isRTL ?? 'rtl' }}">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>{{ __('Invoice') }}</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
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
        right: 100;
        width: 210mm;
        height: 297mm;
        margin: 0;
        /* padding: 0; */
      }

      /* Optional: remove page margin to match exactly A4 */
      @page {
        size: A4;
        /* margin: 10px; */
        /* margin-bottom: 5px;
        margin-top: 0px; */
      }
    }
  </style>

</head>
<body class="bg-white text-black p-4">

  <div
  class="bg-white text-black mx-auto p-4 relative overflow-hidden"
  style="width:210mm; height:297mm; box-sizing:border-box;" id="invoice">
  <div class="border-4 border-black rounded-xl p-4 h-full">
    <!-- {/* Header */} -->
    <div class="flex justify-between items-center px-10">
      <!-- {/* QR code image */} -->
      {{-- <img src="qr.png" alt="QR" class="size-24 object-contain" /> --}}
      <div class="flex items-center gap-2">
        <!-- {/* header text */} -->
        <div>
          <h1 class="font-bold text-2xl">FRESH DZ</h1>
          <p class="text-xl">فـــراش ديــــــزاد</p>
          <p class="text-sm">
            بلدية الدورة الجزائر العاصمة | 0551.06.11.49
          </p>
        </div>
        <!-- {/* logo */} -->
        {{-- <img
          src="logo.png"
          alt="Logo"
          class="size-18 object-contain"
        /> --}}
      </div>
    </div>

    <!-- {/* table and side card */} -->

    <div class="flex bg-red-400s min-h-[calc(100%-400px)] mt-8">
      <!-- {/* side bar */} -->
      <div class="flex is flex-col gap-20 mt-8">
        <!-- {/* DESTINATAIRE info */} -->
        <div class="p-2 flex flex-col gap-3 transform rotate-90">
          <h1 class="bg-black text-white text-center font-bold px-2 py-2 rounded-md">
            DESTINATAIRE
          </h1>
          <div class="flex flex-col gap-2 text-end border-2 border-black p-2 rounded-md text-sm">
            <h1 class="">{{ $buyer['name'] ?? 'Nom du client' }}</h1>
            <h2 class="">{{ $buyer['address'] ?? 'Adresse du client' }}</h2>
            <h3 class="">{{ $buyer['phone'] ?? 'Téléphone du client' }}</h3>
          </div>
        </div>
        <!-- {/*  LIVREUR info*/} -->
        <div class="p-4 flex flex-col gap-3 transform rotate-90">
          <h1 class="bg-black text-white text-center font-bold px-2 py-2 rounded-md">
            LIVREUR
          </h1>
          <div class="flex flex-col gap-2 text-end border-2 border-black p-2 rounded-md text-sm min-h-[100px]">
            <h1 class="">{{ $driver['name'] ?? 'Nom du livreur' }}</h1>
            <h3 class="">{{ $driver['phone'] ?? 'Téléphone du livreur' }}</h3>
          </div>
        </div>
      </div>
      <!-- {/* table section */} -->
      <div class="flex-1 border border-black text-sm">
        <table class="w-full table-fixed border-collapse h-full">
          <!-- {/* Header */} -->
          <thead>
            <tr class="border border-black">
              <th class="border border-black px-2 py-1 text-left">
                RECOUVREMENT
              </th>
              <th
                class="border border-black px-2 py-1 text-left"
                colSpan="2"
              >
                DESCRIPTION DU CONTENU
              </th>
            </tr>
          </thead>
          <!-- {/* body */} -->
          <tbody class="text-left" dir="ltr">
            @foreach ($items as $item)
            <tr class="align-top">
              <td class="px-2 border-x border-black">
                {{ number_format($item['subtotal'], 2, '.', ',') }} {{ $currency ?? 'DA' }}
              </td>
              <td class="px-2 border-x border-black">{{ $item['quantity'] }} {{ $item['unit'] ?? 'kg' }}</td>
              <td class="font-bold px-2 border-x border-black">{{ $item['name'] }}</td>
            </tr>
            @endforeach

            <!-- {/* keep this to take the remaining space  */} -->
            <tr class="h-full">
              <td
                class="border border-black"
                colSpan="3"
                style='height: 100%'
              ></td>
            </tr>
            <!-- {/* keep this to take the remaining space  */} -->
          </tbody>
          <!-- {/* table footer */} -->
          <tfoot class="font-bold" dir="ltr">
            <tr class="text-start">
              <td class="border border-black px-2 py-1">{{ number_format($invoice['shipping_amount'] ?? 200.00, 2, '.', ',') }} {{ $currency ?? 'DA' }}</td>
              <td class="border border-black px-2 py-1" colSpan="2">
                FRAIS DE TRANSPORT
              </td>
            </tr>
            <tr class="bg-gray-100 text-start">
              <td class="border border-black px-2 py-1 text-black">
                {{ number_format($invoice['total_amount'] ?? 11.200, 2, '.', ',') }} {{ $currency ?? 'DA' }}
              </td>
              <td class="border border-black px-2 py-1" colSpan="2">
                TOTAL
              </td>
            </tr>
          </tfoot>
        </table>
      </div>
      <!-- {/* Table Section */} -->
    </div>
    <footer class="flex items-center justify-between gap-4 p-8">
      <!-- {/* cashé image  */} -->
      {{-- <img
        src="logo.png"
        alt="cashé image"
        class="size-32 object-contain"
      />
      <!-- {/* tank you image */} -->
      <img
        src="thank.png"
        alt="tank you image"
        class="size-28 object-contain"
      />

      <!-- {/* qr code image */} -->
      <img src="qr.png" alt="QR" class="size-32 object-contain" /> --}}
    </footer>
  </div>
</div>
</body>
</html>
