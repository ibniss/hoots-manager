<?php

namespace App\Http\Controllers;

use App\Models\PlayerTag;
use App\Models\Tag;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class TagController extends Controller
{
  /**
   * View all tags.
   *
   * @return \Inertia\Response
   */
  public function index()
  {
    return Inertia::render('Tags/Index', [
      'tags' => function () {
        return Tag::all();
      }
    ]);
  }

  /**
   * Create a new tag.
   *
   * @param Request $request
   * @return \Illuminate\Http\RedirectResponse
   */
  public function store(Request $request)
  {
    $validatedData = $request->validate([
      'name' => ['required', 'string'],
      'default' => ['required', 'boolean']
    ]);

    Tag::create($validatedData);
    return Redirect::route('tags.index')
      ->with('success', "Tag '{$request->input('name')}' created successfully.");
  }

  /**
   * Update a tag default value.
   *
   * @param Tag $tag
   * @return \Illuminate\Http\RedirectResponse
   */
  public function update(Tag $tag)
  {
    $tag->default = !$tag->default;
    $tag->save();

    // update all player_tag values to remove unnecessary lines
    PlayerTag::where('tag_id', $tag->id)->where('value', $tag->default)->delete();

    return Redirect::route('tags.index')
      ->with('success', "Tag '{$tag->name}' updated successfully.");
  }

  /**
   * Delete a tag.
   *
   * @param Tag $tag
   * @return \Illuminate\Http\RedirectResponse
   */
  public function delete(Tag $tag)
  {
    $tag->delete();

    PlayerTag::where('tag_id', $tag->id)->delete();
    return Redirect::route('tags.index')
      ->with('success', "Tag '{$tag->name}' deleted successfully.");
  }
}
