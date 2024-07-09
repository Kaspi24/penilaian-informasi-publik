@php
    $all_count      = $row->answers->count();
    $answered_count = $row->answers->whereNotNull('pivot.answer')->count();
    foreach ($row->answers as $question) {
        if($question->children->count()>0) {
            $all_answered = true;
            foreach ($question->children as $question_child) {
                $respondent_answer_children = \App\Models\RespondentAnswerChildren::where('question_children_id', $question_child->id)->where('respondent_id',$row->id)->first();
                if($respondent_answer_children->answer === null) {
                    $all_answered = false;
                    break;
                }
            }
            $all_answered && $answered_count++;
        }
    }
@endphp
<div class="w-full">
    <!-- PROFILE NOT COMPLETE -->
    @if (
            $row->name == null || 
            $row->email == null || 
            $row->phone == null || 
            $row->whatsapp == null ||
            $row["work_unit.head_name"] == null ||
            $row["work_unit.phone"] == null ||
            $row["work_unit.email"] == null
        )
        <p class="flex items-center gap-2 text-xs bg-warning-10 text-warning p-1 px-2 w-fit rounded-md border border-warning font-bold">
            <span>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5">
                    <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm8.706-1.442c1.146-.573 2.437.463 2.126 1.706l-.709 2.836.042-.02a.75.75 0 0 1 .67 1.34l-.04.022c-1.147.573-2.438-.463-2.127-1.706l.71-2.836-.042.02a.75.75 0 1 1-.671-1.34l.041-.022ZM12 9a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z" clip-rule="evenodd" />
                </svg>
            </span>
            <span>RESPONDEN BELUM MELENGKAPI PROFIL</span>
        </p>
    @else
        <!-- PROFILE NOT COMPLETE -->
        @if (!$row->score->is_done_filling)
            <div class="w-full p-4 rounded-lg bg-primary-10/40 text-primary-50 block lg:flex">
                <div class="w-full">
                    @php
                        $percentage = round(($answered_count/$all_count)*100, 0);
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
                    <div class="block mb-3 w-full h-8">
                        <div class="w-full border {{ $category_percentage_classlist[0] }} rounded-lg h-full box-border overflow-hidden">
                            <div class="h-full border {{ $category_percentage_classlist[1] }} rounded-lg box-border overflow-hidden" style="width: {{ $percentage }}%;"></div>
                        </div>
                    </div>
                    <p class="text-sm lg:text-base font-bold">
                        {{ $answered_count.' dari '.$all_count.' pertanyaan diisi' }} ({{ $percentage }}%)
                    </p>
                </div>
            </div>
        @else
            @if (!$row->score->is_done_scoring)
                <p class="flex items-center gap-2 text-xs bg-primary-10 text-primary p-1 px-2 w-fit rounded-md border border-primary font-bold">
                    <span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5">
                            <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm8.706-1.442c1.146-.573 2.437.463 2.126 1.706l-.709 2.836.042-.02a.75.75 0 0 1 .67 1.34l-.04.022c-1.147.573-2.438-.463-2.127-1.706l.71-2.836-.042.02a.75.75 0 1 1-.671-1.34l.041-.022ZM12 9a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z" clip-rule="evenodd" />
                        </svg>
                    </span>
                    <span>TANGGAPAN SUDAH DIKIRIMKAN DAN MENUNGGU DINILAI</span>
                </p>
            @else
                <div class="flex justify-start items-center gap-2 w-full p-4 lg:px-6 rounded-md bg-primary text-white ">
                    <span>
                        <p class="flex justify-start items-center gap-2 text-xs lg:text-sm text-warning lg:tracking-wide font-semibold mb-2">
                            Skor Keterbukaan Pelayanan Informasi Publik Unit Kerja {{ $row["work_unit.name"] }}
                        </p>
                        <span class="flex justify-start items-center gap-3">
                            <p class="text-xl lg:text-2xl w-[4.5rem] text-center font-extrabold bg-primary-80 text-white p-2 px-3 rounded-md">
                                {{ $row->score->total_score }}
                            </p>
                            <span>
                                <p class="text-xs lg:text-sm font-thin mb-0.5">
                                    Dengan Predikat :
                                </p>
                                @if ($row->score->total_score >= 90)
                                    <p class="text-xs lg:text-sm font-bold text-green-200 bg-green-600 p-0.5 px-1.5 rounded">
                                        INFORMATIF
                                    </p>
                                @elseif ($row->score->total_score >= 80)
                                    <p class="text-xs lg:text-sm font-bold text-lime-200 bg-lime-600 p-0.5 px-1.5 rounded">
                                        MENUJU INFORMATIF
                                    </p>
                                @elseif ($row->score->total_score >= 60)
                                    <p class="text-xs lg:text-sm font-bold text-yellow-50 bg-yellow-300 p-0.5 px-1.5 rounded">
                                        CUKUP INFORMATIF
                                    </p>
                                @elseif ($row->score->total_score >= 40)
                                    <p class="text-xs lg:text-sm font-bold text-orange-100 bg-orange-500 p-0.5 px-1.5 rounded">
                                        KURANG INFORMATIF
                                    </p>
                                @else
                                    <p class="text-xs lg:text-sm font-bold text-red-200 bg-red-600 p-0.5 px-1.5 rounded">
                                        TIDAK INFORMATIF
                                    </p>
                                @endif
                            </span>
                        </span>
                        <div class="flex justify-start mt-4">
                            <a href="{{ route('questionnaire.showScore', $row->id) }}" target="_blank" class="flex justify-start px-2 items-center gap-2 w-fit rounded-md bg-primary-40 hover:bg-primary-50 text-white hover:text-warning hover:shadow-inner group transition duration-300 ease-in-out">
                                <p class="text-xs font-bold">LIHAT PENILAIAN</p>
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25 21 12m0 0-3.75 3.75M21 12H3" />
                                    </svg>
                                </span>
                            </a>
                        </div>
                    </span>
                </div>
            @endif
        @endif
    @endif
</div>