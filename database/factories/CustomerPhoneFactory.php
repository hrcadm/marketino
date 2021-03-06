<?php

namespace Database\Factories;

use App\Models\CustomerPhone;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerPhoneFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CustomerPhone::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'customer_id' => null,
            'phone'       => $this->faker->phoneNumber,
            'is_default'  => $this->faker->boolean,
        ];
    }
}
