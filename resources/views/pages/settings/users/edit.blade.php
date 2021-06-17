<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      @if(\Request::is('*edit'))
      {{ __('User') }} - {{ $user->name }}
      @else
      {{ __('Create User') }}
      @endif
    </h2>
  </x-slot>

  <x-slot name="actions">
    <div class="mt-4 flex md:mt-0 md:ml-4">
      <span class="ml-3 shadow-sm rounded-md">
        <a href="{{ route('settings.users.index') }}"
        class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:shadow-outline-indigo focus:border-indigo-700 active:bg-indigo-700 transition duration-150 ease-in-out">
        {{ __('Users list') }}
      </a>
    </span>
  </div>
</x-slot>



<div>
  <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
    <div wire:id="MzizcKe7wmZ3OemoLfQV" class="md:grid md:grid-cols-3 md:gap-6">
      <div class="md:col-span-1">
        <div class="px-4 sm:px-0">
          <h3 class="text-lg font-medium text-gray-900">Profile Information</h3>
          <p class="mt-1 text-sm text-gray-600">
            @if(\Request::is('*edit'))
            {{ __('Update :name account\'s profile information.', ['name' => $user->name]) }}
            @else
            {{ __('Insert account\'s profile information.') }}
            @endif
          </p>
        </div>
      </div>


      <div class="mt-5 md:mt-0 md:col-span-2">
        <form action="{{ \Request::is('*create') ? route('settings.users.store') : route('settings.users.update', $user) }}" method="POST" autocomplete="off" enctype="multipart/form-data">
          @if(\Request::is('*edit'))
          {{ method_field('PATCH') }}
          @endif
          @csrf
          <div class="shadow overflow-hidden sm:rounded-md">
            <div class="px-4 py-5 bg-white sm:p-6">
              <div class="grid grid-cols-6 gap-6">
                <div class="col-span-6 sm:col-span-4">
                  <label class="block font-medium text-sm text-gray-700" for="name">Name</label>
                  <input class="form-input rounded-md shadow-sm mt-1 block w-full" type="text" name="name" value="{{ old('name', $user->name) }}" required>
                </div>
                <div class="col-span-6 sm:col-span-4">
                  <label class="block font-medium text-sm text-gray-700" for="email">Email</label>
                  <input class="form-input rounded-md shadow-sm mt-1 block w-full" type="email" name="email" value="{{ old('email', $user->email) }}" required>
                  @if ($errors->has('email'))
                  <p class="text-sm text-red-600 mt-2">{{ $errors->first('email') }}</p>
                  @endif
                </div>
                <div class="col-span-6 sm:col-span-4">
                  <label class="block font-medium text-sm text-gray-700" for="password">Password</label>
                  <input class="form-input rounded-md shadow-sm mt-1 block w-full" type="password" name="password" value="{{ old('password') }}" @if(! \Request::is('*edit')) required @endif>
                  @if ($errors->has('password'))
                  <p class="text-sm text-red-600 mt-2">{{ $errors->first('password') }}</p>
                  @endif
                </div>
                <div class="col-span-6 sm:col-span-4">
                  <label class="block font-medium text-sm text-gray-700" for="role">Role</label>
                  @foreach(\App\Enum\UserRole::getValues() as $role)
                  <div class="flex items-center">
                    <input id="role-{{ $role }}" name="role" type="radio" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300" value="{{ $role }}" required {{ $user->hasRole($role) ? 'checked' : '' }}>
                    <label for="role-{{ $role }}" class="ml-3 block text-sm font-medium text-gray-700">
                      {{ $role }}
                    </label>
                  </div>
                  @endforeach
                  @if ($errors->has('role'))
                  <p class="text-sm text-red-600 mt-2">{{ $errors->first('role') }}</p>
                  @endif
                </div>
                <div class="col-span-6 sm:col-span-4">
                  <label class="block font-medium text-sm text-gray-700" for="groups">{{ __('Groups') }}</label>
                  @foreach(\App\Enum\UserGroup::getValues() as $group)
                  <div class="flex items-center">
                    <input id="group-{{ $group }}" name="groups[]" type="checkbox" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300" value="{{ $group }}" {{ $user->hasGroup($group) ? 'checked' : '' }}>
                    <label for="group-{{ $group }}" class="ml-3 block text-sm font-medium text-gray-700">
                      {{ $group }}
                    </label>
                  </div>
                  @endforeach
                  @if ($errors->has('groups'))
                  <p class="text-sm text-red-600 mt-2">{{ $errors->first('groups') }}</p>
                  @endif
                </div>
              </div>
            </div>

            <div class="flex items-center justify-end px-4 py-3 bg-gray-50 text-right sm:px-6">

              <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150">
                Save
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
    @if(\Request::is('*edit'))
    <x-jet-section-border />

    <div class="mt-10 sm:mt-0">
      <div class="md:grid md:grid-cols-3 md:gap-6">
        <div class="md:col-span-1">
          <div class="px-4 sm:px-0">
            <h3 class="text-lg font-medium text-gray-900">Delete Account</h3>

            <p class="mt-1 text-sm text-gray-600">
              Permanently delete your account.
            </p>
          </div>
        </div>


        <div class="mt-5 md:mt-0 md:col-span-2">
          <div class="px-4 py-5 sm:p-6 bg-white shadow sm:rounded-lg">
            <div class="max-w-xl text-sm text-gray-600">
              Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.
            </div>

            <div class="mt-5">


              <form id="delete-form" method="POST" action="{{ route('settings.users.destroy', $user) }}">
                @csrf
                {{ method_field('DELETE') }}
                <button class="inline-flex items-center justify-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 focus:outline-none focus:border-red-700 focus:shadow-outline-red active:bg-red-600 transition ease-in-out duration-150" type="submit" form="delete-form">
                  Delete Account
                </button>
              </form>


            </div>
          </div>
        </div>
      </div>
    </div>
    @endif
  </div>
</div>


</x-app-layout>

