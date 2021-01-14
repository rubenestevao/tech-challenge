<?php

namespace App\Models;

use App\Models\Traits\PrimaryAsUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Actor extends Model
{
    use PrimaryAsUuid;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'bio',
        'born_at'
    ];

    protected $casts = [
        'roles_count' => 'integer',
    ];

    public function roles(): HasMany
    {
        return $this->hasMany(ActorMovieRole::class);
    }
}
