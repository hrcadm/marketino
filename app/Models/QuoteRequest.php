<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuoteRequest extends MarketinoModel
{
    protected $fillable = [
        'customer_id',
        'quote_id',
        'user_id',
        'from_web',
        'request_data',
        'ip_address'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function quote()
    {
        return $this->belongsTo(Quote::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
