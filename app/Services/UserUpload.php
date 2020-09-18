<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;

class UserUpload
{
  private $autoColumns = [
    'Player' => 'name',
    'Decklist' => 'decklist',
    'Points' => 'points'
  ];

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
      $score = $this->processRecord($row[$recordCol]);

      return $autoData->merge($score);
    });

    $parsed->each(fn ($row) => User::create($row->toArray()));
  }

  /**
   * Process record from a string into separate values.
   *
   * @param String $score
   * @return array
   */
  private function processRecord(string $score): array
  {
    [$wins, $draws, $losses] = Str::of($score)->explode('-');

    return [
      'wins' => $wins,
      'draws' => $draws,
      'losses' => $losses
    ];
  }
}
