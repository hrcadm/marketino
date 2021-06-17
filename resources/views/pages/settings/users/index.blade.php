<x-app-layout>
  <x-slot name="header">
    {{ __('Users') }}
  </x-slot>

  <x-slot name="actions">
    <div class="mt-4 flex md:mt-0 md:ml-4">
      <span class="ml-3 shadow-sm rounded-md">
        <a href="{{ route('settings.users.create') }}"
        class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:shadow-outline-indigo focus:border-indigo-700 active:bg-indigo-700 transition duration-150 ease-in-out">
        {{ __('Create new user') }}
      </a>
    </span>
  </div>
</x-slot>

<!-- This example requires Tailwind CSS v2.0+ -->
<div class="flex flex-col">
  <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
      <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
          <thead>
            <tr>
              <th scope="col" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                {{ __('Name') }}
              </th>
              <th scope="col" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                {{ __('Email') }}
              </th>
              <th scope="col" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                {{ __('Role') }}
              </th>
              <th scope="col" class="px-6 py-3 bg-gray-50">
                <span class="sr-only">{{ __('Edit') }}</span>
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            @foreach($users as $user)
            <tr>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div class="flex-shrink-0 h-10 w-10">
                    <img class="h-10 w-10 rounded-full" src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}">
                  </div>
                  <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900">
                      {{ $user->name }}
                    </div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">{{ $user->email }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                @foreach($user->roles->pluck('name') as $role)
                {{ $role }}
                @endforeach
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <a href="{{ route('settings.users.edit', $user) }}" class="text-indigo-600 hover:text-indigo-900">{{ __('Edit') }}</a>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
        <div class="pagination bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
          {{ $users->links() }}
        </div>
      </div>
    </div>
  </div>
</div>


</x-app-layout>

