<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketTag extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'tag',
        'type',
    ];

    /* RELATIONS */
    public function tickets()
    {
        return $this->belongsToMany(SupportTicket::class, 'ticket_id', 'tag_id');
    }
}
