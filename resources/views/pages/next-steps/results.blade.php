<table class="table-auto w-full mb-2">
  <thead>
    <tr>
      <th class="px-4 py-2">ID</th>
      <th class="px-4 py-2">{{ __('Name and Lastname') }}</th>
      <th class="px-4 py-2">{{ __('Obrt / VAT') }}</th>
      <th class="px-4 py-2">{{ __('Tel / Email') }}</th>
      <th class="px-4 py-2">{{ __('NextStep') }}</th>
      <th class="px-4 py-2" style="min-width: 165px">{{ __('Options') }}</th>
    </tr>
  </thead>
  <tbody>
    @foreach($nextSteps as $nextStep)
    <tr class="{{ $nextStep->resolved ? 'bg-green-80' : '' }}">
      <td class="border px-4 py-2">{{ $nextStep->id }}</td>
      <td class="border px-4 py-2">{{ $nextStep->customer->first_name .' '.$nextStep->customer->last_name }}</td>
      <td class="border px-4 py-2">
        <a href="{{ route('customers.show', ['customer' => $nextStep->customer]) }}">{{ $nextStep->customer->company_name }} <br> {{ $nextStep->customer->vat_number }}</a>
      </td>
      <td class="border px-4 py-2">
        @if($nextStep->customer->phone())
        <a href="tel:{{ $nextStep->customer->phone()->phone }}">{{ $nextStep->customer->phone()->phone }}</a><br>
        @endif
        @if($nextStep->customer->email())
        {{ $nextStep->customer->email()->email }}
        @endif
      </td>
      <td class="border px-4 py-2">
        {{ $nextStep->comment }} <br>
        @if($nextStep->quote)
        Quote: <a href="{{ route('downloadPdf', ['id' => $nextStep->quote->id]) }}">{{ $nextStep->quote->document_number }} // € {{ $nextStep->quote->total_amount }}</a><br>
        @endif
        {{ $nextStep->user ? $nextStep->user->name : '' }} / Date: {{ $nextStep->date->format('d.m.Y') }} <br>
        @if($nextStep->resolved)
        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
          {{ __('Resolved') }}
        </span>
        @elseif(\Carbon\Carbon::today() > $nextStep->date)
        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
          {{ __('Expired') }}
        </span>
        @else
        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
          {{ __('In progress') }}
        </span>
        @endif
        @if($nextStep->quote)
        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">
          {{ __('Quote') }}
        </span>
        @endif
      </td>
      <td class="border px-4 py-2">
        @if($nextStep->customer->phone())
        <a href="tel:{{ $nextStep->customer->phone()->phone }}" class="text-center bg-blue-400 hover:bg-blue-600 text-white py-0 px-2 my-1 rounded block w-full">
          {{ __('Call') }}
        </a>
        <button class="text-center bg-blue-400 hover:bg-blue-600 text-white py-0 px-2 my-1 rounded block w-full copy" data-toggle="copy" data-text="{{ $nextStep->customer->phone()->phone }}">
          {{ __('Copy') }}
        </button>
        @endif
        <a href="{{ route('customers.show', ['customer' => $nextStep->customer]) }}" class="text-center bg-blue-500 hover:bg-blue-700 text-white py-0 px-2 my-1 rounded block w-full">
          {{ __('Contact details') }}
        </a>
        @if($nextStep->data)
        <button class="modal-open text-center bg-yellow-400 hover:bg-yellow-600 text-white py-0 px-2 my-1 rounded block w-full" data-toggle="modal" data-target="#modal-data-{{ $nextStep->id }}">
          {{ __('Data') }}
        </button>

        @endif
        <button class="modal-open text-center bg-orange-400 hover:bg-orange-600 text-white py-0 px-2 my-1 rounded block w-full" data-toggle="modal" data-target="#modal-set-next-step-{{ $nextStep->id }}">
          {{ __('Set nextstep') }}
        </button>
        @if(! $nextStep->resolved)
        <form action="{{ route('next-steps.update', $nextStep) }}" method="POST">
          @csrf
          @method('PATCH')
          <input type="hidden" name="resolved" value="1">
          <button class="modal-open text-center bg-green-400 hover:bg-green-600 text-white py-0 px-2 my-1 rounded block w-full" type="submit">
            {{ __('Set resolved') }}
          </button>
        </form>
        @endif
      </td>
    </tr>
    @endforeach
  </tbody>
</table>
<div class="pagination">  
{{ $nextSteps->appends(request()->input())->links() }}
</div>

@foreach($nextSteps as $nextStep)
@if($nextStep->data)
<div class="fixed z-10 inset-0 overflow-y-auto hidden" id="modal-data-{{ $nextStep->id }}">
  <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
    <div class="fixed inset-0 transition-opacity">
      <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
    </div>

    <!-- This element is to trick the browser into centering the modal contents. -->
    <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>&#8203;
    <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
      <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
        <div class="sm:flex sm:items-start">
          <div class="mt-3 text-center sm:mt-0 sm:text-left d-block w-full">
            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-headline">
              {{ $nextStep->id .' '.$nextStep->comment }} - {{ __('Data') }}
              <span class="close float-right cursor-pointer" data-target="#modal-data-{{ $nextStep->id }}">&times;</span>
            </h3>
            <div class="mt-2">
              <div class="bg-gray-100 rounded px-3 pt-1 my-4 w-full break-words">
                {{ $nextStep->data }}
              </div>


            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endif
<div class="fixed z-10 inset-0 overflow-y-auto hidden" id="modal-set-next-step-{{ $nextStep->id }}">
  <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
    <div class="fixed inset-0 transition-opacity">
      <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
    </div>

    <!-- This element is to trick the browser into centering the modal contents. -->
    <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>&#8203;
    <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
      <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
        <div class="sm:flex sm:items-start">
          <div class="mt-3 text-center sm:mt-0 sm:text-left">
            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-headline">
              {{ $nextStep->customer->first_name .' '.$nextStep->customer->last_name }} - Next steps
              <span class="close float-right cursor-pointer" data-target="#modal-set-next-step-{{ $nextStep->id }}">&times;</span>
            </h3>
            <div class="mt-2">
              <div class="bg-gray-100 rounded px-3 pt-1 my-4" id="nextStep-form">
                <form class="my-6" role="form" method="POST" action="{{ route('next-steps.store',['customer_id'=>$nextStep->customer->id, 'old_nextstep' => $nextStep->id]) }}">
                  @csrf
                  <input type="hidden" name="quote_id" value="{{ $nextStep->quote_id ?? '' }}">
                  <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    <div class="sm:col-span-3">
                      <x-jet-label value="{{ __('Date') }}"></x-jet-label>
                      <x-jet-input class="block mt-1 w-full" type="date" name="date"
                      :value="old('date', Carbon\Carbon::now()->format('Y-m-d'))" required autofocus></x-jet-input>
                    </div>
                    <div class="sm:col-span-3">
                      <x-jet-label value="{{ __('Comment') }}"></x-jet-label>
                      <x-jet-input class="block mt-1 w-full" type="text" name="comment"
                      :value="old('comment')" required autofocus max="1"></x-jet-input>
                    </div>
                  </div>
                  <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    @if($nextStep->quote)
                    <div class="sm:col-span-2">
                      <x-jet-label value="{{ __('For quoute') }}"></x-jet-label>
                      <a href="{{ route('downloadPdf', ['id' => $nextStep->quote->id]) }}">{{ $nextStep->quote->number }} // € {{ $nextStep->quote->total_amount }}</a>
                    </div>
                    @endif
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
              <table class="table-auto w-full">
                <thead>
                  <h2 class="px-4">{{ __('History of interaction') }}:</h2>

                  <tr>
                    <th class="px-4 py-2">{{ __('Date') }}</th>
                    <th class="px-4 py-2">{{ __('Comment') }}</th>
                    <th class="px-4 py-2">{{ __('Status') }}</th>
                    <th class="px-4 py-2">{{ __('User') }}</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($nextStep->customer->nextSteps as $row)
                  <tr>
                   <td class="border px-4 py-2">{{ $row->date->format('Y.m.d') }}</td>
                   <td class="border px-4 py-2">{{ $row->comment }}</td>
                   <td class="border px-4 py-2">{{ $row->resolved ? 'resolved' : 'in progress' }}</td>
                   <td class="border px-4 py-2">{{ $row->user ? $nextStep->user->name ?? '' : 'not assigned' }}</td>
                 </tr>
                 @endforeach
               </tbody>
             </table>


           </div>
         </div>
       </div>
     </div>
   </div>
 </div>
</div>
@endforeach