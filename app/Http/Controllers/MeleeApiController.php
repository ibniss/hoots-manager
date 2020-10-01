<?php

namespace App\Http\Controllers;

use App\Models\Pairing;
use App\Models\Round;
use App\Models\Player;
use App\Models\Standing;
use App\Models\PlayerTag;
use App\Services\MeleeAPI;
use Illuminate\Http\Request;

class MeleeApiController extends Controller
{
  private MeleeAPI $api;

  public function __construct(MeleeAPI $meleeAPI)
  {
    $this->api = $meleeAPI;
  }

  /**
   * Refresh the rounds data from API.
   *
   * @return \Illuminate\Http\RedirectResponse
   */
  public function rounds()
  {
    $rounds = $this->api->getRounds();

    $rounds->each(fn ($round) =>
    Round::updateOrCreate(
      ['id' => $round['id']],
      $round
    ));
    return response()->json([
      'success' => 'Rounds refreshed successfully.'
    ]);
  }

  /**
   * Refresh pairings for a given round from API.
   *
   * @param Request $request
   * @return \Illuminate\Http\RedirectResponse
   */
  public function pairings(Request $request)
  {
    $request->validate([
      'round_id' => 'required|integer'
    ]);

    $pairings = $this->api->getPairings($request->input('round_id'));
    $pairings->each(fn ($pairing) =>
    Pairing::updateOrCreate(
      ['id' => $pairing['id']],
      $pairing
    ));
    return response()->json([
      'success' => 'Pairings refreshed successfully.'
    ]);
  }

  /**
   * Refresh the players data from API.
   *
   * @return \Illuminate\Http\RedirectResponse
   */
  public function players()
  {
    $players = $this->api->getPlayers();

    // wipe all player and player-tag data
    Player::truncate();
    PlayerTag::truncate();

    $players->each(fn ($player) => Player::create($player));
    return response()->json([
      'success' => 'Players refreshed successfully.'
    ]);
  }

  /**
   * Refresh the current standings from API.
   *
   * @return \Illuminate\Http\RedirectResponse
   */
  public function standings()
  {
    $standings = $this->api->getCurrentStandings();
    $standings->each(fn ($standingData) =>
    Standing::updateOrCreate(
      ['id' => $standingData['id']],
      $standingData
    ));

    return response()->json([
      'success' => 'Standings refreshed successfully.'
    ]);
  }
}
