<?php

use App\Services\Math;


it('calculates basic operations', function (string $equation, float $result) {
  expect(app(Math::class)->evaluate($equation, []))->toBe($result);
})->with([
  ['2 + 3', 5],
  ['2 * 4', 8],
  ['10 / 4', 2.5],
  ['12 - 3', 9]
]);

it('evaluates to error when equation is invalid', function (string $equation) {
  expect(app(Math::class)->evaluate($equation, []))->toBe('error');
})->with([
  ['10 / 0'],
  ['5**']
]);

it('uses variables correctly', function (string $equation, array $variables, float $result) {
  expect(app(Math::class)->evaluate($equation, $variables))->toBe($result);
})->with([
  ['x + y', ['x' => 2, 'y' => '3'], 5],
  ['Win*2 - Uro', ['Win' => 3, 'Uro' => 1], 5]
]);

it('evaluates to error when variables are missing', function (string $equation, array $variables) {
  expect(app(Math::class)->evaluate($equation, $variables))->toBe('error');
})->with([
  ['x + y + z', ['x' => 1, 'y' => 2]],
  ['Winner + PreviousEquation', ['Winner' => '10']]
])->only();
