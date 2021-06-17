@push('styles')
    <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet"
          xmlns:wire="http://www.w3.org/1999/xhtml">
    <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet">
@endpush

@push('scripts')
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
    <script src="https://unpkg.com/filepond/dist/filepond.js"></script>
@endpush

<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create support ticket') }}
        </h2>
    </x-slot>

    <div class="px-4 py-5">
        @if ($errors->any())
            <div class="mb-5">
                <div class="font-medium text-red-600">Whoops! Something went wrong.</div>

                <ul class="mt-3 list-disc list-inside text-sm text-red-600">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="post" action="{{ route('tickets.store') }}" enctype="multipart/form-data">
            @csrf
            <div>
                <div>
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            Issue details
                        </h3>
                        <p class="mt-1 text-sm leading-5 text-gray-500">
                            Fill in basic details needed for support team.
                        </p>
                    </div>

                    <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <div class="sm:col-span-3">
                            <label class="block text-sm font-medium leading-5 text-gray-700">
                                {{ __('Select customer') }}
                            </label>
                            <div class="mt-1 rounded-md shadow-sm">
                                <livewire:customer-fast-search/>
                            </div>
                            <div class="mt-1 rounded-md shadow-sm" x-data="{ msg: ''}">
                                <x-jet-input
                                    class="block mt-1 w-full"
                                    type="email"
                                    name="new_contact_email"
                                    id="new_contact_email"
                                    placeholder="Enter E-mail (only if customer not selected)"
                                    x-on:customer-email.window="msg = $event.detail.email"
                                    x-bind:value="msg"
                                >
                                </x-jet-input>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center my-3">
                        <input
                            type="checkbox"
                            name="send_to_customer"
                            id="send_to_customer"
                            class="form-checkbox h-5 w-5 text-indigo-600 transition duration-150 ease-in-out"
                        >
                        <label class="ml-1" for="send_to_customer">
                            <span class="text-gray-700 font-bold">Send to customer?</span>
                        </label>
                    </div>

                    <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <div class="sm:col-span-3">
                            <x-jet-label value="{{ __('Title') }}"></x-jet-label>
                            <x-jet-input class="block mt-1 w-full" type="text" name="title"
                                         :value="old('title')" required autocomplete="off"></x-jet-input>
                        </div>
                    </div>

                    <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <div class="sm:col-span-3">
                            <x-jet-label value="{{ __('Content') }}"></x-jet-label>
                            <textarea
                                name="content"
                                id="content"
                                class="form-textarea block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"
                                cols="30"
                                rows="10"
                                required>{{ old('content') }}</textarea>
                        </div>
                    </div>

                    <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <div class="sm:col-span-3">
                            <x-jet-label value="{{ __('Attachments') }}"></x-jet-label>
                            <div x-data
                                 x-init="FilePond.registerPlugin(FilePondPluginImagePreview);
                                 FilePond.setOptions({
                                        allowMultiple: 'true',
                                      server: {
                                        url: '/filepond/api',
                                        process: '/process',
                                        revert: '/process',
                                        headers: {
                                          'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                        }
                                      }
                                    });
                                FilePond.create($refs.input);"
                            >
                                <input
                                    type="file"
                                       class="filepond px-6 pt-5 pb-6 w-full rounded-md block"
                                       name="filepond[]"
                                       multiple
                                       data-allow-reorder="true"
                                       data-max-file-size="3MB"
                                       x-ref="input"
                                >
                            </div>
                        </div>
                    </div>

                    {{--s5--}}
                    <div class="mt-6 inline-flex">
                        @foreach(\App\Enum\SupportTicketDepartment::asArray() as $key => $value)
                            @if($loop->index === 0)
                            <div>
                            @else
                            <div class="ml-10">
                            @endif
                                <input
                                    type="radio"
                                    name="department"
                                    value="{{ $value }}"
                                    id="department_{{ $value }}"
                                    class="form-radio h-4 w-4 text-indigo-600 transition duration-150 ease-in-out"
                                    required
                                    aria-required="true"
                                    minlength="3"
                                    @if($value == old('department'))
                                    selected
                                    @endif>
                                <label for="department_{{ $value }}" class="ml-3">
                                    <span class="block text-sm leading-5 font-medium text-gray-700">{{ ucfirst($value) }}</span>
                                </label>
                            </div>
                        @endforeach
                    </div>

                    {{--s6--}}
                    <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <div class="sm:col-span-3">
                            <label for="assigned_agent" class="block text-sm font-medium leading-5 text-gray-700">
                                {{ __('Agent') }}
                            </label>
                            <div class="mt-1 rounded-md shadow-sm">
                                <select id="assigned_agent" name="assigned_agent_id"
                                        class="form-select block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" required>
                                    <option value="{{ \Auth::user()->id }}" selected>{{ \Auth::user()->name }}</option>
                                    @foreach(App\Models\User::all() as $user)
                                        @if($user->id !== \Auth::user()->id)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-8 border-t border-gray-200 pt-5">
                <div class="flex justify-start">
                  <span class="inline-flex rounded-md shadow-sm">
                    <a href="{{route('tickets.index')}}"
                            class="py-2 px-4 border border-gray-300 rounded-md text-sm leading-5 font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-50 active:text-gray-800 transition duration-150 ease-in-out">
                      Cancel
                    </a>
                  </span>
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

</x-app-layout>
