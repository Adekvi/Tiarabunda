<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Model
 *
 * @method static Builder|static ordered()
 */
trait HasUrutan
{
    /** @param  Builder<static>  $query */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('urutan')->orderBy('id');
    }
}
