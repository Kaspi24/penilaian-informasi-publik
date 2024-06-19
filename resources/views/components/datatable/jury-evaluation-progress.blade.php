@php
    $assigned       = \App\Models\RespondentScore::where('jury_id',$row->id)->get();
    $assigned_count = $assigned->count();
    $finished_count = $assigned->where('is_done_scoring', true)->count();
@endphp
@if ($assigned_count === 0)
    <p class="w-[21.7rem] text-center text-[8pt] font-extrabold p-1 px-2 rounded-md uppercase bg-gray-200 text-gray-800">
        BELUM ADA UNIT KERJA YANG DITUGASKAN UNTUK DINILAI
    </p>
@else
    <div class="flex justify-between items-center w-[21.7rem]">
        @php
            $percentage = round((($finished_count+9)/$assigned_count)*100, 0);
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
        <div class="w-[85%]">
            <div class="block w-full h-5">
                <div class="w-full border {{ $category_percentage_classlist[0] }} rounded-lg h-full box-border overflow-hidden">
                    <div class="h-full border {{ $category_percentage_classlist[1] }} rounded-lg box-border overflow-hidden" style="width: {{ $percentage }}%;"></div>
                </div>
            </div>
        </div>
        <p class="text-sm lg:text-base font-bold">
            {{ $finished_count.'/'.$assigned_count }}
        </p>
    </div>
@endif