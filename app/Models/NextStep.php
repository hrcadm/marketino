<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NextStep extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'customer_id',
        'quote_id',
        'date',
        'comment',
        'resolved',
        'note',
        'data'
    ];

    protected $dates = [
        'date'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer');
    }

    public function quote()
    {
        return $this->belongsTo('App\Models\Quote');
    }
}
