<?php

namespace App\Http\Controllers;

use App\Models\Formula;
use Inertia\Inertia;
use App\Models\Round;
use App\Models\Player;
use App\Models\Pairing;
use App\Models\Setting;
use App\Services\Math;
use App\Services\MeleeAPI;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class PairingController extends Controller
{
  private MeleeAPI $api;
  private Math $math;

  public function __construct(MeleeAPI $meleeAPI, Math $math)
  {
    $this->api = $meleeAPI;
    $this->math = $math;
  }


  /**
   * View the pairings index page.
   *
   * @param Request $request
   * @return \Inertia\Response
   */
  public function index(Request $request)
  {
    $rounds = Round::all();
    $roundId = $request->query(
      'round',
      $rounds->sortByDesc('sort_order')->first()->id ?? null
    );

    // cast it to int if exists
    $roundId = $roundId ? (int) $roundId : null;

    $formulas = Formula::orderBy('order')->get();

    return Inertia::render('Pairings/Index', [
      'columns' => ['select', 'copy', 'player1.player_name', 'player1.decklist_name', 'player2.player_name', 'player2.decklist_name', 'result'],
      'rounds' => fn () => $rounds,
      'formulas' => fn () => $formulas,
      'pairings' => function () use ($roundId, $formulas) {
        if (!isset($roundId)) {
          return [];
        }

        $pairings = Pairing::with('player1', 'player2')->whereRoundId($roundId)->get();

        // if we have no pairings for a given round, refetch from API
        // the user can still do it manually from the app later
        if ($pairings->isEmpty()) {
          $pairings = $this->api->getPairings($roundId);
          $pairings->each(fn ($pairing) =>
          Pairing::updateOrCreate(
            ['id' => $pairing['id']],
            $pairing
          ));
          $pairings = Pairing::with('player1', 'player2')->whereRoundId($roundId)->get();
        }

        $pairings = $this->computeFormulas($pairings, $formulas);

        return $pairings;
      },
      'pairingsMessageTemplate' => fn () =>
      Setting::whereAttribute('pairings_template')->first()->value ??
        config('app.default_pairings_template'),
      'currentRoundId' => fn () => $roundId
    ]);
  }

  /**
   * Compute formulas for pairings.
   *
   * @param Collection $pairings
   * @param Collection $formulas
   * @return Collection
   */
  private function computeFormulas(Collection $pairings, Collection $formulas)
  {
    $players = Player::with('standing')->get();
    $deckCounts = $players->groupBy('decklist_name')->map->count();

    return $pairings->transform(function (Pairing $pairing) use ($deckCounts, $formulas) {
      $players = ['player1', 'player2'];

      $formulaResults = $formulas->mapWithKeys(fn ($formula) => [$formula->name => 0])->toArray();

      foreach ($players as $playerString) {
        // handle player missing (e.g. bye)
        if (!isset($pairing[$playerString])) {
          continue;
        }

        $player = $pairing[$playerString];
        $player->decklist_count = $deckCounts[$player->decklist_name];

        $variables = collect($player->attributesToArray())
          ->merge($player->tag_values)
          ->merge($player->standing)->toArray();

        foreach ($formulas as $formula) {
          $result = $this->math->evaluate($formula->equation, $variables);
          $formulaResults[$formula->name] = $result;
          $variables[$formula->name] = $result;
        }
      }

      // append the formula results to the pairing
      $pairing->setRawAttributes(array_merge($pairing->attributesToArray(), $formulaResults));

      return $pairing;
    });
  }
}
