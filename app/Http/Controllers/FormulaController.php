<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Formula;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Schema;

class FormulaController extends Controller
{
  /**
   * View all formulas.
   *
   * @return Inertia\Response
   */
  public function index()
  {
    return Inertia::render('Formulas/Index', [
      'formulas' => fn () => Formula::all(),
      'variables' => function () {
        $except = ['id', 'decklist', 'name', 'created_at', 'updated_at'];
        return collect(Schema::getColumnListing('players'))->merge(Tag::all()->pluck('name'))->filter(function ($var) use ($except) {
          return !in_array($var, $except);
        })->values();
      }
    ]);
  }

  /**
   * Create a new formula.
   *
   * @param Request $request
   * @return \Illuminate\Http\RedirectResponse
   */
  public function store(Request $request)
  {
    Formula::create($request->only(['name', 'equation']));
    return Redirect::route('formulas.index');
  }

  /**
   * Delete a formula.
   *
   * @param Formula $formula
   * @return \Illuminate\Http\RedirectResponse
   */
  public function delete(Formula $formula)
  {
    $formula->delete();
    return Redirect::route('formulas.index');
  }
}
