<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;

class InvoiceController extends Controller
{
    public function show($orderId) {
        $order = Order::where('id', $orderId)->with('orderItems')->first();
        $orderItemsCount = $order->orderItems->count();

        try {


            $pdf = FacadePdf::loadView('invoice.order', compact('order', 'orderItemsCount'));

            $filename = 'invoice_' . $order->id . '.pdf';

            return $pdf->download($filename);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to download pdf: ' . $e->getMessage()]);
        }
    }


}
