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
        $except = ['id', 'created_at', 'updated_at'];
        return collect(Schema::getColumnListing('players'))->merge(Tag::all()->pluck('name'))->filter(function ($var) use ($except) {
          return !in_array($var, $except);
        })->values();
      }
    ]);
  }

  /**
   * Creates a new formula;
   *
   * @param Request $request
   * @return void
   */
  public function store(Request $request)
  {
    $formula = Formula::create($request->only(['name', 'equation']));
    return Redirect::route('formulas.index');
  }

  public function delete(Formula $formula)
  {
    $formula->delete();
    return Redirect::route('formulas.index');
  }
}
