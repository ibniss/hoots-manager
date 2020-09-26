<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Redirect;

class SettingController extends Controller
{
  /**
   * View the settings page
   *
   * @return \Inertia\Response
   */
  public function index()
  {
    return Inertia::render('Settings/Index', [
      'settings' => fn () => Setting::all()->pluck('value', 'attribute'),
      'defaultPairingsTemplate' => config('app.default_pairings_template')
    ]);
  }

  /**
   * Update the settings
   *
   * @param Request $request
   * @return \Illuminate\Http\RedirectResponse
   */
  public function update(Request $request)
  {
    $validatedData = $request->validate([
      'tournament_id' => 'required|integer',
      'pairings_template' => 'required|string'
    ]);

    foreach ($validatedData as $key => $value) {
      Setting::updateOrCreate(
        [
          'attribute' => $key
        ],
        [
          'value' => $value
        ]
      );
    }

    return Redirect::route('settings.index')
      ->with('success', 'Settings updated successfully.');
  }
}
