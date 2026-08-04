<?php
use App\Models\Produk;
use App\Models\VariantProduk;
use App\Models\DetailVariantProduk;
use App\Models\FotoProduk;

$produkCount  = Produk::where('id_user', 2)->count();
$produkIds    = Produk::where('id_user', 2)->pluck('id');
$variantIds   = VariantProduk::whereIn('id_produk', $produkIds)->pluck('id');
$variantCount = $variantIds->count();
$detailCount  = DetailVariantProduk::whereIn('id_variant_produk', $variantIds)->count();
$fotoCount    = FotoProduk::whereIn('id_produk', $produkIds)->count();

echo "Total Produk  : {$produkCount}\n";
echo "Total Varian  : {$variantCount}\n";
echo "Total Detail  : {$detailCount}\n";
echo "Total Foto    : {$fotoCount}\n\n";

// Sample 2 produk
$samples = Produk::where('id_user', 2)->take(2)->get();
foreach ($samples as $p) {
    echo "==========================\n";
    echo "Produk : {$p->nama}\n";
    echo "Kategori: {$p->kategori}\n";
    $varians = VariantProduk::where('id_produk', $p->id)->get();
    echo "Warna ({$varians->count()}):\n";
    foreach ($varians as $v) {
        $details = DetailVariantProduk::where('id_variant_produk', $v->id)->get();
        echo "  [{$v->warna}] ({$details->count()} ukuran):\n";
        foreach ($details as $d) {
            echo "    - {$d->ukuran} | Stok: {$d->stok} | Harga: Rp" . number_format($d->harga_sewa, 0, ',', '.') . "\n";
        }
    }
}
