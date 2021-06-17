<tr class="bg-white">
    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 font-medium text-gray-900">
        <a href="{{ route('downloadPdf', ['id' =>$quote->id]) }}"
           class="no-underline hover:underline text-blue-500 text-lg">
            {{$quote->document_number}}</a>
    </td>
    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-500">
        {{$quote->total_amount}} €
    </td>
    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-500">
        {{ $quote->note }}
    </td>
    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-500">
        {{$quote->created_at}}
    </td>
    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-500">
        <a href="{{ route('sendToEmail', ['id' =>$quote->id]) }}"
           class="no-underline hover:underline text-blue-500 text-lg hint--top-right" aria-label="Pošalji e-mail">
            <i class="fas fa-envelope"></i>
        </a>
        <span class="hint--top-right" aria-label="Poveži uplatu">
            <i class="fas fa-link {{ $quote->invoice ? 'text-green-400' : 'text-blue-500'  }} cursor-pointer" wire:click="$toggle('paymentModal')"></i>
        </span>
    </td>
    <td class="px-6 py-4 whitespace-no-wrap text-right text-sm leading-5 font-medium">
        {{$quote->user->name ?? ''}}
    </td>
    <td>
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
                                 :value="old('cro')" autofocus></x-jet-input>

                    <x-jet-label>Broj računa</x-jet-label>
                    <x-jet-input wire:model="invoiceNo"
                                 class="block mt-1 w-full" type="text" name="invoiceNo"
                                 :value="old('invoiceNo')" required autofocus></x-jet-input>
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
