<div>
    <div x-data="customerFastSearch()" class="space-y-1">
        <div>
            <!--suppress HtmlFormInputWithoutLabel -->
            <input
                type="text"
                id="searchInput"
                class="form-input inline-block w-full leading-5 rounded-md shadow-sm"
                x-show="showInput"
                x-ref="searchInput"
                @click.away="hideSearch()"
                wire:model.debounce.250ms="searchTerm"
            />
            <input type="hidden" name="customer_id" value="{{ optional($customer)->id }}">
            <span
                class="inline-block w-full rounded-md shadow-sm"
                x-show="!showInput"
                @click="showSearch()">
                <button
                    type="button"
                    aria-haspopup="listbox"
                    aria-expanded="true"
                    aria-labelledby="listbox-label"
                    class="cursor-default relative w-full rounded-md border border-gray-300 bg-white pl-3 pr-10 py-2 text-left focus:outline-none focus:shadow-outline-blue focus:border-blue-300 transition ease-in-out duration-150 sm:text-sm sm:leading-5"
                >
                    <div class="flex items-center space-x-3">
                        <span aria-label="Online" class="{{ $customer ? 'bg-green-400' : 'bg-gray-200' }} flex-shrink-0 inline-block h-2 w-2 rounded-full"></span>
                        <span class="block truncate">
                            {{ optional($customer)->display_name ?? 'No customer assigned' }}
                        </span>
                    </div>
                    <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="none" stroke="currentColor">
                            <path d="M7 7l3-3 3 3m0 6l-3 3-3-3" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                </button>
            </span>

            <!-- Select popover, show/hide based on select state. -->
            <div x-show="isSearching()" class="mt-1 w-full rounded-md bg-white shadow-lg">
                <ul tabindex="-1" role="listbox" aria-labelledby="listbox-label" aria-activedescendant="listbox-item-3" class="max-h-60 rounded-md py-1 text-base leading-6 shadow-xs overflow-auto focus:outline-none sm:text-sm sm:leading-5">
                    @forelse($customers as $customerItem)
                        <li wire:click="selectCustomer('{{ $customerItem->id }}')"
                            id="listbox-item-{{ $customerItem->id }}"
                            role="option"
                            class="py-2 pl-3 pr-9 relative select-none
                            text-gray-900 cursor-default
                            hover:bg-indigo-600 hover:text-white hover:cursor-pointer"
                        >
                            <div class="flex items-center space-x-3">
                                <span aria-label="Online" class="bg-green-400 flex-shrink-0 inline-block h-2 w-2 rounded-full"></span>
                                <span class="{{ $customerItem->id == optional($customer)->id ? 'font-semibold' : 'font-normal' }} block truncate">
                                    {{ $customerItem->display_name }}
                                </span>
                            </div>

                            @if($customerItem->id == optional($customer)->id)
                                <span class="absolute inset-y-0 right-0 flex items-center pr-4">
                                    <!-- Heroicon name: check -->
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </span>
                            @endif
                        </li>
                    @empty
                        <li id="listbox-item-none" role="option" class="text-gray-900 cursor-default select-none relative py-2 pl-3 pr-9">
                            <div class="flex items-center space-x-3">
                                <span aria-label="Online" class="bg-gray-200 flex-shrink-0 inline-block h-2 w-2 rounded-full"></span>
                                <span class="font-normal block truncate">
                                    Nema rezultata
                                </span>
                            </div>
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
    <!--suppress BadExpressionStatementJS -->
    <script>
        function customerFastSearch() {
            return {
                // @formatter:off
                showInput: false,
                isSearching() { return this.showInput === true },
                showSearch() {
                    this.showInput = true
                    document.getElementById('searchInput').focus()
                },
                hideSearch(){
                    this.showInput = false
                    this.$wire.resetSearch()
                }
                // @formatter:on
            }
        }
    </script>
</div>
