@push('styles')
    <style>
        .navitem-customer {
            margin-left: 1.5rem !important;
        }
    </style>
@endpush
<x-card-component>

    <x-slot name="header">
        {{ $header }}
    </x-slot>

    <x-slot name="tabs">
        <div class="border-b border-gray-200">
            <nav class="card-tabs -mb-px flex" x-data="{ tab: '{{ $tab }}' }">
                <a class="navitem-customer" href="{{ route('customers.show', $customer)}}"
                   :class="{ 'active' : tab == 'details' }"
                   aria-current="page">
                    @lang('Details')
                </a>
                <a class="navitem-customer" href="{{ route('customers.show', ['customer' => $customer, 'tab' => 'quotes'])}}"
                   :class="{ 'active' : tab == 'quotes' }">
                    @lang('Quotes')

                </a>
                <a class="navitem-customer" href="{{ route('customers.show', ['customer' => $customer, 'tab' => 'next-step'])}}"
                   :class="{ 'active' : tab == 'offers' }">
                    @lang('Next Step')

                </a>
                <a class="navitem-customer" href="#"
                    :class="{ 'active' : tab == 'debtStatus' }">
                    @lang('Debt status')
                </a>
{{--                href="{{ route('customers.show', ['customer' => $customer, 'tab' => 'promotions'])}}"--}}
                <a class="navitem-customer" href="#"
                   :class="{ 'active' : tab == 'packets' }">
                    @lang('Packets & Promotions')
                </a>
                <a class="navitem-customer" href="#"
                   :class="{ 'active' : tab == 'invoices' }">
                    @lang('Invoices')
                </a>
                <a class="navitem-customer" href="#"
                   :class="{ 'active' : tab == 'tickets' }">
                    @lang('Tickets')
                </a>
                <a class="navitem-customer" href="#"
                   :class="{ 'active' : tab == 'customerLogs' }">
                    @lang('Customer Logs')
                </a>
                <a class="navitem-customer" href="#"
                   :class="{ 'active' : tab == 'reports' }">
                    @lang('Reports')
                </a>
                <a class="navitem-customer" href="{{ route('customers.show', ['customer' => $customer, 'tab' => 'contacts'])}}"
                   :class="{ 'active' : tab == 'contacts' }">
                    @lang('Contacts')
                </a>
            </nav>
        </div>
    </x-slot>

    {{ $slot }}

</x-card-component>


