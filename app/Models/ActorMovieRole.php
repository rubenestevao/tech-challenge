<?php

namespace App\Models;

use App\Models\Traits\PrimaryAsUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActorMovieRole extends Model
{
    use PrimaryAsUuid;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'actor_id',
        'movie_id'
    ];

    public function actor(): BelongsTo
    {
        return $this->belongsTo(Actor::class);
    }

    public function movie(): BelongsTo
    {
        return $this->belongsTo(Movie::class);
    }
}
