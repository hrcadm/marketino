<?php

namespace App\Http\Livewire\Customer\Contacts;

use App\Models\CustomerEmail;
use Livewire\Component;

class CustomerEmailTd extends Component
{
    /* @var CustomerEmail $customerEmail */
    public $customerEmail;

    public $showDeleteModal = false;

    /* @var array $rules */
    protected $rules = [
        'customerEmail.email'      => 'nullable|email',
        'customerEmail.is_default' => 'nullable|boolean',
    ];

    /* @var array $listeners */
    protected $listeners = [
        'newDefaultEmailChosen' => 'newDefaultEmailChosen',
    ];

    public function render()
    {
        return view('livewire.customer.contacts.customer-email-td');
    }

    public function showDeleteModal()
    {
        $this->showDeleteModal = true;
    }

    public function updateEmail()
    {
        $this->customerEmail->save();

        // dispatch global event to show notification system wide
        $this->dispatchBrowserEvent('notification', ['type' => 'success', 'text' => 'E-mail ažuriran!']);
    }

    public function deleteEmail()
    {
        if (!$this->customerEmail->is_default) {

            $this->customerEmail->delete();

            // close modal
            $this->showDeleteModal = false;

            // reload parent component
            $this->emit('customerContactsUpdated');

            // dispatch global event to show notification system wide
            $this->dispatchBrowserEvent('notification', ['type' => 'success', 'text' => 'E-mail obrisan!']);

        } else {
            $this->showDeleteModal = false;

            $this->dispatchBrowserEvent('notification', ['type' => 'error', 'text' => 'Defaultni ili jedini E-mail customera nije moguće obrisati!']);
        }
    }

    public function makeEmailPrimary()
    {
        CustomerEmail::where('customer_id', $this->customerEmail->customer_id)
                     ->where('is_default', true)
                     ->update(['is_default' => false]);

        $this->customerEmail->is_default = true;
        $this->customerEmail->save();

        // reload all email components
        $this->emit('newDefaultEmailChosen');

        // dispatch global event to show notification system wide
        $this->dispatchBrowserEvent('notification', ['type' => 'success', 'text' => 'E-mail postavljen primarnim!']);
    }

    public function newDefaultEmailChosen()
    {
        $this->customerEmail->refresh();
    }
}
