<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    return $this->belongsToMany(Tag::class);
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
