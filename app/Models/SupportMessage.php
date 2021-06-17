<?php

namespace App\Models;

use App\Enum;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class SupportMessage
 *
 * @package App\Models
 * @mixin Builder
 */
class SupportMessage extends MarketinoModel
{
    protected $fillable = [
        'ticket_id',
        'content',
        'from_email',
        'agent_id',
        'action',
        'mailgun_message_id',
        'canned_mail_id',
        'file',
        'send_to_customer',
        'sent_to_customer_at',
    ];

    protected $appends = [
        'type',
    ];

    /* ACCESSORS & MUTATORS */
    public function getTypeAttribute(): string
    {
        return \Arr::get($this->attributes, 'agent_id') ?
            Enum\TicketMessageType::OUTBOUND :
            Enum\TicketMessageType::INBOUND;
    }

    /* RELATIONS */
    public function ticket()
    {
        return $this->belongsTo(SupportTicket::class, 'ticket_id');
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function customer()
    {
        return $this->ticket->customer();
    }

    /* SCOPES */
    public function scopeOutbound(Builder $query)
    {
        return $query->whereNotNull('agent_id');
    }

    public function scopeInbound(Builder $query)
    {
        return $query->whereNull('agent_id');
    }

    /* HELPERS */
    public function fileDisk()
    {
        return config('filesystems.cloud');
    }

    public function fileDirectory()
    {
        return implode(
            '/',
            [
                'support_messages',
                $this->ticket_id,
                $this->id,
            ]
        );
    }

    public function files()
    {
        return \Storage::disk($this->fileDisk())->files($this->fileDirectory());
    }

}
