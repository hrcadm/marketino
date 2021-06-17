<x-app-layout>
  <x-slot name="header">
    {{ __('3CX Customers') }}

  </x-slot>

  <div class="px-4 py-1 bg-white border-b border-gray-200">


    <table class="table-auto w-full mb-2">
      <thead>
        <tr>
          <th class="px-4 py-2">{{ __('Name and Lastname') }}</th>
          <th class="px-4 py-2">{{ __('Obrt / VAT') }}</th>
          <th class="px-4 py-2">{{ __('Tel / Email') }}</th>
          <th class="px-4 py-2">{{ __('Address') }}</th>
          <th class="px-4 py-2" style="min-width: 165px">{{ __('Options') }}</th>
        </tr>
      </thead>
      <tbody>
        @foreach($customers as $customer)
        <tr>
          <td class="border px-4 py-2">{{ $customer->first_name .' '.$customer->last_name }}</td>
          <td class="border px-4 py-2">
            <a href="{{ route('customers.show', ['customer' => $customer]) }}">{{ $customer->company_name }} <br> {{ $customer->vat_number }}</a>
          </td>
          <td class="border px-4 py-2">
            @if($customer->phone())
            <a href="tel:{{ $customer->phone()->phone }}">{{ $customer->phone()->phone }}</a><br>
            @endif
            @if($customer->email())
            {{ $customer->email()->email }}
            @endif
          </td>
          <td class="border px-4 py-2">
            {{ $customer->address }}
          </td>
          <td class="border px-4 py-2">
            @if($customer->phone())
            <a href="tel:{{ $customer->phone()->phone }}" class="text-center bg-blue-400 hover:bg-blue-600 text-white py-0 px-2 my-1 rounded block w-full">
              {{ __('Call') }}
            </a>
            <button class="text-center bg-blue-400 hover:bg-blue-600 text-white py-0 px-2 my-1 rounded block w-full copy" data-toggle="copy" data-text="{{ $customer->phone()->phone }}">
              {{ __('Copy') }}
            </button>
            @endif
            <a href="{{ route('customers.show', ['customer' => $customer]) }}" class="text-center bg-blue-500 hover:bg-blue-700 text-white py-0 px-2 my-1 rounded block w-full">
              {{ __('Contact details') }}
            </a>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</x-app-layout>

