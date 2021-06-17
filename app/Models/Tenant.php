<?php

namespace App\Models;

use App\Models\Customer as MissingCustomer;
use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Tenant extends UuidModel
{
    use HasUuid;
    use Notifiable;
    use SoftDeletes;

    protected $fillable = [
        'country',
        'company',
        'vat_id',
        'prefer_sms',
    ];

    public function getDefaultCustomerAttribute()
    {
        return $this->customers->first() ?? new MissingCustomer();
    }

    public function getEmailAttribute()
    {
        return $this->default_customer->email;
    }

    public function getPhoneNumberAttribute()
    {
        return $this->default_customer->phone_number;
    }

    /* RELATIONS */
    public function customers()
    {
        return $this->hasMany(Customer::class);
    }
}
