<x-jet-form-section submit="createProject">
    <x-slot name="title">
        {{ __('New Project') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Create a new project to set up REDCap minimisation.') }}
    </x-slot>

    <x-slot name="form">
        <div class="col-span-6 space-y-1">
            <x-jet-validation-errors/>
        </div>


        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="name" value="{{ __('Project name') }}"/>
            <x-jet-input id="name" type="text" class="mt-1 block w-full"
                         wire:model.debounce.200ms="name" autofocus/>
            <x-jet-input-error for="name" class="mt-2"/>
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="url" value="{{ __('REDCap url') }}"/>
            <x-jet-input id="url" type="text" class="mt-1 block w-full"
                         wire:model.debounce.200ms="url" autofocus/>
            <x-jet-input-error for="url" class="mt-2"/>
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="token" value="{{ __('REDCap token') }}"/>
            <x-jet-input id="token" type="text" class="mt-1 block w-full"
                         wire:model.debounce.200ms="token"
                         autofocus/>
            <x-jet-input-error for="token" class="mt-2"/>
        </div>

        <div class="col-span-6 space-y-1">
            <div wire:loading.block wire:target="createProject">
                <x-forms.loading-svg message="Creating new project"/>
            </div>
        </div>

    </x-slot>

    <x-slot name="actions">
        <x-jet-button>
            {{ __('Create') }}
        </x-jet-button>
    </x-slot>


</x-jet-form-section>

