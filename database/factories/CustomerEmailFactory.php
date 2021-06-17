<?php

namespace Database\Factories;

use App\Models\CustomerEmail;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerEmailFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CustomerEmail::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'customer_id' => null,
            'email'       => $this->faker->email,
            'is_default'  => $this->faker->boolean,
        ];
    }
}
