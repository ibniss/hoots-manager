<?php

namespace Database\Factories;

use App\Models\Player;
use App\Models\Decklist;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class PlayerFactory extends Factory
{
  /**
   * The name of the factory's corresponding model.
   *
   * @var string
   */
  protected $model = Player::class;

  /**
   * Define the model's default state.
   *
   * @return array
   */
  public function definition()
  {
    // for now we assume username/screen name/discord name etc are the same
    // in reality there will be minor differences so it's fine for testing
    $userName = $this->faker->userName;

    return [
      'first_name' => $this->faker->firstName,
      'last_name' => $this->faker->lastName,
      'user_name' => $userName,
      'screen_name' => $userName,
      'discord_username' => $userName . '#' . $this->faker->randomNumber(4),
      'player_name' => $userName,
      'decklist_id' => Decklist::factory()
    ];
  }
}
