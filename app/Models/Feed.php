<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Feed extends Model
{
    /** @use HasFactory<\Database\Factories\FeedFactory> */
    use HasFactory;

    protected $fillable = [
        'uuid',
        'name',
        'last_accessed_at'
    ];

    public function events(): HasMany
    {
        return $this->hasMany(Event::class)->orderBy('start_at');
    }

    protected function exportUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => route('feeds.export', $this),
        );
    }
}
