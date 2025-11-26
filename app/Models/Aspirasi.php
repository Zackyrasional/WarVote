<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aspirasi extends Model
{
    protected $primaryKey = 'id_aspirasi';

    protected $fillable = [
        'id_user',
        'judul',
        'deskripsi',
        'status',
        'tanggal_post',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function votings()
    {
        return $this->hasMany(Voting::class, 'id_aspirasi', 'id_aspirasi');
    }
}
