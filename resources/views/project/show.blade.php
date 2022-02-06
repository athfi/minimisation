<x-project-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        @livewire('project.show-project-form',['project' => $project])
    </div>

</x-project-layout>
