<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Decklist
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Player $player
 * @method static \Illuminate\Database\Eloquent\Builder|Decklist newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Decklist newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Decklist query()
 * @method static \Illuminate\Database\Eloquent\Builder|Decklist whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Decklist whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Decklist whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Decklist extends Model
{
  use HasFactory;

  public function player()
  {
    return $this->belongsTo(Player::class);
  }
}
