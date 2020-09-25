<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Str;
use oat\beeme\Parser;

class Math
{
  private Parser $parser;

  public function __construct(Parser $parser)
  {
    $this->parser = $parser;
  }

  /**
   * Evaluate an equation.
   *
   * @param string $equation
   * @param array $variables
   * @return float|string
   */
  function evaluate(string $equation, array $variables)
  {
    // only use variables which are used in the equation; parse them to integers
    $variables = collect($variables)->filter(function ($value, $variable) use ($equation) {
      return Str::contains($equation, $variable);
    })->map(fn ($val) => (int) $val)->toArray();

    try {
      return $this->parser->evaluate($equation, $variables);
    } catch (Exception $e) {
      return 'error';
    }
  }
}
