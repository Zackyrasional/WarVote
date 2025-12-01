<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
    protected $table = 'polls';

    // primary key default "id", jadi tidak perlu diubah
    protected $fillable = [
        'title',
        'allow_multiple',
        'deadline',
        'created_by',
        'status',
        'is_closed',
    ];

    // Relasi ke pembuat polling (admin)
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id_user');
    }

    // Relasi ke opsi polling
    public function options()
    {
        return $this->hasMany(PollOption::class, 'poll_id');
    }

    // Relasi ke vote
    public function votes()
    {
        return $this->hasMany(PollVote::class, 'poll_id');
    }

    // LABEL STATUS DALAM BAHASA INDONESIA
    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            'pending'  => 'Menunggu Persetujuan',
            'approved' => 'Aktif',
            'rejected' => 'Ditolak',
            default    => '-',
        };
    }

    // LABEL KONDISI TUTUP / TERBUKA
    public function getClosedLabelAttribute()
    {
        return $this->is_closed ? 'Ditutup' : 'Terbuka';
    }
}
