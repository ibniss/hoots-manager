<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\Tag;
use App\Services\PlayerUpload;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class PlayerController extends Controller
{
  private PlayerUpload $playerUpload;

  public function __construct(PlayerUpload $upload)
  {
    $this->playerUpload = $upload;
  }

  /**
   * Display players.
   *
   * @return \Inertia\Response
   */
  public function index()
  {
    return Inertia::render('Players/Index', [
      'columns' => ['name', 'wins', 'draws', 'losses', 'points'],
      'tags' => function () {
        return Tag::all();
      },
      'players' => fn () => tap(Player::all(), function ($players) {
        $players->each->append('tag_values');
      })
    ]);
  }

  /**
   * Store players from a CSV upload.
   *
   * @param Request $request
   * @return void
   */
  public function store(Request $request)
  {
    $file = $request->file('data');
    $this->playerUpload->parseCsv($file);
    return Redirect::route('players.index');
  }
}
