<div>
    <button id="new-quote" class="m-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
        New quote
    </button>

    <a href="{{ route('sendGrenkeEmail',['customer' => $customer, 'customer_email' => $customer->email()->email ?? null])}}"
       class="m-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
        Send Grenke email</a>

    @if ($errors->any())
        <div id="alert"
             class="mb-2 text-center bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
             role="alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li><span style="color: #dc3545" class="alert-danger uppercase">{{ $error }}</span></li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session()->has('message'))
        <div id="alert" class="text-center bg-blue-100 border-t border-b border-blue-500 text-blue-700 px-4 py-3"
             role="alert">
            <span>{{ session()->get('message') }}</span>
        </div>
    @endif

    <div class="bg-gray-100 rounded p-3 hidden" id="quote-form">
        {{--            <form class="my-6" role="form" method="POST" action="{{ route('customers.update',$customer->id) }}">--}}
        <form class="my-6" role="form" method="POST" action="{{ route('newQuote',['customer_id'=>$customer->id]) }}">
            {{ csrf_field() }}

            <div class="field">
                <div class="text-lg mb-4 bg-gray-300">Marketino one</div>

                <div class="md:flex mt-3 md:items-center  w-2/5">
                    <div class="pb-2">
                        <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4" for="m1_promo">
                            - Marketino Promo
                        </label>
                    </div>
                    <div class="mr-0 ml-auto mb-2">
                        <input
                            placeholder="Quantity"
                            class="w-3/5 bg-blue-100 appearance-none border-2 border-gray-200 rounded  py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500"
                            id="m1_promo"
                            name="marketinoOne[{{constant('App\Models\SaleItemPrice::M1_PROMO')}}]"
                            type="number"
                            value=""
                        >
                    </div>
                </div>
                <div class="md:flex mt-3 md:items-center  w-3/5">
                    <div class="pb-2">
                        <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4" for="m1_promo">
                            - Marketino Limited Promo
                        </label>
                    </div>
                    <div class="mr-0 ml-auto mb-2">
                        <input
                            placeholder="Quantity"
                            class="w-3/5 bg-blue-100 appearance-none border-2 border-gray-200 rounded  py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500"
                            id="m1_promo"
                            name="marketinoOneLimited[{{constant('App\Models\SaleItemPrice::M1_LIMITED_PROMO')}}]"
                            type="number"
                            value=""
                        >
                    </div>
                </div>
                <div class="md:flex md:items-center  w-2/5">
                    <div>
                        <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4" for="m1_finale">
                            - Marketino Finale
                        </label>
                    </div>
                    <div class="mr-0 ml-auto mb-2">
                        <input
                            placeholder="Quantity"
                            class="w-3/5 bg-blue-100 appearance-none border-2 border-gray-200 rounded  py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500"
                            id="m1_finale"
                            name="marketinoOne[{{constant('App\Models\SaleItemPrice::M1_FINALE')}}]"
                            type="number"
                            value=""
                        >
                    </div>
                </div>
                <div class="md:flex md:items-center  w-2/5">
                    <div>
                        <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4" for="m1_rotam">
                            - Rotamazione offere
                        </label>
                    </div>
                    <div class="mr-0 ml-auto mb-2">
                        <input
                            placeholder="Quantity"
                            class="w-3/5 bg-blue-100 appearance-none border-2 border-gray-200 rounded  py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500"
                            id="m1_rotam"
                            name="marketinoOne[{{constant('App\Models\SaleItemPrice::M1_ROTAM')}}]"
                            type="number"
                            value=""
                        >
                    </div>
                </div>

                <div class="mb-6 border-b-2">
                    <span class="text-gray-700">Select Package</span>
                    <div class="mt-2">
                        <label class="inline-flex items-center">
                            <input type="radio" class="form-radio" name="marketinoOneNote"
                                   value="{{constant('App\Enum\QuotePackageDescription::M_PRIME_28022021')}}" checked>
                            <span class="ml-2">{{constant('App\Enum\QuotePackageDescription::M_PRIME_28022021')}}</span>
                        </label>
                        <label class="inline-flex items-center ml-6">
                            <input type="radio" class="form-radio" name="marketinoOneNote"
                                   value="{{constant('App\Enum\QuotePackageDescription::M_HNB_28022021')}}">
                            <span class="ml-2">{{constant('App\Enum\QuotePackageDescription::M_HNB_28022021')}}</span>
                        </label>
                    </div>
                </div>

                <div class="text-lg mb-4 bg-gray-300">Marketino one - Rateizzazione</div>

                <div class="md:flex md:items-center w-2/5">
                    <div>
                        <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4" for="m1_rate">
                            - Rateizzazione
                        </label>
                    </div>
                    <div class="mr-0 ml-auto mb-2">
                        <input
                            placeholder="Quantity"
                            class="w-3/5 bg-blue-100 appearance-none border-2 border-gray-200 rounded  py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500"
                            id="m1_rate"
                            name="marketinoOneRate[{{constant('App\Models\SaleItemPrice::M1_RATE')}}]"
                            type="number"
                            value=""
                        >
                    </div>
                </div>

                <div class="mb-6 border-b-2">
                    <span class="text-gray-700">Select Package</span>
                    <div class="mt-2">
                        <label class="inline-flex items-center">
                            <input type="radio" class="form-radio" name="marketinoOneRateNote"
                                   value="{{constant('App\Enum\QuotePackageDescription::M_PRIME')}}" checked>
                            <span class="ml-2">{{constant('App\Enum\QuotePackageDescription::M_PRIME')}}</span>
                        </label>
                        <label class="inline-flex items-center ml-6">
                            <input type="radio" class="form-radio" name="marketinoOneRateNote"
                                   value="{{constant('App\Enum\QuotePackageDescription::M_HNB')}}">
                            <span class="ml-2">{{constant('App\Enum\QuotePackageDescription::M_HNB')}}</span>
                        </label>
                    </div>
                </div>

                <div class="text-lg mb-4 bg-gray-300">Card reader</div>
                <div class="md:flex md:items-center mb-md-3 mb-6 w-2/5">
                    <div class="mt-4">
                        <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4" for="cr_promo">
                            - Promo
                        </label>
                    </div>
                    <div class="mr-0 ml-auto">
                        <input
                            placeholder="Quantity"
                            class="w-3/5 bg-blue-100 appearance-none border-2 border-gray-200 rounded  py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500"
                            id="cr_promo"
                            name="cardReader[{{constant('App\Models\SaleItemPrice::CR_PROMO')}}]"
                            type="number"
                            value=""
                        >
                    </div>
                </div>
                <div class="mb-6 border-b-2">
                    <span class="text-gray-700">Select Package</span>
                    <div class="mt-2">
                        <label class="inline-flex items-center">
                            <input type="radio" class="form-radio" name="cardReaderNote"
                                   value="{{constant('App\Enum\QuotePackageDescription::CR_STANDARD')}}" checked>
                            <span class="ml-2">{{constant('App\Enum\QuotePackageDescription::CR_STANDARD')}}</span>
                        </label>
                        <label class="inline-flex items-center ml-6">
                            <input type="radio" class="form-radio" name="cardReaderNote"
                                   value="{{constant('App\Enum\QuotePackageDescription::CR_GOLD')}}">
                            <span class="ml-2">{{constant('App\Enum\QuotePackageDescription::CR_GOLD')}}</span>
                        </label>
                        <label class="inline-flex items-center ml-6">
                            <input type="radio" class="form-radio" name="cardReaderNote"
                                   value="{{constant('App\Enum\QuotePackageDescription::CR_PLATINUM')}}">
                            <span class="ml-2">{{constant('App\Enum\QuotePackageDescription::CR_PLATINUM')}}</span>
                        </label>
                    </div>
                </div>

                <div class="text-lg mb-4 bg-gray-300">Other</div>

                <div class="md:flex md:items-center mb-6 w-2/5">
                    <div>
                        <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4" for="shipment">
                            Shipping
                        </label>
                    </div>
                    <div class="flex items-center h-5">
                        <input
                            id="shipment"
                            type="checkbox"
                            name="shipment[{{constant('App\Models\SaleItemPrice::SHIPMENT')}}]"
                            class="form-checkbox h-4 w-4 text-indigo-600 transition duration-150 ease-in-out"
                            value="1">
                    </div>
                </div>

                <div class="md:flex md:items-center mb-6 border-b-2 w-2/5">
                    <div class="mb-4">
                        <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4" for="setup">
                            Setup
                        </label>
                    </div>
                    <div class="flex items-center h-5 ml-6 mb-4">
                        <input checked disabled readonly
                               id="setup"
                               type="checkbox"
                               name="just_for_display"
                               class="form-checkbox h-4 w-4 text-indigo-600 transition duration-150 ease-in-out"
                               value="1">
                        <input type="hidden" name="setup[{{constant('App\Models\SaleItemPrice::SETUP_PROMO')}}]"
                               value="1"/>
                    </div>
                </div>

                <button id="makeQuote" class="m-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Make
                </button>
            </div>

        </form>
    </div>

    <div class="flex flex-col mt-2">
        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Broj dokumenta</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Iznos</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">{{ __('Note') }}</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Datum</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Opcije</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider text-right">Agent</th>
                            </tr>
                        </thead>

                        <tbody>
                        @foreach($customer->quotes as $quote)
                            <livewire:customer.quotes.quote-td :quote="$quote" :id="$loop->index" key="{{ $quote->id }}"/>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
