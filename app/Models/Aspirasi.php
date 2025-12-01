<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aspirasi extends Model
{
    // Nama tabel di database
    protected $table = 'aspirasis';

    // Primary key bukan "id" tapi "id_aspirasi"
    protected $primaryKey = 'id_aspirasi';

    // Kolom yang boleh diisi mass-assignment
    protected $fillable = [
        'id_user',
        'judul',
        'deskripsi',
        'status',
        'tanggal_post',
    ];

    // Relasi ke user pengaju aspirasi
    public function user()
    {
        // foreign key = id_user, owner key = id_user (primary key di tabel users Anda)
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    // LABEL STATUS DALAM BAHASA INDONESIA
    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            'submitted' => 'Dalam Proses',
            'approved'  => 'Disetujui',
            'rejected'  => 'Ditolak',
            default     => '-',
        };
    }
}
