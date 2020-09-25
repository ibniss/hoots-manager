<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Player
 *
 * @property int $id
 * @property string $name
 * @property string $decklist
 * @property int|null $deckcount
 * @property int $wins
 * @property int $draws
 * @property int $losses
 * @property int $points
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Support\Collection $tag_values
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Tag[] $tags
 * @property-read int|null $tags_count
 * @method static \Illuminate\Database\Eloquent\Builder|Player newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Player newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Player query()
 * @method static \Illuminate\Database\Eloquent\Builder|Player whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Player whereDeckcount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Player whereDecklist($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Player whereDraws($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Player whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Player whereLosses($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Player whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Player wherePoints($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Player whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Player whereWins($value)
 * @mixin \Eloquent
 */
class Player extends Model
{
  use HasFactory;

  /**
   * The attributes that are NOT mass assignable.
   *
   * @var array
   */
  protected $guarded = [];

  public function tags()
  {
    return $this->belongsToMany(Tag::class)->using(PlayerTag::class)->withPivot(['value']);
  }

  /**
   * Get values for extra tags for the player.
   *
   * @return \Illuminate\Support\Collection
   */
  public function getTagValuesAttribute()
  {
    $setTags = $this->tags->pluck('pivot.value', 'id');
    return Tag::all()->mapWithKeys(function ($tag) use ($setTags) {
      return [
        $tag->name => isset($setTags[$tag->id]) ? $setTags[$tag->id] : $tag->default
      ];
    });
  }
}
