<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Nota {{ $order->order_number }}
    </title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>

        @media print {

            body {
                background: white !important;
            }

            .no-print {
                display: none !important;
            }

            .invoice {
                box-shadow: none !important;
                border: none !important;
            }

        }

    </style>

</head>


<body class="bg-gray-100">

<div class="max-w-3xl mx-auto px-4 py-8">


    <!-- BUTTON -->

    <div class="no-print flex gap-3 mb-5">

        <button
            onclick="window.print()"
            class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 rounded-xl"
        >
            🖨️ Cetak / Simpan PDF
        </button>

        <a
            href="{{ route('payment.success', ['order_id' => $order->order_number]) }}"
            class="px-6 py-3 bg-white border border-gray-300 rounded-xl font-semibold"
        >
            Kembali
        </a>

    </div>


    <!-- INVOICE -->

    <div class="invoice bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">


        <!-- HEADER -->

        <div class="p-8 border-b">

            <div class="flex justify-between items-start">

                <div>

                    <h1 class="text-2xl font-bold">
                        STORE.
                    </h1>

                    <p class="text-gray-500 mt-1">
                        Nota Pembelian
                    </p>

                </div>


                <div class="text-right">

                    <p class="font-bold">
                        {{ $order->order_number }}
                    </p>

                    <p class="text-sm text-gray-500">
                        {{ $order->created_at->format('d M Y H:i') }}
                    </p>

                </div>

            </div>

        </div>


        <!-- STATUS -->

        <div class="px-8 py-5 bg-gray-50 flex justify-between">

            <div>

                <p class="text-xs uppercase text-gray-500">
                    Status Pesanan
                </p>

                <p class="font-semibold">
                    {{ ucfirst($order->status) }}
                </p>

            </div>

            <div class="text-right">

                <p class="text-xs uppercase text-gray-500">
                    Pembayaran
                </p>

                <span class="font-semibold text-green-600">
                    {{ ucfirst($order->payment_status) }}
                </span>

            </div>

        </div>


        <!-- CUSTOMER -->

        <div class="p-8 border-b">

            <h2 class="font-bold mb-4">
                Informasi Pengiriman
            </h2>

            <div class="text-sm text-gray-700 space-y-1">

                <p>
                    <strong>
                        {{ $order->shipping_name }}
                    </strong>
                </p>

                <p>
                    {{ $order->shipping_phone }}
                </p>

                <p>
                    {{ $order->shipping_address }}
                </p>

            </div>

        </div>


        <!-- ITEMS -->

        <div class="p-8">

            <h2 class="font-bold mb-5">
                Detail Pesanan
            </h2>

            <div class="space-y-4">

                @foreach($order->items as $item)

                    <div class="flex justify-between gap-4">

                        <div>

                            <p class="font-medium">
                                {{ $item->product->name ?? 'Produk' }}
                            </p>

                            <p class="text-sm text-gray-500">

                                {{ $item->quantity }}
                                ×
                                Rp {{ number_format($item->price, 0, ',', '.') }}

                            </p>

                        </div>

                        <p class="font-semibold">

                            Rp
                            {{ number_format(
                                $item->price * $item->quantity,
                                0,
                                ',',
                                '.'
                            ) }}

                        </p>

                    </div>

                @endforeach

            </div>


            <div class="border-t mt-6 pt-5 space-y-3">

                <div class="flex justify-between">

                    <span class="text-gray-600">
                        Subtotal
                    </span>

                    <span>
                        Rp {{ number_format($order->subtotal, 0, ',', '.') }}
                    </span>

                </div>


                <div class="flex justify-between">

                    <span class="text-gray-600">
                        Pengiriman
                    </span>

                    <span>
                        Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}
                    </span>

                </div>


                <div class="flex justify-between text-xl font-bold pt-3 border-t">

                    <span>
                        Total
                    </span>

                    <span class="text-indigo-700">

                        Rp
                        {{ number_format(
                            $order->grand_total,
                            0,
                            ',',
                            '.'
                        ) }}

                    </span>

                </div>

            </div>

        </div>


        <!-- SHIPPING -->

        <div class="px-8 pb-8">

            <div class="rounded-xl bg-gray-50 p-5">

                <p class="font-semibold mb-2">
                    Metode Pengiriman
                </p>

                <p class="text-sm">
                    {{ strtoupper($order->shipping_courier) }}
                    -
                    {{ $order->shipping_service }}
                </p>

                <p class="text-sm text-gray-500 mt-1">
                    Berat:
                    {{ $order->shipping_weight }} gram
                </p>

            </div>

        </div>


        <!-- FOOTER -->

        <div class="px-8 py-6 border-t text-center text-sm text-gray-500">

            Terima kasih telah berbelanja di STORE.

            <br>

            Simpan nota ini sebagai bukti pembelian.

        </div>

    </div>

</div>

</body>

</html>