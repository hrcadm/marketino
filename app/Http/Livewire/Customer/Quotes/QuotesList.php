<?php

namespace App\Http\Livewire\Customer\Quotes;

use App\Models\Customer;
use Livewire\Component;

class QuotesList extends Component
{
    /* @var Customer $customer */
    public $customer;


    public function render()
    {
        return view('livewire.customer.quotes.quotes-list');
    }
}
