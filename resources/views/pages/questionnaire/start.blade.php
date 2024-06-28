<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Penilaian Anugerah Keterbukaan Informasi Publik') }}</title>
    <link rel="icon" href="{{ asset('logo/KEMENHUB64.png') }}" type="image/png" sizes="32x32 16x16">

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
        showSidebar : false,
    }">

    <!-- PAGE HEADER (EXAM TITLE) -->
    <nav class="fixed z-[999] top-0 w-full bg-primary text-white h-[3.5rem] flex justify-between xl:grid xl:grid-cols-3 items-center px-4">
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
        <div class="flex justify-center items-center gap-2">
            <img src="{{ asset('logo/KEMENHUB.png') }}" class="h-8 w-auto" alt="">
            <p class="block m-0 text-xs xl:text-sm xl:text-center">PENILAIAN ANUGERAH<br class="lg:hidden"> KETERBUKAAN INFORMASI PUBLIK</p>
        </div>
    </nav>

    <!-- QUESTIONS NAVIGATION FOR MOBILE -->
    <div class="flex xl:hidden fixed z-[1010] top-[3.5rem] h-[3rem] shadow shadow-gray-400 w-full bg-primary-10 text-primary py-2 px-4 justify-between items-center">
        @php
            $answered_count = 0;
            foreach ($indicators as $indicator => $categories) {
                foreach ($categories as $category => $questions) {
                    foreach ($questions as $question) {
                        if (
                            ($question->children->count() > 0 && $question->children->whereNull('answer')->count() == 0) 
                            || 
                            ($question->children->count() == 0 && ($question->answer == '1' || $question->answer == '0')) 
                        ) {
                            $answered_count++;
                        }
                    }
                }
            }
            $questionnaire_percentage = round(($answered_count/35)*100, 0);

            if ($questionnaire_percentage==100) {
                $questionnaire_percentage_classlist = [ "category-progress-container-100", "category-progress-100" ];
            } elseif ($questionnaire_percentage>=66) {
                $questionnaire_percentage_classlist = [ "category-progress-container-66", "category-progress-66" ];
            } elseif ($questionnaire_percentage>=33) {
                $questionnaire_percentage_classlist = [ "category-progress-container-33", "category-progress-33" ];
            } else {
                $questionnaire_percentage_classlist = [ "category-progress-container-default", "category-progress-default" ];
            }
        @endphp
        <div class="w-full h-full flex py-1 items-center justify-between">
            <div id="progress_questionnaire_container" class="w-[75%] border {{ $questionnaire_percentage_classlist[0] }} rounded-lg h-full box-border overflow-hidden">
                <div id="progress_questionnaire" class="h-full border {{ $questionnaire_percentage_classlist[1] }} rounded-lg box-border overflow-hidden" style="width: {{ $questionnaire_percentage }}%;"></div>
            </div>
            <p class="w-[24%] text-sm font-bold text-right"><span id="questionnaire_answered_count">{{ $answered_count }}</span>/<span id="questionnaire_all_count">35</span> DIISI</p>
        </div>
    </div>

    <main class="relative w-full" 
        x-data="{
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
        <!-- QUESTIONS NAVIGATION -->
        <div class="hidden xl:block fixed z-[999] top-[3.5rem] h-[calc(100vh-3.5rem)] w-1/4 box-border border-r-4 border-primary bg-primary-10 bg-opacity-60 p-4">
            <div class="flex gap-1 w-full items-center m-0 mb-4 font-semibold text-primary">
                <p>Pertanyaan Penilaian</p>
                <span id="saving" class="saving hidden text-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5 animate-spin">
                        <path fill-rule="evenodd" d="M4.755 10.059a7.5 7.5 0 0 1 12.548-3.364l1.903 1.903h-3.183a.75.75 0 1 0 0 1.5h4.992a.75.75 0 0 0 .75-.75V4.356a.75.75 0 0 0-1.5 0v3.18l-1.9-1.9A9 9 0 0 0 3.306 9.67a.75.75 0 1 0 1.45.388Zm15.408 3.352a.75.75 0 0 0-.919.53 7.5 7.5 0 0 1-12.548 3.364l-1.902-1.903h3.183a.75.75 0 0 0 0-1.5H2.984a.75.75 0 0 0-.75.75v4.992a.75.75 0 0 0 1.5 0v-3.18l1.9 1.9a9 9 0 0 0 15.059-4.035.75.75 0 0 0-.53-.918Z" clip-rule="evenodd" />
                    </svg>
                </span>
                <span id="saved" class="saved text-emerald-600">
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
                                foreach ($questions as $question) {
                                if (
                                    ($question->children->count() > 0 && $question->children->whereNull('answer')->count() == 0) 
                                    || 
                                    ($question->children->count() == 0 && ($question->answer == '1' || $question->answer == '0')) 
                                    ) {
                                        $answered_count++;
                                    }
                                }
                            }
                            $indicator_percentage = round(($answered_count/$all_count)*100, 0);
                            if ($indicator_percentage==100) {
                                $indicator_percentage_classlist = "indicator-progess-100";
                            } elseif ($indicator_percentage>=66) {
                                $indicator_percentage_classlist = "indicator-progess-66";
                            } elseif ($indicator_percentage>=33) {
                                $indicator_percentage_classlist = "indicator-progess-33";
                            } else {
                                $indicator_percentage_classlist = "indicator-progess-default";
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
                                    $answered_questions_count   = 0;
                                    foreach ($questions as $question) {
                                        if (
                                            ($question->children->count() > 0 && $question->children->whereNull('answer')->count() == 0) 
                                            || 
                                            ($question->children->count() == 0 && ($question->answer == '1' || $question->answer == '0')) 
                                        ) {
                                            $answered_questions_count++;
                                        }
                                    }
                                    $percentage = round(($answered_questions_count/$questions->count())*100, 0);
                                    if ($percentage==100) {
                                        $category_percentage_classlist = [ "category-progress-container-100", "category-progress-100" ];
                                    } elseif ($percentage>=66) {
                                        $category_percentage_classlist = [ "category-progress-container-66", "category-progress-66" ];
                                    } elseif ($percentage>=33) {
                                        $category_percentage_classlist = [ "category-progress-container-33", "category-progress-33" ];
                                    } else {
                                        $category_percentage_classlist = [ "category-progress-container-default", "category-progress-default" ];
                                    }
                                @endphp
                                <!-- KATEGORI -->
                                <button id="category-button_{{$i}}_{{$j}}" type="button" class="{{ $i==0 && $j==0 ? 'active-category-button' : '' }} cbtn border border-white block rounded-md hover:bg-gray-100 w-full p-1 lg:p-2 {{ $loop->index === 0 ? 'mt-1.5' : 'mt-2' }} border category-button">
                                    <!-- JUDUL KATEGORI -->
                                    <p class="text-left font-bold text-xs text-primary mb-1"> {{ $upper_alphabet[$loop->index] .". ". $category }} </p>
                                    <!-- PROGRES KATEGORI -->
                                    <div class="flex justify-between w-full h-5">
                                        <div id="progres_category_container_{{$i}}_{{$j}}" class="w-[87%] border {{ $category_percentage_classlist[0] }} rounded-lg h-full box-border overflow-hidden">
                                            <div id="progres_category_{{$i}}_{{$j}}" class="h-full border {{ $category_percentage_classlist[1] }} rounded-lg box-border overflow-hidden" style="width: {{ $percentage }}%;"></div>
                                        </div>
                                        <p class="block w-[12%] text-sm text-right text-primary-60 font-mono">
                                            <span id="category_answered_count_{{$i}}_{{$j}}">{{ $answered_questions_count }}</span>/{{ $questions->count() }}
                                        </p>
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
        <div class="fixed z-[1000] top-[6.5rem] xl:top-[3.5rem] h-[calc(100vh-6.5rem)] box-border xl:h-[calc(100vh-3.5rem)] w-full xl:w-3/4 xl:right-0 pb-10 lg:pb-0">
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
                                        <p class="text-xl lg:text-2xl text-primary-70 font-extrabold tracking-wider mb-4">{{ $category }}</p>
                                        @foreach ($questions as $question)
                                            <div class="w-full flex gap-2 border py-3 pl-1 pr-2 rounded-md bg-white {{ $loop->index === 0 ? '' : 'mt-6' }}">
                                                <p class="text-primary-70 w-8 box-border text-sm lg:text-base text-right font-mono">{{ $k+1 }}.</p>
                                                <div class="w-[calc(100%-2.5rem)] box-border pb-2 pr-2">
                                                    <!-- QUESTION TEXT -->
                                                    <p class="text-primary-80 tracking-tight text-sm lg:text-base font-medium p-0">
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
                                                                class="w-1/2 p-1.5 lg:p-2 text-center text-xs font-black tracking-widest cursor-pointer {{ $question->answer === 1 ? 'bg-emerald-500 text-emerald-50' : 'bg-gray-300 text-gray-500' }} hover:bg-emerald-900 hover:bg-opacity-80 hover:text-white rounded-md transition-all ease-in-out duration-[250ms]">
                                                                YA
                                                            </label>
                                                            <label id="label_{{$i}}_{{$j}}_{{$k}}_0" for="answer_{{$i}}_{{$j}}_{{$k}}_0" 
                                                                class="w-1/2 p-1.5 lg:p-2 text-center text-xs font-black tracking-widest cursor-pointer {{ $question->answer === 0 ? 'bg-red-500 text-red-50' : 'bg-gray-300 text-gray-500' }} hover:bg-red-900 hover:bg-opacity-80 hover:text-white rounded-md transition-all ease-in-out duration-[250ms]">
                                                                TIDAK
                                                            </label>
                                                        </div>
                                                        <!-- INPUT TEXT -->
                                                        <div id="attachment_{{$i}}_{{$j}}_{{$k}}_container" class="attachment-field w-full mt-2 {{ $question->answer === 1 ? '' : 'hidden' }}">
                                                            <label for="attachment_{{$i}}_{{$j}}_{{$k}}" class="block mb-2 text-sm lg:text-base font-medium text-primary">Tautan Bukti Pendukung Jawaban</label>
                                                            <input data-questionDBID="{{ $question->id }}" data-oldAnswer="{{ $question->attachment }}" type="text" 
                                                                id="attachment_{{$i}}_{{$j}}_{{$k}}" name="attachment[{{ $i }}][{{ $j }}][{{ $k }}]" value="{{ $question->attachment }}"
                                                                class="attachment-link text-input bg-gray-50 border border-gray-300 text-primary-50 text-sm rounded-md focus:ring-primary-50 focus:border-primary-50 block w-full p-2" placeholder="https://www.example.com">
                                                            <p id="error_msg_{{$i}}_{{$j}}_{{$k}}" class="error-msg hidden mt-1 text-xs text-danger">Tautan Bukti Pendukung Jawaban Wajib Diisi!</p>
                                                            <p class="mt-1 text-xs text-gray-500 text-justify">Pastikan tautan yang anda masukkan valid dan dapat diakses. Kegagalan saat mengakses tautan bermasalah dapat berdampak pada penilaian buruk.</p>
                                                        </div>
                                                    @else
                                                        @php $l=0; @endphp
                                                        @foreach ($question->children as $question_child)
                                                            <div class="w-full bg-gray-100 p-3 rounded-md flex gap-2 mt-3">
                                                                <p class="text-primary-70 w-10 box-border text-xs text-right font-mono">{{ $k+1 }}.{{ $l+1 }}.</p>
                                                                <div class="w-[calc(100%-3rem)] box-border">
                                                                    <!-- QUESTION CHILD TEXT -->
                                                                    <p class="text-primary-70 tracking-tight text-sm lg:text-base font-normal p-0">
                                                                        {{ $question_child->text }}
                                                                    </p>
                                                                    <!-- CHILD ANSWER INPUT RADIO -->
                                                                    <input type="radio" name="answer[{{$i}}][{{$j}}][{{$k}}][{{$l}}]" 
                                                                        id="answer_{{$i}}_{{$j}}_{{$k}}_{{$l}}_1" 
                                                                        class="hidden radio-button" 
                                                                        value="1" data-questionDBID="{{ $question_child->question_children_id }}" @checked($question_child->answer === 1)>
                                                                    <input type="radio" name="answer[{{$i}}][{{$j}}][{{$k}}][{{$l}}]" 
                                                                        id="answer_{{$i}}_{{$j}}_{{$k}}_{{$l}}_0" 
                                                                        class="hidden radio-button" 
                                                                        value="0" data-questionDBID="{{ $question_child->question_children_id }}" @checked($question_child->answer === 0)>
                                                                    <!-- CHILD ANSWER LABEL -->
                                                                    <div class="flex w-2/3 md:w-1/2 xl:w-1/4 gap-2 mt-2">
                                                                        <label id="label_{{$i}}_{{$j}}_{{$k}}_{{$l}}_1" for="answer_{{$i}}_{{$j}}_{{$k}}_{{$l}}_1" 
                                                                            class="w-1/2 p-1.5 lg:p-2 text-center text-xs font-black tracking-widest cursor-pointer {{ $question_child->answer === 1 ? 'bg-emerald-500 text-emerald-50' : 'bg-gray-300 text-gray-500' }} hover:bg-emerald-900 hover:bg-opacity-80 hover:text-white rounded-md transition-all ease-in-out duration-[250ms]">
                                                                            YA
                                                                        </label>
                                                                        <label id="label_{{$i}}_{{$j}}_{{$k}}_{{$l}}_0" for="answer_{{$i}}_{{$j}}_{{$k}}_{{$l}}_0" 
                                                                            class="w-1/2 p-1.5 lg:p-2 text-center text-xs font-black tracking-widest cursor-pointer {{ $question_child->answer === 0 ? 'bg-red-500 text-red-50' : 'bg-gray-300 text-gray-500' }} hover:bg-red-900 hover:bg-opacity-80 hover:text-white rounded-md transition-all ease-in-out duration-[250ms]">
                                                                            TIDAK
                                                                        </label>
                                                                    </div>
                                                                    <!-- CHILD INPUT TEXT -->
                                                                    <div id="attachment_{{$i}}_{{$j}}_{{$k}}_{{$l}}_container" class="attachment-field w-full mt-2 {{ $question_child->answer === 1 ? '' : 'hidden' }}">
                                                                        <label for="attachment_{{$i}}_{{$j}}_{{$k}}_{{$l}}" class="block mb-2 text-xs lg:text-sm font-medium text-primary">Tautan Bukti Pendukung Jawaban</label>
                                                                        <input data-questionDBID="{{ $question_child->question_children_id }}" data-oldAnswer="{{ $question_child->attachment }}" type="text" 
                                                                            id="attachment_{{$i}}_{{$j}}_{{$k}}_{{$l}}" value="{{ $question_child->attachment }}"
                                                                            class="child-attachment-link text-input bg-gray-50 border border-gray-300 text-primary-50 text-sm rounded-md focus:ring-primary-50 focus:border-primary-50 block w-full p-2" placeholder="https://www.example.com">
                                                                        <p id="error_msg_{{$i}}_{{$j}}_{{$k}}_{{$l}}" class="error-msg hidden mt-1 text-xs text-danger">Tautan Bukti Pendukung Jawaban Wajib Diisi!</p>
                                                                        <p class="mt-1 text-xs text-gray-500 text-justify">Pastikan tautan yang anda masukkan valid dan dapat diakses. Kegagalan saat mengakses tautan bermasalah dapat berdampak pada penilaian buruk.</p>
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
                <div class="border-t bg-white w-full h-14 box-border flex justify-between items-center px-3 lg:px-5">
                    <button type="button" id="" class="hidden prev-btn prev-next-btn gap-2 items-center justify-center uppercase w-40 text-white bg-primary hover:bg-primary-70 border border-primary focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-md text-xs pl-1.5 lg:pl-2 pr-3 lg:pr-4 py-1.5 lg:py-2">
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5">
                                <path fill-rule="evenodd" d="M11.03 3.97a.75.75 0 0 1 0 1.06l-6.22 6.22H21a.75.75 0 0 1 0 1.5H4.81l6.22 6.22a.75.75 0 1 1-1.06 1.06l-7.5-7.5a.75.75 0 0 1 0-1.06l7.5-7.5a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd" />
                            </svg>
                        </span>
                        <span>Sebelumnya</span>
                    </button>
                    <div></div>
                    <button type="button" id="next--category-button_0_1" class="next-btn prev-next-btn flex gap-2 items-center justify-center uppercase w-40 text-white bg-primary hover:bg-primary-70 border border-primary focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-md text-xs pl-3 lg:pl-4 pr-1.5 lg:pr-2 py-1.5 lg:py-2">
                        <span>Berikutnya</span>
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5">
                                <path fill-rule="evenodd" d="M12.97 3.97a.75.75 0 0 1 1.06 0l7.5 7.5a.75.75 0 0 1 0 1.06l-7.5 7.5a.75.75 0 1 1-1.06-1.06l6.22-6.22H3a.75.75 0 0 1 0-1.5h16.19l-6.22-6.22a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                            </svg>
                        </span>
                    </button>
                    <button x-on:click="showEndExamPopUp = true" type="button" id="submit_btn" class="hidden submit-btn gap-2 items-center justify-center uppercase w-40 text-white bg-emerald-600 hover:bg-emerald-700 border border-emerald-600 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-md text-xs pr-4 lg:pr-5 pl-2 lg:pl-2.5 py-2 lg:py-2.5">
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5">
                                <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm.53 5.47a.75.75 0 0 0-1.06 0l-3 3a.75.75 0 1 0 1.06 1.06l1.72-1.72v5.69a.75.75 0 0 0 1.5 0v-5.69l1.72 1.72a.75.75 0 1 0 1.06-1.06l-3-3Z" clip-rule="evenodd" />
                            </svg>
                        </span>
                        <span>KIRIM JAWABAN</span>
                    </button>
                </div>
            </div>
        </div>
    </main>


    <!-- SUBMIT EXAM ANSWERS POP UP -->
    <div class="fixed z-[2220] inset-0" x-cloak x-show="showEndExamPopUp">
        <div class="absolute z-[2222] inset-0 bg-primary-90 bg-opacity-30 flex justify-center items-center py-4">
            <div class="bg-white w-10/12 md:w-1/2 lg:2/5 xl:w-1/3 rounded-md p-5 lg:p-6 py-10 lg:py-12 flex flex-col justify-center items-center">
                <div class="w-full text-center mb-3">
                    <p class="text-lg lg:text-xl text-primary font-bold tracking-wide mb-2">
                        Kirim tanggapan penilaian anda?
                    </p>
                    <p class="text-sm lg:text-base text-justify text-primary-50">
                        Setelah mengirim tanggapan, anda tidak dapat mengubahnya lagi, karena tanggapan anda akan melalui proses evaluasi. <br>
                        <span class="mt-1 font-semibold text-primary-70 text-left">
                            Catatan : Pertanyaan yang tidak/belum diisi akan mendapatkan nilai 0.
                        </span>
                    </p>
                </div>
                <div class="w-full flex gap-2 justify-center items-center bg-warning-10 border border-warning text-warning p-1 rounded-md mb-6 xl:mb-8">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    <p class="text-sm">Aksi tidak dapat dibatalkan!</p>
                </div>
                <div class="w-full flex justify-center items-center gap-4">
                    <button type="button" x-on:click="showEndExamPopUp = false" class="block w-[32%] text-primary bg-white border border-primary focus:ring-4 focus:outline-none focus:ring-blue-200 font-medium hover:font-semibold rounded-md text-sm py-2 text-center">
                        KEMBALI
                    </button>
                    <form action="{{ route('questionnaire.submitResponse') }}" class="block w-[32%]"  method="POST">
                        @csrf @method('PUT')
                        <div class="w-full" id="additionalFormFields"></div>
                        <button type="submit" class="block w-full text-white bg-primary hover:bg-primary-70 border border-primary focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-md text-sm py-2 text-center">
                            KIRIM
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

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
                        Anda yakin ingin meninggalkan halaman pengisian penilaian?
                    </p>
                </div>
                <div class="w-full text-center mb-8 xl:mb-10">
                    <p class="text-sm xl:text-base text-gray-900">
                        Jawaban anda saat ini akan tersimpan.
                    </p>
                </div>
                <div class="w-full flex justify-center items-center gap-2 md:gap-4">
                    <button type="button" x-on:click="showExitPopUp = false" 
                        class="block w-40 text-primary bg-white border border-primary focus:ring-4 focus:outline-none focus:ring-blue-200 font-medium hover:font-semibold rounded-md text-xs xl:text-sm py-2.5 text-cente">
                        Lanjutkan Mengisi
                    </button>
                    <a href="{{ route('questionnaire.index') }}"
                        class=" block w-40 text-white bg-danger hover:bg-danger-70 border border-danger focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-md text-xs xl:text-sm py-2.5 text-center">
                        Tinggalkan Halaman
                    </a>
                </div>
            </div>
        </div>
    </div>


    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/flowbite.min.js') }}"></script>
    <script>
        let indicator_category_indices = [];

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
                    $(".saved").addClass("hidden");
                    $(".saving").removeClass("hidden");
                },
                success: function (response){
                    // console.log(response);
                    let i = 0;
                    let questionnaire_all_count         = 0;
                    let questionnaire_answered_count    = 0;
                    $.each(response, function (indicator, categories) {
                        let indicator_all_count      = 0;
                        let indicator_answered_count = 0;
                        let j = 0;
                        $.each(categories, function (category, questions) {
                            let category_answered_count   = 0;
                            indicator_all_count      += questions.length;
                            $.each(questions, function (index, question) { 
                                if ( question.children.length > 0 ) {
                                    let answered_child = 0;
                                    $.each(question.children, function (id, child) { 
                                        (child.answer == '1' || child.answer == '0') && answered_child ++;
                                    });
                                    (answered_child == question.children.length) && category_answered_count++;
                                    (answered_child == question.children.length) && indicator_answered_count++;
                                } else {
                                    (question.answer == '1' || question.answer == '0') && category_answered_count++;
                                    (question.answer == '1' || question.answer == '0') && indicator_answered_count++;
                                }
                            });
                            let category_percentage = Math.round((category_answered_count/questions.length)*100);

                            $(`#progres_category_container_${i}_${j}`).removeClass("category-progress-container-100");
                            $(`#progres_category_container_${i}_${j}`).removeClass("category-progress-container-66");
                            $(`#progres_category_container_${i}_${j}`).removeClass("category-progress-container-33");
                            $(`#progres_category_container_${i}_${j}`).removeClass("category-progress-container-default");

                            $(`#progres_category_${i}_${j}`).removeClass("category-progress-100");
                            $(`#progres_category_${i}_${j}`).removeClass("category-progress-66");
                            $(`#progres_category_${i}_${j}`).removeClass("category-progress-33");
                            $(`#progres_category_${i}_${j}`).removeClass("category-progress-default");

                            if ( category_percentage == 100 ) {
                                $(`#progres_category_container_${i}_${j}`).addClass("category-progress-container-100");
                                $(`#progres_category_${i}_${j}`).addClass("category-progress-100");
                            } else if ( category_percentage >= 66 ) {
                                $(`#progres_category_container_${i}_${j}`).addClass("category-progress-container-66");
                                $(`#progres_category_${i}_${j}`).addClass("category-progress-66");
                            } else if ( category_percentage >= 33 ) {
                                $(`#progres_category_container_${i}_${j}`).addClass("category-progress-container-33");
                                $(`#progres_category_${i}_${j}`).addClass("category-progress-33");
                            } else {
                                $(`#progres_category_container_${i}_${j}`).addClass("category-progress-container-default");
                                $(`#progres_category_${i}_${j}`).addClass("category-progress-default");
                            }
                            
                            $(`#category_answered_count_${i}_${j}`).text(category_answered_count);
                            $(`#progres_category_${i}_${j}`).attr("style", `width: ${category_percentage}%`);
                            j++;
                        });

                        questionnaire_all_count         += indicator_all_count;
                        questionnaire_answered_count    += indicator_answered_count;
                        
                        let indicator_percentage = Math.round((indicator_answered_count/indicator_all_count)*100);
                        $(`#progress_indikator_${i}`).removeClass("indicator-progess-100");
                        $(`#progress_indikator_${i}`).removeClass("indicator-progess-66");
                        $(`#progress_indikator_${i}`).removeClass("indicator-progess-33");
                        $(`#progress_indikator_${i}`).removeClass("indicator-progess-default");

                        if ( indicator_percentage == 100 ) {
                            $(`#progress_indikator_${i}`).addClass("indicator-progess-100");
                        } else if ( indicator_percentage >= 66 ) {
                            $(`#progress_indikator_${i}`).addClass("indicator-progess-66");
                        } else if ( indicator_percentage >= 33 ) {
                            $(`#progress_indikator_${i}`).addClass("indicator-progess-33");
                        } else {
                            $(`#progress_indikator_${i}`).addClass("indicator-progess-default");
                        }
                        $(`#progress_indikator_${i}`).text(`${indicator_percentage}%`);
                        i++;
                    });

                    let questionnaire_percentage = Math.round((questionnaire_answered_count/questionnaire_all_count)*100);
                    // console.log(questionnaire_answered_count);
                    // console.log(questionnaire_all_count);
                    // console.log(questionnaire_percentage);
                    $(`#progress_questionnaire_container`).removeClass("category-progress-container-100");
                    $(`#progress_questionnaire_container`).removeClass("category-progress-container-66");
                    $(`#progress_questionnaire_container`).removeClass("category-progress-container-33");
                    $(`#progress_questionnaire_container`).removeClass("category-progress-container-default");

                    $(`#progress_questionnaire`).removeClass("category-progress-100");
                    $(`#progress_questionnaire`).removeClass("category-progress-66");
                    $(`#progress_questionnaire`).removeClass("category-progress-33");
                    $(`#progress_questionnaire`).removeClass("category-progress-default");

                    if ( questionnaire_percentage == 100 ) {
                        $(`#progress_questionnaire_container`).addClass("category-progress-container-100");
                        $(`#progress_questionnaire`).addClass("category-progress-100");
                    } else if ( questionnaire_percentage >= 66 ) {
                        $(`#progress_questionnaire_container`).addClass("category-progress-container-66");
                        $(`#progress_questionnaire`).addClass("category-progress-66");
                    } else if ( questionnaire_percentage >= 33 ) {
                        $(`#progress_questionnaire_container`).addClass("category-progress-container-33");
                        $(`#progress_questionnaire`).addClass("category-progress-33");
                    } else {
                        $(`#progress_questionnaire_container`).addClass("category-progress-container-default");
                        $(`#progress_questionnaire`).addClass("category-progress-default");
                    }

                    $("#questionnaire_answered_count").text(questionnaire_answered_count);
                    $("#questionnaire_all_count").text(questionnaire_all_count);
                    $(`#progress_questionnaire`).attr("style", `width: ${questionnaire_percentage}%`);
                },
                complete: function(){
                    $(".saving").addClass("hidden");
                    $(".saved").removeClass("hidden");
                },
            });
        }

        function processQuestion(currentIndex, targetIndex){
            $(".error-msg").addClass("hidden");
            $(".text-input").removeClass("border-danger");
            $(".text-input").addClass("border-gray-300");
            
            let targetRawID         = targetIndex;
            let targetIndicatorID   = targetRawID.split('_')[1];
            let targetCategoryID    = targetRawID.split('_')[2];
            
            let currentRawID        = currentIndex;
            let currentIndicatorID  = currentRawID.split('_')[1];
            let currentCategoryID   = currentRawID.split('_')[2];

            let groupedCurrentRadios         = {};
            var groupsCheckedOnTrueButEmpty  = [];
            
            // GET ALL RADIOS BUTTON GROUP OF CURRENTLY ACTIVE CATEGORY
            $('#questions-container_'+currentIndicatorID+'_'+currentCategoryID)
                .find('[type="radio"]')
                .each(function() {
                    let rawID       = $(this).attr('id');
                    let parts       = rawID.split("_");
                    let newParts    = parts.slice(1, -1);
                    let processedID = newParts.join("_");
                    if (!groupedCurrentRadios[processedID]) {
                        groupedCurrentRadios[processedID] = [];
                    }
                    groupedCurrentRadios[processedID].push(this);
                }
            );

            // GET ALL RADIOS BUTTON GROUP THAT CHECKED ON TRUE BUT THE ATTACHMENT LINK TEXT INPUT IS EMPTY
            $.each(groupedCurrentRadios, function(processedID, radios) {
                let isCheckedOnTrue = radios.some(function(radio) {
                    return $(radio).val() === '1' && $(radio).is(':checked');
                });
                let isTextInputEmpty = $("#attachment_"+processedID).val().trim() === '';
                if (isCheckedOnTrue && isTextInputEmpty) {
                    groupsCheckedOnTrueButEmpty.push(processedID);
                }
            });

            if (groupsCheckedOnTrueButEmpty.length > 0) {
                $.each(groupsCheckedOnTrueButEmpty, function(index, groupName) {
                    let textInput = $("#attachment_"+groupName)
                    if (textInput.val().trim() === '') {
                        textInput.removeClass("border-gray-300");
                        textInput.addClass("border-danger");
                        $("#error_msg_"+groupName).removeClass("hidden");
                    }
                });
            } else {
                let target_index;
                for (let i = 0; i < indicator_category_indices.length; i++) {
                    if(indicator_category_indices[i] === targetRawID) {
                        target_index = i;
                        break;
                    }
                }
                if(target_index == 0) {
                    $("#submit_btn").removeClass("flex");
                    $("#submit_btn").addClass("hidden");

                    $(".prev-btn").removeClass("flex");
                    $(".prev-btn").addClass("hidden");
                    
                    $(".next-btn").attr("id",`next--${indicator_category_indices[target_index+1]}`);
                } else if (target_index == indicator_category_indices.length-1) {
                    $(".next-btn").removeClass("flex");
                    $(".next-btn").addClass("hidden");
                    $("#submit_btn").removeClass("hidden");
                    $("#submit_btn").addClass("flex");
                    $(".prev-btn").attr("id",`prev--${indicator_category_indices[target_index-1]}`);
                } else {
                    $("#submit_btn").removeClass("flex");
                    $("#submit_btn").addClass("hidden");
                    
                    $(".prev-btn").removeClass("hidden");
                    $(".prev-btn").addClass("flex");
                    
                    $(".next-btn").removeClass("hidden");
                    $(".next-btn").addClass("flex");

                    $(".prev-btn").attr("id",`prev--${indicator_category_indices[target_index-1]}`);
                    $(".next-btn").attr("id",`next--${indicator_category_indices[target_index+1]}`);
                }

                $(".category-button").removeClass("active-category-button");
                $(`#${targetIndex}`).addClass("active-category-button");
                $(".questions-container").addClass("hidden");
                $("#questions-container_"+targetIndicatorID+"_"+targetCategoryID).removeClass("hidden");
            }
        }

        $(document).ready(function () {
            // $("#main-loading-indicator").removeClassß("flex");
            // $("#main-loading-indicator").addClass("hidden");
            let indicators = @json($indicators);
            let i = 0;
            $.each(indicators, function (indicatorKey, categories) { 
                let j = 0;
                $.each(categories, function (categoryKey, value) { 
                    indicator_category_indices.push(`category-button_${i}_${j}`); j++;
                }); i++;
            });

            $(".radio-button").change(function (e) { 
                e.preventDefault();

                let rawID           = $(this).attr('id');
                let indicatorID     = rawID.split('_')[1];
                let categoryID      = rawID.split('_')[2];
                let questionID      = rawID.split('_')[3];
                let value           = rawID.split('_').length == 6 ? rawID.split('_')[5] : rawID.split('_')[4];
                let questionChildID = rawID.split('_').length == 6 ? rawID.split('_')[4] : null;

                let questionDBID    = $(this).attr("data-questionDBID");
                
                if (questionChildID != null && value == 1) {
                    // SHOW INPUT TEXT
                    $("#attachment_"+indicatorID+"_"+categoryID+"_"+questionID+"_"+questionChildID+"_container").removeClass("hidden");
                    // COLOR BUTTON
                    $("#label_"+indicatorID+"_"+categoryID+"_"+questionID+"_"+questionChildID+"_0").removeClass("bg-red-500 text-red-50");
                    $("#label_"+indicatorID+"_"+categoryID+"_"+questionID+"_"+questionChildID+"_0").addClass("bg-gray-300 text-gray-500");
                    $("#label_"+indicatorID+"_"+categoryID+"_"+questionID+"_"+questionChildID+"_1").removeClass("bg-gray-300 text-gray-500");
                    $("#label_"+indicatorID+"_"+categoryID+"_"+questionID+"_"+questionChildID+"_1").addClass("bg-emerald-500 text-emerald-50");
                } else if (questionChildID != null && value == 0) {
                    // HIDE INPUT TEXT
                    $("#attachment_"+indicatorID+"_"+categoryID+"_"+questionID+"_"+questionChildID+"_container").addClass("hidden");
                    // CLEAR INPUT TEXT VALUE
                    $("#attachment_"+indicatorID+"_"+categoryID+"_"+questionID+"_"+questionChildID).val("");
                    // REMOVE ERROR MESSAGE
                    $("#attachment_"+indicatorID+"_"+categoryID+"_"+questionID+"_"+questionChildID).removeClass("border-danger");
                    $("#attachment_"+indicatorID+"_"+categoryID+"_"+questionID+"_"+questionChildID).addClass("border-gray-300");
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
                    // REMOVE ERROR MESSAGE
                    $("#attachment_"+indicatorID+"_"+categoryID+"_"+questionID).removeClass("border-danger");
                    $("#attachment_"+indicatorID+"_"+categoryID+"_"+questionID).addClass("border-gray-300");
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

                // console.log(radio);
                // console.log(questionDBID);
                // console.log(oldAnswer);
                // console.log(newAnswer);

                if (radio==1) {
                    if(oldAnswer != newAnswer && newAnswer != "") {
                        ajaxCall("{{ route('questionnaire.updateAnswerChild') }}", questionDBID, 1, newAnswer);
                    }
                }
            });

            // QUESTION NAVIGATION BY CATEGORIES BUTTON
            $(".category-button").click(function (e) { 
                e.preventDefault();
                let targetRawID         = $(this).attr('id');
                let currentRawID        = $(".category-button.active-category-button").attr('id');
                
                // CALL THE MAIN PROCESS
                processQuestion(currentRawID, targetRawID);
            });

            // QUESTION NAVIGATION BY NEXT & PREV BUTTON
            $("#questionContainer").on("click", ".prev-next-btn", function(e){
                e.preventDefault();

                // Getting question index in the Array
                let currentRawID;
                let targetRawID     = $(this).attr('id').split("--")[1];
                let parts           = targetRawID.split("_");
                let newParts        = parts.slice(0, -1);
                if ($(this).attr('id').split("--")[0] === 'prev') {
                    currentRawID    = newParts.join("_") + "_" + (parseInt(parts[parts.length-1])+1).toString();
                } else {
                    currentRawID    = newParts.join("_") + "_" + (parseInt(parts[parts.length-1])-1).toString();
                }
                
                // CALL THE MAIN PROCESS
                processQuestion(currentRawID, targetRawID);
            });
        });
    </script>
    @livewireScripts
</body>
</html>