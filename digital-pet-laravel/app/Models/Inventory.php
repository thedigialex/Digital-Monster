<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'item_id', 'quantity', 'is_equipped'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * Scope a query to only include equipped items.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeEquipped($query)
    {
        return $query->where('is_equipped', true);
    }

    /**
     * Get only the equipped items for a specific user.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getEquippedItemsForUser($userId)
    {
        return self::where('user_id', $userId)->equipped()->get();
    }
}
