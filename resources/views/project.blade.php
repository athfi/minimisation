<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg ">
                <div class="lg:text-center">
                    <h2 class="bg-indigo-600 font-semibold py-2 px-4 text-3xl text-base text-white tracking-wide uppercase">REDCap Project</h2>
                </div>
                <form class="w-full max-w-screen-md p-6 m-auto">
                    @foreach($project as $key => $data)
                        <div class="md:flex md:items-center mb-6 md:flex-1 w-full">
                            <div class="md:w-1/3">
                                <x-jet-label value="{{$key}}" />
                            </div>
                            <div class="md:w-2/3">
                                <x-jet-label value="{{$data}}" class="p-2 border-gray-600 bg-gray-200 rounded-md shadow-sm overflow-y-auto" />
                            </div>
                        </div>
                    @endforeach
                </form>
            </div>
        </div>
    </div>

</x-app-layout>
