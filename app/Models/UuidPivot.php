<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Relations\Pivot;

abstract class UuidPivot extends Pivot
{
    use HasUuid;
}
