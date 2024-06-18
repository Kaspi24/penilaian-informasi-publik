<div class="w-full text-sm text-primary">
    @if ($row->user->count() === 0)
        <p class="flex items-center gap-2 text-xs bg-warning-10 text-warning p-1 px-2 w-fit rounded-md border border-warning font-bold">
            <span>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5">
                    <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm8.706-1.442c1.146-.573 2.437.463 2.126 1.706l-.709 2.836.042-.02a.75.75 0 0 1 .67 1.34l-.04.022c-1.147.573-2.438-.463-2.127-1.706l.71-2.836-.042.02a.75.75 0 1 1-.671-1.34l.041-.022ZM12 9a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z" clip-rule="evenodd" />
                </svg>
            </span>
            <span>BELUM ADA RESPONDEN YANG MENDAFTAR DARI UNIT KERJA INI</span>
        </p>
    @else 
        <p class="font-extrabold tracking-wide uppercase mb-2">Detail Unit Kerja</p>
        <div class="p-2 border rounded bg-primary-10/25 mb-2 border-primary-20">
            <div class="flex justify-start items-center mb-1.5">
                <p class="w-32 font-bold">Kepala Unit Kerja</p>
                <p class="pr-2">:</p>
                <p class="">{{ $row->head_name }}</p>
            </div>
            <div class="flex justify-start items-center mb-1.5">
                <p class="w-32 font-bold">Telepon Unit Kerja</p>
                <p class="pr-2">:</p>
                <p class="">{{ $row->phone }}</p>
            </div>
            <div class="flex justify-start items-center">
                <p class="w-32 font-bold">Email Unit Kerja</p>
                <p class="pr-2">:</p>
                <p class="">{{ $row->email }}</p>
            </div>
        </div>
        <p class="font-extrabold tracking-wide uppercase mb-2">Detail Responden</p>
        <div class="p-2 border rounded bg-primary-10/25 mb-2 border-primary-20">
            <div class="flex justify-start items-center mb-1.5">
                <p class="w-32 font-bold">Nama</p>
                <p class="pr-2">:</p>
                <p class="">{{ $row->user[0]->name }}</p>
            </div>
            <div class="flex justify-start items-center mb-1.5">
                <p class="w-32 font-bold">Username</p>
                <p class="pr-2">:</p>
                <p class="">{{ $row->user[0]->username }}</p>
            </div>
            <div class="flex justify-start items-center mb-1.5">
                <p class="w-32 font-bold">Email</p>
                <p class="pr-2">:</p>
                <p class="">{{ $row->user[0]->email }}</p>
            </div>
            <div class="flex justify-start items-center mb-1.5">
                <p class="w-32 font-bold">Telepon</p>
                <p class="pr-2">:</p>
                <p class="">{{ $row->user[0]->phone }}</p>
            </div>
            <div class="flex justify-start items-center">
                <p class="w-32 font-bold">WhatsApp</p>
                <p class="pr-2">:</p>
                <p class="">{{ $row->user[0]->whatsapp }}</p>
            </div>
        </div>
    @endif
</div>