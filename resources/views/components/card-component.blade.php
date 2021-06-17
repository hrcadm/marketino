
    <x-app-layout>

        <x-slot name="header">
            {{ $header }}
        </x-slot>

        <div class="px-4 py-5">
            <!--
            Tailwind UI components require Tailwind CSS v1.8 and the @tailwindcss/ui plugin.
            Read the documentation to get started: https://tailwindui.com/documentation
            -->
                {{ $tabs }}

            {{ $slot }}
        </div>

        {{--    <div id="details" style="display: block">--}}
        {{--        <livewire:show-customer :customer="$customer"/>--}}
        {{--    </div>--}}

    </x-app-layout>


