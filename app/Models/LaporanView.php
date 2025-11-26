<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanView extends Model
{
    protected $table = 'view_laporan';
    public $timestamps = false;

    protected $fillable = [
        'id_aspirasi',
        'judul',
        'total_setuju',
        'total_tidak_setuju',
        'total_suara',
    ];
}
