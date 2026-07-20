<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alamat extends Model
{
    use HasFactory;

    protected $table = 'alamat';

    protected $fillable = [
        'id_user',
        'longitude',
        'latitude',
        'detail_lainnya',
        'type',
        'kecamatan',
        'kota_kabupaten',
        'provinsi',
        'kode_pos',
    ];

    public function getTypeAttribute($value)
    {
        switch ($value) {
            case 0:
                return 'Rumah';
            case 1:
                return 'Toko';
            case 2:
                return 'Kantor';
            default:
                return 'Unknown';
        }
    }
}
