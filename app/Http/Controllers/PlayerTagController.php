<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class PlayerTagController extends Controller
{
  /**
   * Toggle a tag value for a player.
   *
   * @param Player $player
   * @param string $tagName
   * @return void
   */
  public function update(Player $player, string $tagName)
  {
    $tag = Tag::where('name', $tagName)->first();
    $tagValues = $player->tagValues;
    $currentValue = $tagValues[$tagName];
    $defaultValue = $tag->default;

    // if we're currently on the default, we need to make sure there's a row in player_tag with the opposite val
    if ($currentValue === $defaultValue) {
      $player->tags()->syncWithoutDetaching([$tag->id => ['value' => !$currentValue]]);
    } else {
      // if we're currently not on default, delete row from player_tag
      $player->tags()->detach([$tag->id]);
    }

    return Redirect::route('players.index');
  }
}
