<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use Midtrans\Config;
use Midtrans\Snap;
use App\Models\Transaksi;
use App\Models\Produk;
use App\Models\Menu;
use App\Services\MidtransService;

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

    private function createPayment(array $param) {
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = false;
        Config::$isSanitized = true;
        Config::$is3ds = true;
        return Snap::getSnapToken($param);
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
}
