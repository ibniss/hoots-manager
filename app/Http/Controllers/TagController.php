<?php

namespace App\Http\Controllers;

use App\Models\PlayerTag;
use App\Models\Tag;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class TagController extends Controller
{
  public function index()
  {
    return Inertia::render('Tags/Index', [
      'tags' => function () {
        return Tag::all();
      }
    ]);
  }

  /**
   * Creates a new tag.
   *
   * @param Request $request
   * @return void
   */
  public function store(Request $request)
  {
    $tag = Tag::create($request->only(['name', 'default']));
    return Redirect::route('tags.index');
  }

  public function update(Tag $tag)
  {
    $tag->default = !$tag->default;
    $tag->save();

    // update all player_tag values to remove unnecessary lines
    PlayerTag::where('tag_id', $tag->id)->where('value', $tag->default)->delete();

    return Redirect::route('tags.index');
  }

  public function delete(Tag $tag)
  {
    $tag->delete();

    PlayerTag::where('tag_id', $tag->id)->delete();
    return Redirect::route('tags.index');
  }
}
