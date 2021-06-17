<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Show Support Ticket') }}
        </h2>
    </x-slot>

    <div>
        <livewire:show-support-ticket :ticket="$ticket"/>
    </div>

</x-app-layout>



