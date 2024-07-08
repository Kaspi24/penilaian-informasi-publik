<div class="flex w-fit items-center justify-between gap-2">
    <button x-on:click="
            edit_id             = '{{ $row->id }}',
            edit_name           = '{{ $row->name }}',
            edit_username       = '{{ $row->username }}',
            edit_email          = '{{ $row->email }}',
            showEditAdminModal  = true
        " 
        type="button" class="w-24 py-1.5 pl-2 pr-3 rounded-md text-xs font-extrabold tracking-tight bg-blue-500 hover:bg-blue-600 text-white flex gap-1.5 items-center justify-center">
        <span>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4">
                <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-12.15 12.15a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32L19.513 8.2Z" />
            </svg>
        </span>
        <p>UBAH</p>
    </button>
    <button x-on:click="
            delete_id             = '{{ $row->id }}',
            delete_name           = '{{ $row->name }}',
            delete_username       = '{{ $row->username }}',
            delete_email          = '{{ $row->email }}',
            showDeleteAdminModal  = true
        "  
        type="button" class="w-24 py-1.5 pl-2 pr-3 rounded-md text-xs font-extrabold tracking-tight bg-red-500 hover:bg-red-600 text-white flex gap-1.5 items-center justify-center">
        <span>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4">
                <path fill-rule="evenodd" d="M16.5 4.478v.227a48.816 48.816 0 0 1 3.878.512.75.75 0 1 1-.256 1.478l-.209-.035-1.005 13.07a3 3 0 0 1-2.991 2.77H8.084a3 3 0 0 1-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 0 1-.256-1.478A48.567 48.567 0 0 1 7.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 0 1 3.369 0c1.603.051 2.815 1.387 2.815 2.951Zm-6.136-1.452a51.196 51.196 0 0 1 3.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 0 0-6 0v-.113c0-.794.609-1.428 1.364-1.452Zm-.355 5.945a.75.75 0 1 0-1.5.058l.347 9a.75.75 0 1 0 1.499-.058l-.346-9Zm5.48.058a.75.75 0 1 0-1.498-.058l-.347 9a.75.75 0 0 0 1.5.058l.345-9Z" clip-rule="evenodd" />
            </svg>
        </span>
        <p>HAPUS</p>
    </button>
</div>