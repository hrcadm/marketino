<tr>
    <td class="p-4 flex-fill items-center justify-center">
        @if($quote->status->is(\App\Enum\QuoteStatus::PAID))
            <svg class="w-6 h-6 inline-block align-middle" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
        @elseif($quote->status->is(\App\Enum\QuoteStatus::ACTIVE) && Carbon\Carbon::parse($quote->created_at)->diffInDays(\Carbon\Carbon::now()) < 7)
            <svg class="w-6 h-6 inline-block align-middle" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M8 5a1 1 0 100 2h5.586l-1.293 1.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L13.586 5H8zM12 15a1 1 0 100-2H6.414l1.293-1.293a1 1 0 10-1.414-1.414l-3 3a1 1 0 000 1.414l3 3a1 1 0 001.414-1.414L6.414 15H12z"></path></svg>
        @else
            <svg class="w-6 h-6 inline-block align-middle" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
        @endif
    </td>
    <td class="p-4">
        <a href="{{ route('downloadPdf', ['id' => $quote->id]) }}" class="no-underline hover:underline text-blue-500 text-lg">
            {{$quote->document_number}}
        </a>
    </td>
    <td class="p-4">
        @if($quote->customer)
            <a href="{{route('customers.show', $quote->customer->id)}}" class="no-underline hover:underline text-blue-500 text-lg">
                {{ $quote->customer->getFullNameAttribute() }}
            </a>
        @endif
    </td>
    <td class="p-4">
        <button wire:click="$toggle('packagesModal')" class="bg-transparent border border-gray-500 hover:border-indigo-500 text-gray-500 hover:text-indigo-500 font-bold py-2 px-4 rounded-full">Prikaži</button>
    </td>
    <td class="p-4">
        {{ $quote->getPaymentInfo() }}
    </td>
    <td class="p-4">{{ $quote->created_at }}</td>
    <td class="p-4">{{ $quote->total_net_amount }}</td>
    <td class="p-4">{{ $quote->total_vat_amount }}</td>
    <td class="p-4">{{ $quote->total_amount }}</td>
    <td class="p-4">
        @if($quote->user)
            {{ $quote->user->name }}
        @else
            <div class="text-xs inline-flex items-center font-bold leading-sm uppercase px-3 py-1 bg-blue-200 text-blue-700 rounded-full">
                Web
            </div>
        @endif
    </td>
    <td class="p-4 flex-fill items-center justify-center">
        @if (!is_null($quote->customer->phone()) && $quote->customer->phone()->phone !== null)
            <a href="tel:{{ $quote->customer->phone()->phone }}" class="no-underline hover:underline text-blue-500 inline-block align-middle">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 3h5m0 0v5m0-5l-6 6M5 3a2 2 0 00-2 2v1c0 8.284 6.716 15 15 15h1a2 2 0 002-2v-3.28a1 1 0 00-.684-.948l-4.493-1.498a1 1 0 00-1.21.502l-1.13 2.257a11.042 11.042 0 01-5.516-5.517l2.257-1.128a1 1 0 00.502-1.21L9.228 3.683A1 1 0 008.279 3H5z"></path></svg>
            </a>
        @endif
        <i class="fas fa-link {{ $quote->invoice ? 'text-green-400' : 'text-blue-500'  }} cursor-pointer" wire:click="$toggle('paymentModal')"></i>
    </td>
    <td>
        <!--Paketi Modal-->
        <x-jet-confirmation-modal wire:model="packagesModal">

            <x-slot name="title">
                {{ __('Paketi') }}
            </x-slot>

            <x-slot name="content">
                <table class="table w-full">
                    <thead>
                        <tr>
                            <th>Naziv</th>
                            <th>Količina</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if(!empty($quote->items))
                        @foreach($quote->items as $item)
                            @if($item->saleItemPrice)
                            <tr>
                                <td> @if($item->saleItemPrice->discount_amount === null)
                                        {{ $item->saleItemPrice->name }}
                                    @else
                                        {{ $item->saleItemPrice->name }} (Sconto)
                                    @endif
                                </td>
                                <td>
                                    {{ $item->quantity }}
                                </td>
                            </tr>
                            @endif
                        @endforeach
                    @else
                        <tr>
                            <td colspan="2">No Items</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </x-slot>

            <x-slot name="footer">
                <x-jet-secondary-button wire:click="$toggle('packagesModal')" wire:loading.attr="disabled">
                    {{ __('Zatvori') }}
                </x-jet-secondary-button>
            </x-slot>

        </x-jet-confirmation-modal>

        <form wire:submit.prevent="updatePayment" wire:ignore.self>
            @csrf
            <x-jet-confirmation-modal wire:model="paymentModal">

                <x-slot name="title">
                    {{ __('Poveži uplatu') }}
                </x-slot>

                <x-slot name="content">
                    <x-jet-label>CRO</x-jet-label>
                    <x-jet-input wire:model="cro"
                                 class="block mt-1 w-full" type="text" name="cro"
                                 autofocus></x-jet-input>

                    <x-jet-label>Broj računa</x-jet-label>
                    <x-jet-input wire:model="invoiceNo"
                                 class="block mt-1 w-full" type="text" name="invoiceNo"
                                 required autofocus></x-jet-input>
                </x-slot>

                <x-slot name="footer">
                    <x-jet-secondary-button wire:click="$toggle('paymentModal')" wire:loading.attr="disabled">
                        {{ __('Odustani') }}
                    </x-jet-secondary-button>
                    <input type="submit"
                           class="ml-2 inline-flex items-center justify-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 focus:outline-none focus:border-green-700 focus:shadow-outline-green active:bg-green-600 transition ease-in-out duration-150'"
                           value="{{ __('Ažuriraj') }}"/>
                </x-slot>

            </x-jet-confirmation-modal>
        </form>
    </td>
</tr>
