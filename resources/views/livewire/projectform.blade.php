<x-jet-form-section submit="createProject">
    <x-slot name="title">
        {{ __('New Project') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Create a new project to set up REDCap minimisation.') }}
    </x-slot>

    <x-slot name="form">
        <div class="col-span-6">
            <x-jet-label value="{{ __('Project details') }}" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="url" value="{{ __('Project Url') }}" />
            <x-jet-input id="url" type="text" class="mt-1 block w-full" wire:model.defer="state.url" autofocus />
            <x-jet-input-error for="url" class="mt-2" />
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-jet-button>
            {{ __('Create') }}
        </x-jet-button>
    </x-slot>
</x-jet-form-section>
