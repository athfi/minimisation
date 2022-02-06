<x-guest-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Minimisation Info') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg ">
                <div class="lg:text-center">
                    <h2 class="bg-indigo-600 font-semibold py-2 px-4 text-3xl text-base text-white tracking-wide uppercase">{{ __('Randomisation test') }}</h2>
                </div>
                @foreach($results as $random => $result)
                    <div class="p-4 float-left">
                        <div class="text-center py-2 text-2xl">
                            <h3>frequency table for "{{$random}}" function</h3>
                            <span>time elapsed : {{$result[1]}} secs</span><br>
                            <span>rolls numbers : {{$result[2]}}</span><br>

                        </div>
                        <table class="m-auto border-collapse border border-green-800 items-start align-baseline">
                            <tr>
                                <th>index</th>
                                <th>frequency</th>
                                <th>percent</th>
                                <th>cumulative</th>
                            </tr>
                        @php
                        $cum=0;
                        $cum = 0;
                        @endphp
                        @foreach($result[0] as $index => $freq)
                            @php
                                $percent = $freq * 100 / $result[2];
                                $cum+=$percent;
                            @endphp
                            <tr>
                                <td>{{$index}}</td>
                                <td>{{$freq}}</td>
                                <td>{{round($percent,2)}}</td>
                                <td>{{round($cum,2)}}</td>
                            </tr>
                        @endforeach
                        </table>
                    </div>
                @endforeach

            </div>
        </div>
    </div>

</x-guest-layout>
