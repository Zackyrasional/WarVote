<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PollOption extends Model
{
    // Kalau tidak pakai HasFactory, tidak apa-apa
    protected $table = 'poll_options';

    protected $fillable = [
        'poll_id',
        'option_text',
    ];

    public function poll()
    {
        return $this->belongsTo(Poll::class, 'poll_id');
    }

    public function votes()
    {
        return $this->hasMany(PollVote::class, 'option_id');
    }
}
