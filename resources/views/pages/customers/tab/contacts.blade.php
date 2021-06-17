<x-show-customer-component :customer="$customer" :deliveryAddress="$deliveryAddress">

    <x-slot name="header">
        {{ __('Customer details') }}

    </x-slot>

    <livewire:customer.contacts.customer-contacts :customer="$customer"/>

</x-show-customer-component>>
