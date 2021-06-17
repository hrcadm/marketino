<x-show-customer-component :customer="$customer">

    <x-slot name="header">
        {{ __('Customer details') }}

        <script>
            window.onload = function () {
                $('#new-quote').click(function () {
                    //  var inputValue = $(this).attr("value");
                    $("#quote-form").toggle();
                });

                setTimeout(function () {
                    $("#alert").remove();
                }, 3000);
            };



        </script>
    </x-slot>

    <livewire:customer.quotes.quotes-list :customer="$customer"/>

</x-show-customer-component>
