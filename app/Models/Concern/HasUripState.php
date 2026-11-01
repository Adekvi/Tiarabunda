<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Model
 *
 * @method static Builder|static active()
 * @method static Builder|static inactive()
 */
trait HasUripState
{
    /** @param  Builder<static>  $query */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('urip', true);
    }

    /** @param  Builder<static>  $query */
    public function scopeInactive(Builder $query): Builder
    {
        return $query->where('urip', false);
    }

    public function toggleUrip(): bool
    {
        /** @var Model $this */
        return (bool) $this->update([
            'urip' => ! (bool) $this->getAttribute('urip'),
        ]);
    }
}
