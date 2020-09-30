<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Pairing
 *
 * @property int $id
 * @property int $round_id
 * @property int $player_1_id
 * @property int $player_2_id
 * @property string $result
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Player $player1
 * @property-read \App\Models\Player $player2
 * @property-read \App\Models\Round $round
 * @method static \Illuminate\Database\Eloquent\Builder|Pairing newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Pairing newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Pairing query()
 * @method static \Illuminate\Database\Eloquent\Builder|Pairing whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pairing whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pairing wherePlayer1Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pairing wherePlayer2Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pairing whereResult($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pairing whereRoundId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pairing whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Pairing extends Model
{
  use HasFactory;

  protected $guarded = [];

  protected $keyType = 'string';

  public function player1()
  {
    return $this->belongsTo(Player::class, 'player_1_id');
  }

  public function player2()
  {
    return $this->belongsTo(Player::class, 'player_2_id');
  }

  public function round()
  {
    return $this->belongsTo(Round::class);
  }
}
