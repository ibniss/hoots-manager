<?php

use App\Models\Player;
use App\Services\MeleeAPI;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;

uses(RefreshDatabase::class);

beforeEach(function () {
  $this->seed();
});

it('parses player data', function () {
  $playerFixture = json_decode(file_get_contents(__DIR__ . '/../Fixtures/players_api.json'), true);
  $parsedPlayerFixture = collect(json_decode(file_get_contents(__DIR__ . '/../Fixtures/players_parsed.json'), true));

  Http::fake([
    '*' => Http::response($playerFixture, 200)
  ]);

  $response = app(MeleeAPI::class)->getPlayers();
  expect($response)->toEqual($parsedPlayerFixture);
});

it('parses standings data and matches players by decklist', function () {
  $players = Player::factory()->count(2)->create();
  $standingFixture = json_decode(file_get_contents(__DIR__ . '/../Fixtures/standings_api.json'), true);
  $parsedStandingFixture = json_decode(file_get_contents(__DIR__ . '/../Fixtures/standings_parsed.json'), true);

  // make sure the decklist IDs point at the right players
  $standingFixture[0]['DecklistId'] = $players[0]->decklist_id;
  $standingFixture[1]['DecklistId'] = $players[1]->decklist_id;

  $parsedStandingFixture[0]['player_id'] = $players[0]->id;
  $parsedStandingFixture[1]['player_id'] = $players[1]->id;

  Http::fake([
    '*' => Http::response($standingFixture, 200)
  ]);

  $response = app(MeleeAPI::class)->getCurrentStandings();
  expect($response)->toEqual(collect($parsedStandingFixture));
});
