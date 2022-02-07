<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Projects') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto p-2">
            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div
                        class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div
                            class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg bg-white">
                                <table
                                    class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-fhosting-blue-200">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 w-4 text-left text-sm font-medium text-gray-600 uppercase tracking-wider">
                                            No
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-sm font-medium text-gray-600 uppercase tracking-wider">
                                            Project name
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-sm font-medium text-gray-600 uppercase tracking-wider">
                                            Records
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-sm font-medium text-gray-600 uppercase tracking-wider">
                                            Randomised
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-sm font-medium text-gray-600 uppercase tracking-wider">
                                            Status
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody
                                        class="bg-white divide-y divide-gray-200">
                                    @if($projects->count()<1)
                                        <tr>
                                            <td colspan="5" class="px-6 py-4 whitespace-nowrap text-center">
                                                No projects found. Please create a new project to setup new minimization.
                                            </td>
                                        </tr>
                                    @else
                                    @foreach($projects as $project)
                                        <tr class="hover:bg-gray-200">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div
                                                    class="text-sm font-medium text-gray-900">{{$loop->index + 1}}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <a class="text-blue-600 hover:blue-900 hover:underline"
                                                   href="{{route('project-show', ['project' => $project]);}}">
                                                    {{$project->name}}
                                                </a>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">
                                                    30
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">
                                                    25
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    Development
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @endif
                                    </tbody>
                                </table>
                            <hr>
                            <div
                                class="text-center px-4 py-3 bg-fhosting-blue-50">
                                <a href="{{URL::route('project-create');}}"
                                   class="text-indigo-600 hover:text-indigo-900 hover:underline">Create
                                    new project</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
