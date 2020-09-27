<?php

namespace App\Http\Controllers;

use Str;
use Schema;
use App\Models\Player;
use App\Models\Standing;
use App\Services\MeleeAPI;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class MeleeApiController extends Controller
{
  private MeleeAPI $api;

  public function __construct(MeleeAPI $meleeAPI)
  {
    $this->api = $meleeAPI;
  }

  /**
   * Get a map of ['ApiKey' => 'api_key'] to map fields between the API format and
   * our format.
   *
   * @param string $table
   * @param array $except
   * @return \Illuminate\Support\Collection
   */
  private function getColumnMap(string $table, array $except)
  {
    $dbColumns = collect(Schema::getColumnListing($table))->flip()->except($except)->flip();
    return $dbColumns->mapWithKeys(function ($col) {
      // id doesn't obey the rule
      $apiCol = $col === 'id' ? 'ID' : Str::of($col)
        ->explode('_')
        ->map(fn ($colPart) => Str::title($colPart))
        ->join('');

      return [
        $apiCol => $col
      ];
    });
  }

  /**
   * Remap a data array keys according to a given map.
   *
   * @param array $data
   * @param Collection $map
   * @return array
   */
  private function remapData(array $data, Collection $map)
  {
    return $map->mapWithKeys(function ($col, $apiCol) use ($data) {
      return [
        $col => $data[$apiCol] ?? null
      ];
    })->toArray();
  }

  /**
   * Refresh the players data from API.
   */
  public function players()
  {
    $players = $this->api->getPlayers();
    $fieldMap = $this->getColumnMap('players', ['created_at', 'updated_at']);

    foreach ($players as $playerData) {
      $data = $this->remapData($playerData, $fieldMap);

      Player::updateOrCreate(
        ['id' => $data['id']],
        $data
      );
    }
  }

  /**
   * Refresh the current standings from API.
   */
  public function standings()
  {
    $playersByDecklist = Player::all()->pluck('id', 'decklist_id');
    $standings = $this->api->getCurrentStandings();
    $fieldMap = $this->getColumnMap('standings', ['created_at', 'updated_at']);

    foreach ($standings as $standingData) {
      $data = $this->remapData($standingData, $fieldMap);

      // we have to match the player to the standing by decklist
      $data['player_id'] = $playersByDecklist[$standingData['DecklistId']];

      // there's a typo in the APi so we have to manually fix the issue...
      $data['match_losses'] = $standingData['MatchLoses'];

      Standing::updateOrCreate(
        ['id' => $data['id']],
        $data
      );
    }
  }
}
