<?php

namespace App\Models;

use App\Enum\CountryCode;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DeliveryAddress extends MarketinoModel
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'address',
        'address_additional',
        'zip',
        'city',
        'region',
        'note',
        'first_name',
        'last_name',
        'company_name',
        'phone',
        'country_code'
    ];

    protected $casts = [
        'country_code'    => CountryCode::class,
    ];

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }
}
