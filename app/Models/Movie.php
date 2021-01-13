<?php

namespace App\Models;

use App\Models\Traits\PrimaryAsUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Movie extends Model
{
    use PrimaryAsUuid;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'year',
        'synopsis',
        'runtime',
        'released_at',
        'cost',
        'genre_id',
    ];

    protected $casts = [
        'runtime' => 'integer',
        'cost' => 'integer'
    ];

    public function genre(): BelongsTo
    {
        return $this->belongsTo(Genre::class);
    }
}
