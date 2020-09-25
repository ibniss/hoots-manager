<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Formula
 *
 * @property int $id
 * @property string $name
 * @property string $equation
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Formula newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Formula newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Formula query()
 * @method static \Illuminate\Database\Eloquent\Builder|Formula whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Formula whereEquation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Formula whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Formula whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Formula whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int $order
 * @method static \Illuminate\Database\Eloquent\Builder|Formula whereOrder($value)
 */
class Formula extends Model
{
  use HasFactory;

  protected $guarded = [];
}
