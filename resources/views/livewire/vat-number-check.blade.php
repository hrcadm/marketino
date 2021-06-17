<div class="sm:col-span-3">

    <style>
        .livewire_input {
            border: 2px solid red;
            border-radius: 4px;
        }
        .lw-alert {
            display: none;
        }

        input:focus + .lw-alert {
            display: block;
        }
    </style>

{{--    @php $color = "#d2d6dc"--}}
{{--    @endphp--}}
{{--    @if($customers)--}}
{{--        @php $color = "red"--}}
{{--        @endphp--}}
{{--    @endif--}}

    @php $style = ""
    @endphp
    @if($customers)
        @php $style = "border: 1px solid red"
        @endphp
    @endif

    <x-jet-label value="{{ __('Vat number') }}"></x-jet-label>

    <x-jet-input
        type="text"
        name="vat_number"
        wire:model="vatNumberCheck"
        class="block mt-1 w-full"
{{--        style="border: 1px solid {{$color}}"--}}
        style="{{$style}}"
    ></x-jet-input>

    {{--<input wire:model="vatNumberCheck" type="text" class="form-control">--}}




    @if($customers)
        <div id="alert" class="text-center bg-blue-100 border-t border-b border-blue-500 text-blue-700 px-4 py-3 lw-alert"
             role="alert">
            <p class="font-weight-bold">Customer with vat number {{$vatNumberCheck}} exists</p>

            @foreach($customers as $customer)
                <span>{{$customer['company_name']}}</span><br>
            @endforeach
        </div>
    @endif
</div>
