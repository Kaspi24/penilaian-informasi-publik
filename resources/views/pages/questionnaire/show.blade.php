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
        showAdminEndExamPopUp : false,
        showEndExamPopUp : false,
        showExitPopUp : false,
        showSidebar : false,
    }">

    <!-- PAGE HEADER (EXAM TITLE) -->
    <nav class="fixed z-[999] top-0 w-full bg-primary text-white h-[3.5rem] flex justify-between xl:grid xl:grid-cols-3 items-center px-4">
        <div class="relative">
            <a href="{{ route('questionnaire.index') }}" x-on:click="showExitPopUp = true" class="block xl:flex xl:items-center xl:gap-2 text-white hover:text-gray-200">
                <span class="block m-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 15l-3-3m0 0l3-3m-3 3h8M3 12a9 9 0 1118 0 9 9 0 01-18 0z" />
                    </svg>
                </span>
                <span class="hidden text-xs xl:text-sm xl:block xl:m-0">Kembali</span>
            </a>
        </div>
        <div class="flex justify-center items-center gap-2">
            <img src="{{ asset('logo/KEMENHUB.png') }}" class="h-8 w-auto" alt="">
            <p class="block m-0 text-xs xl:text-sm xl:text-center">PENILAIAN ANUGERAH<br class="lg:hidden">KETERBUKAAN INFORMASI PUBLIK</p>
        </div>
    </nav>

    <!-- QUESTIONS NAVIGATION FOR MOBILE -->
    <div class="flex xl:hidden fixed z-[1010] top-[3.5rem] h-[3rem] shadow shadow-gray-400 w-full bg-primary-10 text-primary py-2 px-4 justify-between items-center"
        x-data="{showRespondentDetails: false}">
        @php
            $scored_count = 0;
            $total_score = 0;
            foreach ($indicators as $indicator => $categories) {
                foreach ($categories as $category => $questions) {
                    foreach ($questions as $question) {
                        $question->updated_by && $scored_count++;
                        $total_score += $question->score;
                    }
                }
            }
            $questionnaire_percentage = round(($scored_count/35)*100, 0);

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
        <div class="w-full h-full flex py-1 items-center justify-between relative">
            <span class="w-7 cursor-pointer" x-on:click="showRespondentDetails =! showRespondentDetails">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                </svg>
            </span>
            <div id="progress_questionnaire_container" class="w-[calc(73%-1.75rem)] md:w-[calc(87%-1.75rem)] border {{ $questionnaire_percentage_classlist[0] }} rounded-lg h-full box-border overflow-hidden">
                <div id="progress_questionnaire" class="h-full border {{ $questionnaire_percentage_classlist[1] }} rounded-lg box-border overflow-hidden" style="width: {{ $questionnaire_percentage }}%;"></div>
            </div>
            <p class="w-[26%] md:w-[12%] text-sm font-bold text-right"><span id="questionnaire_scored_count">{{ $scored_count }}</span>/<span id="questionnaire_all_count">35</span> DINILAI</p>
            <div x-cloak x-show="showRespondentDetails" class="absolute w-full bg-primary-10 border-x border-primary-10/70 shadow shadow-gray-400 text-primary top-full mt-2 rounded-b-md p-2">
                <p class="font-semibold mb-2">Detail Responden</p>
                <div class="p-2 border border-primary-20 rounded-md bg-white text-primary-70 shadow-inner">
                    <table class="text-xs">
                        <tbody>
                            <tr class="">
                                <th class="py-1 w-[24%] align-top text-left">Nama</th>
                                <td class="py-1 inline-block mx-1">:</td>
                                <td class="py-1 text-left">{{ $respondent->name }}</td>
                            </tr>
                            <tr class="">
                                <th class="py-1 w-[24%] align-top text-left">Unit Kerja</th>
                                <td class="py-1 inline-block mx-1">:</td>
                                <td class="py-1 text-left">{{ $respondent->work_unit->name }}</td>
                            </tr>
                            <tr class="">
                                <th class="py-1 w-[24%] align-top text-left">Total Nilai</th>
                                <td class="py-1 inline-block mx-1">:</td>
                                <td class="questionnaire_total_score py-1 text-left">{{ $total_score }}</td>
                            </tr>

                            <tr class="">
                                <th class="py-1 w-[24%] align-top text-left">Predikat</th>
                                <td class="py-1 inline-block mx-1">:</td>
                                <td class="questionnaire_total_score py-1 text-left">
                                    @if ($submission->total_score >= 90)
                                        <p class="text-[0.65rem] w-fit font-bold text-green-200 bg-green-600 py-0.5 px-1.5 rounded">
                                            INFORMATIF
                                        </p>
                                    @elseif ($submission->total_score >= 80)
                                        <p class="text-[0.65rem] w-fit font-bold text-lime-200 bg-lime-600 py-0.5 px-1.5 rounded">
                                            MENUJU INFORMATIF
                                        </p>
                                    @elseif ($submission->total_score >= 60)
                                        <p class="text-[0.65rem] w-fit font-bold text-yellow-50 bg-yellow-300 py-0.5 px-1.5 rounded">
                                            CUKUP INFORMATIF
                                        </p>
                                    @elseif ($submission->total_score >= 40)
                                        <p class="text-[0.65rem] w-fit font-bold text-orange-100 bg-orange-500 py-0.5 px-1.5 rounded">
                                            KURANG INFORMATIF
                                        </p>
                                    @else
                                        <p class="text-[0.65rem] w-fit font-bold text-red-200 bg-red-600 py-0.5 px-1.5 rounded">
                                            TIDAK INFORMATIF
                                        </p>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <main class="relative w-full">
        <!-- QUESTIONS NAVIGATION -->
        <div class="hidden xl:block fixed z-[999] top-[3.5rem] h-[calc(100vh-3.5rem)] w-1/4 box-border border-r-4 border-primary bg-primary-10 bg-opacity-60 p-4">

            <div class="w-full m-0 mb-4 text-primary">
                <p class="font-semibold mb-2">Detail Responden</p>
                <div class="p-2 border border-primary-20 rounded-md bg-primary-10 text-primary-70 shadow-inner">
                    <table class="text-sm">
                        <tbody>
                            <tr class="">
                                <th class="py-1 w-[24%] align-top text-left">Nama</th>
                                <td class="py-1 inline-block mx-1">:</td>
                                <td class="py-1 text-left">{{ $respondent->name }}</td>
                            </tr>
                            <tr class="">
                                <th class="py-1 w-[24%] align-top text-left">Unit Kerja</th>
                                <td class="py-1 inline-block mx-1">:</td>
                                <td class="py-1 text-left">{{ $respondent->work_unit->name }}</td>
                            </tr>
                            <tr class="">
                                <th class="py-1 w-[24%] align-top text-left">Total Nilai</th>
                                <td class="py-1 inline-block mx-1">:</td>
                                <td class="questionnaire_total_score py-1 text-left">{{ $total_score }}</td>
                            </tr>
                            <tr class="">
                                <th class="py-1 w-[24%] align-top text-left">Predikat</th>
                                <td class="py-1 inline-block mx-1">:</td>
                                <td class="questionnaire_total_score py-1 text-left">
                                    @if ($submission->total_score >= 90)
                                        <p class="text-[0.65rem] w-fit font-bold text-green-200 bg-green-600 py-0.5 px-1.5 rounded">
                                            INFORMATIF
                                        </p>
                                    @elseif ($submission->total_score >= 80)
                                        <p class="text-[0.65rem] w-fit font-bold text-lime-200 bg-lime-600 py-0.5 px-1.5 rounded">
                                            MENUJU INFORMATIF
                                        </p>
                                    @elseif ($submission->total_score >= 60)
                                        <p class="text-[0.65rem] w-fit font-bold text-yellow-50 bg-yellow-300 py-0.5 px-1.5 rounded">
                                            CUKUP INFORMATIF
                                        </p>
                                    @elseif ($submission->total_score >= 40)
                                        <p class="text-[0.65rem] w-fit font-bold text-orange-100 bg-orange-500 py-0.5 px-1.5 rounded">
                                            KURANG INFORMATIF
                                        </p>
                                    @else
                                        <p class="text-[0.65rem] w-fit font-bold text-red-200 bg-red-600 py-0.5 px-1.5 rounded">
                                            TIDAK INFORMATIF
                                        </p>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="flex gap-1 w-full items-center m-0 mb-2 font-semibold text-primary">
                <p class="text-base">Tanggapan Penilaian</p>
            </div>
            <div class="relative w-full h-fit max-h-[calc(100vh-17.5rem)] p-2 rounded-md bg-primary-10 overflow-y-auto custom-scrollbar border border-primary-20 shadow-inner">
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
                            $all_count          = 0;
                            $scored_count       = 0;
                            $indicator_score    = 0;
                            foreach ($categories as $category => $questions) {
                                $all_count      += $questions->count();
                                foreach ($questions as $question) {
                                    $question->updated_by && $scored_count++;
                                    $indicator_score += $question->score;
                                }
                            }
                            $indicator_percentage = round(($scored_count/$all_count)*100, 0);
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
                                {{-- <span id="progress_indikator_{{ $loop->index }}" class="w-12 {{ $indicator_percentage_classlist }} text-xs text-center rounded-md h-fit">{{ $indicator_percentage }}%</span> --}}
                                <span>{{ $indicator }}</span>
                                <span id="indicator_total_score_{{ $loop->index }}" class="font-sans font-medium text-warning">({{ $indicator_score }})</span>
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
                                    $scored_questions_count   = 0;
                                    foreach ($questions as $question) {
                                        $question->updated_by && $scored_questions_count++;
                                    }
                                    $percentage = round(($scored_questions_count/$questions->count())*100, 0);
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
                                    <p class="text-left font-bold text-xs text-primary mb-1"> {{ $upper_alphabet[$loop->index] .". ". $category }} (<span class="category-total-score_{{$i}}_{{$j}} font-medium">{{ $questions->sum('score') }}</span>) </p>
                                    <!-- PROGRES KATEGORI -->
                                    <div class="flex justify-between w-full h-5">
                                        <div id="progres_category_container_{{$i}}_{{$j}}" class="w-[87%] border {{ $category_percentage_classlist[0] }} rounded-lg h-full box-border overflow-hidden">
                                            <div id="progres_category_{{$i}}_{{$j}}" class="h-full border {{ $category_percentage_classlist[1] }} rounded-lg box-border overflow-hidden" style="width: {{ $percentage }}%;"></div>
                                        </div>
                                        <p class="block w-[12%] text-sm text-right text-primary-60 font-mono">
                                            <span id="category_scored_count_{{$i}}_{{$j}}">{{ $scored_questions_count }}</span>/{{ $questions->count() }}
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
                        <div class="w-full h-full bg-primary-10 border-y rounded-md overflow-y-auto p-4 shadow-inner"> @php $i=0; @endphp
                            @foreach ($indicators as $indicator => $categories) @php $j=0; @endphp
                                @foreach ($categories as $category => $questions) @php $k=0; @endphp
                                    <!-- RESPONSE CONTAINER -->
                                    <div id="questions-container_{{$i}}_{{$j}}" class="questions-container {{ ($i==0 && $j==0) ? '' : 'hidden' }}  w-full">
                                        <!-- CATEGORY TITLE -->
                                        <p class="text-xl lg:text-2xl text-primary-70 font-extrabold tracking-wider mb-4">
                                            {{ $category }} <span class="tracking-normal font-medium text-lg lg:text-xl">(NILAI KATEGORI : <span class="category-total-score_{{$i}}_{{$j}} font-extrabold">{{ $questions->sum('score') }}</span>)</span>
                                        </p>
                                        @foreach ($questions as $question)
                                            {{-- @php
                                                dump($question);
                                            @endphp --}}
                                            <!-- MAIN CONTENT -->
                                            <div class="w-full flex gap-2 border py-3 pl-1 pr-2 rounded-md bg-white {{ $loop->index === 0 ? '' : 'mt-6' }}">
                                                <p class="text-primary-70 w-8 box-border text-sm lg:text-base text-right font-mono">{{ $k+1 }}.</p>
                                                <div class="w-[calc(100%-2.5rem)] box-border pb-3 pr-3">
                                                    <!-- QUESTION TEXT -->
                                                    <p class="text-primary-80 tracking-tight text-sm lg:text-base font-medium p-0 mb-2">
                                                        {{ $question->text }}
                                                        <!-- QUESTION TEXT DETAIL -->
                                                        @if ($question->details)
                                                            <br>
                                                            <span class="text-xs text-primary-50 font-medium">{{ $question->details }}</span>
                                                        @endif
                                                    </p>
                                                    
                                                    
                                                    <!-- RESPONSE -->
                                                    @if ($question->children->count() === 0)
                                                        <div class="w-full md:flex md:gap-3 h-fit">
                                                            @if ($question->answer === 1)
                                                                <div class="shadow shadow-primary-20 w-full md:w-[41.5%] xl:w-[31.25%] rounded-md mb-4 md:mb-0">
                                                                    <div class="flex items-center justify-center w-full gap-2 border rounded-t-md p-1">
                                                                        <p class="px-2 text-xs md:text-sm font-bold uppercase text-primary-40">Tanggapan responden </p>
                                                                        <p class="w-20 md:w-24 p-2 text-center text-xs shadow-inner shadow-emerald-700 font-black tracking-bold bg-emerald-500 text-emerald-50 rounded-md">
                                                                            YA
                                                                        </p>
                                                                    </div>
                                                                    <div class="py-2 px-3 bg-primary-10 rounded-b-md">
                                                                        <a href="{{ $question->attachment }}" target="_blank" class="flex gap-2 justify-center items-center text-primary-50 hover:text-primary">
                                                                            <p class="uppercase text-xs md:text-sm font-bold pt-0.5">Bukti Pendukung Jawaban</p>
                                                                            <span>
                                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 md:w-[1.125rem] md:h-[1.125rem]">
                                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                                                                                </svg>
                                                                            </span>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                                <!-- LABELS -->
                                                                <div class="flex justify-between w-full md:w-[57%] xl:w-[67.5%] rounded-md ">
                                                                    <div class="w-[calc(100%-3.5rem)] md:w-[calc(100%-4.75rem)] p-1 md:p-2 rounded-md bg-primary-10 shadow shadow-primary-20">
                                                                        <p class="text-xs md:text-sm mb-1 text-center text-primary font-bold">KESESUAIAN BUKTI PENDUKUNG</p>
                                                                        <div class="w-full grid grid-cols-4 gap-1 md:gap-1.5 xl:gap-2 p-1 md:p-1.5 xl:p-[0.4rem] rounded-md bg-gray-50 border border-primary-20">
                                                                            <div class="flex justify-center items-center rounded-md {{ $question->score == $question->less_good ? 'bg-primary text-white' : 'bg-gray-200 text-gray-500' }}">
                                                                                <p class="text-[0.6rem] md:text-[0.65rem] xl:text-[0.7rem] leading-3 font-bold py-3 md:p-1.5 tracking-tighter xl:tracking-tight text-center">KURANG</p>
                                                                            </div>
                                                                            <div class="flex justify-center items-center rounded-md {{ $question->score == $question->good_enough ? 'bg-primary text-white' : 'bg-gray-200 text-gray-500' }}">
                                                                                <p class="text-[0.6rem] md:text-[0.65rem] xl:text-[0.7rem] leading-3 font-bold py-3 md:p-1.5 tracking-tighter xl:tracking-tight text-center">CUKUP</p>
                                                                            </div>
                                                                            <div class="flex justify-center items-center rounded-md {{ $question->score == $question->good ? 'bg-primary text-white' : 'bg-gray-200 text-gray-500' }}">
                                                                                <p class="text-[0.6rem] md:text-[0.65rem] xl:text-[0.7rem] leading-3 font-bold py-3 md:p-1.5 tracking-tighter xl:tracking-tight text-center">HAMPIR</p>
                                                                            </div>
                                                                            <div class="flex justify-center items-center rounded-md {{ $question->score == $question->very_good ? 'bg-primary text-white' : 'bg-gray-200 text-gray-500' }}">
                                                                                <p class="text-[0.6rem] md:text-[0.65rem] xl:text-[0.7rem] leading-3 font-bold py-3 md:p-1.5 tracking-tighter xl:tracking-tight text-center">SESUAI</p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="border border-primary rounded-md overflow-hidden w-12 md:w-16 bg-primary-10/25">
                                                                        <p class="text-xs font-bold tracking-tight p-1 bg-primary text-warning text-center">NILAI</p>
                                                                        <p id="value_score_{{$i}}_{{$j}}_{{$k}}" class="text-lg md:text-2xl text-center font-sans font-black p-1 py-2.5 md:py-[0.68rem] bg-primary-10/25 text-primary">{{ round($question->score,1) }}</p>
                                                                    </div>
                                                                </div>
                                                            @else
                                                                <div class="shadow shadow-primary-20 w-full md:w-fit h-fit rounded-md">
                                                                    <div class="flex items-center justify-center w-full gap-1 lg:gap-2 rounded-md p-1">
                                                                        <p class="px-2 text-xs md:text-sm font-bold uppercase text-primary-40">Tanggapan responden </p>
                                                                        <p class="w-20 md:w-24 p-2 text-center text-xs shadow-inner shadow-red-700 font-black tracking-bold bg-red-500 text-red-50 rounded-md">
                                                                            TIDAK
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                                <div class="mt-3 md:mt-0 w-full md:w-[57%] xl:w-[67.5%] flex justify-center border border-primary rounded-md overflow-hidden bg-primary-10/25">
                                                                    <div class="bg-primary flex items-center w-1/2">
                                                                        <p class="text-xs font-bold tracking-tight p-1 text-warning mx-auto">NILAI</p>
                                                                    </div>
                                                                    <div class="flex items-center w-1/2">
                                                                        <p id="value_score_{{$i}}_{{$j}}_{{$k}}" class="text-lg md:text-2xl mx-auto font-sans font-black text-primary">
                                                                            {{ round($question->score,1) }}
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    @else 
                                                        @php $l=0; @endphp
                                                        <div class="w-full">
                                                            @foreach ($question->children as $question_child)
                                                                <div class="w-full bg-gray-100/80 p-2 md:p-3 rounded-md mt-2 md:mt-3 border border-primary-10/25 shadow">
                                                                    <div class="w-full box-border">
                                                                        <!-- QUESTION CHILD TEXT -->
                                                                        <p class="text-primary-70 tracking-tight text-sm font-normal p-0 w-full mb-2">
                                                                            <span class="text-primary-80 font-semibold">{{ $k+1 }}.{{ $l+1 }}.</span> {{ $question_child->text }}
                                                                        </p>
                                                                        @if ($question_child->answer === 1)
                                                                            <div class="shadow shadow-primary-20 w-full md:w-fit rounded-md">
                                                                                <div class="flex items-center justify-center w-full gap-1 lg:gap-2 border rounded-t-md p-1">
                                                                                    <p class="px-1 lg:px-2 text-xs lg:text-sm font-semibold tracking-tight uppercase text-primary-40">Tanggapan responden </p>
                                                                                    <p class="w-[4.5rem] lg:w-24 p-1 lg:p-2 text-center text-xs shadow-inner shadow-emerald-700 font-medium bg-emerald-500 text-emerald-50 rounded-md">
                                                                                        YA
                                                                                    </p>
                                                                                </div>
                                                                                <div class="py-2 px-3 bg-primary-10 rounded-b-md">
                                                                                    <a href="{{ $question_child->attachment }}" target="_blank" class="flex gap-2 justify-center items-center text-primary-50 hover:text-primary">
                                                                                        <p class="uppercase text-xs lg:text-sm font-bold pt-0.5">Bukti Pendukung Jawaban</p>
                                                                                        <span>
                                                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 md:w-[1.125rem] md:h-[1.125rem]">
                                                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                                                                                            </svg>
                                                                                        </span>
                                                                                    </a>
                                                                                </div>
                                                                            </div>
                                                                        @else
                                                                            <div class="shadow shadow-primary-20 w-full md:w-fit rounded-md">
                                                                                <div class="flex items-center justify-center w-full gap-1 lg:gap-2 rounded-md p-1">
                                                                                    <p class="px-1 lg:px-2 text-xs lg:text-sm font-semibold tracking-tight uppercase text-primary-40">Tanggapan responden </p>
                                                                                    <p class="w-[4.5rem] lg:w-24 p-1 lg:p-2 text-center text-xs shadow-inner shadow-red-700 font-black tracking-bold bg-red-500 text-red-50 rounded-md">
                                                                                        TIDAK
                                                                                    </p>
                                                                                </div>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                </div> @php $l++; @endphp
                                                            @endforeach
                                                        </div>
                                                        <!-- LABELS FOR QUESTION WITH CHILDREN -->
                                                        @if ($question->children->where('answer',1)->count() > 0)
                                                            <div class="flex justify-between w-full rounded-md mt-4">
                                                                <div class="w-[calc(100%-3.5rem)] md:w-[calc(100%-4.75rem)] p-1 md:p-2 rounded-md bg-primary-10 shadow shadow-primary-20">
                                                                    <p class="text-xs md:text-sm mb-1 text-center text-primary font-bold">KESESUAIAN BUKTI PENDUKUNG</p>
                                                                    <div class="w-full grid grid-cols-4 gap-1 md:gap-1.5 xl:gap-2 p-1 md:p-1.5 xl:p-[0.4rem] rounded-md bg-gray-50 border border-primary-20">
                                                                        <div class="flex justify-center items-center rounded-md {{ $question->score == $question->less_good ? 'bg-primary text-white' : 'bg-gray-200 text-gray-500' }}">
                                                                            <p class="text-[0.6rem] md:text-[0.65rem] xl:text-[0.7rem] leading-3 font-bold py-3 md:p-1.5 tracking-tighter xl:tracking-tight text-center">KURANG</p>
                                                                        </div>
                                                                        <div class="flex justify-center items-center rounded-md {{ $question->score == $question->good_enough ? 'bg-primary text-white' : 'bg-gray-200 text-gray-500' }}">
                                                                            <p class="text-[0.6rem] md:text-[0.65rem] xl:text-[0.7rem] leading-3 font-bold py-3 md:p-1.5 tracking-tighter xl:tracking-tight text-center">CUKUP</p>
                                                                        </div>
                                                                        <div class="flex justify-center items-center rounded-md {{ $question->score == $question->good ? 'bg-primary text-white' : 'bg-gray-200 text-gray-500' }}">
                                                                            <p class="text-[0.6rem] md:text-[0.65rem] xl:text-[0.7rem] leading-3 font-bold py-3 md:p-1.5 tracking-tighter xl:tracking-tight text-center">HAMPIR</p>
                                                                        </div>
                                                                        <div class="flex justify-center items-center rounded-md {{ $question->score == $question->very_good ? 'bg-primary text-white' : 'bg-gray-200 text-gray-500' }}">
                                                                            <p class="text-[0.6rem] md:text-[0.65rem] xl:text-[0.7rem] leading-3 font-bold py-3 md:p-1.5 tracking-tighter xl:tracking-tight text-center">SESUAI</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="border border-primary rounded-md overflow-hidden w-12 md:w-16 bg-primary-10/25">
                                                                    <p class="text-xs font-bold tracking-tight p-1 bg-primary text-warning text-center">NILAI</p>
                                                                    <p id="value_score_{{$i}}_{{$j}}_{{$k}}" class="text-lg md:text-2xl text-center font-sans font-black p-1 py-2.5 md:py-[0.68rem] bg-primary-10/25 text-primary">
                                                                        {{ round($question->score,1) }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        @else 
                                                            <div class="mt-3 flex justify-center border border-primary rounded-md overflow-hidden bg-primary-10/25">
                                                                <div class="bg-primary flex items-center w-1/2">
                                                                    <p class="text-xs font-bold tracking-tight p-1 text-warning mx-auto">NILAI</p>
                                                                </div>
                                                                <div class="flex items-center w-1/2">
                                                                    <p id="value_score_{{$i}}_{{$j}}_{{$k}}" class="text-lg md:text-2xl mx-auto font-sans font-black text-primary">
                                                                        {{ round($question->score,1) }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    <div id="updated_by_score_{{$i}}_{{$j}}_{{$k}}" 
                                                        class="{{ $question->updated_by && ($question->answer === 1 || ($question->children->count() > 0 && $question->children->where('answer',1)->count() > 0)) ? '' : 'hidden' }} w-full mt-4 p-1.5 bg-primary-10/25 rounded border border-primary-20/25">
                                                        <p class="text-xs md:text-sm font-medium text-gray-500">
                                                            Nilai terakhir diperbarui oleh
                                                            <span id="updated_by_name_score_{{$i}}_{{$j}}_{{$k}}" class="text-primary font-semibold">
                                                                {{ $question->updated_by === Auth::user()->id ? 'Anda' : $question->updated_by_name }}
                                                            </span>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div> @php $k++; @endphp
                                        @endforeach
                                    </div> @php $j++; @endphp
                                @endforeach @php $i++; @endphp
                            @endforeach
                        </div>
                    </div>
                </div>
                <!-- QUESTION CONTAINER FOOTER -->
                <div class="border-t bg-white w-full h-14 lg:h-20 box-border flex justify-between items-center px-3 lg:px-5">
                    <button type="button" id="" class="hidden prev-btn prev-next-btn gap-2 items-center justify-center uppercase w-40 text-white bg-primary hover:bg-primary-70 border border-primary focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-md text-xs pl-2 pr-4 py-2">
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5">
                                <path fill-rule="evenodd" d="M11.03 3.97a.75.75 0 0 1 0 1.06l-6.22 6.22H21a.75.75 0 0 1 0 1.5H4.81l6.22 6.22a.75.75 0 1 1-1.06 1.06l-7.5-7.5a.75.75 0 0 1 0-1.06l7.5-7.5a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd" />
                            </svg>
                        </span>
                        <span>Sebelumnya</span>
                    </button>
                    <div></div>
                    <button type="button" id="next--category-button_0_1" class="next-btn prev-next-btn flex gap-2 items-center justify-center uppercase w-40 text-white bg-primary hover:bg-primary-70 border border-primary focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-md text-xs pl-4 pr-2 py-2">
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


    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/flowbite.min.js') }}"></script>
    <script>
        let indicator_category_indices = [];

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

            let target_index;
            for (let i = 0; i < indicator_category_indices.length; i++) {
                if(indicator_category_indices[i] === targetRawID) {
                    target_index = i;
                    break;
                }
            }
            if(target_index == 0) {
                $(".prev-btn").removeClass("flex");
                $(".prev-btn").addClass("hidden");
                
                $(".next-btn").attr("id",`next--${indicator_category_indices[target_index+1]}`);
            } else if (target_index == indicator_category_indices.length-1) {
                $(".next-btn").removeClass("flex");
                $(".next-btn").addClass("hidden");

                $(".prev-btn").attr("id",`prev--${indicator_category_indices[target_index-1]}`);
                $(".prev-btn").removeClass("hidden");
                $(".prev-btn").addClass("flex");
            } else {
                
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

        $(document).ready(function () {
            let indicators      = @json($indicators);
            let respondent_id   = @json($respondent).id;
            
            let i = 0;
            $.each(indicators, function (indicatorKey, categories) { 
                let j = 0;
                $.each(categories, function (categoryKey, value) { 
                    indicator_category_indices.push(`category-button_${i}_${j}`); j++;
                }); i++;
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