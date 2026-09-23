<?php
require __DIR__ . "/vendor/autoload.php";
$app = require_once __DIR__ . "/bootstrap/app.php";
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$produk = App\Models\Produk::with("foto")
    ->select("produk.id as id_produk", "produk.nama")
    ->first();
if ($produk) {
    echo "Without id: " . count($produk->foto) . "\n";
}

$produk2 = App\Models\Produk::with("foto")
    ->select("produk.id", "produk.id as id_produk", "produk.nama")
    ->first();
if ($produk2) {
    echo "With id: " . count($produk2->foto) . "\n";
}
