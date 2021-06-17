<?php

namespace App\Http\Livewire;

use App\Models\Customer;
use App\Models\CustomerEmail;
use Livewire\Component;

class ShowCustomer extends Component
{

    public Customer $customer;
    public CustomerEmail $email;
    public $deliveryAddress;

    public function render()
    {

        return view('livewire.show-customer');
    }
}
