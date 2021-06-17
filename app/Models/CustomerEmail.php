<?php

namespace App\Models;

use Cassandra\Custom;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class CustomerEmail
 *
 * @package App\Models
 * @mixin Builder
 */
class CustomerEmail extends MarketinoModel
{
    protected $fillable = [
        'customer_id',
        'email',
        'is_default',
        'note'
    ];

    /**
     * @var $cachetest
     */
    private static $customerEmail;

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public static function getByEmail($email)
    {
        if (!static::$customerEmail) {

            static::$customerEmail = CustomerEmail::where('email', $email)
                ->whereNotNull('customer_id');
        }

        return static::$customerEmail;
    }
}
