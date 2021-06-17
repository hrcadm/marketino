<?php

namespace App\Http\Livewire;

use App\Models\Customer;
use Livewire\Component;

class CustomerFastSearch extends Component
{
    /* @var Customer|null $customer */
    public $customer;

    /* @var string|null $searchTerm */
    public $searchTerm;

    public function render()
    {
        return view(
            'livewire.customer-fast-search',
            [
                'customers' => $this->customers,
            ]
        );
    }

    /* ACCESSORS */
    public function getCustomersProperty()
    {
        $query = $this->searchTerm ?
            Customer::search($this->searchTerm) :
            Customer::query();

        return $query->orderByDesc('created_at')
                     ->take(20)
                     ->get();
    }

    /* ACTIONS */
    public function resetSearch()
    {
        $this->reset('searchTerm');
    }

    public function selectCustomer($customerId)
    {
        $this->customer = Customer::find($customerId);
        $this->emit('customerSelected', $customerId);
        $this->dispatchBrowserEvent('customer-email', ['email' => $this->customer->email_address]);
        $this->resetSearch();
    }
}
