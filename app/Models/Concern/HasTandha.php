<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Model
 *
 * @method static Builder|static byTandha(string $tandha)
 */
trait HasTandha
{
    /** @param  Builder<static>  $query */
    public function scopeByTandha(Builder $query, string $tandha): Builder
    {
        return $query->where('tandha', $tandha);
    }
}
