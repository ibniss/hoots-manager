<?php

namespace App\Http\Controllers;

use App\Models\Formula;
use App\Models\Tag;
use Inertia\Inertia;
use App\Models\Player;
use App\Services\Math;
use Illuminate\Http\Request;
use App\Services\PlayerUpload;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Database\Eloquent\Collection;

class PlayerController extends Controller
{
  private PlayerUpload $playerUpload;
  private Math $math;

  public function __construct(PlayerUpload $upload, Math $math)
  {
    $this->playerUpload = $upload;
    $this->math = $math;
  }

  /**
   * Display players.
   *
   * @return \Inertia\Response
   */
  public function index()
  {
    $formulas = Formula::all();

    return Inertia::render('Players/Index', [
      'columns' => ['name', 'wins', 'draws', 'losses', 'points'],
      'tags' => function () {
        return Tag::all();
      },
      'formulas' => function () {
        return Formula::all();
      },
      'players' => fn () => with(Player::all(), function (Collection $players) use ($formulas) {
        $players->each->append('tag_values');
        $players->transform(function (Player $player) use ($formulas) {
          $variables = collect($player->attributesToArray())->filter(function ($val, $attr) {
            return !in_array($attr, ['created_at', 'updated_at', 'id', 'tag_values', 'name', 'decklist']);
          })->merge($player->tag_values)->toArray();

          foreach ($formulas as $formula) {
            $player->{$formula->name} = $this->math->evaluate($formula->equation, $variables);
          }
          return $player;
        });

        return $players;
      })
    ]);
  }

  /**
   * Store players from a CSV upload.
   *
   * @param Request $request
   * @return void
   */
  public function store(Request $request)
  {
    $file = $request->file('data');
    $this->playerUpload->parseCsv($file);
    return Redirect::route('players.index');
  }
}
