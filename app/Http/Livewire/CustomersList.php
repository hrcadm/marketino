<?php

namespace App\Http\Livewire;

use App\Models\Customer;
use Livewire\Component;
use Livewire\WithPagination;

class CustomersList extends Component
{
    use WithPagination;

    /* @var string|null $searchTerm */
    public $searchTerm;

    public function render()
    {
        return view(
            'livewire.customers-list',
            [
                'customers' => $this->customers,
            ]
        );
    }

    public function getCustomersProperty()
    {
        $query = $this->searchTerm ?
            Customer::search($this->searchTerm) :
            Customer::query();

        return $query->orderBy('company_name')
                     ->orderBy('first_name')
                     ->orderBy('last_name')
                     ->paginate(10);
    }
}
