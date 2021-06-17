<?php

namespace App\Http\Livewire;

use App\Enum\SupportTicketStatus;
use App\Models\SupportTicket;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class SupportTicketsList extends Component
{
    use WithPagination;

    /* @var string|null $status */
    public $status;
    /* @var string|null $department */
    public $department;
    /* @var string|null $search */
    public $search = '';
    /* @var string|null $dateFilter */
    public $dateFilter = '';
    /* @var string|null $agent */
    public $agent = '';

    protected $queryString = [
        'status',
        'department'  => ['except' => ''],
        'search'      => ['except' => ''],
    ];

    public function mount()
    {
        $this->fill(request()->only('status', 'department', 'search', 'page'));

        $this->status = $this->status ?? SupportTicketStatus::OPEN;
    }

    public function resetSearch()
    {
        $this->reset('search');
    }

    public function resetDateFilter()
    {
        $this->reset('dateFilter');
    }

    public function render()
    {
        // Build initial query
        $tickets = SupportTicket::query()
            ->with('customer.emails', 'messages', 'assignedAgent')
            ->orderByDesc('updated_at');

        // Filter by status
        if ($this->status) {
            ($this->status === 'new') ? $tickets->where('first_opened_at', null) : $tickets->where('status', $this->status);
        }

        // Filter by department
        if ($this->department) {
            $tickets->where('department', $this->department);
        }

        // Filter by agent
        if ($this->agent) {
            $tickets->where('assigned_agent_id', $this->agent);
        }

        // Filter by date
        if($this->dateFilter) {
            if (\Str::contains($this->dateFilter, 'to')) {
                $dates = explode(' to ', $this->dateFilter);
                $tickets->whereBetween('created_at', [Carbon::parse($dates[0])->format('Y-m-d H:i:s'), Carbon::parse($dates[1])->addDay()->format('Y-m-d H:i:s')]);
            } else {
                $tickets->whereDate('created_at', Carbon::parse($this->dateFilter));
            }
        }

        // Filter by query string
        if ($this->search) {
            $tickets->where(
                function ($query) {
                    $query
                        ->where('title', 'LIKE', "%$this->search%")
                        ->orWhere('new_contact_email', 'like', "%$this->search%")
                        ->orWhereHas(
                            'customer',
                            function ($query) {
                                $query->where('company_name', 'LIKE', "%$this->search%");
                                $query->orWhere(DB::raw("CONCAT_WS(' ', first_name, last_name)"), 'like', '%'.$this->search.'%');
                            }
                        )
                        ->orWhereHas('customer.emails', function ($query) {
                            $query->where('email', 'like', "%$this->search%");
                        });
                }
            );
        }

        // Get all agents that have created a quote
        $agents = User::whereHas('tickets')->pluck('name', 'id');

        return view(
            'livewire.support-tickets-list',
            [
                'tickets' => $tickets->paginate(10),
                'agents' => $agents
            ]
        );
    }
}
