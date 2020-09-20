<?php

namespace App\Services;

use App\Models\Player;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class PlayerUpload
{
  private $autoColumns = [
    'Player' => 'name',
    'Decklist' => 'decklist',
    'Points' => 'points'
  ];

  /**
   * Parse a CSV (in a MTGMelee standings format) and store player data.
   *
   * @param UploadedFile $file
   * @return void
   */
  public function parseCsv(UploadedFile $file)
  {
    $path = $file->getRealPath();
    $data = array_map('str_getcsv', file($path));

    // map of col => index
    $header = collect(array_flip($data[0]));
    $recordCol = $header['Record'];

    // map of col name => index
    $autoIndexes = $header->only(array_keys($this->autoColumns));
    $parsed = collect($data)->slice(1)->map(function ($row) use ($autoIndexes, $recordCol) {
      // get auto columns first
      $autoData = $autoIndexes->mapWithKeys(fn ($index, $col) => [$this->autoColumns[$col] => $row[$index]]);

      // process record col
      $score = $this->parseRecord($row[$recordCol]);

      return $autoData->merge($score);
    });

    $parsed->each(fn ($row) => Player::updateOrCreate(['name' => $row['name']], $row->toArray()));

    $this->updateDeckcounts();
  }

  /**
   * Update the deckcounts for each player.
   *
   * @return void
   */
  private function updateDeckcounts()
  {
    $players = Player::all();
    $deckCounts = $players->groupBy('decklist')->map->count();

    foreach ($players as $player) {
      $player->deckcount = $deckCounts[$player->decklist];
      $player->save();
    }
  }

  /**
   * Parse a record from a string into separate values.
   * E.g. '10-2-0' => ['wins' => 10, 'draws' => 0, 'losses' => 2]
   *
   * @param String $score
   * @return array
   */
  private function parseRecord(string $score): array
  {
    [$wins, $losses, $draws] = Str::of($score)->explode('-');

    return [
      'wins' => $wins,
      'draws' => $draws,
      'losses' => $losses
    ];
  }
}
