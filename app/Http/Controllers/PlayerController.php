<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Inertia\Inertia;
use App\Models\Player;
use App\Services\Math;
use App\Models\Formula;
use App\Models\PlayerTag;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Database\Eloquent\Collection;

class PlayerController extends Controller
{
  private Math $math;

  public function __construct(Math $math)
  {
    $this->math = $math;
  }

  /**
   * Display players.
   *
   * @return \Inertia\Response
   */
  public function index()
  {
    $formulas = Formula::orderBy('order')->get();
    $players = Player::with('standing')->get();
    $deckCounts = $players->groupBy('decklist_name')->map->count();

    return Inertia::render('Players/Index', [
      'columns' => ['full_name', 'decklist_name', 'decklist_count', 'match_wins', 'match_draws', 'match_losses', 'rank'],
      'tags' => function () {
        return Tag::all();
      },
      'formulas' => function () {
        return Formula::all();
      },
      'players' => fn () => with($players, function (Collection $players) use ($formulas, $deckCounts) {
        $players->each->append('tag_values')->each->append('full_name');
        $players->transform(function (Player $player) use ($formulas, $deckCounts) {
          // Append deckcount at runtime
          $player->decklist_count = $deckCounts[$player->decklist_name];

          // Merge standings data onto the attributes - make sure to preserve ID as it repeats
          $playerId = $player->id;
          $player->setRawAttributes(array_merge(
            $player->getAttributes(),
            $player->standing ? $player->standing->getAttributes() : []
          ));
          $player->id = $playerId;

          // Calculate formulas sequentially
          $variables = collect($player->attributesToArray())
            ->merge($player->tag_values)
            ->merge($player->standing)->toArray();

          foreach ($formulas as $formula) {
            $result = $this->math->evaluate($formula->equation, $variables);
            $player->{$formula->name} = $this->math->evaluate($formula->equation, $variables);
            $variables[$formula->name] = $result;
          }
          $player->makeHidden('standing');

          return $player;
        });

        return $players;
      })
    ]);
  }
}
