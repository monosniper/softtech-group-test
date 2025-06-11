<?php

namespace App\Models;

use App\Observers\MediaObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[ObservedBy(MediaObserver::class)]
class Media extends Model
{
    protected $fillable = [
        'client_id',
        'name',
        'file_unique_id',
        'type',
        'size_bytes',
        'name',
        'extension',
        'mime',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    protected function fileName(): Attribute
    {
        return Attribute::make(
            get: fn () => "$this->name.$this->extension",
        );
    }

    protected function path(): Attribute
    {
        return Attribute::make(
            get: fn () => "{$this->client->client_code}/$this->type/$this->name.$this->extension",
        );
    }

    #[Scope]
    protected function byIdOrUuid(Builder $query, string $idOrUuid): void
    {
        $query->where('id', $idOrUuid)
            ->orWhere('file_unique_id', $idOrUuid);
    }
}
