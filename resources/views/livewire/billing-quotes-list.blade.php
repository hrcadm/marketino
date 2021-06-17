@push('styles')
    <style>
        #filtersubmit {
            position: relative;
            z-index: 1;
            left: -25px;
            top: 1px;
            color: #7B7B7B;
            cursor: pointer;
            width: 0;
        }
    </style>
@endpush
<div>
    <div class="my-4">
        <div class="inline-block m-4">
            <label class="block">Tip dokumenta</label>
            <select class="w-100  h-10 bg-white rounded-md transition ease-in-out duration-150 border-2" wire:model="document">
                <option value="svi">Svi</option>
                <option value="{{ \App\Enum\QuoteStatus::ACTIVE }}">Ponuda</option>
                <option value="{{ \App\Enum\QuoteStatus::PAID }}">Račun</option>
                <option value="{{ \App\Enum\QuoteStatus::INACTIVE }}">Nerealizirane ponude</option>
            </select>
        </div>
        <div class="inline-block m-3">
            <label class="block">Datum</label>
            <input class="w-auto h-10 bg-white rounded-md transition ease-in-out duration-150 border-2" type="search" id="quotesDatePicker" wire:model="date" placeholder="Odaberite datum" readonly>
            @if($date)
                <i id="filtersubmit" class="fa fa-times" wire:click="clearDate"></i>
            @endif
        </div>
        <div class="inline-block m-3">
            <label class="block">Agent</label>
            <select class="w-100 h-10 bg-white rounded-md transition ease-in-out duration-150 border-2" wire:model="agent">
                <option value="">Svi</option>
                @foreach($agents as $id => $name)
                    <option value="{{ $id }}">{{ $name }}</option>
                @endforeach
            </select>
        </div>
        <div class="inline-block m-3">
            <label class="block">Paket</label>
            <select class="w-100 h-10 bg-white rounded-md transition ease-in-out duration-150 border-2" wire:model="package">
                <option value="">Svi</option>
                @foreach($packages as $package)
                    @if($package->discount_amount !== null && $package->name !== 'RATA 1 - Marketino ONE')
                        <option value="{{ $package->id }}">{{ $package->name }} (Sconto)</option>
                    @elseif($package->name === 'RATA 1 - Marketino ONE' && $package->discuont_amount === null)
                        <option value="{{ $package->id }}">Marketino Rateizzazione</option>
                    @elseif($package->name === 'RATA 1 - Marketino ONE' && $package->discuont_amount !== null)
                        <option value="{{ $package->id }}">Marketino Rateizzazione (Sconto)</option>
                    @else
                        <option value="{{ $package->id }}">{{ $package->name }} (Sconto)</option>
                    @endif
                @endforeach
            </select>
        </div>
        <div class="inline-block m-3">
            <label class="block">Država</label>
            <select class="w-100 h-10 bg-white rounded-md transition ease-in-out duration-150 border-2" disabled>
                <option value="">Italija</option>
            </select>
        </div>
        <div class="inline-block m-3">
            <label class="block">Način plaćanja</label>
            <select class="w-100 h-10 bg-white rounded-md transition ease-in-out duration-150 border-2" wire:model="payment">
                <option value="">Svi</option>
                <option value="jednokratno">Jednokratno</option>
                <option value="rate">Obročno</option>
            </select>
        </div>
    </div>


    <div class="m-4">
        <div class="inline-block focus-within:z-10">
            <div class="absolute mt-3 ml-2 flex items-center pointer-events-none">
                <svg class="mr-3 h-4 w-4 text-gray-400" x-description="Heroicon name: search" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
                </svg>
            </div>

            <input
                type="text"
                name="search"
                id="search_quotes"
                class="form-input block w-100 pl-9 rounded-md transition ease-in-out duration-150 sm:text-sm sm:leading-5"
                placeholder="Search quote"
                wire:model.debounce.250ms="searchTerm"
            />
        </div>
        <div class="inline-block float-right">
                <span class="ml-3 shadow-sm rounded-md">
                    <button disabled
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-indigo-400 hover:bg-indigo-400 focus:outline-none focus:shadow-outline-indigo focus:border-indigo-700 active:bg-indigo-700 transition duration-150 ease-in-out">
                        Export
                    </button>
                </span>
        </div>
    </div>

    <table class="table-active m-2 text-center ">
        <tr class="bg-gray-200">
            <th class="p-4">Status</th>
            <th class="p-4">Broj dokumenta</th>
            <th class="p-4">Klijent</th>
            <th class="p-4">Paketi</th>
            <th class="p-4">Način plaćanja</th>
            <th class="p-4">Vrijeme izdavanja</th>
            <th class="p-4">Netto</th>
            <th class="p-4">PDV</th>
            <th class="p-4">Ukupno</th>
            <th class="p-4">Ponudu izradio</th>
            <th class="p-4">Opcije</th>
        </tr>

        @if($quotes->count() > 0)
            @foreach($quotes as $quote)
                <livewire:billing.quotes.quote-td :quote="$quote" :id="$loop->index" key="{{ $quote->id }}"/>
            @endforeach

        @else
            <tr>
                <td colspan="11" class="text-center">Nema podataka</td>
            </tr>
        @endif
    </table>
    @if($quotes->count() > 0)
        <div class="ml-3 mr-3 mb-3">
            <span>{{ $quotes->links() }}</span>
        </div>
    @endif
</div>

@push('scripts')
<script>
    flatpickr("#quotesDatePicker", {
        mode: "range",
        dateFormat: "d.m.Y",
        maxDate: "today",
    });
</script>
@endpush
