<?php

namespace Database\Factories;

use App\Enum\SupportTicketDepartment;
use App\Enum\SupportTicketStatus;
use App\Models\Customer;
use App\Models\SupportTicket;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SupportTicketFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SupportTicket::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'customer_id'       => $this->faker->boolean ? Customer::factory() : null,
            'title'             => $this->faker->sentence,
            'status'            => SupportTicketStatus::getRandomInstance(),
            'department'        => SupportTicketDepartment::getRandomInstance(),
            'assigned_agent_id' => User::query()->inRandomOrder()->first(),
        ];
    }
}
