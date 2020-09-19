<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PlayerTag extends Pivot
{
  protected $casts = [
    'value' => 'boolean'
  ];
}
