<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    protected $fillable = [
        'client_code',
        'size_bytes',
    ];

    public function media(): HasMany
    {
        return $this->hasMany(Media::class);
    }

    #[Scope]
    protected function byCode(Builder $query, string $client_code): void
    {
        $query->where('client_code', $client_code);
    }
}
