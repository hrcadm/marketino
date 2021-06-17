<?php

namespace App\Models;

use App\Enum;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class SupportTicket
 *
 * @package App\Models
 * @mixin Builder
 */
class SupportTicket extends MarketinoModel
{
    protected $fillable = [
        'customer_id',
        'title',
        'status',
        'department',
        'assigned_agent_id',
        'short_id',
        'first_opened_at',
        'new_contact_email'
    ];

    protected $casts = [
        'status'     => Enum\SupportTicketStatus::class,
        'department' => Enum\SupportTicketDepartment::class,
    ];

    protected static function booted()
    {
        static::created(function (SupportTicket $ticket) {
            $ticket->short_id = (string)\Str::of($ticket->id)->substr(0, 8);
            $ticket->save();
        });
    }

    /* RELATIONS */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function assignedAgent()
    {
        return $this->belongsTo(User::class, 'assigned_agent_id');
    }

    public function messages()
    {
        return $this->hasMany(SupportMessage::class, 'ticket_id')
                    ->orderByDesc('created_at')
                    ->orderBy('id');
    }

    public function tags()
    {
        return $this->belongsToMany(TicketTag::class, 'tag_id', 'ticket_id');
    }

    /* HELPERS */
    public function badgeColor(): string
    {
        return $this->status === Enum\SupportTicketStatus::OPEN ? 'warning' : 'success';
    }

    /**
     * @return string|null
     */
    public function lastContactEmail()
    {
        /* @var SupportMessage $lastInboundMessage */
        $lastInboundMessage = $this->messages()->inbound()->latest()->first();

        return optional($lastInboundMessage)->from_email;
    }
}
