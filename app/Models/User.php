<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'position_id',
        'name',
        'email',
        'phone',
        'photo',
    ];

    public function scopeByEmailOrPhone(Builder $query, string $email, string $phone): Builder
    {
        return $query
            ->where('email', $email)
            ->orWhere('phone', $phone);
    }


    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }
}
