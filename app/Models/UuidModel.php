<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

abstract class UuidModel extends Model
{
    use HasUuid;
}
