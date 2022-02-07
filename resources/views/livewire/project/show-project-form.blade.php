<div>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg "
             wire:init="loadData">
            <div class="lg:text-center">
                <h2 class="bg-indigo-500 font-semibold py-2 px-4 text-3xl text-base text-white tracking-wide uppercase">
                    REDCap Project</h2>
            </div>


            <div class="w-full max-w-screen-md p-6 m-auto">
                @foreach($projectSetting as $key => $data)
                    <div class="md:flex md:items-center mb-6 md:flex-1 w-full">
                        <div class="md:w-1/3">
                            <x-jet-label value="{{$key}}"/>
                        </div>
                        <div class="md:w-2/3">
                            <x-jet-label value="{{$data}}"
                                         class="p-2 border-gray-600 bg-gray-100 rounded-md shadow-sm overflow-y-auto"/>
                        </div>
                    </div>
                @endforeach

                @if($projectInfo)
                    @foreach($projectInfo as $key => $data)
                        <div
                            class="md:flex md:items-center mb-6 md:flex-1 w-full">
                            <div class="md:w-1/3">
                                <x-jet-label value="{{$key}}"/>
                            </div>
                            <div class="md:w-2/3">
                                <x-jet-label value="{{$data}}"
                                             class="p-2 border-gray-600 bg-gray-100 rounded-md shadow-sm overflow-y-auto"/>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>


            <div wire:loading.block class="m-auto text-center p-4">
                    <x-forms.loading-svg message="Loading data from REDCap server" />
            </div>


            @if($projectMetadata)
                <div class="lg:text-center">
                    <h2 class="bg-indigo-500 font-semibold py-2 px-4 text-3xl text-base text-white tracking-wide uppercase">
                        FORMS METADATA</h2>
                </div>
                <div class="w-full max p-6 m-auto">
                    @php
                        $counter = 1
                    @endphp
                    @if($projectMetadata)
                        <table
                            class="border-collapse border border-blue-600 items-start align-baseline">
                            <thead>
                            <tr>
                                <th class="border border-blue-600">#</th>
                                <th class="border border-blue-600">Field Name
                                </th>
                                <th class="border border-blue-600">Field Label
                                </th>
                                <th class="border border-blue-600">Field
                                    Attribute
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($metadata as $form => $fields)
                                <tr>
                                    <td colspan="4"
                                        class="border border-blue-600 bg-blue-300 text-2xl uppercase px-4 ">
                                        {{$form}}
                                    </td>
                                </tr>
                                @foreach($fields as $field)

                                    <tr>
                                        <td class="border border-blue-600 align-baseline px-2">
                                            {{$counter}}
                                        </td>
                                        <td class="border border-blue-600 align-baseline px-2">
                                            {{strip_tags($field['field_name'])}}
                                        </td>
                                        <td class="border border-blue-600 align-baseline px-2">
                                            {{strip_tags($field['field_label'])}}
                                        </td>
                                        <td class="border border-blue-600 align-baseline px-2">
                                            {{$field['field_type']}}
                                            @if($field['text_validation_type_or_show_slider_number']!='')
                                                ({{
                                                ($field['text_validation_type_or_show_slider_number']) .
                                                ($field['text_validation_min']?', '.$field['text_validation_min']:'') .
                                                ($field['text_validation_max']?', '.$field['text_validation_max']:'')}}
                                                )
                                            @endif
                                            {{$field['required_field']=='y'?', Required':''}}

                                            @if($field['field_type']==='calc')

                                                <br>
                                                Calculation: {{$field['select_choices_or_calculations']}}

                                            @elseif(is_array($field['select_choices_or_calculations']))

                                                <br>Option:<br>
                                                <table
                                                    class="border border-gray-300 ml-6">
                                                    @foreach($field['select_choices_or_calculations'] as $key => $value)

                                                        <tr>
                                                            <td class="border border-gray-300 align-baseline">
                                                                {{$key}}
                                                            </td>
                                                            <td class="border border-gray-300 align-baseline">
                                                                {{$value}}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </table>

                                            @endif

                                        </td>
                                    </tr>
                                    @php
                                        $counter ++
                                    @endphp
                                @endforeach
                            @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            @endif

        </div>
    </div>


</div>
