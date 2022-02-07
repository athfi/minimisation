<div>
    @if (session()->has('error'))
        <x-forms.error-message error="{{session('error')}}"/>
    @endif

    <div wire:init="loadMetadata">

        <div wire:loading.inline>
            <x-forms.loading-svg message="Loading metadata"/>
        </div>

        @php $counter = 1 @endphp
        @if($metadata)
            <table
                class="border-collapse border border-green-800 items-start align-baseline">
                <thead>
                <tr>
                    <th class="border border-green-600">#</th>
                    <th class="border border-green-600">Field Name</th>
                    <th class="border border-green-600">Field Label</th>
                    <th class="border border-green-600">Field Attribute</th>
                </tr>
                </thead>
                <tbody>
                @foreach($metadata as $form => $fields)
                    <tr>
                        <td colspan="4"
                            class="border border-green-600 bg-gray-50 text-2xl uppercase px-4 ">
                            {{$form}}
                        </td>
                    </tr>
                    @foreach($fields as $field)
                        <tr>
                            <td class="border border-green-600 align-baseline px-2">
                                {{$counter}}
                            </td>
                            <td class="border border-green-600 align-baseline px-2">
                                {{strip_tags($field['field_name'])}}
                            </td>
                            <td class="border border-green-600 align-baseline px-2">
                                {{strip_tags($field['field_label'])}}
                            </td>
                            <td class="border border-green-600 align-baseline px-2">
                                {{$field['field_type']}}
                                @if($field['text_validation_type_or_show_slider_number']!='')
                                    ({{ ($field['text_validation_type_or_show_slider_number']) .
                                         ($field['text_validation_min']?', ' . $field['text_validation_min']:'') .
                                         ($field['text_validation_max']?', ' . $field['text_validation_max']:'')}})
                                @endif
                                {{$field['required_field']=='y'?', Required':''}}

                                @if($field['field_type']==='calc')
                                    <br>
                                    Calculation: {{$field['select_choices_or_calculations']}}
                                @elseif(is_array($field['select_choices_or_calculations']))
                                    <br>Option:<br>
                                    <table class="border border-gray-300 ml-6">
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
                        @php $counter ++ @endphp
                    @endforeach
                @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
