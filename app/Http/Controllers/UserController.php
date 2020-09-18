<?php

namespace App\Http\Controllers;

use App\Services\UserUpload;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;

class UserController extends Controller
{
  private UserUpload $userUpload;

  public function __construct(UserUpload $upload)
  {
    $this->userUpload = $upload;
  }

  /**
   * Display users.
   *
   * @return \Inertia\Response
   */
  public function index()
  {
    return Inertia::render('Users/Index', [
      'columns' => ['name', 'wins', 'draws', 'losses', 'points'],
      'users' => fn () => User::all()
    ]);
  }

  public function store(Request $request)
  {
    $file = $request->file('data');
    $this->userUpload->parseCsv($file);
  }
}
