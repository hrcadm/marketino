<?php

namespace App\Models;

use App\Enum\CompanyType;
use App\Enum\CountryCode;
use App\Enum\CustomerStatus;
use App\Enum\SaleChannel;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class Customer
 *
 * @package App\Models
 * @mixin Builder
 */
class Customer extends MarketinoModel
{
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'company_name',
        'vat_number',
        'fiscal_number',
        'address',
        'address_additional',
        'zip',
        'city',
        'region',
        'note',
        'gdpr',
        'newsletter',
        'activity_type_id',
        'einvoice_code',
        'iban',
        'iban_name',
        'company_date',
        'legal_contact',
        'country_code',
        'sale_channel',
        'customer_status',
        'company_type'
    ];

    protected $casts = [
        'country_code'    => CountryCode::class,
        'sale_channel'    => SaleChannel::class,
        'customer_status' => CustomerStatus::class,
        'company_type'    => CompanyType::class,
    ];

    /* ACCESSORS & MUTATORS */
    public function getDisplayNameAttribute()
    {
        if ($this->company_name) {
            return $this->company_name . ', ' . $this->full_name;
        }

        return $this->full_name;
    }

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getEmailAddressAttribute()
    {
        return optional($this->email())->email;
    }

    public function getFullAddressAttribute()
    {
        return collect(
            [
                $this->address,
                $this->zip,
                $this->city,
                $this->region,
                $this->country_code,
            ]
        )->filter()
         ->implode(', ');
    }

    /* RELATIONS */
    public function activity()
    {
        return $this->belongsTo(ActivityType::class, 'activity_type_id');
    }

    public function emails()
    {
        return $this->hasMany(CustomerEmail::class);
    }

    public function quotes()
    {
        return $this->hasMany(Quote::class)->orderByDesc('created_at');
    }

    public function phones()
    {
        return $this->hasMany(CustomerPhone::class);
    }

    public function deliveryAddress()
    {
        return $this->hasMany(DeliveryAddress::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /* SCOPES */
    public function scopeSearch(Builder $query, string $term)
    {
        $term = "%$term%";

        return $query->where('first_name', $term)
                    ->orWhere('last_name', $term)
                    ->where('first_name', 'ILIKE', $term)
                     ->orWhere('last_name', 'ILIKE', $term)
                    ->orWhere('company_name', $term)
                     ->orWhere('company_name', 'ILIKE', $term)
                     ->orWhere('vat_number', 'ILIKE', $term)
                     ->orWhereHas(
                         'phones',
                         function ($phones) use ($term) {
                             $phones->where('phone', 'ILIKE', $term);
                         }
                     )
                     ->orWhereHas(
                         'emails',
                         function ($emails) use ($term) {
                             $emails->where('email', 'ILIKE', $term);
                         }
                     );
    }

    /* HELPER */
    public function email()
    {
        $default_email = $this->emails()->where('is_default', true)->first();

        return $default_email ?? $this->emails->first();
    }

    public function phone()
    {
        $default_phone = $this->phones()->where('is_default', true)->first();

        return $default_phone ?? $this->phones->first();
    }

    public function hasDeliveryAddress()
    {
        return !!$this->deliveryAddress()->first();
    }

    public function getContactData(): array
    {
        return [
            "first_name"         => $this->first_name,
            "last_name"          => $this->last_name,
            "company_name"       => $this->company_name,
            "vat_number"         => $this->vat_number,
            "address"            => $this->address,
            "address_additional" => $this->address_additional,
            "zip"                => $this->zip,
            "city"               => $this->city,
            "region"             => $this->region,
            "country_code"       => $this->country_code,
        ];
    }

    public function setVatNumberAttribute($value)
    {
        $this->quotes()->count() === 0 ? $this->attributes['vat_number'] = $value : NULL;
    }

    public function nextSteps()
    {
        return $this->hasMany('App\Models\NextStep')->orderByDesc('date')->orderByDesc('id');
    }
}
