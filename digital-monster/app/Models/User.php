<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'bits',
        'role'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function userMonsters()
    {
        return $this->hasMany(UserMonster::class);
    }

    public function userItems()
    {
        return $this->hasMany(UserItem::class);
    }

    public function userEquipment()
    {
        return $this->hasMany(UserEquipment::class);
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function friendships()
    {
        return $this->hasMany(Friendship::class, 'requester_user_id');
    }

    public function reverseFriendships()
    {
        return $this->hasMany(Friendship::class, 'addressee_user_id');
    }

    protected function getUserIdsByStatus(string $status)
    {
        return $this->friendships()->where('status', $status)->pluck('addressee_user_id')
            ->merge($this->reverseFriendships()->where('status', $status)->pluck('requester_user_id'))
            ->unique();
    }

    public function friends()
    {
        $friendIds = $this->getUserIdsByStatus('accepted');
        return User::whereIn('id', $friendIds)->get();
    }

    public function requestedFriends()
    {
        return User::whereIn('id', function ($query) {
            $query->select('addressee_user_id')
                ->from('friendships')
                ->where('requester_user_id', $this->id)
                ->where('status', 'pending');
        })->get();
    }

    public function pendingFriendRequests()
    {
        return User::whereIn('id', function ($query) {
            $query->select('requester_user_id')
                ->from('friendships')
                ->where('addressee_user_id', $this->id)
                ->where('status', 'pending');
        })->get();
    }

    public function blockedUserIds()
    {
        return $this->getUserIdsByStatus('blocked');
    }

    public function locations()
    {
        return $this->hasMany(UserLocation::class);
    }
}
