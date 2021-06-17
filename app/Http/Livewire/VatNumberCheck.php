<?php

namespace App\Http\Livewire;

use App\Models\Customer;
use Livewire\Component;

class VatNumberCheck extends Component
{
    public $vatNumberCheck = '';
    public $customers = [];
    public $customer = NULL;

    public function mount(Customer $customer)
    {
        $this->customer = $customer;
        $this->vatNumberCheck = $customer->vat_number ?? '';
    }

    public function render()
    {
        $customers = Customer::where('id', '!=', $this->customer->id)->where('vat_number', 'ILIKE', $this->vatNumberCheck)->get()->toArray();

        if($customers){
            $this->customers = $customers;
//            $this->message = '1';
        }else{
            $this->customers = [];
        }

        return view('livewire.vat-number-check', ['customers', $this->customers]);
    }
}
