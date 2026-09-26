<!DOCTYPE html>
<html>
<head>

    <meta charset="UTF-8">

    <title>Laporan Penjualan</title>

    <style>

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #222;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 20px;
        }

        .header p {
            margin: 5px 0;
            color: #666;
        }

        .summary {
            width: 100%;
            margin-bottom: 20px;
        }

        .summary td {
            width: 50%;
            padding: 12px;
            border: 1px solid #ddd;
        }

        .summary-title {
            font-size: 10px;
            color: #777;
        }

        .summary-value {
            font-size: 17px;
            font-weight: bold;
            margin-top: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #f3f4f6;
            font-weight: bold;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 7px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .footer {
            margin-top: 20px;
            text-align: right;
            color: #777;
        }

    </style>

</head>

<body>

<div class="header">

    <h1>LAPORAN PENJUALAN</h1>

    <p>
        Dicetak:
        {{ now()->translatedFormat('d F Y H:i') }}
    </p>

</div>

<table class="summary">

    <tr>

        <td>

            <div class="summary-title">
                TOTAL PESANAN
            </div>

            <div class="summary-value">
                {{ number_format($totalOrders, 0, ',', '.') }}
            </div>

        </td>

        <td>

            <div class="summary-title">
                TOTAL PENJUALAN
            </div>

            <div class="summary-value">
                Rp {{ number_format($totalSales, 0, ',', '.') }}
            </div>

        </td>

    </tr>

</table>

<table>

    <thead>

        <tr>

            <th width="5%">No</th>

            <th>No. Pesanan</th>

            <th>Tanggal</th>

            <th>Pelanggan</th>

            <th>Subtotal</th>

            <th>Ongkir</th>

            <th>Total</th>

            <th>Pembayaran</th>

        </tr>

    </thead>

    <tbody>

        @forelse($orders as $index => $order)

            <tr>

                <td class="text-center">
                    {{ $index + 1 }}
                </td>

                <td>
                    {{ $order->order_number }}
                </td>

                <td>
                    {{ $order->created_at?->format('d/m/Y H:i') }}
                </td>

                <td>
                    {{ $order->shipping_name }}
                </td>

                <td class="text-right">
                    Rp {{ number_format($order->subtotal, 0, ',', '.') }}
                </td>

                <td class="text-right">
                    Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}
                </td>

                <td class="text-right">
                    <strong>
                        Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                    </strong>
                </td>

                <td>
                    {{ $order->payment_type ?? '-' }}
                </td>

            </tr>

        @empty

            <tr>

                <td colspan="8" class="text-center">
                    Tidak ada data penjualan.
                </td>

            </tr>

        @endforelse

    </tbody>

</table>

<div class="footer">

    Dicetak oleh sistem

</div>

</body>
</html>