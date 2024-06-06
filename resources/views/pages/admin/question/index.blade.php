<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Pertanyaan Kuesioner') }}
        </h2>
    </x-slot>

    <div class="border border-gray-300 w-full p-5 lg:p-8">
        <div class="shadow-sm shadow-gray-500 w-full p-5 lg:p-8 rounded-sm bg-white">
            @php
                $upper_alphabet = "ABCDEGHIJKLMNOPQRSTUVWXYZ";
                $lower_alphabet = "abcdefghijklmnopqrstuvwxyz";
            @endphp
            @foreach ($indicators as $indicator => $categories)
                <!-- NOMOR INDIKATOR -->
                <h2 class="w-full box-border font-mono tracking-wider font-extrabold text-2xl p-2 lg:p-3 bg-primary text-warning border {{ $loop->index === 0 ? '' : 'mt-5 lg:mt-8' }}">{{ $indicator }}</h2>
                <div class="border w-full box-border p-2 lg:p-4">
                    @foreach ($categories as $category => $questions)
                        <!-- KATEGORI -->
                        <h2 class="font-mono tracking-wide font-bold text-xl text-primary p-1 lg:p-2 {{ $loop->index === 0 ? '' : 'mt-3 lg:mt-4' }}">{{ $upper_alphabet[$loop->index] .". ". $category }}</h2>
                        <div class="box-border w-full mt-1.5 lg:mt-3 rounded-tl-sm rounded-tr-sm overflow-auto">
                            <!-- START TABLE -->
                            <table class="w-full text-sm text-primary-50">
                                <!-- TABLE HEADER -->
                                <thead class="text-xs text-primary-70 uppercase bg-primary-10">
                                    <tr >
                                        <th scope="col" rowspan="2" class="border-r border-b p-1.5 lg:p-2 w-10">No.</th>
                                        <th colspan="2" scope="col" rowspan="2" class="border-r border-b p-1.5 lg:p-2">Kuesioner</th>
                                        <th colspan="5" class="border-b p-1.5 lg:p-2">Bobot Nilai Berdasarkan Kesesuaian Bukti</th>
                                    </tr>
                                    <tr>
                                        <th scope="col" class="border-r border-b p-1.5 lg:p-2 w-20">Ya<br>(Sangat Sesuai)</th>
                                        <th scope="col" class="border-r border-b p-1.5 lg:p-2 w-20">Ya<br>(Hampir Sesuai)</th>
                                        <th scope="col" class="border-r border-b p-1.5 lg:p-2 w-20">Ya<br>(Kurang Sesuai)</th>
                                        <th scope="col" class="border-r border-b p-1.5 lg:p-2 w-20">Ya<br>(Tidak Sesuai)</th>
                                        <th scope="col" class="border-b p-1.5 lg:p-2 w-20">Menjawab Tidak</th>
                                    </tr>
                                </thead>
                                <!-- TABLE HEADER -->
                                <tbody>
                                    @php
                                        $total_very_good    = 0;
                                        $total_good         = 0;
                                        $total_good_enough  = 0;
                                        $total_less_good    = 0;
                                    @endphp
                                    <!-- START QUESTION -->
                                    @foreach ($questions as $question)
                                        @php
                                            $total_very_good    += $question->very_good;
                                            $total_good         += $question->good;
                                            $total_good_enough  += $question->good_enough;
                                            $total_less_good    += $question->less_good;
                                        @endphp
                                        <tr class="bg-white {{ $loop->index === 0 ? '' : 'border-t' }}">
                                            <th rowspan="{{ $question->children->count()+1 }}" scope="row" class="p-2 border-r lg:p-3 w-20">{{ $loop->index+1 }}</th>
                                            <td colspan="2" class="p-2 border-r lg:p-3">{{ $question->text }}</td>
                                            <td rowspan="{{ $question->children->count()+1 }}" class="p-2 border-r lg:p-3 w-20 text-center">{{ $question->very_good }}</td>
                                            <td rowspan="{{ $question->children->count()+1 }}" class="p-2 border-r lg:p-3 w-20 text-center">{{ $question->good }}</td>
                                            <td rowspan="{{ $question->children->count()+1 }}" class="p-2 border-r lg:p-3 w-20 text-center">{{ $question->good_enough }}</td>
                                            <td rowspan="{{ $question->children->count()+1 }}" class="p-2 border-r lg:p-3 w-20 text-center">{{ $question->less_good }}</td>
                                            <td rowspan="{{ $question->children->count()+1 }}" class="p-2 lg:p-3 w-20 text-center">0</td>
                                        </tr>
                                        <!-- START QUESTION'S CHILDREN -->
                                        @foreach ($question->children as $child_question)
                                            <tr class="bg-white border-t">
                                                <td class="px-2 py-1 border-r">{{ $loop->index+1 }}.</td>
                                                <td class="px-2 py-1 border-r">{{ $child_question->text }}</td>
                                            </tr>
                                        @endforeach
                                        <!-- END QUESTION'S CHILDREN -->
                                    @endforeach
                                    <!-- END QUESTION -->
                                    <tr class="bg-primary-10 border-t">
                                        <th scope="row" colspan="3" class="p-2 border-r lg:p-3 w-20 text-right">TOTAL</th>
                                        <td class="p-2 border-r lg:p-3 w-20 text-center">{{ $total_very_good }}</td>
                                        <td class="p-2 border-r lg:p-3 w-20 text-center">{{ $total_good }}</td>
                                        <td class="p-2 border-r lg:p-3 w-20 text-center">{{ $total_good_enough }}</td>
                                        <td class="p-2 border-r lg:p-3 w-20 text-center">{{ $total_less_good }}</td>
                                        <td class="p-2 lg:p-3 w-20 text-center">0</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    @endforeach
                </div>
                
            @endforeach
        </div>
    </div>

    {{-- <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @php
                        $upper_alphabet = "ABCDEGHIJKLMNOPQRSTUVWXYZ";
                    @endphp
                    @foreach ($indicators as $indicator => $categories)
                    <h1 class="w-full text-left font-bold text-2xl ml-4 mt-4 border border-gray-600">
                        {{ $indicator }}
                    </h1>
                        <table class="mb-4">
                            <tbody>
                                <tr class="mb-4">
                                </tr>
                            </tbody>
                        </table>

                        @foreach ($categories as $category => $questions)
                            <tr>
                                <th colspan="7" class="text-left border border-gray-600 font-bold text-xl ml-4 mt-4">
                                    {{ $upper_alphabet[$loop->index] .". ". $category }}
                                </th>
                            </tr>
                                <table>
                                    <tbody>
                                    <tr>
                                        <th class="border border-gray-600 font-bold text-xl ml-4 mt-4">
                                            No.
                                        </th>
                                        <th class="border border-gray-600 font-bold text-xl ml-4 mt-4 text-left">
                                            Pertanyaan Kuesioner
                                        </th>
                                        <th colspan="5" class="border border-gray-600 font-bold text-xl ml-4 mt-4">
                                            Nilai
                                        </th>
                                    </tr>
                                    @foreach ($questions as $question)
                                        <tr>
                                            <td class="border border-gray-600 p-1 text-center">
                                                {{ $loop->index+1 }}
                                            </td>
                                            <td class="border border-gray-600 p-1">
                                                {{ $question->text }}
                                            </td>
                                            <td class="border border-gray-600 p-1"> {{ $question->very_good }} </td>
                                            <td class="border border-gray-600 p-1"> {{ $question->good }} </td>
                                            <td class="border border-gray-600 p-1"> {{ $question->good_enough }} </td>
                                            <td class="border border-gray-600 p-1"> {{ $question->less_good }} </td>
                                            <td class="border border-gray-600 p-1"> 0 </td>
                                        </tr>
                                        @foreach ($question->children as $child_question)
                                            <p class="ml-12"> {{ $loop->index+1 .". ". $child_question->text }}</p>
                                        @endforeach
                                    @endforeach
                                @endforeach
                            @endforeach
                        </table>
                    </table>
                </div>
            </div>
        </div>
    </div> --}}
</x-app-layout>
