<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class SalesReportController extends Controller
{
    public function pdf(Request $request)
    {
        $orders = Order::query()
            ->where('payment_status', 'paid')
            ->orderBy('created_at', 'desc')
            ->get();

        $totalSales = $orders->sum('grand_total');

        $totalOrders = $orders->count();

        return Pdf::loadView(
            'reports.sales',
            [
                'orders' => $orders,
                'totalSales' => $totalSales,
                'totalOrders' => $totalOrders,
            ]
        )
        ->setPaper('a4', 'landscape')
        ->download(
            'laporan-penjualan-' .
            now()->format('Y-m-d-His') .
            '.pdf'
        );
    }
}