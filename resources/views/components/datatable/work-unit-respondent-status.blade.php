<div class="w-fit">
    @if ($row->user->count() === 0)
        <p class="w-fit text-xs font-extrabold p-1 px-2 rounded-md uppercase bg-red-200 text-red-800">
            BELUM MENDAFTAR
        </p>
    @else
        <p class="w-fit text-xs font-extrabold p-1 px-2 rounded-md uppercase bg-emerald-200 text-emerald-800">
            SUDAH MENDAFTAR
        </p>
    @endif
</div>