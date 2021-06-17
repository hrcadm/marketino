<div>
    <div class="flex justify-between items-center flex-wrap sm:flex-no-wrap mb-3">
        <div class="flex w-full mt-3">
            <div class="absolute mt-3 ml-2 flex items-center pointer-events-none">
                <svg class="mr-3 ml-5 h-4 w-4 text-gray-400" x-description="Heroicon name: search" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
                </svg>
            </div>

            <input
                type="search"
                name="search"
                class="form-input pl-9 rounded-md transition ease-in-out duration-150 sm:text-sm sm:leading-5 ml-5"
                placeholder="Search tickets..."
                wire:model.debounce.250ms="search"
                wire:keydown.escape="resetSearch"
            />
        </div>

        {{-- Right side actions --}}
        <div class="flex justify-end flex-shrink-0">
            {{-- Datum--}}
            <div class="inline-block m-3">
                <label class="block">Datum</label>
                <input class="w-auto h-10 bg-white rounded-md transition ease-in-out duration-150 border-2" type="search" id="ticketsDatePicker" wire:model="dateFilter" placeholder="Odaberite datum" readonly>
                @if($dateFilter)
                    <i id="filtersubmit" class="fa fa-times" wire:click="resetDateFilter"></i>
                @endif
            </div>

            {{-- Agent --}}
            <div class="inline-block m-3">
                <label class="block">Agent</label>
                <select class="w-100 h-10 bg-white rounded-md transition ease-in-out duration-150 border-2" wire:model="agent">
                    <option value="">Svi</option>
                    @foreach($agents as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Department --}}
            <div class="inline-block m-3">
                <label class="block">Department</label>
                <select class="w-100 h-10 bg-white rounded-md transition ease-in-out duration-150 border-2" wire:model="department">
                    <option value="">All</option>
                    @foreach(\App\Enum\SupportTicketDepartment::getValues() as $department)
                        <option value="{{ $department }}">{{ ucfirst($department) }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Status --}}
            <div class="inline-block m-3">
                <label class="block">Status</label>
                <select class="w-100 h-10 bg-white rounded-md transition ease-in-out duration-150 border-2" wire:model="status">
                    <option value="">All</option>
                    <option value="new">New</option>
                    @foreach(\App\Enum\SupportTicketStatus::getValues() as $status)
                        <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <hr>

    <ul>
        @foreach($tickets as $ticket)
            <li @if(!$ticket->first_opened_at) class="bg-blue-100" @endif>
                <a href="{{ route('tickets.show', $ticket) }}" class="block hover:bg-gray-50 focus:outline-none focus:bg-gray-50 transition duration-150 ease-in-out">
                    <div class="px-4 py-4 sm:px-6">
                        <div class="flex items-center justify-between">
                            <div class="text-sm leading-5 font-medium text-indigo-600 truncate">
                                @if(!$ticket->first_opened_at)
                                    <span class="px-2 mr-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        NEW
                                    </span>
                                @endif
                                {{ $ticket->title }}
                            </div>
                            <div class="ml-2 flex-shrink-0 flex">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $ticket->status == \App\Enum\SupportTicketStatus::OPEN ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ strtoupper($ticket->status) }}
                                </span>
                            </div>
                        </div>
                        <div class="mt-2 sm:flex sm:justify-between">
                            <div class="sm:flex w-1/2">
                                <div class="mr-6 flex items-center text-sm leading-5 text-gray-500">
                                @if($ticket->customer)
                                    <!-- Heroicon name: users -->
                                        <svg class="flex-shrink-0 mr-1.5 h-5 w-4 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                                        </svg>
                                    {{ $ticket->customer->display_name }}
                                @else
                                    <!-- Heroicon name: mail -->
                                        <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                        </svg>
                                        {{ $ticket->lastContactEmail() }}
                                    @endif
                                </div>
                                <div class="mr-6 flex items-center text-sm leading-5 text-gray-500">
                                    <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path
                                            d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                                    </svg>
                                    {{ $ticket->department->value }}
                                </div>
                                @if($ticket->assignedAgent)
                                    <div class="mr-6 flex items-center text-sm leading-5 text-gray-500">
                                        <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $ticket->assignedAgent->name }}
                                    </div>
                                @endif
                            </div>
                            <div class="mt-2 flex items-center text-sm leading-5 text-gray-500 sm:mt-0">
                                <!-- Heroicon name: calendar -->
                                <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                </svg>
                                <span>
                                Last updated:
                                <time datetime="{{ $ticket->updated_at->toUserTimezone()->format('Y-m-d H:i:s') }}">{{ $ticket->updated_at->toUserTimezone()->format('d.m.Y. H:i:s') }}</time>
                            </span>
                            </div>
                        </div>
                    </div>
                </a>
            </li>
            @if(!$loop->last)
                <hr>
            @endif
        @endforeach
    </ul>

    {{-- Pagination --}}
    <div class="pagination bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
        {{ $tickets->links() }}
    </div>

</div>

@push('scripts')
    <script>
        flatpickr("#ticketsDatePicker", {
            mode: "range",
            dateFormat: "d.m.Y",
            maxDate: "today",
        });
    </script>
@endpush
