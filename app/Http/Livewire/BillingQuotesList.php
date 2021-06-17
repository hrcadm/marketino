<?php

namespace App\Http\Livewire;

use App\Enum\QuoteStatus;
use App\Models\Quote;
use App\Models\SaleItemPrice;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class BillingQuotesList extends Component
{
    /* @var string|null $searchTerm */
    public $searchTerm;

    /* @var string|null $agent */
    public $agent;

    /* @var string|null $package */
    public $package;

    /* @var string|null $payment */
    public $payment;

    /* @var string|null $date */
    public $date;

    /* @var string|null $document */
    public $document;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $quotes = Quote::query()
            ->with(['user', 'items.saleItemPrice', 'customer', 'invoice'])
            ->orderBy('created_at', 'DESC');

            if($this->date) {
                if (\Str::contains($this->date, 'to')) {
                    $dates = explode(' to ', $this->date);
                    $quotes->whereBetween('created_at', [Carbon::parse($dates[0])->format('Y-m-d H:i:s'), Carbon::parse($dates[1])->addDay()->format('Y-m-d H:i:s')]);
                } else {
                    $quotes->whereDate('created_at', Carbon::parse($this->date));
                }
            }

            if ($this->document !== 'svi' && $this->document !== null) {
                if ($this->document === QuoteStatus::INACTIVE) {
                    $quotes->where('status', $this->document)->orWhere(function ($query) {
                        $query->where('created_at', '<=', Carbon::now()->startOfDay()->subDays(7));
                        $query->where('status', '<>', QuoteStatus::PAID);
                    });
                } else {
                    $quotes->where('status', $this->document);
                }
            }

            if ($this->agent) {
                $quotes->where('user_id', $this->agent);
            }

            if ($this->package) {
                $quotes->whereHas('items', function ($query) {
                    $query->where('sale_item_price_id', $this->package);
                });
            }

            if ($this->payment) {
                if ($this->payment === 'rate') {

                    $quotes->whereHas('items.saleItemPrice', function ($query) {
                        $query->where('name', 'RATA 1 - Marketino ONE');
                    });

                } elseif ($this->payment === 'jednokratno') {

                    $quotes->whereDoesntHave('items.saleItemPrice', function ($query) {
                        $query->where('name', 'RATA 1 - Marketino ONE');
                    });

                }
            }

            if ($this->searchTerm) {

                $quotes->whereHas('customer', function ($query) {
                    $query->where(DB::raw('CONCAT(number, control_number)'), 'like', "%$this->searchTerm%");
                    $query->orWhere('first_name', 'like', "%$this->searchTerm%");
                    $query->orWhere('last_name', 'like', "%$this->searchTerm%");
                });

                $quotes->orWhere('total_amount', 'like', "%$this->searchTerm%");
                $quotes->orWhere('total_net_amount', 'like', "%$this->searchTerm%");

            }

        // Get all agents that have created a quote
        $agents = User::whereHas('quotes')->pluck('name', 'id');
        // Get all packages that are used in quotes
        $packages = SaleItemPrice::whereHas('quoteItem')->get();

        return view('livewire.billing-quotes-list', [
            'quotes' => $quotes->paginate(10),
            'packages' => $packages,
            'agents' => $agents
        ]);
    }

    public function clearDate()
    {
        $this->date = null;
    }
}
