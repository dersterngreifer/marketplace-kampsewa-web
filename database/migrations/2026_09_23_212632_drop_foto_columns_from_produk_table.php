<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Pindahkan data yang sudah ada ke tabel foto_produk
        $produks = \Illuminate\Support\Facades\DB::table('produk')->get();
        foreach ($produks as $produk) {
            $fotos = [
                1 => $produk->foto_depan ?? null,
                2 => $produk->foto_belakang ?? null,
                3 => $produk->foto_kiri ?? null,
                4 => $produk->foto_kanan ?? null,
            ];
            
            foreach ($fotos as $urutan => $urlFoto) {
                if (!empty($urlFoto) && $urlFoto !== 'Belum di isi') {
                    $exists = \Illuminate\Support\Facades\DB::table('foto_produk')
                        ->where('id_produk', $produk->id)
                        ->where('url_foto', $urlFoto)
                        ->exists();
                        
                    if (!$exists) {
                        $tipe = preg_match('/^https?:\/\//', $urlFoto) ? 'external' : 'internal';
                        \Illuminate\Support\Facades\DB::table('foto_produk')->insert([
                            'id_produk' => $produk->id,
                            'url_foto' => $urlFoto,
                            'tipe_sumber' => $tipe,
                            'urutan' => $urutan,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                    }
                }
            }
        }

        // 2. Hapus kolom setelah data aman dipindahkan
        Schema::table('produk', function (Blueprint $table) {
            $table->dropColumn(['foto_depan', 'foto_belakang', 'foto_kiri', 'foto_kanan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produk', function (Blueprint $table) {
            $table->string('foto_depan', 255)->nullable();
            $table->string('foto_belakang', 255)->nullable();
            $table->string('foto_kiri', 255)->nullable();
            $table->string('foto_kanan', 255)->nullable();
        });
    }
};
