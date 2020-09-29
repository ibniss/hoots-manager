<?php

namespace Database\Factories;

use App\Models\Player;
use App\Models\Standing;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class StandingFactory extends Factory
{
  /**
   * The name of the factory's corresponding model.
   *
   * @var string
   */
  protected $model = Standing::class;

  /**
   * Define the model's default state.
   *
   * @return array
   */
  public function definition()
  {
    return [
      'player_id' => Player::factory(),
      'rank' => $this->faker->numberBetween(1, 100),
      'match_wins' => $this->faker->numberBetween(0, 20),
      'match_losses' => $this->faker->numberBetween(0, 20),
      'match_draws' => $this->faker->numberBetween(0, 20),
    ];
  }

  public function configure()
  {
    return $this->afterMaking(function (Standing $standing) {
      $standing->points = $standing->match_wins * 3;
      return $standing;
    });
  }
}
