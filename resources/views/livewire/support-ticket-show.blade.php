@push('styles')
    <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet"
          xmlns:wire="http://www.w3.org/1999/xhtml">
    <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet">
@endpush

@push('scripts')
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
    <script src="https://unpkg.com/filepond/dist/filepond.js"></script>
@endpush

<div>
    <div class="bg-white px-4 py-5 border-b border-gray-200 sm:px-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900">
            Title: {{ $ticket->title }}
        </h3>
    </div>

    <div class="flex">
        <div class="flex flex-col w-2/3" x-data="{ alternativeEmailShow: false }">
            <form
                id="support_ticket_respond_form"
                method="get"
                action=""
                class="px-4 border-b border-gray-200"
                wire:submit.prevent="createNewSupportMessage">
                @csrf
                <div>
                    <div class="mt-2 mb-2">
                        <div>
                            <x-jet-label value="{{ __('Response') }}"></x-jet-label>
                            <div
                                class="form-textarea w-full"
                                x-data
                                x-init="
                                    ClassicEditor.create($refs.messageContent, {
                                        toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote' ],
                                    })
                                    .then( function(editor){
                                        editor.model.document.on('change:data', () => {
                                           $dispatch('input', editor.getData())
                                        })
                                    })
                                    .catch( error => {
                                        console.error( error );
                                    } );
                                "
                                wire:ignore
                                wire:key="messageContent"
                                x-ref="messageContent"
                            >
                                {!! $message->content !!}
                            </div>
                        </div>
                        <div class="mt-3">
                            <label for="cover_photo" class="block text-sm leading-5 font-medium text-gray-700">
                                Attachments
                            </label>
                            <x-input.filepond wire:model="attachments" class="px-6 pt-5 pb-6 w-full rounded-md" multiple/>
                        </div>
                        <div class="flex items-center my-3">
                            <input
                                type="checkbox"
                                id="send_customer_mail"
                                class="form-checkbox h-5 w-5 text-indigo-600 transition duration-150 ease-in-out"
                                wire:model="message.send_to_customer"
                                x-on:click="alternativeEmailShow = !alternativeEmailShow">
                            <label class="ml-1" for="send_customer_mail">
                                <span class="text-gray-700 font-bold">Send to customer?</span>
                            </label>
                        </div>
                        <div class="items-center my-3" id="alternativeEmail">
                            <x-jet-input id="alternative_email"
                                         x-show="alternativeEmailShow"
                                         name="alternative_email"
                                         class="form-control block w-full"
                                         placeholder="Enter different e-mail (If empty, customer known E-mail will be used to reply)"
                                         wire:model="ticket.new_contact_email"
                            ></x-jet-input>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end my-3">
                    <span class="inline-flex rounded-md shadow-sm">
                        <button type="submit"
                                class="inline-flex justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo active:bg-indigo-700 transition duration-150 ease-in-out">
                          Send response
                        </button>
                    </span>
                </div>
            </form>

            <div class="mt-4 mb-5">
                @foreach($messages as $message)
                    <div class="clearfix">
                        <div
                            id="{{ $message->id }}"
                            class="{{ is_null($message->agent_id) ? 'bg-gray-300' : 'bg-indigo-300 float-right' }} w-3/4 mx-4 my-2 p-2 rounded-lg {{ $loop->first ? '' : 'clearfix' }}">
                            <strong>
                                @if($message->agent_id)
                                    {{ $message->agent->name }}
                                @elseif($ticket->customer_id)
                                    {{ $ticket->customer->company_name }}
                                @else
                                    {{ $message->from_email }}
                                @endif
                            </strong>
                            <small>{{ $message->created_at->toUserTimezone() }}</small>
                            @if($message->sent_to_customer_at)
                                <span class="ml-3 px-2 inline-flex text-xs leading-4 font-semibold rounded-full bg-indigo-100 text-indigo-800">
                                SENT TO CUSTOMER
                            </span>
                            @endif
                            <br>
                            {!! $message->content !!}
                            <br>
                            @foreach($message->files() as $index => $path)
                                <svg class="flex-shrink-0 {{ $index > 0 ? 'ml-1.5 ' : '' }}h-5 w-4 text-gray-800 inline" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z" clip-rule="evenodd"/>
                                </svg>
                                <a href="{{ route('ticket.attachment.show', [$ticket, 'path' => $path]) }}" target="_blank">attachment-{{ $index + 1 }}</a>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="flex flex-col w-1/3 border-l border-gray-200">

            {{-- Ticket control --}}
            <header class="px-2 py-3 bg-gray-100 sm:px-6">
                <div class="flex items-start justify-between space-x-3">
                    <div class="space-y-1">
                        <h2 class="text-lg leading-7 font-medium text-gray-900">
                            @lang('Ticket control')
                        </h2>
                    </div>
                </div>
            </header>
            <form
                id="support_ticket_control_form"
                method="get"
                action=""
                class="px-4"
                wire:submit.prevent="saveSupportTicket">
                @csrf
                <div>
                    <x-jet-label value="{{ __('Department') }}" class="mt-6 text-base"></x-jet-label>
                    <div class="mt-2">
                        @foreach(\App\Enum\SupportTicketDepartment::asArray() as $key =>$value)
                            <div class="sm:col-span-1">
                                <input
                                    type="radio"
                                    name="department"
                                    value="{{ $value }}"
                                    id="department_{{ $value }}"
                                    class="form-radio h-4 w-4 text-indigo-600 transition duration-150 ease-in-out"
                                    required
                                    wire:model="ticket.department">
                                <label for="department_{{ $value }}" class="ml-3">
                                    <span class="text-gray-700">{{ ucfirst($value) }}</span>
                                </label>
                            </div>
                        @endforeach
                    </div>

                    {{--s6--}}
                    <div class="mt-6">
                        <div class="sm:col-span-3">
                            <x-jet-label value="{{ __('Assigned agent') }}" for="assigned_agent" class="mt-6 text-base"></x-jet-label>
                            <div class="mt-2 rounded-md shadow-sm">
                                <select id="assigned_agent" name="assigned_agent_id"
                                        class="form-select block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"
                                        wire:model="ticket.assigned_agent_id">
                                    @if($ticket->assignedAgent !== null)
                                        <option value="{{$ticket->assignedAgent->id}}">{{ $ticket->assignedAgent->name }}</option>
                                        @foreach(App\Models\User::all() as $user)
                                            @if($user->id !== $ticket->assignedAgent->id)
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endif
                                        @endforeach
                                    @else
                                        <option value=""></option>
                                        @foreach(App\Models\User::all() as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mt-6">
                        <div class="sm:col-span-3">
                            <div class="mt-2">
                                <x-jet-label value="{{ __('Status') }}" class="mt-6 text-base"></x-jet-label>
                                @foreach(\App\Enum\SupportTicketStatus::asArray() as $value)
                                    <div>
                                        <input
                                            type="radio"
                                            id="status_{{ $value }}"
                                            value="{{ $value }}"
                                            class="form-radio h-4 w-4 text-indigo-600 transition duration-150 ease-in-out"
                                            wire:model="ticket.status">
                                        <label for="status_{{ $value }}" class="ml-3">
                                            <span class="text-gray-700">{{ ucfirst($value) }}</span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pt-5">
                    <div class="flex justify-start">
                        <span class="mb-3 inline-flex rounded-md shadow-sm">
                            <button type="submit"
                                    class="inline-flex justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo active:bg-indigo-700 transition duration-150 ease-in-out">
                                Update
                            </button>
                        </span>
                        <span class="mb-3 inline-flex rounded-md shadow-sm ml-2">
                            <a href="#" wire:click="$toggle('modalShow')" class="inline-flex justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-red-600 hover:bg-red-500 focus:outline-none focus:border-red-700 focus:shadow-outline-red active:bg-red-700 transition duration-150 ease-in-out">
                                Delete
                            </a>
                            <x-jet-confirmation-modal wire:model="modalShow">

                                <x-slot name="title">
                                    {{ __('Jeste li sigurni da želite obrisati ovaj ticket?') }}
                                </x-slot>

                                <x-slot name="content">
                                </x-slot>

                                <x-slot name="footer">

                                    <x-jet-secondary-button wire:click="$toggle('modalShow')" wire:loading.attr="disabled">
                                        {{ __('Odustani') }}
                                    </x-jet-secondary-button>

                                    <input type="submit"
                                           wire:click="deleteTicket"
                                           class="ml-2 inline-flex items-center justify-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 focus:outline-none focus:border-red-700 focus:shadow-outline-red active:bg-red-600 transition ease-in-out duration-150'"
                                           value="{{ __('Obriši') }}"/>

                                </x-slot>

                            </x-jet-confirmation-modal>
                        </span>
                        <span class="mb-3 inline-flex rounded-md shadow-sm ml-2">
                             <a href="{{ route('tickets.index') }}"
                                class="inline-flex justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-gray-600 hover:bg-gray-500 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo active:bg-gray-700 transition duration-150 ease-in-out">
                              Back
                            </a>
                        </span>
                        @if (session()->has('message'))
                            <span class="ml-2 inline-flex justify-center py-1.5 px-2 text-sm font-medium text-green-500">
                                {{ session('message') }}
                            </span>
                        @endif
                    </div>
                </div>
            </form>
            {{-- Customer control--}}
            @if($customer)
                <header class="px-2 py-3 bg-gray-100 sm:px-6">
                    <div class="flex items-start justify-between space-x-3">
                        <div class="space-y-1">
                            <h2 class="text-lg leading-7 font-medium text-gray-900">
                                @lang('Customer')
                            </h2>
                        </div>
                    </div>
                </header>
                <a href="{{ route('customers.show', $customer->id) }}" class="block px-4">
                    <h3 class="mt-2 text-xl leading-7 font-semibold text-indigo-500">
                        {{ $customer->company_name }}
                    </h3>
                    <p class="mt-3 text-base leading-6 text-indigo-500 font-semibold">
                        Contact: {{ $customer->full_name }}
                    </p>
                </a>
                <p class="mt-1 text-base leading-6 text-gray-500 block px-4">
                    Address: {{ $customer->full_address }}
                </p>
                <p class="mt-1 text-base leading-6 text-gray-500 block px-4">
                    Known phones: {{ $customer->phones->pluck('phone')->implode(', ') ?: '-' }}
                </p>
                <p class="mt-1 text-base leading-6 text-gray-500 block px-4">
                    Known emails: {{ $customer->emails->pluck('email')->implode(', ') ?: '-' }}
                </p>

                {{-- Customer fast search --}}
                <div class="px-4 mb-40" >
                    <x-jet-label value="{{ __('Assign to customer') }}" class="mt-6 text-base"></x-jet-label>
                    <div class="relative h-40">
                        <livewire:customer-fast-search :customer="$customer" :key="time().$customer->id"/>
                    </div>
                </div>
            @elseif($ticket->customer_id === null && \App\Models\CustomerEmail::getByEmail($message->from_email)->count() > 1)
                <div class="mt-6 mr-4 ml-4">
                    <div class="sm:col-span-3">
                        <label for="multipleCustomersAssignedToEmail">{{ __('Assign to customer') }} associated with this E-mail</label>
                        <div class="mt-2 rounded-md shadow-sm">
                            <select
                                name="multipleCustomersAssignedToEmail"
                                class="form-select block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"
                                wire:change="updateSupportTicketCustomer();"
                                wire:model="ticket.customer_id"
                            >
                                <option value=""></option>
                                @foreach(\App\Models\CustomerEmail::getByEmail($message->from_email)->get() as $associatedCustomer)
                                    <option value="{{ $associatedCustomer->customer->id }}">{{ $associatedCustomer->customer->display_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                {{-- Customer fast search --}}
                <div class="px-4 mb-40" >
                    <x-jet-label value="{{ __('Assign to customer') }}" class="mt-6 text-base"></x-jet-label>
                    <div class="relative h-40">
                        <livewire:customer-fast-search :customer="$customer"/>
                    </div>
                </div>
            @else
                <header class="px-2 py-3 bg-gray-100 sm:px-6">
                    <div class="flex items-start justify-between space-x-3">
                        <div class="space-y-1">
                            <h2 class="text-lg leading-7 font-medium text-gray-900">
                                @lang('New Contact')
                                <div class="text-xs inline-flex items-center font-bold leading-sm uppercase px-3 py-1 bg-blue-200 text-blue-700 rounded-full">
                                    New
                                </div>
                            </h2>
                        </div>
                    </div>
                </header>
                <a href="mailto:{{ $ticket->new_contact_email }}" class="block px-4">
                    <p class="mt-1 text-base leading-6 text-gray-500">
                        E-mail: {{ $ticket->new_contact_email }}
                    </p>
                </a>
                {{-- Customer fast search --}}
                <div class="px-4 mb-40" >
                    <x-jet-label value="{{ __('Assign to customer') }}" class="mt-6 text-base"></x-jet-label>
                    <div class="relative h-40">
                        <livewire:customer-fast-search :customer="$customer"/>
                    </div>
                </div>
            @endif
        </div>
    </div>

</div>
@push('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/23.1.0/classic/ckeditor.js"></script>
@endpush
