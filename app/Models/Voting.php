<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voting extends Model
{
    public $timestamps = false; // karena kita hanya pakai created_at
    protected $primaryKey = 'id_vote';

    protected $fillable = [
        'id_aspirasi',
        'id_user',
        'nilai',
    ];

    public function aspirasi()
    {
        return $this->belongsTo(Aspirasi::class, 'id_aspirasi', 'id_aspirasi');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}
