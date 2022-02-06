
<div class="overflow-x-auto">
    <div x-data="{ open: false }" class="py-2 inline-block min-w-full px-2">
        <div class="text-right">
            <button @click.prevent="open = true;  $nextTick(() => { setTimeout(() => { document.getElementById('newName').focus(); }, 300);});"
                    class="bg-gray-900 hover:bg-gray-700 text-white font-bold mb-2 py-2 px-4 rounded"
            >
                New group
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
                    <span class="text-white">Ratio</span>
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
            @foreach ($groups as $group)
                @if ($group['id'] == $editedRow)
                    <!-- edit form -->
                        <tr class="bg-white border-4 border-gray-200 hover:bg-gray-100" @click.away="$wire.editedRow = null">
                            <td>
                                <input wire:model.defer="editname" id="editname" type="text" class="w-full rounded h-6 border border-gray-900 focus:ring-1 focus:ring-gray-900 ">
                            </td>
                            <td>
                                <input wire:model.defer="editratio" type="number" class="w-32 rounded h-6 border border-gray-900 focus:ring-1 focus:ring-gray-900 ">
                            </td>
                            <td>
                                <button wire:click="editSubmit"  class="w-full content-center bg-green-600 hover:bg-green-700 text-white font-bold px-4 rounded">save</button>
                            </td>
                        </tr>
                @else
                    <!-- content  -->
                    <tr class="bg-white border-4 border-gray-200 hover:bg-gray-100">

                        <td class="text-left">
                            <span class="ml-2 font-semibold">{{$group['name']}}</span>
                        </td>
                        <td class="px-2 py-2">
                            <span>{{$group['ratio']}}</span>
                        </td>
                        <td class="px-2 py-2">
                            <!-- will set the editedRow variable to the edited id  -->
                            <span class="text-blue-500 inline-block mr-1 cursor-pointer">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" wire:click='editRow("{{$group['id']}}")'>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </span>
                            <span class="text-red-500 inline-block ml-1 cursor-pointer">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                         x-on:click="confirm('Are you sure want to delete &quot;{{$group['name']}}&quot;?') ? @this.deleteRow('{{$group['id']}}') : false"
                                    >
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                            </span>
                        </td>
                    </tr>
                @endif
            @endforeach
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
    <script>
        window.addEventListener('livewire:load', () => {
            @this.on('editGroup', () => {
                document.querySelector('#editname').focus()
            })
            @this.on('newGroup', () => {
                document.querySelector('#newName').focus()
            })
        })
    </script>
@endpush
