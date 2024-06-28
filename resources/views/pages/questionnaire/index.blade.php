<x-app-layout x-data="{showEndExamPopUp : false}">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-primary leading-tight">
            {{ __('Penilaian') }}
        </h2>
    </x-slot>

    @include('components.alert')

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!--  -->
            <div class="bg-white shadow-sm rounded-sm mb-6">
                <div class="p-4 text-primary">
                    <p class="text-lg lg:text-xl font-bold">Penilaian Anugerah Keterbukaan Informasi Publik</p>
                </div>
            </div>
            
            @if (!$submission->is_done_filling)
                @if ($answered_count === 0)
                    <div class="w-full bg-white p-3">
                        <div class="w-full p-3 rounded-lg bg-primary-10/40 text-primary-50 block lg:flex">
                            <div class="w-full lg:w-2/3 p-6 flex gap-3 items-center">
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-20 lg:size-28">
                                        <path fill-rule="evenodd" d="M5.625 1.5c-1.036 0-1.875.84-1.875 1.875v17.25c0 1.035.84 1.875 1.875 1.875h12.75c1.035 0 1.875-.84 1.875-1.875V12.75A3.75 3.75 0 0 0 16.5 9h-1.875a1.875 1.875 0 0 1-1.875-1.875V5.25A3.75 3.75 0 0 0 9 1.5H5.625ZM7.5 15a.75.75 0 0 1 .75-.75h7.5a.75.75 0 0 1 0 1.5h-7.5A.75.75 0 0 1 7.5 15Zm.75 2.25a.75.75 0 0 0 0 1.5H12a.75.75 0 0 0 0-1.5H8.25Z" clip-rule="evenodd" />
                                        <path d="M12.971 1.816A5.23 5.23 0 0 1 14.25 5.25v1.875c0 .207.168.375.375.375H16.5a5.23 5.23 0 0 1 3.434 1.279 9.768 9.768 0 0 0-6.963-6.963Z" />
                                    </svg>
                                </span>
                                <p class="text-lg md:text-3xl lg:text-4xl font-semibold mb-1 lg:mb-1.5">Silakan mulai <br>mengisi penilaian</p>
                            </div>
                            <a href="{{ route('questionnaire.start') }}" class="flex justify-end items-center gap-2 w-full p-4 lg:px-6 rounded-md lg:w-1/3 bg-primary hover:bg-primary-70 text-white hover:text-warning hover:shadow-inner group transition duration-300 ease-in-out">
                                <p class="text-lg lg:text-2xl lg:tracking-wide font-bold">MULAI MENGISI PENILAIAN</p>
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-6 lg:size-8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25 21 12m0 0-3.75 3.75M21 12H3" />
                                    </svg>
                                </span>
                            </a>
                        </div>
                    </div>
                @else
                    <div class="w-full bg-white p-3">
                        <div class="w-full p-3 rounded-lg bg-primary-10/40 text-primary-50 block lg:flex">
                            <div class="w-full lg:w-2/3 p-6 flex flex-col gap-3 items-start">
                                <p class="text-lg md:text-3xl lg:text-4xl font-semibold mb-1 lg:mb-1.5">Progres pengisian <br> penilaian anda</p>
                                @php
                                    $percentage = round(($answered_count/$questions->count())*100, 0);
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
                                <div class="block lg:flex justify-between items-center w-full h-8">
                                    <div class="w-full border {{ $category_percentage_classlist[0] }} rounded-lg h-full box-border overflow-hidden">
                                        <div class="h-full border {{ $category_percentage_classlist[1] }} rounded-lg box-border overflow-hidden" style="width: {{ $percentage }}%;"></div>
                                    </div>
                                </div>
                                <p class="lg:text-lg font-bold">
                                    {{ $answered_count.' dari '.$questions->count().' pertanyaan diisi' }} ({{ $percentage }}%)
                                </p>
                            </div>
                            @if ($answered_count !== $questions->count())
                                <a href="{{ route('questionnaire.start') }}" class="flex justify-end items-center gap-2 w-full p-4 lg:px-6 rounded-md lg:w-1/3 bg-primary hover:bg-primary-70 text-white hover:text-warning hover:shadow-inner group transition duration-300 ease-in-out">
                                    <p class="text-lg lg:text-xl lg:tracking-wide font-bold">LANJUT MENGISI PENILAIAN</p>
                                    <span>
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-6 lg:size-7">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25 21 12m0 0-3.75 3.75M21 12H3" />
                                        </svg>
                                    </span>
                                </a>
                            @else
                                <div class="flex flex-col justify-center w-full p-2 rounded-md lg:w-1/3 bg-primary-40 text-primary-10">
                                    <p class="text-base lg:px-2 md:text-lg lg:text-xl font-semibold mb-1 lg:mb-3">Anda telah mengisi <br class="hidden lg:block"> semua pertanyaan penilaian.</p>
                                    <div class="flex justify-between items-center">
                                        <button x-on:click="showEndExamPopUp = true" type="button" id="submit_btn" class="flex gap-2 items-center justify-center uppercase w-[49%] text-white bg-emerald-600 hover:bg-emerald-700 font-bold rounded-md text-xs pr-5 pl-2.5 py-2.5">
                                            <span>
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4">
                                                    <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm.53 5.47a.75.75 0 0 0-1.06 0l-3 3a.75.75 0 1 0 1.06 1.06l1.72-1.72v5.69a.75.75 0 0 0 1.5 0v-5.69l1.72 1.72a.75.75 0 1 0 1.06-1.06l-3-3Z" clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                            <span>KIRIM JAWABAN</span>
                                        </button>
                                        <a href="{{ route('questionnaire.start') }}" class="flex gap-2 justify-end p-2.5 items-center w-[49%] rounded-md bg-primary hover:bg-primary-70 text-white hover:text-warning hover:shadow-inner group transition duration-300 ease-in-out">
                                            <p class="text-xs lg:tracking-wide font-bold">PERIKSA <span class="hidden md:inline">JAWABAN</span></p>
                                            <span>
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4 lg:size-5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25 21 12m0 0-3.75 3.75M21 12H3" />
                                                </svg>
                                            </span>
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            @else
                @if (!$submission->is_done_scoring)
                    <div class="w-full bg-white p-3">
                        <div class="w-full p-3 rounded-lg bg-primary-10/40 text-primary-50 block lg:flex">
                            <div class="w-full lg:w-2/3 p-6 flex gap-3 items-center">
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-20 lg:size-24">
                                        <path fill-rule="evenodd" d="M9 1.5H5.625c-1.036 0-1.875.84-1.875 1.875v17.25c0 1.035.84 1.875 1.875 1.875h12.75c1.035 0 1.875-.84 1.875-1.875V12.75A3.75 3.75 0 0 0 16.5 9h-1.875a1.875 1.875 0 0 1-1.875-1.875V5.25A3.75 3.75 0 0 0 9 1.5Zm6.61 10.936a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 14.47a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z" clip-rule="evenodd" />
                                        <path d="M12.971 1.816A5.23 5.23 0 0 1 14.25 5.25v1.875c0 .207.168.375.375.375H16.5a5.23 5.23 0 0 1 3.434 1.279 9.768 9.768 0 0 0-6.963-6.963Z" />
                                    </svg>
                                </span>
                                <p class="text-lg md:text-3xl lg:text-4xl font-semibold mb-1 lg:mb-1.5">Terima kasih. <br>Anda telah mengirim <br>tanggapan penilaian.</p>
                            </div>
                            <div class="flex justify-start items-center gap-2 w-full p-4 lg:px-6 rounded-md lg:w-1/3 bg-primary text-white ">
                                <span>
                                    <p class="flex justify-start items-center gap-2 text-sm text-warning lg:text-base lg:tracking-wide font-bold mb-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5">
                                            <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm8.706-1.442c1.146-.573 2.437.463 2.126 1.706l-.709 2.836.042-.02a.75.75 0 0 1 .67 1.34l-.04.022c-1.147.573-2.438-.463-2.127-1.706l.71-2.836-.042.02a.75.75 0 1 1-.671-1.34l.041-.022ZM12 9a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z" clip-rule="evenodd" />
                                        </svg>
                                        <span>
                                            PENILAIAN SEDANG BERLANGSUNG
                                        </span>
                                    </p>
                                    <p class="text-xs lg:text-sm font-thin text-justify">
                                        Tanggapan yang anda kirimkan sedang melalui proses penilaian. Setelah proses penilaian selesai, nilai yang anda peroleh akan ditampilkan di halaman ini.
                                    </p>
                                </span>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="w-full bg-white p-3">
                        <div class="w-full p-3 rounded-lg bg-primary-10/40 text-primary-50 block lg:flex">
                            <div class="w-full lg:w-1/2 p-6 flex gap-3 items-center">
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-20 lg:size-24">
                                        <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.005Z" clip-rule="evenodd" />
                                    </svg>
                                </span>
                                <p class="text-lg md:text-3xl lg:text-4xl font-semibold mb-1 lg:mb-1.5">Terima kasih. <br>Tanggapan anda <br>telah dievaluasi.</p>
                            </div>
                            <div class="flex justify-start items-center gap-2 w-full p-4 lg:px-6 rounded-md lg:w-1/2 bg-primary text-white ">
                                <div class="w-full">
                                    <p class="flex justify-start items-center gap-2 text-sm lg:text-base text-warning lg:tracking-wide font-semibold mb-3">
                                        Skor Keterbukaan Pelayanan Informasi Publik Unit Kerja Anda adalah
                                    </p>
                                    <span class="flex justify-start items-center gap-3">
                                        <p class="text-2xl lg:text-3xl xl:text-4xl w-24 text-center font-extrabold bg-primary-80 text-white p-3 rounded-md">
                                            {{ round($submission->total_score,1) }}
                                        </p>
                                        <span>
                                            <p class="text-sm lg:text-base font-thin mb-1">
                                                Dengan Predikat :
                                            </p>
                                            @if ($submission->total_score >= 90)
                                                <p class="lg:text-lg font-bold text-green-200 bg-green-600 p-1 px-2 rounded">
                                                    INFORMATIF
                                                </p>
                                            @elseif ($submission->total_score >= 80)
                                                <p class="text-sm lg:text-lg font-bold text-lime-200 bg-lime-600 p-1 px-2 rounded">
                                                    MENUJU INFORMATIF
                                                </p>
                                            @elseif ($submission->total_score >= 60)
                                                <p class="text-sm lg:text-lg font-bold text-yellow-50 bg-yellow-300 p-1 px-2 rounded">
                                                    CUKUP INFORMATIF
                                                </p>
                                            @elseif ($submission->total_score >= 40)
                                                <p class="text-sm lg:text-lg font-bold text-orange-100 bg-orange-500 p-1 px-2 rounded">
                                                    KURANG INFORMATIF
                                                </p>
                                            @else
                                                <p class="lg:text-lg font-bold text-red-200 bg-red-600 p-1 px-2 rounded">
                                                    TIDAK INFORMATIF
                                                </p>
                                            @endif
                                        </span>
                                    </span>
                                    <div class="flex justify-end mt-4">
                                        <a href="{{ route('questionnaire.showScore', Auth::user()->id) }}" class="flex justify-end px-2 items-center gap-2 w-fit rounded-md bg-primary-40 hover:bg-primary-50 text-white hover:text-warning hover:shadow-inner group transition duration-300 ease-in-out">
                                            <p class=" font-bold">LIHAT PENILAIAN</p>
                                            <span>
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-6 lg:size-7">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25 21 12m0 0-3.75 3.75M21 12H3" />
                                                </svg>
                                            </span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>

    @if (!$submission->is_done_filling && $answered_count === $questions->count())
        <x-slot name="modals">
            <!-- SUBMIT RESPONSE CONFIRMATION MODAL -->
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
                            <a href="{{ route('questionnaire.start') }}" class="block w-[32%] text-white bg-emerald-600 hover:bg-emerald-700 border border-emerald-600 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-md text-sm py-2 text-center">
                                PERIKSA
                            </a>
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
        </x-slot>
    @endif
</x-app-layout>
