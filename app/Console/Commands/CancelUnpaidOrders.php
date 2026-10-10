<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Penyewaan;
use App\Models\DetailPenyewaan;
use App\Models\DetailVariantProduk;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CancelUnpaidOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:cancel-unpaid';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Membatalkan pesanan yang belum dibayar/diunggah bukti bayar lebih dari 2 jam untuk mencegah deadlock stok.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Cari pesanan yang status_penyewaan nya masih Pending 
        // dan status_pembayarannya masih Belum lunas
        // dan sudah lewat dari 2 jam sejak dibuat
        
        $twoHoursAgo = Carbon::now()->subHours(2);
        
        $ordersToCancel = Penyewaan::join('pembayaran_penyewaan', 'penyewaan.id', '=', 'pembayaran_penyewaan.id_penyewaan')
            ->where('penyewaan.status_penyewaan', 'Pending')
            ->where('pembayaran_penyewaan.status_pembayaran', 'Belum lunas')
            ->where('penyewaan.created_at', '<', $twoHoursAgo)
            ->select('penyewaan.*')
            ->get();
            
        $count = 0;
        
        foreach ($ordersToCancel as $order) {
            try {
                // Update status pesanan
                $order->status_penyewaan = 'Dibatalkan';
                $order->save();
                
                // Kembalikan stok
                $details = DetailPenyewaan::where('id_penyewaan', $order->id)->get();
                foreach ($details as $detail) {
                    $variant = null;
                    if ($detail->id_detail_variant_produk) {
                        $variant = DetailVariantProduk::find($detail->id_detail_variant_produk);
                    }
                    if (!$variant) {
                        $variant = DetailVariantProduk::join('variant_produk', 'detail_variant_produk.id_variant_produk', '=', 'variant_produk.id')
                            ->where('variant_produk.id_produk', $detail->id_produk)
                            ->where('variant_produk.warna', $detail->warna_produk)
                            ->where('detail_variant_produk.ukuran', $detail->ukuran)
                            ->select('detail_variant_produk.*')
                            ->first();
                    }
                    if ($variant) {
                        $variant->stok += $detail->qty;
                        $variant->save();
                    }
                }
                
                $count++;
            } catch (\Exception $e) {
                Log::error("Gagal membatalkan otomatis order ID {$order->id}: " . $e->getMessage());
            }
        }
        
        $this->info("Berhasil membatalkan {$count} pesanan yang belum dibayar (expired > 2 jam).");
    }
}
