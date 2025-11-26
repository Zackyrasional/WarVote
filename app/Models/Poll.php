<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
    protected $table = 'polls';

    protected $fillable = [
        'title',
        'allow_multiple',
        'deadline',
        'created_by',
        'status',
        'is_closed',
    ];

    protected $casts = [
        'allow_multiple' => 'boolean',
        'deadline'       => 'datetime',
        'is_closed'      => 'boolean',
    ];

    public function options()
    {
        return $this->hasMany(PollOption::class);
    }

    public function votes()
    {
        return $this->hasMany(PollVote::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id_user');
    }
}
