<?php

namespace App\Http\Livewire\Customer\Contacts;

use App\Models\CustomerPhone;
use Livewire\Component;

class CustomerPhoneTd extends Component
{
    /* @var CustomerPhone|null $customer */
    public $customerPhone;

    public $phone;

    public $showDeleteModal = false;

    /* @var array $listeners */
    protected $listeners = [
        'newDefaultPhoneChosen' => 'newDefaultPhoneChosen',
    ];

    public function mount()
    {
        $this->phone = $this->customerPhone->phone;
    }

    public function render()
    {
        return view('livewire.customer.contacts.customer-phone-td');
    }

    public function showDeleteModal()
    {
        $this->showDeleteModal = true;
    }

    public function updatePhone()
    {
        $this->customerPhone->phone = $this->phone;
        $this->customerPhone->save();
        $this->customerPhone->refresh();

        // dispatch global event to show notification system wide
        $this->dispatchBrowserEvent('notification', ['type' => 'success', 'text' => 'Kontakt broj ažuriran!']);
    }

    public function deletePhone()
    {
        if (!$this->customerPhone->is_default) {

            $this->customerPhone->delete();
            $this->customerPhone->refresh();

            // close modal
            $this->showDeleteModal = false;

            // reload parent component
            $this->emit('customerContactsUpdated');

            // dispatch global event to show notification system wide
            $this->dispatchBrowserEvent('notification', ['type' => 'success', 'text' => 'Kontakt broj obrisan!']);

        } else {

            $this->showDeleteModal = false;

            $this->dispatchBrowserEvent('notification', ['type' => 'error', 'text' => 'Defaultni ili jedini broj customera nije moguće obrisati!']);
        }
    }

    public function makePhonePrimary()
    {
        CustomerPhone::where('customer_id', $this->customerPhone->customer_id)
                                   ->where('is_default', true)
                                   ->update(['is_default' => false]);

        $this->customerPhone->is_default = true;
        $this->customerPhone->save();

        // reload all email components
        $this->emit('newDefaultPhoneChosen');

        // dispatch global event to show notification system wide
        $this->dispatchBrowserEvent('notification', ['type' => 'success', 'text' => 'Kontakt postavljen primarnim!']);
    }

    public function newDefaultPhoneChosen()
    {
        $this->customerPhone->refresh();
    }
}
