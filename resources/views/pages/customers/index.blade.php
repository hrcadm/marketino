<x-app-layout>
    <x-slot name="header">
        {{ __('Customers') }}
    </x-slot>

    <x-slot name="actions">
        <div class="mt-4 flex md:mt-0 md:ml-4">
            <span class="ml-3 shadow-sm rounded-md">
                <a href="{{ route('customers.create') }}"
                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:shadow-outline-indigo focus:border-indigo-700 active:bg-indigo-700 transition duration-150 ease-in-out">
                    Create new customer
                </a>
            </span>
        </div>
    </x-slot>

{{--     @livewireAssets--}}


    @livewire('customers-list')


    {{-- Pagination    --}}


</x-app-layout>

