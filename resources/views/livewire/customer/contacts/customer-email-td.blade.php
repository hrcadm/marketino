<tr>

    <td class="px-6 py-4 whitespace-no-wrap">
        <x-jet-input type="text"
                     class="text-sm leading-5 text-gray-900"
                     wire:model="customerEmail.email"
        ></x-jet-input>
    </td>

    <td class="px-6 py-4 whitespace-no-wrap text-center">
        @if($customerEmail->is_default)
            <span class="hint--top" aria-label="Ovaj E-mail je primarni">
                <i class="fas fa-check-circle fa-2x text-indigo-600"></i>
            </span>
        @endif
    </td>

    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 font-medium">
        <span wire:click="updateEmail"
              class="text-indigo-600 hover:text-indigo-900 hint--top-left"
              aria-label="Ažuriraj"
        >
            <i class="fas fa-save fa-2x"></i>
        </span>

        <span wire:click="showDeleteModal"
              class="text-indigo-600 hover:text-indigo-900 ml-2 hint--top-left"
              aria-label="Obriši"
        >
            <i class="fas fa-trash-alt fa-2x"></i>
        </span>

        @if(!$customerEmail->is_default)
            <span wire:click="makeEmailPrimary"
                  class="text-indigo-600 hover:text-indigo-900 ml-2 hint--top-left"
                  aria-label="Postavi primarnim"
            >
                <i class="fas fa-chevron-circle-up fa-2x"></i>
            </span>
        @endif

        <x-jet-confirmation-modal wire:model="showDeleteModal">
            <x-slot name="title">
                {{ __('Brisanje E-maila') }}
            </x-slot>

            <x-slot name="content">
                {{ __('Jeste li sigurni da želite obrisati ovaj E-mail?') }}
            </x-slot>

            <x-slot name="footer">
                <x-jet-secondary-button wire:click="$toggle('showDeleteModal')" wire:loading.attr="disabled">
                    {{ __('Odustani') }}
                </x-jet-secondary-button>

                <x-jet-danger-button class="ml-2" wire:click="deleteEmail" wire:loading.attr="disabled">
                    {{ __('Obriši') }}
                </x-jet-danger-button>
            </x-slot>
        </x-jet-confirmation-modal>

    </td>
</tr>
