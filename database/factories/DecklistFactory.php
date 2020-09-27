<?php

namespace Database\Factories;

use App\Models\Decklist;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class DecklistFactory extends Factory
{
  /**
   * The name of the factory's corresponding model.
   *
   * @var string
   */
  protected $model = Decklist::class;

  /**
   * Define the model's default state.
   *
   * @return array
   */
  public function definition()
  {
    return [];
  }
}
