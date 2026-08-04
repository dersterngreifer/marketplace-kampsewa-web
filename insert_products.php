<?php

use App\Models\Produk;
use App\Models\VariantProduk;
use App\Models\DetailVariantProduk;
use App\Models\FotoProduk;
use Illuminate\Support\Facades\DB;

// -------------------------------------------------------
// CONFIG
// -------------------------------------------------------
$userId = 2; // user@gmail.com

// Pool warna – dipilih acak 3-4 warna per produk
$colorPool = [
    ['id' => 'Merah',       'css' => 'red'],
    ['id' => 'Biru',        'css' => 'blue'],
    ['id' => 'Hijau',       'css' => 'green'],
    ['id' => 'Hitam',       'css' => 'black'],
    ['id' => 'Putih',       'css' => 'white'],
    ['id' => 'Abu-abu',     'css' => 'gray'],
    ['id' => 'Kuning',      'css' => 'yellow'],
    ['id' => 'Ungu',        'css' => 'purple'],
    ['id' => 'Jingga',      'css' => 'orange'],
    ['id' => 'Navy',        'css' => 'navy'],
    ['id' => 'Tosca',       'css' => 'teal'],
    ['id' => 'Coklat',      'css' => 'saddlebrown'],
    ['id' => 'Marun',       'css' => 'maroon'],
    ['id' => 'Pink',        'css' => 'deeppink'],
];

// Ukuran per kategori produk
$sizesByCategory = [
    'tenda'   => ['1 Orang', '2 Orang', '3 Orang', '4 Orang', '6 Orang'],
    'kantong' => ['S', 'M', 'L', 'XL'],
    'matras'  => ['Single', 'Double', 'King'],
    'ransel'  => ['20L', '30L', '40L', '50L', '60L'],
    'botol'   => ['350ml', '500ml', '750ml', '1L'],
    'sepatu'  => ['38', '39', '40', '41', '42', '43', '44'],
    'baju'    => ['S', 'M', 'L', 'XL', 'XXL'],
    'default' => ['S', 'M', 'L', 'XL'],
];

// Kategori produk berdasarkan keyword di nama produk
$categoryRules = [
    'tenda'     => 'Tenda',
    'matras'    => 'Matras',
    'kantong tidur' => 'Kantong Tidur',
    'sleeping'  => 'Kantong Tidur',
    'ransel'    => 'Ransel & Tas',
    'tas'       => 'Ransel & Tas',
    'backpack'  => 'Ransel & Tas',
    'botol'     => 'Perlengkapan Minum',
    'flask'     => 'Perlengkapan Minum',
    'termos'    => 'Perlengkapan Minum',
    'gelas'     => 'Perlengkapan Minum',
    'kantong air' => 'Perlengkapan Minum',
    'sepatu'    => 'Alas Kaki',
    'sandal'    => 'Alas Kaki',
    'jaket'     => 'Pakaian',
    'celana'    => 'Pakaian',
    'baju'      => 'Pakaian',
    'kaos'      => 'Pakaian',
    'bantal'    => 'Aksesori Kemah',
    'lampu'     => 'Aksesori Kemah',
    'kompas'    => 'Aksesori Kemah',
    'senter'    => 'Aksesori Kemah',
    'tiang'     => 'Aksesori Kemah',
    'trekking'  => 'Aksesori Kemah',
    'headlamp'  => 'Aksesori Kemah',
];

function detectCategory(string $name): string {
    global $categoryRules;
    $lower = mb_strtolower($name);
    foreach ($categoryRules as $keyword => $cat) {
        if (str_contains($lower, $keyword)) return $cat;
    }
    return 'Peralatan Kemah';
}

function getSizeKey(string $category): string {
    return match($category) {
        'Tenda'              => 'tenda',
        'Matras'             => 'matras',
        'Kantong Tidur'      => 'kantong',
        'Ransel & Tas'       => 'ransel',
        'Perlengkapan Minum' => 'botol',
        'Alas Kaki'          => 'sepatu',
        'Pakaian'            => 'baju',
        default              => 'default',
    };
}

function parsePrice(string $priceStr): int {
    $clean = preg_replace('/[^0-9]/', '', $priceStr);
    return $clean ? (int)$clean : 150000;
}

// Harga sewa realistis: antara 5%-15% dari harga asli, tergantung ukuran
function rentalPrice(int $basePrice, int $sizeIndex, int $colorIndex): int {
    // Base rental: 8% dari harga produk
    $pct   = 0.08 + ($sizeIndex * 0.015) + ($colorIndex * 0.005);
    $price = (int)round($basePrice * $pct);
    // Bulatkan ke ribuan terdekat
    return (int)(ceil($price / 1000) * 1000);
}

// -------------------------------------------------------
// 1. HAPUS SEMUA DATA LAMA
// -------------------------------------------------------
echo "Menghapus data produk lama untuk user ID {$userId}...\n";

$oldProducts = Produk::where('id_user', $userId)->get();
$deletedCount = 0;

foreach ($oldProducts as $p) {
    FotoProduk::where('id_produk', $p->id)->delete();
    $variants = VariantProduk::where('id_produk', $p->id)->get();
    foreach ($variants as $v) {
        DetailVariantProduk::where('id_variant_produk', $v->id)->delete();
        $v->delete();
    }
    $p->delete();
    $deletedCount++;
}

echo "Berhasil menghapus {$deletedCount} produk lama.\n\n";

// -------------------------------------------------------
// 2. BACA JSON
// -------------------------------------------------------
$jsonPath  = public_path('products.json');
$jsonRaw   = file_get_contents($jsonPath);
$products  = json_decode($jsonRaw, true);

echo "Total produk dari JSON: " . count($products) . "\n\n";

// -------------------------------------------------------
// 3. INSERT PRODUK BARU
// -------------------------------------------------------
$insertedCount = 0;

foreach ($products as $idx => $prod) {
    $name        = $prod['name'];
    $description = $prod['description'] ?? '';
    $basePrice   = parsePrice($prod['price'] ?? '150000');
    $category    = detectCategory($name);
    $sizeKey     = getSizeKey($category);
    $sizes       = $sizesByCategory[$sizeKey];

    // Pilih 3-4 warna secara deterministik (bukan random, agar konsisten setiap run)
    $colorCount  = ($idx % 2 === 0) ? 4 : 3;
    $offset      = ($idx * 3) % count($colorPool);
    $selectedColors = [];
    for ($c = 0; $c < $colorCount; $c++) {
        $selectedColors[] = $colorPool[($offset + $c) % count($colorPool)];
    }

    // Pilih 3-4 ukuran
    $sizeCount    = min(count($sizes), ($idx % 2 === 0) ? 4 : 3);
    $selectedSizes = array_slice($sizes, 0, $sizeCount);

    // Foto: ambil semua dari detail_photos (max 8)
    $detailPhotos = array_slice($prod['detail_photos'] ?? [], 0, 8);
    $listingPhoto = $prod['listing_photo'] ?? ($detailPhotos[0] ?? '');

    // Buat produk
    $newProd = Produk::create([
        'id_user'       => $userId,
        'nama'          => $name,
        'deskripsi'     => mb_substr($description, 0, 1000),
        'status'        => 'Tersedia',
        'kategori'      => $category,
        'foto_depan'    => $listingPhoto,
        'foto_belakang' => '',
        'foto_kiri'     => '',
        'foto_kanan'    => '',
    ]);

    // Insert foto
    foreach ($detailPhotos as $fotoIdx => $photoUrl) {
        if (empty($photoUrl)) continue;
        FotoProduk::create([
            'id_produk'   => $newProd->id,
            'url_foto'    => $photoUrl,
            'tipe_sumber' => 'external',
            'urutan'      => $fotoIdx + 1,
        ]);
    }

    // Insert varian warna
    foreach ($selectedColors as $colorIdx => $color) {
        $variant = VariantProduk::create([
            'id_produk' => $newProd->id,
            'warna'     => $color['id'],
        ]);

        // Insert detail varian ukuran
        foreach ($selectedSizes as $sizeIdx => $ukuran) {
            // Stok berbeda-beda: antara 3-30, lebih besar untuk ukuran tengah
            $stok = match($sizeIdx) {
                0       => rand(3, 8),
                1       => rand(8, 20),
                2       => rand(8, 20),
                default => rand(3, 10),
            };

            // Harga per warna+ukuran berbeda
            $harga = rentalPrice($basePrice, $sizeIdx, $colorIdx);

            DetailVariantProduk::create([
                'id_variant_produk' => $variant->id,
                'ukuran'            => $ukuran,
                'stok'              => $stok,
                'harga_sewa'        => $harga,
            ]);
        }
    }

    $insertedCount++;

    if ($insertedCount % 10 === 0) {
        echo "Sudah insert {$insertedCount} produk...\n";
    }
}

echo "\n✅ Selesai! Total produk berhasil diinsert: {$insertedCount}\n";
echo "   Setiap produk punya " . count($selectedColors) . " varian warna (terakhir)\n";
echo "   Setiap warna punya " . count($selectedSizes) . " varian ukuran (terakhir)\n";
