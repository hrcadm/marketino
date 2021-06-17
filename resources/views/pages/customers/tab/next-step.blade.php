<x-show-customer-component :customer="$customer">

    <x-slot name="header">
        {{ __('Customer details') }}

    </x-slot>

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


    <div class="bg-gray-100 rounded px-3 pt-1 my-4" id="nextStep-form">
        <form class="my-6" role="form" method="POST" action="{{ route('next-steps.store',['customer_id'=>$customer->id]) }}">
            @csrf

            <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                <div class="sm:col-span-2">
                    <x-jet-label value="{{ __('Date') }}" data-date-inline-picker="true"></x-jet-label>
                    <x-jet-input class="block mt-1 w-full" type="date" name="date"
                    :value="old('date', Carbon\Carbon::now()->format('Y-m-d'))" required autofocus></x-jet-input>
                </div>
                <div class="sm:col-span-4">
                    <x-jet-label value="{{ __('Comment') }}"></x-jet-label>
                    <x-jet-input class="block mt-1 w-full" type="text" name="comment"
                    :value="old('comment')" required autofocus maxlength="255"></x-jet-input>
                </div>
            </div>
            <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                <div class="sm:col-span-2">
                  <x-jet-label value="{{ __('For quote') }}"></x-jet-label>
                  <div class="relative">
                    <select class="block appearance-none w-full border shadow-sm border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="quote_id" name="quote_id">
                      <option value="">-</option>
                      @foreach($customer->quotes as $quote)
                      <option value="{{ $quote->id }}">{{ $quote->document_number }} // € {{ $quote->total_amount }} // {{ $quote->created_at }}</option>
                      @endforeach
                  </select>
                  <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                      <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                  </div>
              </div>
          </div>
          <div class="sm:col-span-4">
            <div class="flex items-center justify-end mt-4">
                <div class="inline-block py-4 mr-4">
                    <label class="flex items-center">
                        <input type="checkbox" class="form-checkbox" name="resolved" value="1">
                        <span class="ml-2 text-sm text-gray-600">{{ __('Resolved') }}</span>
                    </label>
                </div>


                <x-jet-button class="ml-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    {{ __('Save') }}
                </x-jet-button>
            </div>
        </div>
    </div>


</form>
</div>

<div>

    <table class="table-auto w-full">
        <thead>
            <h2 class="px-4">{{ __('History of interaction') }}:</h2>

            <tr>
                <th class="px-4 py-2">{{ __('Date') }}</th>
                <th class="px-4 py-2">{{ __('Comment') }}</th>
                <th class="px-4 py-2">{{ __('Status') }}</th>
                <th class="px-4 py-2">{{ __('User') }}</th>
                <th class="px-4 py-2">{{ __('Options') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($customer->nextSteps as $nextStep)
            <tr class="{{ $nextStep->resolved ? 'bg-green-100' : (\Carbon\Carbon::today() > $nextStep->date ? 'bg-gray-100' : '') }}">
             <td class="border px-4 py-2">{{ $nextStep->date->format('d.m.Y') }}</td>
             <td class="border px-4 py-2">{{ $nextStep->comment }}</td>
             <td class="border px-4 py-2">{{ $nextStep->resolved ? 'resolved' : 'in progress' }}</td>
             <td class="border px-4 py-2">{{ $nextStep->user ? $nextStep->user->name : 'not assigned' }}</td>
             <td class="border px-4 py-2">
                @if(! $nextStep->resolved)
                <form action="{{ route('next-steps.update', $nextStep) }}" method="POST">
                  @csrf
                  @method('PATCH')
                  <input type="hidden" name="resolved" value="1">
                  <button class="modal-open text-center bg-green-400 hover:bg-green-600 text-white py-0 px-2 my-1 rounded block w-full" type="submit">
                    {{ __('Set resolved') }}
                </button>
                @endif
            </form>
        </td>
    </tr>
    @endforeach
</tbody>
</table>

</div>

</x-show-customer-component>
