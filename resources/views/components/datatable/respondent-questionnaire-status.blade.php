<div class="w-fit">
    @if ($row->score->is_done_filling)
        @if ($row->score->is_done_scoring)
            <p class="w-40 text-center text-xs font-extrabold p-1 px-2 rounded-md uppercase bg-emerald-200 text-emerald-800">
                SUDAH DINILAI
            </p>
        @else
            <p class="w-40 text-center text-xs font-extrabold p-1 px-2 rounded-md uppercase bg-red-200 text-red-800">
                MENUNGGU DINILAI
            </p>
        @endif
    @else
        <p class="w-40 text-center text-xs font-extrabold p-1 px-2 rounded-md uppercase bg-gray-200 text-gray-500">
            BELUM DIKIRIMKAN
        </p>
    @endif
</div>