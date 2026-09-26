<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Pembayaran Berhasil</title>

    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-gray-100 min-h-screen">

<div class="max-w-3xl mx-auto px-4 py-10">

    <!-- SUCCESS -->

    <div class="bg-white rounded-3xl shadow-lg overflow-hidden">

        <div class="text-center px-6 py-10">

            <!-- ICON -->

            <div class="mx-auto mb-5 w-20 h-20 rounded-full bg-green-100 flex items-center justify-center">

                <svg
                    class="w-10 h-10 text-green-600"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M5 13l4 4L19 7"
                    />

                </svg>

            </div>

            <h1 class="text-3xl font-bold text-gray-900">
                Pembayaran Berhasil!
            </h1>

            <p class="mt-2 text-gray-500">
                Terima kasih, pembayaran pesanan Anda telah diterima.
            </p>

        </div>


        <!-- ORDER -->

        <div class="border-t px-6 py-6">

            <div class="flex justify-between items-center">

                <div>

                    <p class="text-sm text-gray-500">
                        Nomor Pesanan
                    </p>

                    <p class="font-bold text-lg">
                        {{ $order->order_number }}
                    </p>

                </div>

                <div class="text-right">

                    <p class="text-sm text-gray-500">
                        Status Pembayaran
                    </p>

                    <span class="inline-flex mt-1 px-3 py-1 rounded-full bg-green-100 text-green-700 text-sm font-semibold">
                        {{ ucfirst($order->payment_status) }}
                    </span>

                </div>

            </div>

        </div>


        <!-- TOTAL -->

        <div class="bg-gray-50 px-6 py-6">

            <div class="flex justify-between mb-3">

                <span class="text-gray-600">
                    Subtotal
                </span>

                <span class="font-medium">
                    Rp {{ number_format($order->subtotal, 0, ',', '.') }}
                </span>

            </div>

            <div class="flex justify-between mb-3">

                <span class="text-gray-600">
                    Ongkos Kirim
                </span>

                <span class="font-medium">
                    Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}
                </span>

            </div>

            <div class="border-t pt-4 flex justify-between">

                <span class="font-bold text-lg">
                    Total Pembayaran
                </span>

                <span class="font-bold text-xl text-indigo-700">
                    Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                </span>

            </div>

        </div>


        <!-- BUTTON -->

        <div class="px-6 py-6 space-y-3">

            <a
                href="{{ route('payment.invoice', ['order_id' => $order->order_number]) }}"
                class="block w-full text-center bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 rounded-xl transition"
            >
                Lihat Nota Pesanan
            </a>


            <a
                href="{{ route('payment.invoice', ['order_id' => $order->order_number]) }}"
                class="block w-full text-center border border-gray-300 hover:bg-gray-50 text-gray-700 font-semibold py-3 rounded-xl transition"
            >
                Cetak Nota
            </a>


            @php

                $adminWhatsapp =
                    env(
                        'ADMIN_WHATSAPP',
                        '628xxxxxxxxxx'
                    );

                $waMessage =
                    "Halo Admin, saya ingin mengonfirmasi pesanan."
                    . "\n\n"
                    . "No. Pesanan: "
                    . $order->order_number
                    . "\n"
                    . "Total: Rp "
                    . number_format(
                        $order->grand_total,
                        0,
                        ',',
                        '.'
                    );

                $waUrl =
                    'https://wa.me/'
                    . $adminWhatsapp
                    . '?text='
                    . urlencode($waMessage);

            @endphp


            <a
                href="{{ $waUrl }}"
                target="_blank"
                class="flex items-center justify-center gap-2 w-full bg-green-500 hover:bg-green-600 text-white font-semibold py-3 rounded-xl transition"
            >

                <svg
                    class="w-5 h-5"
                    fill="currentColor"
                    viewBox="0 0 24 24"
                >

                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.372-.025-.521-.075-.149-.669-1.611-.916-2.206-.242-.579-.487-.5-.67-.51-.173-.008-.372-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.847 1.213 3.045.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.626.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>

                    <path d="M20.52 3.449A11.815 11.815 0 0012.04 0C5.48 0 .14 5.34.14 11.9c0 2.096.547 4.142 1.587 5.946L.057 24l6.307-1.65a11.88 11.88 0 005.676 1.447h.005c6.56 0 11.9-5.34 11.9-11.9a11.8 11.8 0 00-3.425-8.448z"/>

                </svg>

                Chat Admin via WhatsApp

            </a>

        </div>

    </div>

</div>

</body>

</html>