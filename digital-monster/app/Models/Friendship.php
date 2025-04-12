<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Friendship extends Model
{
    protected $fillable = [
        'requester_user_id',
        'addressee_user_id',
        'status',
    ];

    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_user_id');
    }

    public function addressee()
    {
        return $this->belongsTo(User::class, 'addressee_user_id');
    }
}
