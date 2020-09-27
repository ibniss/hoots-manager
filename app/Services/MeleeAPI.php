<?php

namespace App\Services;

use App\Models\Player;
use App\Models\Setting;
use App\Models\Standing;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Factories\Sequence;

class MeleeAPI
{
  private Client $client;

  public function __construct()
  {
    $this->client = new Client([
      'base_uri' => config('app.melee_base_url'),
      'auth' => [
        env('MELEE_CLIENT_ID'),
        env('MELEE_CLIENT_SECRET')
      ]
    ]);
  }

  /**
   * Get all players from the API.
   *
   * @return mixed
   */
  public function getPlayers()
  {
    // TODO: replace with actual API call once it works
    return json_decode(file_get_contents(storage_path('fixtures/players.json')), true);
  }

  public function getCurrentStandings()
  {
    // TODO: replace with actual API call once it works
    return json_decode(file_get_contents(storage_path('fixtures/standings.json')), true);
  }
}
