<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    $id = \App\Models\User::where('email', 'user@gmail.com')->first()->id;
    $query = \App\Models\Produk::leftJoin('variant_produk', 'produk.id', '=', 'variant_produk.id_produk')
        ->leftJoin('detail_variant_produk', 'variant_produk.id', '=', 'detail_variant_produk.id_variant_produk')
        ->select('produk.id as id_produk', 'produk.id_user as id_user', 'produk.nama as nama_produk', 'produk.status as status_produk', 'produk.foto_depan as foto', \DB::raw('SUM(detail_variant_produk.stok) as stok_produk'))
        ->where('produk.id_user', $id)
        ->groupBy('produk.id', 'produk.id_user', 'produk.nama', 'produk.status', 'produk.foto_depan');
    
    $res = $query->paginate(50);
    var_dump(get_class($res));
} catch(\Exception $e) {
    var_dump('ERROR: ' . get_class($e) . ' - ' . $e->getMessage());
}
