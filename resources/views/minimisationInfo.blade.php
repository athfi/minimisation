<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Minimisation Info') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg ">
                <div class="lg:text-center">
                    <h2 class="bg-indigo-600 font-semibold py-2 px-4 text-3xl text-base text-white tracking-wide uppercase">{{ __('Minimisation Info') }}</h2>
                </div>
                <div class="p-4 ">
                    @dump($minim->getFactors())
                    <div class="text-center py-2 text-2xl">
                        <span>Minimisation frequency table</span>
                    </div>
                    <table class="m-auto border-collapse border border-green-800 items-start align-baseline">
                        <tr>
                            <td rowspan="2" class="border border-green-600 bg-green-300 px-4 uppercase text-center">Group</td>
                            @foreach($minim->getFactors() as $factor => $levels)
                                <td colspan="{{count($levels)}}" class="border border-green-600 bg-green-300 px-4 uppercase text-center">{{$factor}}</td>
                            @endforeach
                        </tr>
                        <tr>
                            @foreach($minim->getFactors() as $factor => $levels)
                                @foreach($levels as $level=>$val)
                                    <td class="border border-green-600 bg-green-300 px-4">{{$level}}</td>
                                @endforeach
                            @endforeach
                        </tr>

                        @foreach($minim->getGroups() as $group )
                            <tr>
                                <td class="border border-green-600  px-4">{{$group}}</td>
                                @php $freqTable= $minim->getFreqTable(); @endphp
                                @foreach($minim->getFactors() as $factor => $levels)
                                    @foreach($levels as $level=>$val)
                                        <td class="border border-green-600  px-4">{{$freqTable[$group][$factor][$level]}}</td>
                                    @endforeach
                                @endforeach
                            </tr>
                        @endforeach
                    </table>
                </div>

            </div>
        </div>
    </div>

</x-app-layout>
