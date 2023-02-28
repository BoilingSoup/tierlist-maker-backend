<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TierList extends Model
{
    use HasFactory;

    const FOREIGN_KEY = 'tier_list_id';

    protected $fillable = [
        'title',
        'data',
        'user_id',
        'description'
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, User::FOREIGN_KEY);
    }
}
