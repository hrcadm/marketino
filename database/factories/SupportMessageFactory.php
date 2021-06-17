<?php

namespace Database\Factories;

use App\Models\SupportMessage;
use App\Models\SupportTicket;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SupportMessageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SupportMessage::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'ticket_id'          => SupportTicket::factory(),
            'content'            => $this->faker->text(200),
            'from_email'         => $this->faker->email,
            'agent_id'           => $this->faker->boolean ? User::query()->inRandomOrder()->first() : null,
            'action'             => null,
            'mailgun_message_id' => null,
            'canned_mail_id'     => null,
            "file"               => null,
        ];
    }
}
