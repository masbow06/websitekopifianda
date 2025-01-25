<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Transaksi;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        // Menghitung total pemasukan
        $totalbayar = Transaksi::where('paymentstat', 'paid')->sum('totalbayar');

        // Contoh data statis atau Anda bisa mengganti dengan query dinamis sesuai kebutuhan
        $totalItemTerjual = Transaksi::sum('jumlah');
        $totalBerhasil = Transaksi::where('paymentstat', 'paid')->count();
        $totalPending = Transaksi::where('paymentstat', 'pending')->count();
        $totalGagal = Transaksi::whereNotIn('paymentstat', ['paid','pending'])->count();
        $produkTerjual = Transaksi::distinct('produk_id')->count('produk_id');

        return [
            Stat::make('Total Item Terjual', number_format($totalItemTerjual, 0, ',', '.')),
            Stat::make('Total Berhasil', number_format($totalBerhasil, 0, ',', '.')),
            Stat::make('Total Pending', number_format($totalPending, 0, ',', '.')),
            Stat::make('Total Gagal', number_format($totalGagal, 0, ',', '.')),
            Stat::make('Produk Terjual', number_format($produkTerjual, 0, ',', '.')),
            Stat::make('Total Pemasukan', 'Rp ' . number_format($totalbayar, 0, ',', '.')),
        ];
    }
}
