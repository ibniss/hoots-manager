<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Round;
use App\Models\Pairing;
use App\Models\Setting;
use App\Services\MeleeAPI;
use Illuminate\Http\Request;

class PairingController extends Controller
{
  private MeleeAPI $api;

  public function __construct(MeleeAPI $meleeAPI)
  {
    $this->api = $meleeAPI;
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

    return Inertia::render('Pairings/Index', [
      'columns' => ['select', 'copy', 'player1.player_name', 'player1.decklist_name', 'player2.player_name', 'player2.decklist_name', 'result'],
      'rounds' => fn () => $rounds,
      'pairings' => function () use ($roundId) {
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
          return Pairing::with('player1', 'player2')->whereRoundId($roundId)->get();
        }

        return $pairings;
      },
      'pairingsMessageTemplate' => fn () =>
      Setting::whereAttribute('pairings_template')->first()->value ??
        config('app.default_pairings_template'),
      'currentRoundId' => fn () => $roundId
    ]);
  }
}
