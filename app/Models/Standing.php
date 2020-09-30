<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Standing
 *
 * @property int $id
 * @property int $player_id
 * @property int|null $rank
 * @property int|null $points
 * @property int|null $match_wins
 * @property int|null $match_losses
 * @property int|null $match_draws
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Player $player
 * @method static \Illuminate\Database\Eloquent\Builder|Standing newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Standing newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Standing query()
 * @method static \Illuminate\Database\Eloquent\Builder|Standing whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Standing whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Standing whereMatchDraws($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Standing whereMatchLosses($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Standing whereMatchWins($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Standing wherePlayerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Standing wherePoints($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Standing whereRank($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Standing whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Standing extends Model
{
  use HasFactory;

  protected $guarded = [];

  public function player()
  {
    return $this->belongsTo(Player::class);
  }
}
