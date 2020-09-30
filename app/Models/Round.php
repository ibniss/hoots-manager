<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Round
 *
 * @property int $id
 * @property string $name
 * @property int $sort_order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Pairing[] $pairings
 * @property-read int|null $pairings_count
 * @method static \Illuminate\Database\Eloquent\Builder|Round newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Round newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Round query()
 * @method static \Illuminate\Database\Eloquent\Builder|Round whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Round whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Round whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Round whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Round whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Round extends Model
{
  use HasFactory;

  protected $guarded = [];

  public function pairings()
  {
    return $this->hasMany(Pairing::class);
  }
}
