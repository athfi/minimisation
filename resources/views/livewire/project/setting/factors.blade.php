
<div class="overflow-x-auto">
    <div x-data="{ open: false }" class="py-2 inline-block min-w-full px-2">
{{--        @dump($factors)--}}
        <div class="text-right">
            <button @click.prevent="open = true;  $nextTick(() => { setTimeout(() => { document.getElementById('newName').focus(); }, 300);});"
                    class="bg-gray-900 hover:bg-gray-700 text-white font-bold mb-2 py-2 px-4 rounded"
            >
                New factor
            </button>
        </div>
        <table class="text-center w-full">
            <!-- Table header -->
            <thead class="justify-between">
            <tr class="bg-gray-900">

                <th class="px-2 py-2">
                    <span class="text-white">Name</span>
                </th>
                <th class="px-2 py-2">
                    <span class="text-white">Type</span>
                </th>
                <th class="px-2 py-2">
                    <span class="text-white">Config</span>
                </th>
                <th class="px-2 py-2">
                    <span class="text-white">Action</span>
                </th>
            </tr>
            </thead>
            <tbody class="bg-gray-200">
            <!-- Add popup -->
            <tr class="bg-white border-4 border-gray-200 hover:bg-gray-100"
                x-cloak
                x-show.transition.duration.500="open"
                @click.away="open = !open"
            >

                <td>
                    <input
                        type="text"
                        autocomplete="off"
                        id="newName"
                        autofocus
                        wire:model.defer='name'
                        name="name"
                        class="w-full rounded h-6 border border-gray-900 focus:ring-1 focus:ring-gray-900 "
                    >
                </td>
                <td>
                    <input type="number"
                           autocomplete="off"
                           wire:model.defer='ratio'
                           name="ratio"
                           class="w-32 rounded h-6 border border-gray-900 focus:ring-1 focus:ring-gray-900 "
                    >
                </td>

                <td class=" content-center">
                    <button wire:loading.remove @click="$wire.addNewGroup; open = !open; "
                            class="content-center bg-green-600 hover:bg-green-700 text-white font-bold px-4 rounded">
                        save
                    </button>
                </td>
            </tr>


            @foreach ($factors as $factor)
                <!-- content  -->
                    <tr class="bg-white border-4 border-gray-200 hover:bg-gray-100">

                        <td class="text-left">
                            @if ($factor['id'] == $editedFactor)
                                <div @click.away="$wire.editedFactor = null">
                                    <input wire:model.defer="editName" id="editName" type="text" class="w-max rounded h-6 border border-gray-900 focus:ring-1 focus:ring-gray-900 ">
                                    <button wire:click="editNameSubmit"  class="w-min content-center bg-green-600 hover:bg-green-700 text-white font-bold px-4 rounded">save</button>
                                </div>
                            @else
                                <span class="ml-2 font-semibold">{{$factor['name']}}</span>
                                <span class="text-blue-500 inline-block mr-1 cursor-pointer" title="edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" wire:click='editName("{{$factor['id']}}")'>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                            </span>
                            @endif
                        </td>
                        <td class="text-left">
                            <span class="ml-2 font-semibold">{{$factor['type']}}</span>
                        </td>
                        <td class="px-2 py-2">
                            @if($factor['type'] == "Radio")
                                <div class="text-left">
                                    Field name: {{$factor['config']['fieldName']}} <br>
                                    Levels:
                                </div>
                                <table class="w-full">
                                    <thead class="justify-between">
                                    <tr class="bg-gray-100">
                                        <th class="px-2 py-2">
                                            <span class="text-gray-600 text-sm">Name</span>
                                        </th>
                                        <th class="px-2 py-2">
                                            <span class="text-gray-600 text-sm">Coded Value</span>
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>

                                    </tr>
                                    </tbody>
                                </table>
                            @else()
                            <table class="w-full">
                                <thead class="justify-between">
                                <tr class="bg-gray-100">

                                    <th class="px-2 py-2">
                                        <span class="text-gray-600 text-sm">Name</span>
                                    </th>
                                    <th class="px-2 py-2">
                                        <span class="text-gray-600 text-sm">Formula</span>
                                    </th>
                                    <th class="px-2 py-2">
                                        <span class="text-gray-600 text-sm"></span>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $levels = collect($factor['config'])->sortByDesc('priority');
                                @endphp
                                @foreach ($levels as $level)
                                    <tr>
                                        <td>{{$level['name']}}</td>
                                        <td>{{$level['formula']}}</td>
                                        <td>edit</td>
                                    </tr>

                                @endforeach

                                </tbody>
                            </table>
                            @endif

                        </td>
                        <td class="px-2 py-2 align-midle">
                            <span class="text-red-500 inline-block ml-1 cursor-pointer" title="Delete &quot;{{$factor['name']}}&quot;">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                         x-on:click="confirm('Are you sure want to delete &quot;{{$factor['name']}}&quot;?') ? @this.deleteRow('{{$factor['id']}}') : false"
                                    >
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                            </span>
                        </td>
                    </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
    <script>
        window.addEventListener('livewire:load', () => {
        @this.on('editFactorName', () => {
            document.querySelector('#editName').focus()
        })
        @this.on('newGroup', () => {
            document.querySelector('#newName').focus()
        })
        })
    </script>
@endpush
