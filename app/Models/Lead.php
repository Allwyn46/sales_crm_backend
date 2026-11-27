<?php

namespace App\Models;

use App\Enums\LeadSource;
use App\Enums\LeadStatus;
use Database\Factories\LeadFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'assigned_to_id',
    'created_by_id',
    'name',
    'phone',
    'email',
    'company',
    'status',
    'source',
    'value',
    'notes',
    'last_called_at',
    'follow_up_at',
])]
class Lead extends Model
{
    use HasFactory, SoftDeletes;

    /** @use HasFactory<LeadFactory> */
    protected $casts = [
        'status' => LeadStatus::class,
        'source' => LeadSource::class,
        'value' => 'decimal:2',
        'last_called_at' => 'datetime',
        'follow_up_at' => 'datetime',
    ];

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }
}
