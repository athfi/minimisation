<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('RECORD') }}
        </h2>
    </x-slot>

    @if (session('status'))
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg ">
            <div class="lg:text-center">
                <h2 class="bg-green-500 font-semibold py-2 px-4 text-3xl text-base text-black tracking-wide uppercase">
                    Success
                </h2>
            </div>
            <div class="w-full max p-6 m-auto bg-green-100 text-2xl">
                {{ session('status') }}
                @php
                    dump(session('minim'));
                @endphp
            </div>
        </div>
    </div>
    @endif

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg ">
                <div class="lg:text-center">
                    <h2 class="bg-indigo-600 font-semibold py-2 px-4 text-3xl text-base text-white tracking-wide uppercase">
                        RECORD
                    </h2>
                </div>
                <div class="w-full max p-6 m-auto">
                    @if($record)
                        @foreach($record as $field => $value)
                            <div
                                class="md:flex md:items-center mb-6 md:flex-1 w-full">
                                <div class="md:w-1/3">
                                    <x-jet-label
                                        value="{{ strip_tags($metadata[$field]['field_label']??$field) }}"/>
                                </div>
                                <div class="ml-3 md:w-2/3 md:ml-0">
                                    @if($metadata->has($field))
                                        @if($metadata[$field]['field_type'] == 'checkbox')
                                            <div
                                                class="p-2 border-gray-600 bg-gray-200 rounded-md shadow-sm overflow-y-auto">
                                                @foreach($metadata[$field]['select_choices_or_calculations'] as $key => $label )
                                                    <div>
                                                        <input
                                                            class="disabled:opacity-50 text-gray-400"
                                                            type="checkbox"
                                                            id="{{$field."___".$key}}"
                                                            name="{{$field."___".$key}}"
                                                            value="{{$key}}"
                                                            {{$value[$key]?'checked':''}} disabled>
                                                        {{$metadata[$field]['select_choices_or_calculations'][$key]}}
                                                    </div>
                                                @endforeach
                                            </div>
                                        @elseif($metadata[$field]['field_type'] == 'radio')
                                            <div
                                                class="p-2 border-gray-600 bg-gray-200 rounded-md shadow-sm overflow-y-auto">
                                                @foreach($metadata[$field]['select_choices_or_calculations'] as $key => $label )
                                                    <div>
                                                        <input
                                                            class="disabled:opacity-50 text-gray-400"
                                                            type="radio"
                                                            id="{{$key}}"
                                                            name="{{$field}}"
                                                            value="{{$value}}"
                                                            {{$key==$value?'checked':''}}
                                                            disabled>
                                                        {{$metadata[$field]['select_choices_or_calculations'][$key]}}
                                                    </div>
                                                @endforeach
                                            </div>
                                        @elseif($metadata[$field]['field_type'] == 'yesno')
                                            <div
                                                class="p-2 border-gray-600 bg-gray-200 rounded-md shadow-sm overflow-y-auto">
                                                <div>
                                                    <input
                                                        class="disabled:opacity-50 text-gray-400"
                                                        type="radio"
                                                        id="{{$field."__yes"}}"
                                                        name="{{$field}}"
                                                        value="1"
                                                        {{$value==1?'checked':''}}
                                                        disabled>
                                                    Yes
                                                </div>
                                                <div>
                                                    <input
                                                        class="disabled:opacity-50 text-gray-400"
                                                        type="radio"
                                                        id="{{$field."__no"}}"
                                                        name="{{$field}}"
                                                        value="0"
                                                        {{$value==0?'checked':''}}
                                                        disabled>
                                                    No
                                                </div>
                                            </div>
                                        @else
                                            <x-jet-label
                                                value="{{$value==''?'- ':$value}}"
                                                class="p-2 border-gray-600 bg-gray-200 rounded-md shadow-sm overflow-y-auto"/>
                                        @endif
                                    @else
                                        <x-jet-label
                                            value="{{$value==''?'- ':$value}}"
                                            class="p-2 border-gray-600 bg-gray-200 rounded-md shadow-sm overflow-y-auto"/>
                                    @endif

                                </div>
                            </div>
                        @endforeach
                    @else

                        <x-forms.error-message error="Sorry, we cannot find a record with id {{request('id')}}." />
                    @endif
                </div>
            </div>
        </div>
    </div>

</x-app-layout>

