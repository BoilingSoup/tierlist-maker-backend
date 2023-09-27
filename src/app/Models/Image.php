<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Image extends Model
{
    use HasFactory, HasUuids;

    const TABLE = 'images';

    const FOREIGN_KEY = 'image_id';

    public function users(): BelongsToMany
    {
      $pivotTableName = Image::TABLE.'_'.User::TABLE;

      return $this->belongsToMany(User::class, $pivotTableName, Image::FOREIGN_KEY, User::FOREIGN_KEY);
    }
}
