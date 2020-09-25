<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * App\Models\PlayerTag
 *
 * @property int $id
 * @property int $player_id
 * @property int $tag_id
 * @property bool $value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerTag newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerTag newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerTag query()
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerTag whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerTag whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerTag wherePlayerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerTag whereTagId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerTag whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerTag whereValue($value)
 * @mixin \Eloquent
 */
class PlayerTag extends Pivot
{
  protected $casts = [
    'value' => 'boolean'
  ];
}
