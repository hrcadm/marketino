<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Inbox') }}
        </h2>
    </x-slot>

    <x-slot name="actions">
        <div class="mt-4 flex md:mt-0 md:ml-4">
            <span class="ml-3 shadow-sm rounded-md">
                <a href="{{ route('tickets.create') }}"
                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:shadow-outline-indigo focus:border-indigo-700 active:bg-indigo-700 transition duration-150 ease-in-out">
                    Create new ticket
                </a>
            </span>
        </div>
    </x-slot>

    <div>
        <livewire:support-tickets-list :tickets="$tickets"/>
    </div>

</x-app-layout>



