<div>

    {{--    <x-slot name="card_header">--}}
    <div class="card">

{{--        <div class="flex justify-between items-center flex-wrap sm:flex-no-wrap">--}}
        <div class="card-body">
            <div class="flex m-5">
                <div class="mt-1 flex rounded-md shadow-sm">

                    <div class="relative flex-grow focus-within:z-10">
                        <input wire:model.debounce.250ms="searchTerm" type="text" id="search" name="search"
                               class="form-input block w-full rounded-none rounded-l-md transition ease-in-out duration-150 sm:text-sm sm:leading-5"
                               placeholder="Search customer">
                        {{--                        <input wire:model="searchTerm" type="text" placeholder="Search customers"/>--}}
                    </div>

                    <button
                        class="-ml-px relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm leading-5 font-medium rounded-r-md text-gray-700 bg-gray-50 hover:text-gray-500 hover:bg-white focus:outline-none focus:shadow-outline-blue focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
                        <!-- Heroicon name: search -->
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 20 20"
                             xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>
                </div>
            </div>
{{--            Right side actions--}}
{{--            <div class="flex justify-end flex-shrink-0">--}}
{{--                <x-jet-button>--}}
{{--                    Filters--}}
{{--                </x-jet-button>--}}
{{--            </div>--}}
        </div>
    </div>

    {{--    </x-slot>--}}


    <ul>
        @foreach($customers as $customer)
            <li>
                <a href="{{ route('customers.show', ['customer'=>$customer]) }}"
                   class="block hover:bg-gray-50 focus:outline-none focus:bg-gray-50 transition duration-150 ease-in-out">
                    <div class="px-4 py-4 sm:px-6">
                        <div class="flex items-center justify-between">
                            <div class="text-sm leading-5 font-medium text-indigo-600 truncate">
                                {{ $customer->company_name }}
                            </div>
                            <div class="ml-2 flex-shrink-0 flex">
              <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                Active
              </span>
                            </div>
                        </div>
                        <div class="mt-2 sm:flex sm:justify-between">
                            <div class="sm:flex">
                                <div class="mr-6 flex items-center text-sm leading-5 text-gray-500">
                                    <!-- Heroicon name: users -->
                                    <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" viewBox="0 0 20 20"
                                         fill="currentColor">
                                        <path
                                            d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                                    </svg>
                                    {{ $customer->full_name }}
                                </div>
                                <div class="mt-2 flex items-center text-sm leading-5 text-gray-500 sm:mt-0">
                                    <!-- Heroicon name: location-marker -->
                                    <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" viewBox="0 0 20 20"
                                         fill="currentColor">
                                        <path fill-rule="evenodd"
                                              d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                              clip-rule="evenodd"/>
                                    </svg>
                                    {{ $customer->country_code }}
                                </div>
                            </div>
                            <div class="mt-2 flex items-center text-sm leading-5 text-gray-500 sm:mt-0">
                                <!-- Heroicon name: calendar -->
                                <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" viewBox="0 0 20 20"
                                     fill="currentColor">
                                    <path fill-rule="evenodd"
                                          d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                          clip-rule="evenodd"/>
                                </svg>
                                <span>
                                Created at
                                <time
                                    datetime="{{ $customer->created_at->format('Y-m-d') }}">{{ $customer->created_at->format('d.m.Y.') }}</time>
                            </span>
                            </div>
                        </div>
                    </div>
                </a>
            </li>
        @endforeach
    </ul>

    <div class="pagination bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">

        {{ $customers->links()}}

        {{-- <div class="flex-1 flex justify-between sm:hidden"> --}}
        {{-- <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between"> --}}
    </div>
</div>
