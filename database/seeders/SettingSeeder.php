<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $defaults = [
      [
        'attribute' => 'tournament_id',
        'value' => null
      ],
      [
        'attribute' => 'pairings_template',
        'value' => config('app.default_pairings_template')
      ]
    ];

    foreach ($defaults as  $default) {
      Setting::updateOrCreate(
        [
          'attribute' => $default['attribute']
        ],
        [
          'value' => $default['value']
        ]
      );
    }
  }
}
