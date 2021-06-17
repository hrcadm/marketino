<div>
    @if ($errors->any())
        <div id="alert" class="mb-2 text-center bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li><span style="color: #dc3545" class="alert-danger uppercase">{{ $error }}</span></li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session()->has('message'))
        <div class="alert alert-danger text-center col-sm-8  form-group-sm col-lg-12" id="error_msg">
            <span>{{ session()->get('message') }}</span>
        </div>
    @endif

    <div class="flex mb-5">
        <div class="bg-white shadow overflow-hidden sm:rounded-lg mt-5 inline-block m-auto">
            <div class="flex">
                <div class="px-4 py-5 border-b border-gray-200 sm:px-6 inline-block">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        E-mail adrese
                    </h3>
                </div>
                <div class="inline-block ml-auto px-4 py-3 sm:px-6">
                    <x-jet-button
                        wire:click="newEmailModalShow"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white
                            bg-indigo-600 hover:bg-indigo-400 focus:outline-none focus:shadow-outline-indigo focus:border-indigo-700 active:bg-indigo-700 transition duration-150 ease-in-out">
                        {{ __('Dodaj novi') }}
                    </x-jet-button>
                </div>
            </div>
            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                        E-mail
                                    </th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider text-center">
                                        Primarni <br>E-mail
                                    </th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider text-center">
                                        Opcije
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($customer->emails as $email)
                                    <livewire:customer.contacts.customer-email-td :customerEmail="$email" :id="$loop->index" key="{{ $email->id }}"/>
                                @endforeach
                                <!-- More rows... -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white shadow overflow-hidden sm:rounded-lg mt-5 ml-2 inline-block m-auto">
            <div class="flex">
                <div class="px-4 py-5 border-b border-gray-200 sm:px-6 inline-block text-left">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Brojevi telefona
                    </h3>
                </div>
                <div class="inline-block ml-auto px-4 py-3 sm:px-6">
                    <x-jet-button
                        wire:click="newPhoneModalShow"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white
                            bg-indigo-600 hover:bg-indigo-400 focus:outline-none focus:shadow-outline-indigo focus:border-indigo-700 active:bg-indigo-700 transition duration-150 ease-in-out">
                        {{ __('Dodaj novi') }}
                    </x-jet-button>
                </div>
            </div>
            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                        Broj telefona
                                    </th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider text-center">
                                        Primarni <br> telefon
                                    </th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider text-center">
                                        Opcije
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($customer->phones as $phone)
                                    <livewire:customer.contacts.customer-phone-td :customerPhone="$phone" :id="$loop->index" key="{{ $phone->id }}"/>
                                @endforeach
                                <!-- More rows... -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form wire:submit.prevent="storeNewCustomerEmail" wire:ignore.self>
        @csrf
        <x-jet-confirmation-modal wire:model="newEmailModalShow">

            <x-slot name="title">
                {{ __('Dodavanje novog E-maila') }}
            </x-slot>

            <x-slot name="content">

                @error('email') {{ $message }} @enderror
                <x-jet-label>E-mail</x-jet-label>
                <x-jet-input type="e-mail" wire:model="new_email" placeholder="Unesite E-mail" required></x-jet-input>

                @error('is_default') {{ $message }} @enderror
                <label>Primarni</label>
                <input type="checkbox" wire:model="is_default" class="form-check-input rounded-md shadow-sm" >

            </x-slot>

            <x-slot name="footer">

                <x-jet-secondary-button wire:click="$toggle('newEmailModalShow')" wire:loading.attr="disabled">
                    {{ __('Odustani') }}
                </x-jet-secondary-button>

                <input type="submit"
                       class="ml-2 inline-flex items-center justify-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 focus:outline-none focus:border-green-700 focus:shadow-outline-green active:bg-green-600 transition ease-in-out duration-150'"
                       value="{{ __('Spremi') }}"/>

            </x-slot>

        </x-jet-confirmation-modal>
    </form>

        <form wire:submit.prevent="storeNewCustomerPhone" wire:ignore.self>
            @csrf
            <x-jet-confirmation-modal wire:model="newPhoneModalShow" id="test">

                <x-slot name="title">
                    {{ __('Dodavanje novog kontakt broja') }}
                </x-slot>

                <x-slot name="content">

                    @error('email') {{ $message }} @enderror
                    <x-jet-label>Broj</x-jet-label>
                    <x-jet-input type="text" wire:model="new_phone" placeholder="Unesite broj" required></x-jet-input>

                    @error('is_default') {{ $message }} @enderror
                    <label>Primarni</label>
                    <input type="checkbox" wire:model="is_default" class="form-check-input rounded-md shadow-sm" >

                </x-slot>

                <x-slot name="footer">

                    <x-jet-secondary-button wire:click="$toggle('newPhoneModalShow')" wire:loading.attr="disabled">
                        {{ __('Odustani') }}
                    </x-jet-secondary-button>

                    <input type="submit"
                           class="ml-2 inline-flex items-center justify-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 focus:outline-none focus:border-green-700 focus:shadow-outline-green active:bg-green-600 transition ease-in-out duration-150'"
                           value="{{ __('Spremi') }}"/>

                </x-slot>

            </x-jet-confirmation-modal>
        </form>
</div>
