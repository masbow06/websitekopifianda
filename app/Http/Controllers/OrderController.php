<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use Midtrans\Notification;
use Midtrans\CoreApi;
use App\Models\Transaksi;
use App\Models\Produk;
use App\Models\Menu;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    protected $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    public function create(OrderRequest $request) {

        $paymentParam = $this->createPaymentParam($request);
        $tokenSnap = $this->midtransService->createSnapToken($paymentParam);
        
        $listOfProduk = [];
        foreach ($paymentParam['item_details'] as $item) {
            $trx = new Transaksi();
            $trx->trx_code = $paymentParam['transaction_details']['order_id'];
            $trx->produk_id = $item['id'];
            $trx->tanggalpemesanan = now();
            $trx->codemidtrans = $tokenSnap;
            $trx->jumlah = $item['quantity'];
            $trx->totalbayar = $item['total'];
            $trx->paymentstat = 'pending';
            $trx->save();
        }
        Transaksi::insert($listOfProduk);

        $menu = Menu::all();
        $produk = Produk::all();
        return view('home', [
            'menus' => $menu,
            "produks" => $produk,
            "snap_token" => $tokenSnap
        ]);
    }

    public function notificationHandler(Request $request) {

        $this->midtransService;

        $notif = new Notification();
        $orderId = $notif->order_id;
        $transactionStatus = $notif->transaction_status;
        $fraudStatus = $notif->fraud_status;

        $transactions = Transaksi::where('trx_code', $orderId)->get();
        if ($transactions->isEmpty()) {
            return response(['message' => "Transaction $orderId not found"], 404);
        }
        Log::info(json_encode($notif));
        Log::info(json_encode($transactions));


        $status = $this->getTrxStatus($transactionStatus, $fraudStatus);
        foreach ($transactions as $trx) {
            $trx->paymentstat = $status;
            $trx->save();
        }
        return response(['message' => 'Notification received'], 200);
    }

    private function createPaymentParam(OrderRequest $request) {
        $items = json_decode($request->items);
        $itemMap = collect($items)->pluck('quantity', 'id')->toArray();
        
        $itemIds = collect($items)->map(function($item) {return $item->id;})->toArray();
        $products = Produk::whereIn('id', $itemIds)->get()
            ->collect()
            ->map(function($product) use ($itemMap) {
                return [
                    'id' => $product->id,
                    'price' => $product->harga,
                    'quantity' => $itemMap[$product->id],
                    'name' => $product->namaproduk,
                    'total' => $product->harga * $itemMap[$product->id]
                ];
        });

        $currentTimeMilis = floor(microtime(true) * 1000);
        $transaction_detail = [
            'order_id' => "TRX-$currentTimeMilis",
            'gross_amount' => $products->sum('total')
        ];
        $customer_detail = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address
        ];

        return [
            'transaction_details' => $transaction_detail,
            'item_details' => $products,
            'customer_details' => $customer_detail
        ];
    }

    private function getTrxStatus($transactionStatus, $fraudStatus) {
        if ($transactionStatus == 'capture') {
            if ($fraudStatus == 'challenge') {
                return 'challenged';
            } else {
                return 'paid';
            }
        } elseif ($transactionStatus == 'settlement') {
            return 'paid';
        } elseif ($transactionStatus == 'pending') {
            return 'pending';
        } elseif ($transactionStatus == 'deny') {
            return 'failed';
        } elseif ($transactionStatus == 'expire') {
            return 'expired';
        } elseif ($transactionStatus == 'cancel') {
            return 'canceled';
        }
        return 'unknown';
    }
}
