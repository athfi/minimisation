<x-project-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg ">
                <div class="lg:text-center">
                    <h2 class="bg-indigo-600 font-semibold py-2 px-4 text-3xl text-base text-white tracking-wide uppercase">
                        RECORDS
                    </h2>
                </div>
                <div class="w-full max p-6 m-auto">
                    <table class="border-collapse border border-green-800 items-start align-baseline mx-auto">
                        <tr>
                            <td class="border border-green-600 text-2xl uppercase px-4">Participant ID</td>
                            <td class="border border-green-600 text-2xl uppercase px-4">Group</td>
                            <td class="border border-green-600 text-2xl uppercase px-4">Randomisation time</td>
                            <td class="border border-green-600 text-2xl uppercase px-4">Action</td>
                        </tr>
                        @foreach($records as $record)
                            <tr>
                               <td class="border border-green-600 px-4">
                                   <a href="{{ route('project-record', ['project' => $projectId, 'id' => $record[$field['recordId']] ])}}">
                                       {{$record[$field['recordId']]}}
                                   </a>
                               </td>
                                <td class="border border-green-600 px-4">
                                    {{$record[$field['randGroup']]!=''?$record[$field['randGroup']]:'-'}}
                                </td>
                                <td class="border border-green-600 px-4">
                                    {{$record[$field['randTime']]??'-'}}
                                </td>
                                <td class="border border-green-600 px-4 text-center">
                                    <a class="text-green-500 hover:text-red-500"
                                       href="{{ route('project-record', ['project' => $projectId, 'id' => $record[$field['recordId']] ])}}">
                                        view
                                    </a>
                                @if($record[$field['randGroup']]=='')
                                    <a class="text-green-500 hover:text-red-500" href="{{ route('randomise').'/'.$record[$field['recordId']] }}">randomise</a>
                                @endif
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>

</x-project-layout>
