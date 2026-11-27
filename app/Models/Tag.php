<?php

namespace App\Models;

use Database\Factories\TagFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'color'])]
class Tag extends Model
{
    /** @use HasFactory<TagFactory> */
    use HasFactory;

    public function leads(): BelongsToMany
    {
        return $this->belongsToMany(Lead::class);
    }
}
