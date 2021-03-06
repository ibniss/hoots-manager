<?php

namespace App\Services;

use App\Models\Player;
use App\Models\Setting;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;

class MeleeAPI
{
  /**
   * Get a map of ['ApiKey' => 'api_key'] to map fields between the API format and
   * our format.
   *
   * @param string $table
   * @param array $except
   * @return \Illuminate\Support\Collection
   */
  private function getColumnMap(string $table, array $except = ['created_at', 'updated_at'])
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
   * Get all rounds from phase 1 (currently only support 1 phase) from the API.
   *
   * @return Collection
   */
  public function getRounds()
  {
    $tournamentId = Setting::whereAttribute('tournament_id')->first()->value;
    $response = Http::withBasicAuth(
      env('MELEE_CLIENT_ID'),
      env('MELEE_CLIENT_SECRET')
    )->post(config('app.melee_base_url') . "/Tournament/Tournament/{$tournamentId}");
    $phases = $response->throw()->json('Phases');

    $fieldMap = $this->getColumnMap('rounds');
    return collect($phases[0]['Rounds'])->map(fn ($round) => $this->remapData($round, $fieldMap));
  }

  /**
   * Get all pairings for the given round from the API.
   *
   * @param integer $roundId
   * @return Collection
   */
  public function getPairings(int $roundId)
  {
    $response = Http::withBasicAuth(
      env('MELEE_CLIENT_ID'),
      env('MELEE_CLIENT_SECRET')
    )->post(config('app.melee_base_url') . "/Tournament/RoundMatches/{$roundId}");
    $pairings = $response->throw()->json();

    $fieldMap = $this->getColumnMap('pairings');
    return collect($pairings)->map(fn ($pairing) => $this->remapData($pairing, $fieldMap));
  }

  /**
   * Get all players from the API.
   *
   * @return Collection
   */
  public function getPlayers()
  {
    $tournamentId = Setting::whereAttribute('tournament_id')->first()->value;
    $response = Http::withBasicAuth(
      env('MELEE_CLIENT_ID'),
      env('MELEE_CLIENT_SECRET')
    )->post(config('app.melee_base_url') . "/Tournament/TournamentPlayers/{$tournamentId}");
    $players = $response->throw()->json();

    $fieldMap = $this->getColumnMap('players');
    return collect($players)->map(function ($player) use ($fieldMap) {
      $data = $this->remapData($player, $fieldMap);

      // API changed so deckslists is now an array - manually remap to single decklist
      $data['decklist_id'] = $player['Decklists'][0]['ID'] ?? null;
      $data['decklist_name'] = $player['Decklists'][0]['Name'] ?? null;

      return $data;
    });
  }

  /**
   * Get current standings from the API.
   *
   * @return Collection
   */
  public function getCurrentStandings()
  {
    $tournamentId = Setting::whereAttribute('tournament_id')->first()->value;
    $response = Http::withBasicAuth(
      env('MELEE_CLIENT_ID'),
      env('MELEE_CLIENT_SECRET')
    )->post(config('app.melee_base_url') . "/Tournament/CurrentStandings/{$tournamentId}");
    $standings = $response->throw()->json();

    $playersByDecklist = Player::all()->pluck('id', 'decklist_id');
    $fieldMap = $this->getColumnMap('standings');

    return collect($standings)->map(function ($standingData) use ($fieldMap, $playersByDecklist) {
      $data = $this->remapData($standingData, $fieldMap);

      // we have to match the player to the standing by decklist
      $data['player_id'] = $playersByDecklist[$standingData['Decklists'][0]['ID']];

      // there's a typo in the APi so we have to manually fix the issue...
      $data['match_losses'] = $standingData['MatchLoses'];

      return $data;
    });
  }
}
