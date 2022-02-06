<x-project-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Minimisation Setting') }}
        </h2>
    </x-slot>

    <div class="py-6 ">
        <div
            class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="text-center">
                @if (session()->has('message'))
                    <div class="alert alert-success">
                        {{ session('message') }}
                    </div>
                @endif
            </div>


            <x-section >
                <x-slot name="title">
                    {{ __('Groups') }}
                </x-slot>

                <x-slot name="description">
                    {{ __('Set minimisation groups') }}
                </x-slot>

                <x-slot name="content">
                    <div class="col-span-6 sm:col-span-6">
                    @livewire('project.setting.groups', ['project_id' => $project->id, 'groups' => (array) $project->setting['group']], key('project'.$project->id))
                    </div>
                </x-slot>

            </x-section>

            <x-spacer />

            <x-section >
                <x-slot name="title">
                    {{ __('Factor') }}
                </x-slot>

                <x-slot name="description">
                    {{ __('Set minimisation factor') }}
                </x-slot>

                <x-slot name="content">
                    <div class="col-span-6 sm:col-span-6">
                        @livewire('project.setting.factors', ['project_id' => $project->id, 'factors' => (array) $project->setting['factor']], key('project'.$project->id))
                    </div>
                </x-slot>
            </x-section>

            <x-spacer />

            <x-jet-form-section submit="createProject">
                <x-slot name="title">
                    {{ __('Minimisation method') }}
                </x-slot>

                <x-slot name="description">
                    {{ __('Set up minimisation method for this project.') }}
                </x-slot>

                <x-slot name="form">
                    <div class="col-span-6 space-y-1">
                        <x-jet-validation-errors/>
                    </div>


                    <div class="col-span-6 sm:col-span-4">
                        <x-jet-label for="probability"
                                     value="{{ __('Probability method') }}"/>
                        <x-jet-input id="probability" type="text"
                                     class="mt-1 block w-full"
                                     value="naive method"
                                     disabled
                                     />
                        <x-jet-input-error for="name" class="mt-2"/>
                    </div>

                    <div class="col-span-6 sm:col-span-4">
                        <x-jet-label for="distance" value="{{ __('Distance Neasure') }}"/>
                        <x-jet-input id="distance" type="text"
                                     class="mt-1 block w-full"
                                     value="Range"
                                     disabled
                        />
                        <x-jet-input-error for="url" class="mt-2"/>
                    </div>

                    <div class="col-span-6 sm:col-span-4">
                        <x-jet-label for="highProbability"
                                     value="{{ __('High Probability') }}"/>
                        <x-jet-input id="highProbability" type="text"
                                     class="mt-1 block w-full"
                                     value="90%"
                                     disabled
                                     />
                        <x-jet-input-error for="token" class="mt-2"/>
                    </div>


                </x-slot>



            </x-jet-form-section>

        </div>
    </div>

</x-project-layout>
