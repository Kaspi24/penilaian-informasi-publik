<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Penilaian Informasi Publik') }}</title>
    <link rel="icon" href="{{ asset('logo/KEMENHUB.png') }}" type="image/png" sizes="32x32 16x16">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @if (isset($styles))
        {{ $styles }}
    @endif

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body 
    x-data="{
        showEndExamPopUp : false,
        showExitPopUp : false,
    }">

    <!-- PAGE HEADER (EXAM TITLE) -->
    <nav class="fixed z-[999] top-0 w-full bg-primary text-white h-[3.5rem] flex justify-between xl:grid xl:grid-cols-3 items-center px-4">
        <p id="user_ID" class="hidden">{{ Auth::user()->id }}</p>
        <div class="relative">
            <button type="button" x-on:click="showExitPopUp = true" class="block xl:flex xl:items-center xl:gap-2 text-white hover:text-gray-200">
                <span class="block m-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 15l-3-3m0 0l3-3m-3 3h8M3 12a9 9 0 1118 0 9 9 0 01-18 0z" />
                    </svg>
                </span>
                <span class="hidden text-xs xl:text-sm xl:block xl:m-0">Kembali</span>
            </button>
        </div>
        <p class="block m-0 text-xs xl:text-sm xl:text-center">KUESIONER PENILAIAN INFORMASI PUBLIK</p>
    </nav>

    {{-- <!-- QUESTIONS INDICATOR AND EXAM TIMER FOR MOBILE -->
    <div class="flex xl:hidden fixed z-[999] top-[3.45rem] w-full bg-primary-10 text-primary py-2 px-4 justify-between items-center">
        <div class="relative" x-data="{ showMobilePreview : false }">
            <button class="w-fit flex items-center gap-2" x-on:click="showMobilePreview =! showMobilePreview">
                <span class="block m-0 text-sm">Semua soal</span>
                <span class="block m-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </span>
            </button>
            <div class="fixed z-[1000] top-[6.5rem] w-fit h-fit max-h-[calc(50vh)] overflow-y-auto custom-scrollbar rounded-md p-3 border-2 border-primary bg-white drop-shadow"
                x-cloak x-show="showMobilePreview" x-on:click.outside="showMobilePreview = false">
                <div class="w-fit grid grid-cols-5 items-start gap-3 h-fit">

                </div>
            </div>
        </div>
    </div> --}}

    <main class="relative w-full" x-data="{
            @php $x=0; @endphp
            @foreach ($indicators as $indicator => $categories)
                @php $y=0; @endphp
                @foreach ($categories as $category)
                    showQuestions_{{ $x }}_{{ $y }}: {{ ($x==0 && $y==0) ? 'true' : 'false' }},
                    @php $y++; @endphp
                @endforeach
                @php $x++; @endphp
            @endforeach
        }">
        <!-- QUESTIONS NAVIGATOR -->
        <div class="block fixed z-[1000] top-[3.5rem] h-[calc(100vh-3.5rem)] w-1/4 box-border border-r-4 border-primary bg-primary-10 bg-opacity-60 p-4">
            <div class="flex gap-1 w-full items-center m-0 mb-4 font-semibold text-primary">
                <p>Pertanyaan Kuesioner</p>
                <span id="saving" class="hidden text-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5 animate-spin">
                        <path fill-rule="evenodd" d="M4.755 10.059a7.5 7.5 0 0 1 12.548-3.364l1.903 1.903h-3.183a.75.75 0 1 0 0 1.5h4.992a.75.75 0 0 0 .75-.75V4.356a.75.75 0 0 0-1.5 0v3.18l-1.9-1.9A9 9 0 0 0 3.306 9.67a.75.75 0 1 0 1.45.388Zm15.408 3.352a.75.75 0 0 0-.919.53 7.5 7.5 0 0 1-12.548 3.364l-1.902-1.903h3.183a.75.75 0 0 0 0-1.5H2.984a.75.75 0 0 0-.75.75v4.992a.75.75 0 0 0 1.5 0v-3.18l1.9 1.9a9 9 0 0 0 15.059-4.035.75.75 0 0 0-.53-.918Z" clip-rule="evenodd" />
                    </svg>
                </span>
                <span id="saved" class="text-emerald-600">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M17.7427 10.2859C17.7427 10.578 17.7184 10.8643 17.6716 11.1431H18.5999C20.7301 11.1431 22.457 12.87 22.457 15.0002C22.457 17.1305 20.7301 18.8574 18.5999 18.8574L12.5999 18.8574H5.74275C3.37581 18.8574 1.45703 16.9386 1.45703 14.5716C1.45703 12.2047 3.37581 10.2859 5.74275 10.2859H7.45703C7.45703 7.4456 9.75957 5.14307 12.5999 5.14307C15.4402 5.14307 17.7427 7.4456 17.7427 10.2859ZM16.08 11.8088L12.298 15.5908L12.298 15.5908L11.0374 16.8515L7.88569 13.6998L9.14636 12.4392L11.0373 14.3301L14.8193 10.5481L16.08 11.8088Z"/>
                    </svg>
                </span>
            </div>
            <div class="relative w-full h-fit max-h-[calc(100vh-3.5rem-3rem-1.5rem)] overflow-y-auto custom-scrollbar">
                <div class="w-full h-fit" x-data="{
                        @for ($i = 0; $i < $indicators->count(); $i++)
                            showIndicator_{{ $i }}: {{ $i==0 ? 'true' : 'false' }},
                        @endfor
                    }">
                    @php
                        $upper_alphabet = "ABCDEGHIJKLMNOPQRSTUVWXYZ";
                        $lower_alphabet = "abcdefghijklmnopqrstuvwxyz";
                        $i=0;
                    @endphp
                    @foreach ($indicators as $indicator => $categories)
                        @php
                            $all_count      = 0;
                            $answered_count = 0;
                            foreach ($categories as $category => $questions) {
                                $all_count      += $questions->count();
                                $answered_count += $questions->whereNotNull('answer')->count();
                            }
                            $indicator_percentage = round(($answered_count/$all_count)*100, 0);
                            if ($indicator_percentage==100) {
                                $indicator_percentage_classlist = "bg-emerald-600 text-white";
                            } elseif ($indicator_percentage>=66) {
                                $indicator_percentage_classlist = "bg-yellow-300 text-yellow-50";
                            } elseif ($indicator_percentage>=33) {
                                $indicator_percentage_classlist = "bg-amber-300 text-amber-50";
                            } else {
                                $indicator_percentage_classlist = "bg-gray-400 text-gray-50";
                            }
                        @endphp
                        <!-- NOMOR INDIKATOR -->
                        <button type="button" x-on:click="showIndicator_{{ $loop->index }} =! showIndicator_{{ $loop->index }}" 
                            class="flex justify-between items-center w-full box-border font-mono font-bold text-base p-1 lg:p-1.5 pr-2 bg-primary rounded-md text-white border {{ $loop->index === 0 ? '' : 'mt-2 lg:mt-3' }}">
                            <div class="flex gap-2 items-center">
                                <span id="progress_indikator_{{ $loop->index }}" class="w-12 {{ $indicator_percentage_classlist }} text-xs text-center rounded-md h-fit">{{ $indicator_percentage }}%</span>
                                <span>{{ $indicator }}</span>
                            </div>
                            <span :class="showIndicator_{{ $loop->index }} && 'rotate-90'">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                                    <path fill-rule="evenodd" d="M16.28 11.47a.75.75 0 0 1 0 1.06l-7.5 7.5a.75.75 0 0 1-1.06-1.06L14.69 12 7.72 5.03a.75.75 0 0 1 1.06-1.06l7.5 7.5Z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        </button>
                        <div x-cloak x-show="showIndicator_{{ $loop->index }}" class="w-full box-border">
                            @php $j=0; @endphp
                            @foreach ($categories as $category => $questions)
                                @php
                                    $answered_questions_count = $questions->whereNotNull('answer')->count();
                                    $percentage = round(($answered_questions_count/$questions->count())*100, 0);
                                    if ($percentage==100) {
                                        $category_percentage_classlist = [ "border-emerald-600 bg-emerald-50", "bg-emerald-600" ];
                                    } elseif ($percentage>=66) {
                                        $category_percentage_classlist = [ "border-yellow-300 bg-yellow-50 bg-opacity-40", "bg-yellow-300" ];
                                    } elseif ($percentage>=33) {
                                        $category_percentage_classlist = [ "border-amber-600 bg-amber-50 bg-opacity-40", "bg-amber-600" ];
                                    } else {
                                        $category_percentage_classlist = [ "border-gray-400 bg-gray-200", "bg-gray-400" ];
                                    }
                                @endphp
                                <!-- KATEGORI -->
                                <button id="category-button_{{$i}}_{{$j}}" type="button" class="{{ $i==0 && $j==0 ? 'bg-white' : '' }} border border-white category-button block rounded-md hover:bg-gray-100 w-full p-1 lg:p-2 {{ $loop->index === 0 ? 'mt-1.5' : 'mt-2' }} border">
                                    <!-- JUDUL KATEGORI -->
                                    <p class="text-left font-bold text-xs text-primary mb-1"> {{ $upper_alphabet[$loop->index] .". ". $category }} </p>
                                    <!-- PROGRES KATEGORI -->
                                    <div class="flex justify-between w-full h-5">
                                        <div class="w-[87%] border {{ $category_percentage_classlist[0] }} rounded-lg h-full box-border overflow-hidden">
                                            <div class="h-full border {{ $category_percentage_classlist[1] }} rounded-lg box-border overflow-hidden" style="width: {{ $percentage }}%;"></div>
                                        </div>
                                        <p class="block w-[12%] text-sm text-right text-primary-60 font-mono">{{ $answered_questions_count }}/{{ $questions->count() }}</p>
                                    </div>
                                </button>
                                @php $j++; @endphp
                            @endforeach
                            @php $i++; @endphp
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- QUESTION CONTAINER -->
        {{-- <div class="absolute top-[6.5rem] xl:top-[3.5rem] min-h-[calc(100vh-6.5rem)] xl:min-h-[calc(100vh-3.5rem)] w-full xl:w-[calc(100vw-6.5rem-12.5rem-31px)] xl:left-[calc(19rem+14px)] p-4"> --}}
        <div class="fixed z-[1000] top-[6.5rem] xl:top-[3.5rem] h-[calc(100vh-6.5rem)] box-border xl:h-[calc(100vh-3.5rem)] w-full xl:w-3/4 xl:right-0">
            <div id="questionContainer" class="text-gray-900 text-sm lg:text-base h-full">
                <!-- QUESTION CONTAINER BODY -->
                <div class="w-full h-[calc(100%-5rem)] box-border bg-gray-50 xl:p-4">
                    <div class="w-full h-full p-4 bg-white rounded-md shadow">
                        <div class="w-full h-full bg-primary-10 border-y rounded-md overflow-y-auto p-4">
                            @php $i=0; @endphp
                            @foreach ($indicators as $indicator => $categories)
                                @php $j=0; @endphp
                                @foreach ($categories as $category => $questions)
                                    @php $k=0; @endphp
                                    <div id="questions-container_{{$i}}_{{$j}}" class="questions-container {{ ($i==0 && $j==0) ? '' : 'hidden' }}  w-full">
                                        <p class="text-2xl text-primary-70 font-extrabold tracking-wider mb-4">{{ $category }}</p>
                                        @foreach ($questions as $question)
                                            <div class="w-full flex gap-2 border py-3 pl-1 pr-2 rounded-md bg-white {{ $loop->index === 0 ? '' : 'mt-6' }}">
                                                <p class="text-primary-70 w-8 box-border text-right font-mono">{{ $k+1 }}.</p>
                                                <div class="w-[calc(100%-2.5rem)] box-border">
                                                    <!-- QUESTION TEXT -->
                                                    <p class="text-primary-80 tracking-tight text-base font-medium p-0">
                                                        {{ $question->text }}
                                                        <!-- QUESTION TEXT DETAIL -->
                                                        @if ($question->details)
                                                            <br>
                                                            <span class="text-xs text-primary-50 font-medium">{{ $question->details }}</span>
                                                        @endif
                                                    </p>
                                                    @if ($question->children->count() === 0)
                                                        <!-- ANSWER INPUT RADIO -->
                                                        <input type="radio" name="answer[{{ $i }}][{{ $j }}][{{ $k }}]" 
                                                            id="answer_{{$i}}_{{$j}}_{{$k}}_1"
                                                            class="hidden radio-button" value="1"
                                                            data-questionDBID="{{ $question->id }}"
                                                            @checked($question->answer === 1)>
                                                        <input type="radio" name="answer[{{ $i }}][{{ $j }}][{{ $k }}]" 
                                                            id="answer_{{$i}}_{{$j}}_{{$k}}_0"
                                                            class="hidden radio-button" value="0"
                                                            data-questionDBID="{{ $question->id }}"
                                                            @checked($question->answer === 0)>
                                                        <!-- ANSWER LABEL -->
                                                        <div class="flex w-2/3 md:w-1/2 xl:w-1/4 gap-2 mt-2">
                                                            <label id="label_{{$i}}_{{$j}}_{{$k}}_1" for="answer_{{$i}}_{{$j}}_{{$k}}_1" 
                                                                class="w-1/2 p-2 text-center text-xs font-black tracking-widest cursor-pointer {{ $question->answer === 1 ? 'bg-emerald-500 text-emerald-50' : 'bg-gray-300 text-gray-500' }} hover:bg-emerald-900 hover:bg-opacity-80 hover:text-white rounded-md transition-all ease-in-out duration-[250ms]">
                                                                YA
                                                            </label>
                                                            <label id="label_{{$i}}_{{$j}}_{{$k}}_0" for="answer_{{$i}}_{{$j}}_{{$k}}_0" 
                                                                class="w-1/2 p-2 text-center text-xs font-black tracking-widest cursor-pointer {{ $question->answer === 0 ? 'bg-red-500 text-red-50' : 'bg-gray-300 text-gray-500' }} hover:bg-red-900 hover:bg-opacity-80 hover:text-white rounded-md transition-all ease-in-out duration-[250ms]">
                                                                TIDAK
                                                            </label>
                                                        </div>
                                                        <!-- INPUT TEXT -->
                                                        <div id="attachment_{{$i}}_{{$j}}_{{$k}}_container" class="attachment-field w-full mt-2 {{ $question->answer === 0 ? 'hidden' : '' }}">
                                                            <label for="attachment_{{$i}}_{{$j}}_{{$k}}" class="block mb-2 text-base font-medium text-primary">Tautan Bukti Pendukung Jawaban</label>
                                                            <input data-questionDBID="{{ $question->id }}" data-oldAnswer="{{ $question->attachment }}" type="text" 
                                                                id="attachment_{{$i}}_{{$j}}_{{$k}}" name="attachment[{{ $i }}][{{ $j }}][{{ $k }}]" value="{{ $question->attachment }}"
                                                                class="attachment-link bg-gray-50 border border-gray-300 text-primary-50 text-sm rounded-md focus:ring-primary-50 focus:border-primary-50 block w-full p-2" placeholder="https://www.example.com">
                                                            <p class="mt-1 text-xs text-gray-500">Pastikan tautan yang anda masukkan valid dan dapat diakses. Kegagalan saat mengakses tautan bermasalah dapat berdampak pada penilaian buruk.</p>
                                                        </div>
                                                    @else
                                                        @php $l=0; @endphp
                                                        @foreach ($question->children as $question_child)
                                                            <div class="w-full bg-gray-100 p-3 rounded-md flex gap-2 mt-3">
                                                                <p class="text-primary-70 w-10 box-border text-right font-mono">{{ $k+1 }}.{{ $l+1 }}.</p>
                                                                <div class="w-[calc(100%-3rem)] box-border">
                                                                    <!-- QUESTION CHILD TEXT -->
                                                                    <p class="text-primary-70 tracking-tight text-base font-normal p-0">
                                                                        {{ $question_child->text }}
                                                                    </p>
                                                                    <!-- CHILD ANSWER INPUT RADIO -->
                                                                    <input type="radio" name="answer[{{$i}}][{{$j}}][{{$k}}][{{$l}}]" 
                                                                        id="answer_{{$i}}_{{$j}}_{{$k}}_{{$l}}_1" 
                                                                        class="hidden radio-button" 
                                                                        value="1" data-questionDBID="{{ $question_child->id }}" @checked($question_child->answer === 1)>
                                                                    <input type="radio" name="answer[{{$i}}][{{$j}}][{{$k}}][{{$l}}]" 
                                                                        id="answer_{{$i}}_{{$j}}_{{$k}}_{{$l}}_0" 
                                                                        class="hidden radio-button" 
                                                                        value="0" data-questionDBID="{{ $question_child->id }}" @checked($question_child->answer === 0)>
                                                                    <!-- CHILD ANSWER LABEL -->
                                                                    <div class="flex w-2/3 md:w-1/2 xl:w-1/4 gap-2 mt-2">
                                                                        <label id="label_{{$i}}_{{$j}}_{{$k}}_{{$l}}_1" for="answer_{{$i}}_{{$j}}_{{$k}}_{{$l}}_1" 
                                                                            class="w-1/2 p-2 text-center text-xs font-black tracking-widest cursor-pointer {{ $question_child->answer === 1 ? 'bg-emerald-500 text-emerald-50' : 'bg-gray-300 text-gray-500' }} hover:bg-emerald-900 hover:bg-opacity-80 hover:text-white rounded-md transition-all ease-in-out duration-[250ms]">
                                                                            YA
                                                                        </label>
                                                                        <label id="label_{{$i}}_{{$j}}_{{$k}}_{{$l}}_0" for="answer_{{$i}}_{{$j}}_{{$k}}_{{$l}}_0" 
                                                                            class="w-1/2 p-2 text-center text-xs font-black tracking-widest cursor-pointer {{ $question_child->answer === 0 ? 'bg-red-500 text-red-50' : 'bg-gray-300 text-gray-500' }} hover:bg-red-900 hover:bg-opacity-80 hover:text-white rounded-md transition-all ease-in-out duration-[250ms]">
                                                                            TIDAK
                                                                        </label>
                                                                    </div>
                                                                    <!-- CHILD INPUT TEXT -->
                                                                    <div id="attachment_{{$i}}_{{$j}}_{{$k}}_{{$l}}_container" class="attachment-field w-full mt-2 {{ $question_child->answer === 0 ? 'hidden' : '' }}">
                                                                        <label for="attachment_{{$i}}_{{$j}}_{{$k}}_{{$l}}" class="block mb-2 text-base font-medium text-primary">Tautan Bukti Pendukung Jawaban</label>
                                                                        <input data-questionDBID="{{ $question_child->id }}" data-oldAnswer="{{ $question_child->attachment }}" type="text" 
                                                                            id="attachment_{{$i}}_{{$j}}_{{$k}}_{{$l}}" value="{{ $question_child->attachment }}"
                                                                            class="child-attachment-link bg-gray-50 border border-gray-300 text-primary-50 text-sm rounded-md focus:ring-primary-50 focus:border-primary-50 block w-full p-2" placeholder="https://www.example.com">
                                                                        <p class="mt-1 text-xs text-gray-500">Pastikan tautan yang anda masukkan valid dan dapat diakses. Kegagalan saat mengakses tautan bermasalah dapat berdampak pada penilaian buruk.</p>
                                                                        {{-- '{{ $question_child->attachment }}' --}}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            @php $l++; @endphp
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>
                                            @php $k++; @endphp
                                        @endforeach
                                    </div>
                                    @php $j++; @endphp
                                @endforeach
                                @php $i++; @endphp
                            @endforeach
                        </div>
                    </div>
                </div>
                <!-- QUESTION CONTAINER FOOTER -->
                <div class="bg-white w-full h-20 box-border flex justify-between items-center px-5">
                    <button type="button" id="" class="flex gap-2 items-center justify-center uppercase question-btn w-40 text-white bg-primary hover:bg-primary-70 border border-primary focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-md text-xs pl-2 pr-4 py-2">
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5">
                                <path fill-rule="evenodd" d="M11.03 3.97a.75.75 0 0 1 0 1.06l-6.22 6.22H21a.75.75 0 0 1 0 1.5H4.81l6.22 6.22a.75.75 0 1 1-1.06 1.06l-7.5-7.5a.75.75 0 0 1 0-1.06l7.5-7.5a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd" />
                            </svg>
                        </span>
                        <span>Sebelumnya</span>
                    </button>
                    <button type="button" id="" class="flex gap-2 items-center justify-center uppercase question-btn w-40 text-white bg-primary hover:bg-primary-70 border border-primary focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-md text-xs pl-4 pr-2 py-2">
                        <span>Berikutnya</span>
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5">
                                <path fill-rule="evenodd" d="M12.97 3.97a.75.75 0 0 1 1.06 0l7.5 7.5a.75.75 0 0 1 0 1.06l-7.5 7.5a.75.75 0 1 1-1.06-1.06l6.22-6.22H3a.75.75 0 0 1 0-1.5h16.19l-6.22-6.22a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                            </svg>
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </main>


    <!-- SUBMIT EXAM ANSWERS POP UP -->
    {{-- <div class="fixed z-[2220] inset-0" x-cloak x-show="showEndExamPopUp">
        <div class="absolute z-[2222] inset-0 bg-black bg-opacity-30 flex justify-center items-center py-4">
            <div class="bg-white w-10/12 md:w-1/2 lg:2/5 xl:w-1/3 rounded-md p-5 py-10 xl:py-12 flex flex-col justify-center items-center">
                <div class="w-fit text-warning mb-6 xl:mb-8">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="w-full text-center mb-8 xl:mb-10">
                    <p class="text-base xl:text-lg text-gray-900 font-semibold tracking-wide">
                        Anda yakin ingin mengakhiri ujian?
                    </p>
                </div>
                <div class="w-full flex justify-center items-center gap-4">
                    <button type="button" x-on:click="showEndExamPopUp = false" class="block w-36 text-primary bg-white border border-primary focus:ring-4 focus:outline-none focus:ring-blue-200 font-medium hover:font-semibold rounded-md text-sm px-5 py-2.5 text-center">
                        Kembali
                    </button>
                    <form action="{{ route('exam.save', [$classroom->slug, $exam->slug]) }}" method="POST">
                        @csrf @method('POST')
                        <div class="" id="additionalFormFields"></div>
                        <button type="submit" class="block w-36 text-white bg-primary hover:bg-primary-70 border border-primary focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-md text-sm px-5 py-2.5 text-center">
                            Akhiri Ujian
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div> --}}

    <!-- QUIT EXAM POP UP -->
    <div class="fixed z-[2220] inset-0" x-cloak x-show="showExitPopUp">
        <div class="absolute z-[2222] inset-0 bg-black bg-opacity-30 flex justify-center items-center py-4">
            <div class="bg-white w-10/12 md:w-1/2 lg:2/5 xl:w-1/3 rounded-md p-5 py-10 xl:py-12 flex flex-col justify-center items-center">
                <div class="w-fit text-warning mb-6 xl:mb-8">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="w-full text-center mb-4 xl:mb-6">
                    <p class="text-base xl:text-lg text-gray-900 font-semibold tracking-wide">
                        Anda yakin ingin meninggalkan ujian?
                    </p>
                </div>
                <div class="w-full text-center mb-8 xl:mb-10">
                    <p class="text-sm xl:text-base text-gray-900">
                        Jawaban anda saat ini akan tersimpan.
                    </p>
                </div>
                <div class="w-full flex justify-center items-center gap-2 md:gap-4">
                    <button type="button" x-on:click="showExitPopUp = false" 
                        class="block w-40 text-white bg-primary hover:bg-primary-70 border border-primary focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-md text-xs xl:text-sm py-2.5 text-center">
                        Lanjutkan Ujian
                    </button>
                    <a href=""
                        class="block w-40 text-primary bg-white border border-primary focus:ring-4 focus:outline-none focus:ring-blue-200 font-medium hover:font-semibold rounded-md text-xs xl:text-sm py-2.5 text-center">
                        Tinggalkan Ujian
                    </a>
                </div>
            </div>
        </div>
    </div>


    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/flowbite.min.js') }}"></script>
    <script>
        const ajaxCall = (url, question_id, new_answer, attachment = null) => {
            $.ajax({
                type    : "POST",
                url     : url,
                data    : {
                    _method     : 'PUT',
                    _token      : '{{ csrf_token() }}',
                    question_id : question_id,
                    answer      : new_answer,
                    attachment  : attachment,
                },
                dataType: 'JSON',
                beforeSend : function(){
                    $("#saved").addClass("hidden");
                    $("#saving").removeClass("hidden");
                },
                success: function (response){
                    console.log(response);
                    // reponseAction(response);
                    // colorButton(currentIndex);
                },
                complete: function(){
                    $("#saving").addClass("hidden");
                    $("#saved").removeClass("hidden");
                },
            });
        }

        $(document).ready(function () {

            $(".radio-button").change(function (e) { 
                e.preventDefault();

                let rawID           = $(this).attr('id');
                let indicatorID     = rawID.split('_')[1];
                let categoryID      = rawID.split('_')[2];
                let questionID      = rawID.split('_')[3];
                let value           = rawID.split('_').length == 6 ? rawID.split('_')[5] : rawID.split('_')[4];
                let questionChildID = rawID.split('_').length == 6 ? rawID.split('_')[4] : null;

                let questionDBID    = $(this).attr("data-questionDBID");

                // console.log(questionDBID);
                
                if (questionChildID != null && value == 1) {
                    // SHOW INPUT TEXT
                    $("#attachment_"+indicatorID+"_"+categoryID+"_"+questionID+"_"+questionChildID+"_container").removeClass("hidden");
                    // COLOR BUTTON
                    $("#label_"+indicatorID+"_"+categoryID+"_"+questionID+"_"+questionChildID+"_0").removeClass("bg-red-500 text-red-50");
                    $("#label_"+indicatorID+"_"+categoryID+"_"+questionID+"_"+questionChildID+"_0").addClass("bg-gray-300 text-gray-500");
                    $("#label_"+indicatorID+"_"+categoryID+"_"+questionID+"_"+questionChildID+"_1").removeClass("bg-gray-300 text-gray-500");
                    $("#label_"+indicatorID+"_"+categoryID+"_"+questionID+"_"+questionChildID+"_1").addClass("bg-emerald-500 text-emerald-50");
                } else if (questionChildID != null && value == 0) {
                    // console.log("OKE");
                    // HIDE INPUT TEXT
                    $("#attachment_"+indicatorID+"_"+categoryID+"_"+questionID+"_"+questionChildID+"_container").addClass("hidden");
                    // CLEAR INPUT TEXT VALUE
                    $("#attachment_"+indicatorID+"_"+categoryID+"_"+questionID+"_"+questionChildID).val("");
                    // COLOR BUTTON
                    $("#label_"+indicatorID+"_"+categoryID+"_"+questionID+"_"+questionChildID+"_0").removeClass("bg-gray-300 text-gray-500");
                    $("#label_"+indicatorID+"_"+categoryID+"_"+questionID+"_"+questionChildID+"_0").addClass("bg-red-500 text-red-50");
                    $("#label_"+indicatorID+"_"+categoryID+"_"+questionID+"_"+questionChildID+"_1").removeClass("bg-emerald-500 text-emerald-50");
                    $("#label_"+indicatorID+"_"+categoryID+"_"+questionID+"_"+questionChildID+"_1").addClass("bg-gray-300 text-gray-500");
                    
                    ajaxCall("{{ route('questionnaire.updateAnswerChild') }}", questionDBID, value);
                } else if (questionChildID == null && value == 1) {
                    // SHOW INPUT TEXT
                    $("#attachment_"+indicatorID+"_"+categoryID+"_"+questionID+"_container").removeClass("hidden");
                    // COLOR BUTTON
                    $("#label_"+indicatorID+"_"+categoryID+"_"+questionID+"_0").removeClass("bg-red-500 text-red-50");
                    $("#label_"+indicatorID+"_"+categoryID+"_"+questionID+"_0").addClass("bg-gray-300 text-gray-500");
                    $("#label_"+indicatorID+"_"+categoryID+"_"+questionID+"_1").removeClass("bg-gray-300 text-gray-500");
                    $("#label_"+indicatorID+"_"+categoryID+"_"+questionID+"_1").addClass("bg-emerald-500 text-emerald-50");
                } else if (questionChildID == null && value == 0) {
                    // HIDE INPUT TEXT
                    $("#attachment_"+indicatorID+"_"+categoryID+"_"+questionID+"_container").addClass("hidden");
                    // CLEAR INPUT TEXT VALUE
                    $("#attachment_"+indicatorID+"_"+categoryID+"_"+questionID).val("");
                    // COLOR BUTTON
                    $("#label_"+indicatorID+"_"+categoryID+"_"+questionID+"_0").removeClass("bg-gray-300 text-gray-500");
                    $("#label_"+indicatorID+"_"+categoryID+"_"+questionID+"_0").addClass("bg-red-500 text-red-50");
                    $("#label_"+indicatorID+"_"+categoryID+"_"+questionID+"_1").removeClass("bg-emerald-500 text-emerald-50");
                    $("#label_"+indicatorID+"_"+categoryID+"_"+questionID+"_1").addClass("bg-gray-300 text-gray-500");

                    ajaxCall("{{ route('questionnaire.updateAnswer') }}", questionDBID, value);
                }
            });
            
            $(".attachment-link").on("focusout", async function (e) {
                e.preventDefault();
                await new Promise(resolve => setTimeout(resolve, 1000));

                let questionDBID    = $(this).attr("data-questionDBID");
                let oldAnswer       = $(this).attr("data-oldAnswer");
                let newAnswer       = $(this).val();

                let rawID           = $(this).attr('id');
                let indicatorID     = rawID.split('_')[1];
                let categoryID      = rawID.split('_')[2];
                let questionID      = rawID.split('_')[3];
                // let value           = rawID.split('_')[4];
                
                let radio = $('input[name="answer['+indicatorID+']['+categoryID+']['+questionID+']"]:checked').val();

                if (radio==1) {
                    if(oldAnswer != newAnswer && newAnswer != "") {
                        ajaxCall("{{ route('questionnaire.updateAnswer') }}", questionDBID, 1, newAnswer);
                    }
                }
            });

            $(".child-attachment-link").on("focusout", async function (e) {
                e.preventDefault();
                await new Promise(resolve => setTimeout(resolve, 1000));

                let questionDBID    = $(this).attr("data-questionDBID");
                let oldAnswer       = $(this).attr("data-oldAnswer");
                let newAnswer       = $(this).val();

                let rawID           = $(this).attr('id');
                let indicatorID     = rawID.split('_')[1];
                let categoryID      = rawID.split('_')[2];
                let questionID      = rawID.split('_')[3];
                let questionChildID = rawID.split('_')[4];
                // let value           = rawID.split('_')[5];

                
                let radio = $('input[name="answer['+indicatorID+']['+categoryID+']['+questionID+']['+questionChildID+']"]:checked').val();

                console.log(radio);
                console.log(questionDBID);
                console.log(oldAnswer);
                console.log(newAnswer);

                if (radio==1) {
                    if(oldAnswer != newAnswer && newAnswer != "") {
                        ajaxCall("{{ route('questionnaire.updateAnswerChild') }}", questionDBID, 1, newAnswer);
                    }
                }
            });

            $(".category-button").click(function (e) { 
                e.preventDefault();

                let rawID           = $(this).attr('id');
                let indicatorID     = rawID.split('_')[1];
                let categoryID      = rawID.split('_')[2];

                $(".category-button").removeClass("bg-white shadow");
                $(this).addClass("bg-white shadow");

                // $(selector).find(selector2);

                console.log($("#questions-container_"+indicatorID+"_"+categoryID).find('.attachment-field.w-full.mt-2>[type="text"]'));

                // $(".questions-container").addClass("hidden");
                // $("#questions-container_"+indicatorID+"_"+categoryID).removeClass("hidden");
            });
        });
    </script>
    @livewireScripts
</body>
</html>
