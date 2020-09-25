<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Inertia\Inertia;
use App\Models\Formula;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Database\Eloquent\Collection;

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
      'formulas' => fn () => Formula::orderBy('order')->get(),
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
    $validatedData = $request->validate([
      'name' => ['required', 'string'],
      'equation' => ['required', 'string']
    ]);

    $maxOrder = Formula::all()->max('order') ?? 0;

    Formula::create(
      array_merge(
        $validatedData,
        ['order' => $maxOrder + 1]
      )
    );
    return Redirect::route('formulas.index')
      ->with('success', "Formula '{$request->input('name')}' created successfully.");
  }

  /**
   * Delete a formula.
   *
   * @param Request $request
   * @param Formula $formula
   * @return \Illuminate\Http\RedirectResponse
   */
  public function move(Request $request, Formula $formula)
  {
    $request->validate([
      'direction' => 'required|in:up,down'
    ]);

    $direction = $request->input('direction');
    $formulas = Formula::all();
    [$minOrder, $maxOrder] = [$formulas->min('order'), $formulas->max('order')];

    if ($direction === 'up' && $formula->order === $minOrder) {
      return Redirect::back()->with('error', "The formula '{$formula->name}' is already the first one.");
    }

    if ($direction === 'down' && $formula->order === $maxOrder) {
      return Redirect::back()->with('error', "The formula '{$formula->name}' is already the last one.");
    }

    $formulasByOrder = $formulas->sortBy('order')->values()->toArray();
    $formulaIndex = array_search($formula->id, array_column($formulasByOrder, 'id'));

    $swapWithIndex = $direction === 'up' ? $formulaIndex - 1 : $formulaIndex + 1;

    $temp = $formulasByOrder[$swapWithIndex];
    $formulasByOrder[$swapWithIndex] = $formula->toArray();
    $formulasByOrder[$formulaIndex] = $temp;

    // re-order all formulas to fix missing order values in case of deletions happening
    $order = 1;
    foreach ($formulasByOrder as $f) {
      Formula::whereId($f['id'])->update([
        'order' => $order
      ]);
      $order++;
    }


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
    return Redirect::route('formulas.index')
      ->with('success', "Formula '{$formula->name}' created successfully.");
  }
}
