<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerPhone extends MarketinoModel
{

    protected $fillable = [
        'customer_id',
        'phone',
        'is_default',
        'note'
    ];
    //todo::rules

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }
}
