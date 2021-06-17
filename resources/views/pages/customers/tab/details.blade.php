
<x-show-customer-component :customer="$customer" :deliveryAddress="$deliveryAddress">

    <x-slot name="header">
        {{ __('Customer details') }}
    </x-slot>

        <livewire:show-customer :customer="$customer" :deliveryAddress="$deliveryAddress"/>

</x-show-customer-component>
