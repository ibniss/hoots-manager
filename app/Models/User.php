<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
  use HasFactory;

  /**
   * The attributes that are NOT mass assignable.
   *
   * @var array
   */
  protected $guarded = [];
}
