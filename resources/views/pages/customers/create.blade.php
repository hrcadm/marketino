<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Customer') }}
        </h2>


        <script>
            window.onload = function () {
                $('#da_checkbox').click(function () {
                    //  var inputValue = $(this).attr("value");
                    $("#da_div").toggle();
                });

                $('#grenkeCheckbox').click(function () {
                    //  var inputValue = $(this).attr("value");
                    $("#grenkeDiv").toggle();
                });
            };
        </script>

    </x-slot>
    {{--todo::proučiti x-app-layout styles & classes--}}
    <link rel="stylesheet"
          href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css"
          integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS"
          crossorigin="anonymous">

    @if ($errors->any())
        <div class="alert alert-danger text-center col-sm-8  form-group-sm col-lg-12" id="error_msg">
            <ul>
                @foreach ($errors->all() as $error)
                    <li><span>{{ $error }}</span></li>
                @endforeach
            </ul>
        </div>
    @endif


    <div class="px-4 py-5">
        <form method="post" action="{{ route('customers.store') }}">
            @csrf
            <div>
                <div>
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            Company information
                        </h3>
                        <p class="mt-1 text-sm leading-5 text-gray-500">
                            Fill in basic details needed for billing.
                        </p>
                    </div>

                    <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <div class="sm:col-span-3">
                            <label for="country" class="block text-sm font-medium leading-5 text-gray-700">
                                {{ __('Status') }}
                            </label>
                            <div class="mt-1 rounded-md shadow-sm">
                                <select id="customer_status" name="customer_status"
                                        class="form-select block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                                    @foreach(\App\Enum\ClientStatus::asSelectArray() as $value => $label)
                                        <option value="{{ $value }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="sm:col-span-3">
                            <label for="sale_channel" class="block text-sm font-medium leading-5 text-gray-700">
                                {{ __('Sale channel') }}
                            </label>
                            <div class="mt-1 rounded-md shadow-sm">
                                <select id="sale_channel" name="sale_channel"
                                        class="form-select block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                                    @foreach(\App\Enum\SaleChannel::asSelectArray() as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    {{--s2--}}
                    <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <div class="sm:col-span-3">
                            <x-jet-label value="{{ __('First name') }}"></x-jet-label>
                            <x-jet-input class="block mt-1 w-full" type="text" name="first_name"
                                         :value="old('first_name')" required autofocus></x-jet-input>
                        </div>
                        <div class="sm:col-span-3">
                            <x-jet-label value="{{ __('Last name') }}"></x-jet-label>
                            <x-jet-input class="block mt-1 w-full" type="text" name="last_name"
                                         :value="old('last_name')" required autofocus></x-jet-input>
                        </div>
                    </div>
                    {{--s3--}}


                    <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">

                        <div class="sm:col-span-2">
                            <x-jet-label value="{{ __('Address') }}"></x-jet-label>
                            <x-jet-input class="block mt-1 w-full" type="text" name="address"
                                         :value="old('address')"></x-jet-input>
                        </div>
                        <div class="sm:col-span-2">
                            <x-jet-label value="{{ __('Additional Address') }}"></x-jet-label>
                            <x-jet-input class="block mt-1 w-full" type="text" name="address_additional"
                                         :value="old('address_additional')"></x-jet-input>
                        </div>

                        <div class="sm:col-span-2">
                            <label for="country" class="block text-sm font-medium leading-5 text-gray-700">
                                {{ __('Country') }}
                            </label>
                            <div class="mt-1 rounded-md shadow-sm">
                                <select id="country" name="country_code"
                                        class="form-select block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                                    @foreach(\App\Enum\CountryCode::asSelectArray() as $value => $label)
                                        <option value="{{ $value }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    {{--s4--}}

                    <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <div class="sm:col-span-2">
                            <x-jet-label value="{{ __('ZIP') }}"></x-jet-label>
                            <x-jet-input class="block mt-1 w-full" type="text" name="zip"
                                         :value="old('zip')"></x-jet-input>
                        </div>
                        <div class="sm:col-span-2">
                            <x-jet-label value="{{ __('City') }}"></x-jet-label>
                            <x-jet-input class="block mt-1 w-full" type="text" name="city"
                                         :value="old('city')"></x-jet-input>
                        </div>
                        <div class="sm:col-span-2">
                            <x-jet-label value="{{ __('Region') }}"></x-jet-label>
                            <x-jet-input class="block mt-1 w-full" type="text" name="region"
                                         :value="old('region')"></x-jet-input>
                        </div>
                    </div>


                    {{--  delivery address  --}}
                    <div class="mt-4 border-t border-gray-200"></div>
                    <div class="mt-4">
                        <div class="relative flex items-start">
                            <div class="flex items-center h-5">
                                {{--                                                                    <input type="hidden" name="param" value="0">
                                --}}
                                <input type="checkbox"
                                       name="deliveryAddress"
                                       class="form-checkbox h-4 w-4 text-indigo-600 transition duration-150 ease-in-out"
                                       id="da_checkbox"
                                >
                            </div>
                            <div class="ml-3 text-sm leading-5">
                                <label for="comments" class="font-medium text-gray-700">Delivery address</label>
                            </div>
                        </div>
                    </div>
                    <div id="da_div" style="background:lightblue; display: none" class="p-6">

                        <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                            <div class="sm:col-span-2">
                                <x-jet-label value="{{ __('Delivery First name') }}"></x-jet-label>
                                <x-jet-input class="block mt-1 w-full" type="text" name="da_first_name"
                                             :value="old('da_first_name')"></x-jet-input>
                            </div>
                            <div class="sm:col-span-2">
                                <x-jet-label value="{{ __('Delivery Last name') }}"></x-jet-label>
                                <x-jet-input class="block mt-1 w-full" type="text" name="da_last_name"
                                             :value="old('da_last_name')"></x-jet-input>
                            </div>
                            <div class="sm:col-span-2">
                                <x-jet-label value="{{ __('Delivery Phone number') }}"></x-jet-label>
                                <x-jet-input class="block mt-1 w-full" type="text" name="da_phone"
                                             :value="old('da_phone')"></x-jet-input>
                            </div>
                        </div>

                        <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">

                            <div class="sm:col-span-2">
                                <x-jet-label value="{{ __('Delivery Address') }}"></x-jet-label>
                                <x-jet-input class="block mt-1 w-full" type="text" name="da_address"
                                             :value="old('da_address')"></x-jet-input>
                            </div>
                            <div class="sm:col-span-2">
                                <x-jet-label value="{{ __('Delivery Address Additional ') }}"></x-jet-label>
                                <x-jet-input class="block mt-1 w-full" type="text" name="da_address_additional"
                                             :value="old('da_address_additional')"></x-jet-input>
                            </div>

                            <div class="sm:col-span-2">
                                <label for="da_country" class="block text-sm font-medium leading-5 text-gray-700">
                                    {{ __('Delivery Country') }}
                                </label>
                                <div class="mt-1 rounded-md shadow-sm">
                                    <select id="da_country" name="da_country_code"
                                            class="form-select block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                                        @foreach(\App\Enum\CountryCode::asSelectArray() as $value => $label)
                                            <option value="{{ $value }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                            <div class="sm:col-span-2">
                                <x-jet-label value="{{ __('Delivery ZIP') }}"></x-jet-label>
                                <x-jet-input class="block mt-1 w-full" type="text" name="da_zip"
                                             :value="old('da_zip')"></x-jet-input>
                            </div>
                            <div class="sm:col-span-2">
                                <x-jet-label value="{{ __('Delivery City') }}"></x-jet-label>
                                <x-jet-input class="block mt-1 w-full" type="text" name="da_city"
                                             :value="old('da_city')"></x-jet-input>
                            </div>
                            <div class="sm:col-span-2">
                                <x-jet-label value="{{ __('Delivery Region') }}"></x-jet-label>
                                <x-jet-input class="block mt-1 w-full" type="text" name="da_region"
                                             :value="old('da_region')"></x-jet-input>
                            </div>
                        </div>

                        <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                            <div class="sm:col-span-3">
                                <x-jet-label value="{{ __('Delivery Company name') }}"></x-jet-label>
                                <x-jet-input class="block mt-1 w-full" type="text" name="da_company_name"
                                             :value="old('da_company_name')"></x-jet-input>
                            </div>
                            <div class="sm:col-span-3">
                                <x-jet-label value="{{ __('Delivery Notes') }}"></x-jet-label>
                                <x-jet-input class="block mt-1 w-full" type="text" name="da_note"
                                             :value="old('da_note')"></x-jet-input>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 border-t border-gray-200"></div>
                    {{--delivery address end --}}

                    <div class="mt-8 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <div class="sm:col-span-3">
                            <x-jet-label value="{{ __('Company name') }}"></x-jet-label>
                            <x-jet-input class="block mt-1 w-full" type="text" name="company_name"
                                         :value="old('company_name')"></x-jet-input>
                        </div>
                        <div class="sm:col-span-3">
                            <label for="country" class="block text-sm font-medium leading-5 text-gray-700">
                                {{ __('Activity') }}
                            </label>
                            <div class="mt-1 rounded-md shadow-sm">
                                <select id="country" name="activity_type_id"
                                        class="form-select block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                                    @foreach(App\Models\ActivityType::all() as $activityType)
                                        <option value="{{ $activityType->id }}">{{ $activityType->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    {{--s6--}}
                    <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        {{--                            <x-jet-input class="block mt-1 w-full" type="text" name="vat_number"--}}
                        {{--                                         :value="old('vat_number')"></x-jet-input>--}}

                        <livewire:vat-number-check/>

                        <div class="sm:col-span-3">
                            <x-jet-label value="{{ __('Fiscal number') }}"></x-jet-label>
                            <x-jet-input class="block mt-1 w-full" type="text" name="fiscal_number"
                                         :value="old('fiscal_number')"></x-jet-input>
                        </div>
                    </div>

                    <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <div class="sm:col-span-3">
                            <x-jet-label value="{{ __('Phone') }}"></x-jet-label>
                            <x-jet-input class="block mt-1 w-full" type="text" name="phone"
                                         :value="old('phone')"></x-jet-input>
                        </div>
                        <div class="sm:col-span-3">
                            <x-jet-label value="{{ __('Email') }}"></x-jet-label>
                            <x-jet-input class="block mt-1 w-full" type="text" name="email"
                                         :value="old('email')"></x-jet-input>
                        </div>
                    </div>

                    <div class="mt-8 border-t border-gray-200 pt-5">

                        {{--GRENKE FIELDS--}}

                        <div class="mt-4">
                            <div class="relative flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="grenkeCheckbox" type="checkbox" name="grenkeCheckbox"
                                           class="form-checkbox h-4 w-4 text-indigo-600 transition duration-150 ease-in-out">
                                </div>
                                <div class="ml-3 text-sm leading-5">
                                    <label for="grenkeCheckbox" class="font-medium text-gray-700">Grenke</label>
                                </div>
                            </div>
                        </div>

                        <div class=" grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6 p-6" id="grenkeDiv"
                             style="display: none ; background: lightblue">
                            <div class="sm:col-span-2">
                                <label for="country" class="block text-sm font-medium leading-5 text-gray-700">
                                    {{ __('Company type') }}
                                </label>
                                <div class="mt-1 rounded-md shadow-sm">
                                    <select id="country" name="company_type"
                                            class="form-select block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                                        @foreach(\App\Enum\CompanyType::asSelectArray() as $value => $label)
                                            <option value="{{ $value }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="sm:col-span-2">
                                <div class="sm:col-span-2">
                                    <x-jet-label value="{{ __('Company date') }}"></x-jet-label>
                                    <x-jet-input class="block mt-1 w-full" type="date" name="company_date"
                                                 :value="old('company_date', Carbon\Carbon::now()->format('Y-m-d'))" autofocus></x-jet-input>
                                </div>
                            </div>
                            <div class="sm:col-span-2" id="grenkeDiv">
                                <x-jet-label value="{{ __('Legal contact') }}"></x-jet-label>
                                <x-jet-input class="block mt-1 w-full" type="text" name="legal_contact"
                                             :value="old('legal_contact')"></x-jet-input>
                            </div>
                        </div>
                    </div>

                    {{--PAYMENT--}}
                    <div class="mt-8 border-t border-gray-200 pt-5">

                        <div class="mt-4">
                            <div class="relative flex items-start">
                                <div class="ml-3 text-sm leading-5">
                                    <label for="comments" class="font-medium text-gray-700">Payment</label>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                            <div class="sm:col-span-3">
                                <x-jet-label value="{{ __('Iban') }}"></x-jet-label>
                                <x-jet-input class="block mt-1 w-full" type="text" name="iban"
                                             :value="old('iban')"></x-jet-input>
                            </div>
                            <div class="sm:col-span-3">
                                <x-jet-label value="{{ __('Iban_name') }}"></x-jet-label>
                                <x-jet-input class="block mt-1 w-full" type="text" name="iban_name"
                                             :value="old('iban_name')"></x-jet-input>
                            </div>
                        </div>

                    </div>

                    {{--                    notes--}}
                    <div class="mt-8 border-t border-gray-200 pt-5">

                        <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                            <div class="sm:col-span-3">
                                <x-jet-label value="{{ __('Notes') }}"></x-jet-label>
                                {{--                                <x-jet- class="block mt-1 w-full" type="area" name="note"--}}
                                {{--                                             :value="old('note')"></x-jet->--}}

                                <textarea class="form-control" name="note"
                                ></textarea>
                            </div>
                        </div>

                    </div>

                    <div class="mt-8">
                        <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                            <div class="sm:col-span-3">
                                <x-jet-label value="{{ __('PEC / SDI') }}"></x-jet-label>
                                <x-jet-input class="block mt-1 w-full" type="text" name="einvoice_code"
                                             :value="old('einvoice_code')"></x-jet-input>
                            </div>
                        </div>

                    </div>

                    <div class="mt-8">
                        <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                            <div class="mt-4">
                                <div class="relative flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="gdpr" type="checkbox" name="gdpr"
                                               class="form-checkbox h-4 w-4 text-indigo-600 transition duration-150 ease-in-out">
                                    </div>
                                    <div class="ml-3 text-sm leading-5">
                                        <label for="gdpr" class="font-medium text-gray-700">GDPR</label>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4">
                                <div class="relative flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="newsletter" type="checkbox" name="newsletter"
                                               class="form-checkbox h-4 w-4 text-indigo-600 transition duration-150 ease-in-out"
                                        >
                                    </div>
                                    <div class="ml-3 text-sm leading-5">
                                        <label for="newsletter" class="font-medium text-gray-700">Newsletter</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{--                hidden fields  --}}
                    <div class="hidden mt-8 border-t border-gray-200 pt-8">
                        <div>
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                Notifications
                            </h3>
                            <p class="mt-1 text-sm leading-5 text-gray-500">
                                We'll always let you know about important changes, but you pick what else you want
                                to
                                hear
                                about.
                            </p>
                        </div>
                        <div class="mt-6">
                            <fieldset>
                                <legend class="text-base font-medium text-gray-900">
                                    By Email
                                </legend>
                                <div class="mt-4">
                                    <div class="relative flex items-start">
                                        <div class="flex items-center h-5">
                                            <input id="comments" type="checkbox"
                                                   class="form-checkbox h-4 w-4 text-indigo-600 transition duration-150 ease-in-out">
                                        </div>
                                        <div class="ml-3 text-sm leading-5">
                                            <label for="comments" class="font-medium text-gray-700">Comments</label>
                                            <p class="text-gray-500">Get notified when someones posts a comment on a
                                                posting.</p>
                                        </div>
                                    </div>
                                    <div class="mt-4">
                                        <div class="relative flex items-start">
                                            <div class="flex items-center h-5">
                                                <input id="candidates" type="checkbox"
                                                       class="form-checkbox h-4 w-4 text-indigo-600 transition duration-150 ease-in-out">
                                            </div>
                                            <div class="ml-3 text-sm leading-5">
                                                <label for="candidates"
                                                       class="font-medium text-gray-700">Candidates</label>
                                                <p class="text-gray-500">Get notified when a candidate applies for a
                                                    job.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-4">
                                        <div class="relative flex items-start">
                                            <div class="flex items-center h-5">
                                                <input id="offers" type="checkbox"
                                                       class="form-checkbox h-4 w-4 text-indigo-600 transition duration-150 ease-in-out">
                                            </div>
                                            <div class="ml-3 text-sm leading-5">
                                                <label for="offers" class="font-medium text-gray-700">Offers</label>
                                                <p class="text-gray-500">Get notified when a candidate accepts or
                                                    rejects an
                                                    offer.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset class="mt-6">
                                <legend class="text-base font-medium text-gray-900">
                                    Push Notifications
                                </legend>
                                <p class="text-sm leading-5 text-gray-500">These are delivered via SMS to your
                                    mobile
                                    phone.</p>
                                <div class="mt-4">
                                    <div class="flex items-center">
                                        <input id="push_everything" name="push_notifications" type="radio"
                                               class="form-radio h-4 w-4 text-indigo-600 transition duration-150 ease-in-out">
                                        <label for="push_everything" class="ml-3">
                                        <span
                                            class="block text-sm leading-5 font-medium text-gray-700">Everything</span>
                                        </label>
                                    </div>
                                    <div class="mt-4 flex items-center">
                                        <input id="push_email" name="push_notifications" type="radio"
                                               class="form-radio h-4 w-4 text-indigo-600 transition duration-150 ease-in-out">
                                        <label for="push_email" class="ml-3">
                                        <span
                                            class="block text-sm leading-5 font-medium text-gray-700">Same as email</span>
                                        </label>
                                    </div>
                                    <div class="mt-4 flex items-center">
                                        <input id="push_nothing" name="push_notifications" type="radio"
                                               class="form-radio h-4 w-4 text-indigo-600 transition duration-150 ease-in-out">
                                        <label for="push_nothing" class="ml-3">
                                            <span class="block text-sm leading-5 font-medium text-gray-700">No push notifications</span>
                                        </label>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-8 border-t border-gray-200 pt-5">
                <div class="flex justify-end">
      <a href="{{url()->previous()}}" class="inline-flex rounded-md shadow-sm">
        <button type="button"
                class="py-2 px-4 border border-gray-300 rounded-md text-sm leading-5 font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-50 active:text-gray-800 transition duration-150 ease-in-out">
          Cancel
        </button>
      </a>
                    <span class="ml-3 inline-flex rounded-md shadow-sm">
        <button type="submit"
                class="inline-flex justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo active:bg-indigo-700 transition duration-150 ease-in-out">
          Save
        </button>
      </span>
                </div>
            </div>
        </form>
    </div>
    </div>

</x-app-layout>





