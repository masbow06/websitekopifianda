<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Produk;

class HomeController extends Controller
{
    public function view($snapToken = null) {
        $menu = Menu::all();
        $produk = Produk::all();
        
        return view('home', [
            'menus' => $menu,
            "produks" => $produk,
            "snap_token" => $snapToken
        ]);
    }

    public function produkList() {
        return Produk::all();
    }
}
