<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\Standing;
use App\Models\PlayerTag;
use App\Services\MeleeAPI;

class MeleeApiController extends Controller
{
  private MeleeAPI $api;

  public function __construct(MeleeAPI $meleeAPI)
  {
    $this->api = $meleeAPI;
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
