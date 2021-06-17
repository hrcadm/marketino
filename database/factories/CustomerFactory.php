<?php

namespace Database\Factories;

use App\Enum\CountryCodeEnum;
use App\Enum\SaleChannelEnum;
use App\Models\Customer;
use DB;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory {
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Customer::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition() {

        $activityTypeIds = DB::table('activity_types')->pluck('id')->toArray();


        return [
            'first_name'   => $this->faker->firstName,
            'last_name'    => $this->faker->lastName,
            'company_name' => $this->faker->company,
            'vat_number'   => app('Faker')->vatNumberIt,

            'address' => $this->faker->streetAddress,
            'zip'     => $this->faker->numerify(str_repeat('#', 5)),
            'city'    => $this->faker->city,
            'region'  => $this->faker->city,

            'activity_type_id' => $this->faker->randomElement($activityTypeIds)

        ];
    }
}
