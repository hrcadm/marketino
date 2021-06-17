<?php

namespace App\Http\Livewire\Customer\Contacts;

use App\Models\Customer;
use App\Models\CustomerEmail;
use App\Models\CustomerPhone;
use Livewire\Component;

class CustomerContacts extends Component
{
    /* @var Customer $customer */
    public $customer;

    public $newEmailModalShow = false;
    public $newPhoneModalShow = false;

    public $new_email, $new_phone, $is_default;

    /* @var array $listeners */
    protected $listeners = [
        'customerContactsUpdated' => '$refresh',
    ];

    public function render()
    {
        $this->customer->load(
            [
                'emails' => function ($query) {
                    $query->orderBy('id');
                },
                'phones' => function ($query) {
                    $query->orderBy('id');
                },
            ]
        );

        return view('livewire.customer.contacts.customer-contacts');
    }

    public function newEmailModalShow()
    {
        $this->newEmailModalShow = true;
    }

    public function newPhoneModalShow()
    {
        $this->newPhoneModalShow = true;
    }

    public function storeNewCustomerEmail()
    {
        $this->validate([
            'new_email' => 'required|email'
        ]);

        // Clear the default flag if needed
        if ($this->is_default) {
            CustomerEmail::where('customer_id', $this->customer->id)
                         ->where('is_default', true)
                         ->update(['is_default' => false]);
        }

        $newEmail = new CustomerEmail();
        $newEmail->customer_id = $this->customer->id;
        $newEmail->email = $this->new_email;
        $newEmail->is_default = (bool)$this->is_default;
        $newEmail->save();

        $this->resetNewEmailPhoneInputs();

        $this->newEmailModalShow = false;

        // reload parent component
        $this->emit('newDefaultEmailChosen');

        // dispatch global event to show notification system wide
        $this->dispatchBrowserEvent('notification', ['type' => 'success', 'text' => 'E-mail spremljen!']);
    }

    public function storeNewCustomerPhone()
    {
        $this->validate([
            'new_phone' => 'required'
        ]);

        // Clear the default flag if needed
        if ($this->is_default) {
            CustomerPhone::where('customer_id', $this->customer->id)
                ->where('is_default', true)
                ->update(['is_default' => false]);
        }

        $newPhone = new CustomerPhone();
        $newPhone->customer_id = $this->customer->id;
        $newPhone->phone = $this->new_phone;
        $newPhone->is_default = (bool)$this->is_default;
        $newPhone->save();

        $this->resetNewEmailPhoneInputs();

        $this->newPhoneModalShow = false;

        // reload parent component
        $this->emit('newDefaultPhoneChosen');

        // dispatch global event to show notification system wide
        $this->dispatchBrowserEvent('notification', ['type' => 'success', 'text' => 'Kontakt telefon spremljen!']);
    }

    public function resetNewEmailPhoneInputs()
    {
        $this->new_email = null;
        $this->new_phone = null;
        $this->is_default = null;
    }
}
