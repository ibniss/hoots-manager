<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
  /**
   * Display users.
   *
   * @return \Inertia\Response
   */
  public function index()
  {
    return Inertia::render('Users/Index', []);
  }
}
