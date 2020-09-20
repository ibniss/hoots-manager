<?php

namespace App\Services;

use Exception;
use FormulaParser\FormulaParser;
use Illuminate\Support\Str;

class Math
{
  /**
   * Allowed internal variables. The currently used math parser only supports up to 5 variables.
   *
   * @var array
   */
  private $vars = ['x', 'y', 'z', 'a', 'b'];

  /**
   * Evaluate an equation.
   *
   * @param string $equation
   * @param array $variables
   * @return string
   */
  function evaluate(string $equation, array $variables)
  {
    $variables = collect($variables)->filter(function ($key, $variable) use ($equation) {
      return Str::contains($equation, $variable);
    });

    // remap variables into single letters
    $i = 0;
    $remappedVariables = collect($variables)->mapWithKeys(function ($variable, $name) use (&$i) {
      $ret = [
        $this->vars[$i] => $name
      ];
      $i++;
      return $ret;
    });

    // replace the variables in the equation string
    $eq = Str::of($equation);
    foreach ($remappedVariables as $letter => $variable) {
      $eq = $eq->replaceFirst($variable, $letter);
    }

    $variablesToReplace = $remappedVariables->mapWithKeys(function ($var, $letter) use ($variables) {
      return [
        $letter => (int) $variables[$var]
      ];
    })->toArray();

    try {
      $parser = new FormulaParser($eq->__toString());
      $parser->setVariables($variablesToReplace);
      [$status, $result] = $parser->getResult();
      return $result;
    } catch (Exception $e) {
      dd($e->getMessage());
    }
  }
}
