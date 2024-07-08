<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Penilaian AKIP KEMENHUB RI') }}</title>
    <link rel="icon" href="{{ asset('logo/KEMENHUB64.png') }}" type="image/png" sizes="32x32 16x16">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>
        .bg-header-nav {
            background: rgb(8,8,22);
            background: linear-gradient(347deg, rgba(8,8,22,1) 6%, rgba(47,48,134,1) 81%, rgba(82,82,154,1) 92%, rgba(116,117,174,1) 100%);
        }
        .bg-section-1 {
            background: rgb(227,227,241);
            background: linear-gradient(90deg, rgba(227,227,241,1) 0%, rgba(231,231,255,1) 35%, rgba(255,255,255,1) 100%);
        }
        .draggable {
            padding: 20px;
            margin: 10px 0;
            border: 1px solid #ccc;
            cursor: move;
        }
        .drag-over {
            border: 2px dashed #000;
        }
    </style>
</head>
<body class="antialiased">
    <div class="bg-primary-10/25 pb-6 w-full" x-data="{
            showDeleteContentModal  : false,
            deleteContentTitle      : 'HAPUS INI YAA',
            deleteContentImgSrc     : '/design/EMPTY-CONTENT.png',
            deleteContentID         : 0,
        }">
        <div class="w-full max-w-7xl mx-auto p-4 lg:p-6 grid grid-cols-3 items-center bg-primary-10">
            <a href="/" class="w-fit text-primary hover:text-primary-70 p-2 text-sm font-bold flex items-center gap-2 rounded">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 9-3 3m0 0 3 3m-3-3h7.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                <span class="uppercase">
                    Kembali ke beranda
                </span>
            </a>
            <div class="text-center uppercase font-bold text-primary text-lg">KELOLA KONTEN BERANDA</div>
            <div></div>
        </div>

        <!-- ALERT -->
        <div class="max-w-7xl mt-4 mx-auto">
            @if (session('success'))
                <div id="successAlert" class="flex items-center p-4 bg-green-200 rounded-sm" role="alert">
                    <svg aria-hidden="true" class="flex-shrink-0 w-5 h-5 text-green-700" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="sr-only">Info</span>
                    <div class="ml-3 text-sm font-medium text-green-700">
                        <span class="font-semibold">Berhasil!</span> <span id="successMessage"> {{ session('success') }}</span>
                    </div>
                    <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-green-200 text-green-500 rounded-sm focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-300 inline-flex h-8 w-8" data-dismiss-target="#successAlert" aria-label="Close">
                        <span class="sr-only">Tutup</span>
                        <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
            @endif
            @if (session('warning'))
                <div class="max-w-7xl mt-4 mx-auto">
                    <div id="warningAlert" class="flex items-center p-4 bg-yellow-200 rounded-sm" role="alert">
                        <svg aria-hidden="true" class="flex-shrink-0 w-5 h-5 text-yellow-700" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="sr-only">Info</span>
                        <div class="ml-3 text-sm font-medium text-yellow-700">
                            <span class="font-semibold">Peringatan!</span> <span id="warningMessage"> {{ session('warning') }}</span>
                        </div>
                        <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-yellow-200 text-yellow-500 rounded-sm focus:ring-2 focus:ring-yellow-400 p-1.5 hover:bg-yellow-300 inline-flex h-8 w-8" data-dismiss-target="#warningAlert" aria-label="Close">
                            <span class="sr-only">Tutup</span>
                            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            @endif
            @if (session('danger'))
                <div class="max-w-7xl mt-4 mx-auto">
                    <div id="dangerAlert" class="flex items-center p-4 bg-red-200 rounded-sm" role="alert">
                        <svg aria-hidden="true" class="flex-shrink-0 w-5 h-5 text-red-700" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="sr-only">Info</span>
                        <div class="ml-3 text-sm font-medium text-red-700">
                            <span class="font-semibold">Gagal!</span> <span id="dangerMessage"> {{ session('danger') }}</span>
                        </div>
                        <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-red-200 text-red-500 rounded-sm focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-300 inline-flex h-8 w-8" data-dismiss-target="#dangerAlert" aria-label="Close">
                            <span class="sr-only">Tutup</span>
                            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            @endif
        </div>

        <!-- MAIN CONTAINER -->
        <main class="w-full max-w-7xl mx-auto" 
            x-data="{ 
                @foreach ($contents as $content)
                    content_{{ $content->id }}_is_visible : {{ $content->is_visible }},
                    content_{{ $content->id }}_name : '{{ $content->name }}',
                @endforeach
            }">
            <div class="w-full bg-white rounded-md shadow mt-4 p-4">
                <!-- HEADER -->
                <div class="w-full bg-white border-b-2 border-primary-10 pb-4 flex justify-between items-center">
                    <div>
                        <p>
                            Kelola konten yang akan ditampilkan di beranda
                        </p>
                        <p class="text-sm text-gray-500 font-medium">
                            Harap isi seluruh field yang dibutuhkan, <span class="font-extrabold text-primary">Drag dan Drop</span> Konten untuk mengatur urutan.
                        </p>
                    </div>
                    <button type="button" id="addContentButton" class="w-fit py-2 px-3 text-sm bg-primary text-white rounded flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4">
                            <path fill-rule="evenodd" d="M12 3.75a.75.75 0 0 1 .75.75v6.75h6.75a.75.75 0 0 1 0 1.5h-6.75v6.75a.75.75 0 0 1-1.5 0v-6.75H4.5a.75.75 0 0 1 0-1.5h6.75V4.5a.75.75 0 0 1 .75-.75Z" clip-rule="evenodd" />
                        </svg>
                        <span>
                            Tambah Konten
                        </span>
                    </button>
                </div>

                <!-- WRAPPER -->
                <div class="grid grid-cols-3 w-full ">
                    <!-- CONTENT NAVIGATION - DRAG AND DROP CONTAINER -->
                    <div class="bg-primary-10 p-6" id="sequence_container"> 
                        <!-- CONTENT SQUENCE -->
                        <div id="draggable-container">
                            @forelse ($contents as $content)
                                <div class="rounded-md draggable flex justify-between items-center" 
                                    x-bind:class="content_{{ $content->id }}_is_visible ? 'bg-white text-gray-600' : 'bg-gray-200 text-gray-400'"
                                    draggable="true" data-id="{{ $content->id }}">
                                    <div class="flex justify-start gap-2 items-center">
                                        <span class="">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                                                <path d="M3.5 2A1.5 1.5 0 0 0 2 3.5v2A1.5 1.5 0 0 0 3.5 7h2A1.5 1.5 0 0 0 7 5.5v-2A1.5 1.5 0 0 0 5.5 2h-2ZM3.5 9A1.5 1.5 0 0 0 2 10.5v2A1.5 1.5 0 0 0 3.5 14h2A1.5 1.5 0 0 0 7 12.5v-2A1.5 1.5 0 0 0 5.5 9h-2ZM9 3.5A1.5 1.5 0 0 1 10.5 2h2A1.5 1.5 0 0 1 14 3.5v2A1.5 1.5 0 0 1 12.5 7h-2A1.5 1.5 0 0 1 9 5.5v-2ZM10.5 9A1.5 1.5 0 0 0 9 10.5v2a1.5 1.5 0 0 0 1.5 1.5h2a1.5 1.5 0 0 0 1.5-1.5v-2A1.5 1.5 0 0 0 12.5 9h-2Z" />
                                            </svg>
                                        </span> 
                                        <p class="text-sm" x-text="content_{{ $content->id }}_name"></p>
                                    </div>
                                    <div class="flex justify-end gap-2 items-center">
                                        <!-- EDIT BUTTON -->
                                        <button type="button" id="edit_{{ $content->id }}_{{ $loop->index }}" class="edit-content-btn rounded w-7 h-7 text-white flex items-center justify-center"
                                            x-bind:class="content_{{ $content->id }}_is_visible ? 'bg-warning-60 hover:bg-warning-70' : 'bg-warning-40 hover:bg-warning-50'">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4">
                                                <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-12.15 12.15a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32L19.513 8.2Z" />
                                            </svg>
                                        </button>
                                        <!-- DELETE BUTTON -->
                                        <button type="button" id="delete_{{ $content->id }}" class="delete-content-btn rounded w-7 h-7 text-white flex items-center justify-center"
                                            x-on:click="
                                                showDeleteContentModal = true,
                                                deleteContentTitle = '{{ $content->name }}',
                                                deleteContentID = {{ $content->id }}
                                                @if (!($content->image == null || $content->image == ''))
                                                    ,
                                                    deleteContentImgSrc = '/storage/{{ $content->image }}'
                                                @endif
                                            "
                                            x-bind:class="content_{{ $content->id }}_is_visible ? 'bg-danger-60 hover:bg-danger-70' : 'bg-danger-40 hover:bg-danger-50'">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4">
                                                <path fill-rule="evenodd" d="M16.5 4.478v.227a48.816 48.816 0 0 1 3.878.512.75.75 0 1 1-.256 1.478l-.209-.035-1.005 13.07a3 3 0 0 1-2.991 2.77H8.084a3 3 0 0 1-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 0 1-.256-1.478A48.567 48.567 0 0 1 7.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 0 1 3.369 0c1.603.051 2.815 1.387 2.815 2.951Zm-6.136-1.452a51.196 51.196 0 0 1 3.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 0 0-6 0v-.113c0-.794.609-1.428 1.364-1.452Zm-.355 5.945a.75.75 0 1 0-1.5.058l.347 9a.75.75 0 1 0 1.499-.058l-.346-9Zm5.48.058a.75.75 0 1 0-1.498-.058l-.347 9a.75.75 0 0 0 1.5.058l.345-9Z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <div class="p-5 text-warning-70 border border-warning-70 bg-warning-50 uppercase font-bold text-sm text-center rounded-md">
                                    Belum ada konten untuk Tahun ini
                                </div>
                            @endforelse
                        </div>
                        @if ($contents->count() > 0)
                            @php
                                $sequence = "";
                                foreach ($contents as $index => $content) {
                                    $sequence .= strval($content->id);
                                    if ($index < $contents->count()-1) {
                                        $sequence .= ",";
                                    }
                                }
                            @endphp
                            <p id="old_sequence" class="hidden">{{ $sequence }}</p>
                            <form action="{{ route('landing-page.updateSequence') }}" method="POST" class="w-full">
                                @csrf @method('PUT')
                                <!-- SEQUENCE STRING DIVIDED BY COMMA -->
                                <input type="hidden" name="sequence" id="sequence" value="{{ $sequence }}">
                                <button type="submit" id="updateSequenceButton" class="w-full bg-primary-20 text-xs font-bold uppercase py-3 text-center text-white rounded" disabled>
                                    SIMPAN URUTAN
                                </button>
                            </form>
                        @endif
                    </div>

                    <!-- CONTENT EDITOR CONTAINER -->
                    <div class="bg-gray-50 border-x border-b border-primary-10 flex flex-col gap-6 p-6 w-full col-span-2" id="content-editor-container">
                        @if ($contents->count() > 0)
                            @if (session('last_edited'))
                                @php
                                    $last_edited = intval(session('last_edited'))-1;
                                @endphp

                                <form action="{{ route('landing-page.update') }}" method="POST" enctype="multipart/form-data">
                                    @csrf @method('PUT')
                                    <div class="bg-primary-10/30 rounded-md p-5" data-id="{{ $contents[$last_edited]->id }}">
                                        <div class="mb-4">
                                            <!-- ID -->
                                            <input type="hidden" name="id" value="{{ $contents[$last_edited]->id }}" class="text-xs p-0.5">
                                            <!-- VISIBILITY -->
                                            <div class="mb-4">
                                                <p class="block mb-0.5 font-medium text-sm text-gray-700">Visibilitas Konten</p>
                                                <small class="block w-full text-xs mb-2 font-bold text-primary-30">Menentukan apakah konten akan ditampilkan di beranda atau tidak</small>
                                                <input type="radio" name="is_visible" value="1" id="content_{{ $contents[$last_edited]->id }}_is_visible_1" 
                                                    x-model="content_{{ $contents[$last_edited]->id }}_is_visible" class="hidden">
                                                <input type="radio" name="is_visible" value="0" id="content_{{ $contents[$last_edited]->id }}_is_visible_0" 
                                                    x-model="content_{{ $contents[$last_edited]->id }}_is_visible" class="hidden">
                                                
                                                <button type="button" class="inline-flex items-center cursor-pointer" 
                                                    x-on:click="content_{{ $contents[$last_edited]->id }}_is_visible =! content_{{ $contents[$last_edited]->id }}_is_visible">
                                                    <div class="w-12 h-6 p-1 flex rounded-full"  
                                                        :class="content_{{ $contents[$last_edited]->id }}_is_visible ? 'justify-end bg-primary' : 'justify-start bg-gray-400'">
                                                        <div class="w-4 h-4 m-0 rounded-full bg-white"></div>
                                                    </div>
                                                    <span class="text-sm ms-2" x-text="content_{{ $contents[$last_edited]->id }}_is_visible ? 'Ditampilkan' : 'Tidak Ditampilkan'"></span>
                                                </button>
                                            </div>
            
                                            <!-- TITLE -->
                                            <div class="mb-4">
                                                <x-input-label for="content_{{ $contents[$last_edited]->id }}_name" :value="__('Judul Konten')"/>
                                                <small class="block w-full text-xs mb-2 font-bold text-primary-30">Akan menjadi menu header di Beranda</small>
                                                <div class="bg-primary-10 border border-primary-20 p-2 rounded mb-2">
                                                    <p class="uppercase mb-1 text-xs font-bold">Contoh :</p>
                                                    <img draggable="false" src="{{ asset('image/ContohHeader.png') }}" class="w-full h-auto" alt="">
                                                </div>
                                                <x-text-input id="content_{{ $contents[$last_edited]->id }}_name" name="name" type="text" 
                                                    class="mt-1 block w-full" x-model="content_{{ $contents[$last_edited]->id }}_name" autocomplete="off" placeholder="Judul Konten"/>
                                                <x-input-error class="mt-1" :messages="$errors->get('name')" />
                                            </div>
            
                                            <!-- IMAGE -->
                                            <div class="">
                                                <p class="block mb-0.5 font-medium text-sm text-gray-700">Gambar Konten</p>
                                                <small class="block w-full text-xs mb-2 font-bold text-primary-30">File Gambar (JPG/JPEG/PNG). Akan menjadi konten yang ditampilkan di beranda.</small>
                                                <input type="file" name="image" id="image" class="hidden"/>
                                                <small class="{{ $contents[$last_edited]->image ? 'hidden' : 'block' }} w-full text-xs mb-2 font-bold text-danger">Gambar Konten belum diisi.</small>
                                                <div class="relative block w-1/2 aspect-[1920/1080] lg:flex lg:items-center lg:justify-center rounded-sm overflow-hidden bg-white border-2 border-primary-30 focus:ring-primary-70 focus:border-primary-70 p-1 group">
                                                    <img draggable="false" id="image_preview" src="{{ $contents[$last_edited]->image ? asset('storage/'.$contents[$last_edited]->image) : asset('design/EMPTY-CONTENT.png') }}" class="object-contain rounded-sm" alt="GAMBAR KONTEN {{ $contents[$last_edited]->nth_sequence }}" >
                                                </div>
                                                <p id="imageerrorcontainer"
                                                    class="hidden w-1/2 items-center  gap-2 p-2.5 rounded-md border-2 text-xs my-2 bg-danger-10/40  border-danger-30 text-danger">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd"
                                                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                    <span id="imageerror" class="max-w-[calc(100%-1.375rem)] break-words">Lorem ipsum dolor sit amet consectetur adipisicing elit. Illo nostrum eaque a voluptatum ad repellat.</span>
                                                </p>
                                                <label class="block" for="image">
                                                    <p id="gantiimage"
                                                        class="{{ $contents[$last_edited]->image ? 'block' : 'hidden' }} px-1 mt-2 text-xs font-bold tracking-wider text-gray-400 cursor-pointer hover:text-gray-500 transition-all ease-in-out duration-200 w-fit">
                                                        Ganti Gambar Konten
                                                    </p>
                                                    <div id="pilihimage" class="{{ $contents[$last_edited]->image ? 'hidden' : 'flex' }} mt-2 w-1/2 justify-center items-center gap-2.5 p-2.5 border-2 border-primary-20 rounded-md cursor-pointer text-primary-50 hover:bg-primary-10/25 transition-all ease-in-out duration-200 hover:ring-primary-70 hover:border-primary-70">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                                        </svg>
                                                        <p class="font-bold text-sm">
                                                            Pilih File
                                                        </p>
                                                    </div>
                                                </label>
                                                @error('image')
                                                    <p class="block mt-1 text-xs font-medium text-danger">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <!-- SAVE CONTENT CHANGES BUTTON -->
                                        <button type="submit" id="updateContent_{{ $contents[$last_edited]->id }}" class="update-content w-full bg-primary hover:bg-primary-70 text-xs font-bold py-2 text-center tracking-wide text-white rounded-md">SIMPAN PERUBAHAN</button>
                                    </div>
                                </form>
                            @else
                                <div class="bg-primary-10/30 rounded-md p-5 aspect-[3/2] flex justify-center items-center uppercase text-gray-600 font-black">
                                    Klik tombol edit pada Konten di sebelah kiri Untuk mengubah
                                </div>
                            @endif
                        @elseif ($errors->hasBag('create_content'))
                            <form action="{{ route('landing-page.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="bg-primary-10/30 rounded-md p-5">
                                    <div class="mb-4">
                                        <!-- VISIBILITY -->
                                        <div class="mb-4" x-data="{new_content_is_visible:false}">
                                            <p class="block mb-0.5 font-medium text-sm text-gray-700">Visibilitas Konten</p>
                                            <small class="block w-full text-xs mb-2 font-bold text-primary-30">Menentukan apakah konten akan ditampilkan di beranda atau tidak</small>
                                            <input type="radio" name="is_visible" value="1" id="new_content_is_visible_1" 
                                                x-model="new_content_is_visible" class="hidden">
                                            <input type="radio" name="is_visible" value="0" id="new_content_is_visible_0" 
                                                x-model="new_content_is_visible" class="hidden">
                                            
                                            <button type="button" class="inline-flex items-center cursor-pointer" 
                                                x-on:click="new_content_is_visible =! new_content_is_visible">
                                                <div class="w-12 h-6 p-1 flex rounded-full"  
                                                    :class="new_content_is_visible ? 'justify-end bg-primary' : 'justify-start bg-gray-400'">
                                                    <div class="w-4 h-4 m-0 rounded-full bg-white"></div>
                                                </div>
                                                <span class="text-sm ms-2" x-text="new_content_is_visible ? 'Ditampilkan' : 'Tidak Ditampilkan'"></span>
                                            </button>
                                        </div>
        
                                        <!-- TITLE -->
                                        <div class="mb-4">
                                            <x-input-label for="new_content_name" :value="__('Judul Konten')"/>
                                            <small class="block w-full text-xs mb-2 font-bold text-primary-30">Akan menjadi menu header di Beranda</small>
                                            <div class="bg-primary-10 border border-primary-20 p-2 rounded mb-2">
                                                <p class="uppercase mb-1 text-xs font-bold">Contoh :</p>
                                                <img draggable="false" src="{{ asset('image/ContohHeader.png') }}" class="w-full h-auto" alt="">
                                            </div>
                                            <x-text-input id="new_content_name" name="name" type="text" 
                                                class="mt-1 block w-full" autocomplete="off" placeholder="Judul Konten" :value="old('name')"/>
                                            <x-input-error class="mt-1" :messages="$errors->create_content->first('name')" />
                                        </div>
        
                                        <!-- IMAGE -->
                                        <div class="">
                                            <p class="block mb-0.5 font-medium text-sm text-gray-700">Gambar Konten</p>
                                            <small class="block w-full text-xs mb-2 font-bold text-primary-30">File Gambar (JPG/JPEG/PNG). Akan menjadi konten yang ditampilkan di beranda.</small>
                                            <input type="file" name="image" id="image" class="hidden"/>
                                            <small class="block w-full text-xs mb-2 font-bold text-danger">Gambar Konten belum diisi.</small>
                                            <div class="relative block w-1/2 aspect-[1920/1080] lg:flex lg:items-center lg:justify-center rounded-sm overflow-hidden bg-white border-2 border-primary-30 focus:ring-primary-70 focus:border-primary-70 p-1 group">
                                                <img draggable="false" id="image_preview" src="/design/EMPTY-CONTENT.png" class="object-contain rounded-sm" alt="GAMBAR KONTEN BARU" >
                                            </div>
                                            <p id="imageerrorcontainer"
                                                class="hidden w-1/2 items-center  gap-2 p-2.5 rounded-md border-2 text-xs my-2 bg-danger-10/40  border-danger-30 text-danger">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                <span id="imageerror" class="max-w-[calc(100%-1.375rem)] break-words">Lorem ipsum dolor sit amet consectetur adipisicing elit. Illo nostrum eaque a voluptatum ad repellat.</span>
                                            </p>
                                            <label class="block" for="image">
                                                <p id="gantiimage"
                                                    class="hidden px-1 mt-2 text-xs font-bold tracking-wider text-gray-400 cursor-pointer hover:text-gray-500 transition-all ease-in-out duration-200 w-fit">
                                                    Ganti Gambar Konten
                                                </p>
                                                <div id="pilihimage" class="flex mt-2 w-1/2 justify-center items-center gap-2.5 p-2.5 border-2 border-primary-20 rounded-md cursor-pointer text-primary-50 hover:bg-primary-10/25 transition-all ease-in-out duration-200 hover:ring-primary-70 hover:border-primary-70">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                                    </svg>
                                                    <p class="font-bold text-sm">
                                                        Pilih File
                                                    </p>
                                                </div>
                                            </label>
                                            <x-input-error class="mt-1" :messages="$errors->create_content->first('image')" />
                                        </div>
                                    </div>
                                    <!-- SAVE CONTENT CHANGES BUTTON -->
                                    <button type="submit" id="storeNewContent" class="update-content w-full bg-primary hover:bg-primary-70 text-xs font-bold py-2 text-center tracking-wide text-white rounded-md">SIMPAN PERUBAHAN</button>
                                </div>
                            </form>
                        @else
                            {{-- @php
                                dump($errors);
                            @endphp --}}
                            <div class="bg-primary-10/30 rounded-md p-5 aspect-[3/2] flex justify-center items-center uppercase text-gray-600 font-black">
                                Belum ada konten untuk tahun ini
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
        </main>
        
        <!-- DELETE CONTENT MODAL -->
        <div class="fixed z-[2220] inset-0" x-cloak x-show="showDeleteContentModal">
            <div class="absolute z-[2222] inset-0 bg-black bg-opacity-30 flex justify-center items-center py-4">
                <div class="bg-white w-10/12 md:w-1/2 lg:2/5 xl:w-1/3 rounded-md p-6 flex flex-col justify-center items-center">
                    {{-- <div class="w-fit text-warning mb-6 xl:mb-8">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div> --}}
                    <div class="w-full text-center mb-4 ">
                        <p class="text-base xl:text-lg text-gray-800 font-extrabold ">
                            KONFIRMASI PENGAHPUSAN KONTEN
                        </p>
                    </div>
                    <div class="w-full mb-4">
                        <p class="text-sm xl:text-base text-gray-700 mb-4">
                            Anda yakin ingin menghapus konten berikut :
                        </p>
                        <p class="w-full bg-primary-10 text-xs xl:text-sm text-primary p-2 text-center" x-text="deleteContentTitle"></p>
                        <div class="w-full p-1 bg-white border border-primary-10 aspect-[1920/1080]">
                            <img x-bind:src="deleteContentImgSrc" class="object-contain" alt="">
                        </div>
                    </div>
                    <div class="w-full flex justify-center items-center gap-2 md:gap-4">
                        <button type="button" x-on:click="showDeleteContentModal = false" 
                            class="block w-40 text-primary bg-white border border-primary focus:ring-4 focus:outline-none focus:ring-blue-200 font-medium hover:font-semibold rounded-md text-xs xl:text-sm py-2.5 text-cente">
                            KEMBALI
                        </button>
                        <form action="{{ route('landing-page.delete') }}" method="POST" class="block w-40">
                            @csrf @method('DELETE')
                            <input type="hidden" name="id" x-model="deleteContentID">
                            <button type="submit"
                                class="w-full text-white bg-danger hover:bg-danger-70 border border-danger focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-md text-xs xl:text-sm py-2.5 text-center">
                                HAPUS
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @livewireScripts
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/flowbite.min.js') }}"></script>
    <script>
        const container = document.getElementById('draggable-container');
        let draggedItem = null;

        container.addEventListener('dragstart', (e) => {
            draggedItem = e.target;
            e.target.style.opacity = 0.5;
        });

        container.addEventListener('dragend', (e) => {
            e.target.style.opacity = "";
            draggedItem = null;
        });

        container.addEventListener('dragover', (e) => {
            e.preventDefault();
            const afterElement = getDragAfterElement(container, e.clientY);
            const draggable = document.querySelector('.dragging');
            if (afterElement == null) {
                container.appendChild(draggedItem);
            } else {
                container.insertBefore(draggedItem, afterElement);
            }
        });

        container.addEventListener('drop', (e) => {
            e.preventDefault();
            updateSequence();
        });

        function getDragAfterElement(container, y) {
            const draggableElements = [...container.querySelectorAll('.draggable:not(.dragging)')];

            return draggableElements.reduce((closest, child) => {
                const box = child.getBoundingClientRect();
                const offset = y - box.top - box.height / 2;
                if (offset < 0 && offset > closest.offset) {
                    return { offset: offset, element: child };
                } else {
                    return closest;
                }
            }, { offset: Number.NEGATIVE_INFINITY }).element;
        }

        function updateSequence() {
            const sequenceInput = document.getElementById('sequence');
            const draggableElements = [...container.querySelectorAll('.draggable')];
            const sequence = draggableElements.map(el => el.getAttribute('data-id')).join(',');
            const old_sequence = document.getElementById('old_sequence').textContent.replace(/\s+/g, '');
            const submitUpdateSequenceButton = document.getElementById('updateSequenceButton');
            sequenceInput.value = sequence;
            // console.log(old_sequence); // For debugging
            // console.log(sequence); // For debugging
            if(sequence != old_sequence) {
                if (submitUpdateSequenceButton.hasAttribute('disabled')) {
                    submitUpdateSequenceButton.removeAttribute('disabled');
                    $("#updateSequenceButton").removeClass("bg-primary-20");
                    $("#updateSequenceButton").addClass("bg-primary");
                }
            } else {
                if (!submitUpdateSequenceButton.hasAttribute('disabled')) {
                    submitUpdateSequenceButton.setAttribute('disabled', 'true');
                    $("#updateSequenceButton").removeClass("bg-primary");
                    $("#updateSequenceButton").addClass("bg-primary-20");
                }
            }
        }

        // Add dragstart event listener to each draggable element
        document.querySelectorAll('.draggable').forEach(item => {
            item.addEventListener('dragstart', (e) => {
                item.classList.add('dragging');
            });
            item.addEventListener('dragend', (e) => {
                item.classList.remove('dragging');
            });
        });

        $(document).ready(function () {
            // let content = @json($contents)[5];
            const reader = new FileReader();

            reader.onload = function(e) {
                $("#image_preview").attr("src", e.target.result);
            }

            $("#content-editor-container").on('change', "#image", function (e) {
                e.preventDefault();

                let image     = document.getElementById("image");
                let imageFile = document.getElementById("image").files[0];

                if(imageFile){
                    let fileExt = imageFile.type;
                    if (fileExt === "image/png" || fileExt === "image/jpeg" || fileExt === "image/jpg") {
                        if(imageFile.size < 10485768){
                            // Hide Error
                            $("#imageerrorcontainer").removeClass("flex");
                            $("#imageerrorcontainer").addClass("hidden");
                            // Hide Label Pilih
                            $("#pilihimage").removeClass("flex");
                            $("#pilihimage").addClass("hidden");
                            // Show Label Ganti
                            $("#gantiimage").removeClass("hidden");
                            $("#gantiimage").addClass("block");
                            // Show Preview
                            reader.readAsDataURL(imageFile);
                        }else{
                            // Clear Input
                            image.value = null
                            // Error message
                            $("#imageerror").text("Ukuran maksimal gambar 10MB");
                            // Show Error
                            $("#imageerrorcontainer").removeClass("hidden");
                            $("#imageerrorcontainer").addClass("flex");
                            
                            if(content.image != ""){
                                // Show Label Ganti
                                $("#gantiimage").removeClass("hidden");
                                $("#gantiimage").addClass("block");
                                // Hide Label Pilih
                                $("#pilihimage").removeClass("flex");
                                $("#pilihimage").addClass("hidden");
                            } else {
                                // Hide Label Ganti
                                $("#gantiimage").removeClass("block");
                                $("#gantiimage").addClass("hidden");
                                // Show Label Pilih
                                $("#pilihimage").removeClass("hidden");
                                $("#pilihimage").addClass("flex");
                            }
                        }
                    }else{
                        // Clear Input
                        image.value = null
                        // Error Message
                        $("#imageerror").text("Hanya dapat menerima file gambar (JPG/JPEG/PNG)");
                        // Show Error
                        $("#imageerrorcontainer").removeClass("hidden");
                        $("#imageerrorcontainer").addClass("flex");
                        
                        if(content.image != ""){
                            // Show Label Ganti
                            $("#gantiimage").removeClass("hidden");
                            $("#gantiimage").addClass("block");
                            // Hide Label Pilih
                            $("#pilihimage").removeClass("flex");
                            $("#pilihimage").addClass("hidden");
                        } else {
                            // Hide Label Ganti
                            $("#gantiimage").removeClass("block");
                            $("#gantiimage").addClass("hidden");
                            // Show Label Pilih
                            $("#pilihimage").removeClass("hidden");
                            $("#pilihimage").addClass("flex");
                        }
                    }
                }
            });

            $(".edit-content-btn").click(function (e) { 
                e.preventDefault();
                
                let contentID = $(this).attr("id").split('_')[1];
                let index     = $(this).attr("id").split('_')[2];

                let content = @json($contents)[index];

                let content_image = !(content.image == '' || content.image == null) ? '/storage/'+content.image : '/design/EMPTY-CONTENT.png'
                let block_element       = !(content.image == '' || content.image == null) ? 'block' : 'hidden'
                let hide_block_element  = !(content.image == '' || content.image == null) ? 'hidden' : 'block'
                let hide_flex_element   = !(content.image == '' || content.image == null) ? 'hidden' : 'flex'
                
                let htmlContent = `
                    <form action="{{ route('landing-page.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf @method('PUT')
                        <div class="bg-primary-10/30 rounded-md p-5" data-id="${contentID}">
                            <div class="mb-4">
                                <!-- ID -->
                                <input type="hidden" name="id" value="${contentID}" class="text-xs p-0.5">

                                <!-- SEQUENCE -->
                                {{-- <input type="text" name="nth_sequence[${contentID}]" value="{{ $content->nth_sequence }}" class="text-xs p-0.5"> --}}

                                <!-- VISIBILITY -->
                                <div class="mb-4">
                                    <p class="block mb-0.5 font-medium text-sm text-gray-700">Visibilitas Konten</p>
                                    <small class="block w-full text-xs mb-2 font-bold text-primary-30">Menentukan apakah konten akan ditampilkan di beranda atau tidak</small>
                                    <input type="radio" name="is_visible" value="1" id="content_${contentID}_is_visible_1" 
                                        x-model="content_${contentID}_is_visible" class="hidden">
                                    <input type="radio" name="is_visible" value="0" id="content_${contentID}_is_visible_0" 
                                        x-model="content_${contentID}_is_visible" class="hidden">
                                    
                                    <button type="button" class="inline-flex items-center cursor-pointer" 
                                        x-on:click="content_${contentID}_is_visible =! content_${contentID}_is_visible">
                                        <div class="w-12 h-6 p-1 flex rounded-full"  
                                            :class="content_${contentID}_is_visible ? 'justify-end bg-primary' : 'justify-start bg-gray-400'">
                                            <div class="w-4 h-4 m-0 rounded-full bg-white"></div>
                                        </div>
                                        <span class="text-sm ms-2" x-text="content_${contentID}_is_visible ? 'Ditampilkan' : 'Tidak Ditampilkan'"></span>
                                    </button>
                                </div>

                                <!-- TITLE -->
                                <div class="mb-4">
                                    <x-input-label for="content_${contentID}_name" :value="__('Judul Konten')"/>
                                    <small class="block w-full text-xs mb-2 font-bold text-primary-30">Akan menjadi menu header di Beranda</small>
                                    <div class="bg-primary-10 border border-primary-20 p-2 rounded mb-2">
                                        <p class="uppercase mb-1 text-xs font-bold">Contoh :</p>
                                        <img draggable="false" src="{{ asset('image/ContohHeader.png') }}" class="w-full h-auto" alt="">
                                    </div>
                                    <x-text-input id="content_${contentID}_name" name="name" type="text" 
                                        class="mt-1 block w-full" x-model="content_${contentID}_name" autocomplete="off" placeholder="Judul Konten"/>
                                    <x-input-error class="mt-1" :messages="$errors->get('name')" />
                                </div>

                                <!-- IMAGE -->
                                <div class="">
                                    <p class="block mb-0.5 font-medium text-sm text-gray-700">Gambar Konten</p>
                                    <small class="block w-full text-xs mb-2 font-bold text-primary-30">File Gambar (JPG/JPEG/PNG). Akan menjadi konten yang ditampilkan di beranda.</small>
                                    <input type="file" name="image" id="image" class="hidden"/>
                                    <small class="${hide_block_element} w-full text-xs mb-2 font-bold text-danger">Gambar Konten belum diisi.</small>
                                    <div class="relative block w-1/2 aspect-[1920/1080] lg:flex lg:items-center lg:justify-center rounded-sm overflow-hidden bg-white border-2 border-primary-30 focus:ring-primary-70 focus:border-primary-70 p-1 group">
                                        <img draggable="false" id="image_preview" src="${content_image}" class="object-contain rounded-sm" alt="GAMBAR KONTEN ${contentID}" >
                                    </div>
                                    <p id="imageerrorcontainer"
                                        class="hidden w-1/2 items-center  gap-2 p-2.5 rounded-md border-2 text-xs my-2 bg-danger-10/40  border-danger-30 text-danger">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        <span id="imageerror" class="max-w-[calc(100%-1.375rem)] break-words">Lorem ipsum dolor sit amet consectetur adipisicing elit. Illo nostrum eaque a voluptatum ad repellat.</span>
                                    </p>
                                    <label class="block" for="image">
                                        <p id="gantiimage"
                                            class="${block_element} px-1 mt-2 text-xs font-bold tracking-wider text-gray-400 cursor-pointer hover:text-gray-500 transition-all ease-in-out duration-200 w-fit">
                                            Ganti Gambar Konten
                                        </p>
                                        <div id="pilihimage" class="${hide_flex_element} mt-2 w-1/2 justify-center items-center gap-2.5 p-2.5 border-2 border-primary-20 rounded-md cursor-pointer text-primary-50 hover:bg-primary-10/25 transition-all ease-in-out duration-200 hover:ring-primary-70 hover:border-primary-70">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                            </svg>
                                            <p class="font-bold text-sm">
                                                Pilih File
                                            </p>
                                        </div>
                                    </label>
                                    @error('image')
                                        <p class="block mt-1 text-xs font-medium text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <!-- SAVE CONTENT CHANGES BUTTON -->
                            <button type="submit" id="updateContent_${contentID}" class="update-content w-full bg-primary hover:bg-primary-70 text-xs font-bold py-2 text-center tracking-wide text-white rounded-md">SIMPAN PERUBAHAN</button>
                        </div>
                    </form>
                `;

                $(`#content-editor-container`).html("");
                $(`#content-editor-container`).html(htmlContent);
            });

            $(".delete-content-btn").click(function (e) { 
                e.preventDefault();
                let contentID = $(this).attr("id").split('_')[1];
                console.log(contentID);
            });

            $("#addContentButton").click(function (e) { 
                e.preventDefault();
                let htmlContent = `
                    <form action="{{ route('landing-page.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="bg-primary-10/30 rounded-md p-5">
                            <div class="mb-4">
                                <!-- VISIBILITY -->
                                <div class="mb-4" x-data="{new_content_is_visible:false}">
                                    <p class="block mb-0.5 font-medium text-sm text-gray-700">Visibilitas Konten</p>
                                    <small class="block w-full text-xs mb-2 font-bold text-primary-30">Menentukan apakah konten akan ditampilkan di beranda atau tidak</small>
                                    <input type="radio" name="is_visible" value="1" id="new_content_is_visible_1" 
                                        x-model="new_content_is_visible" class="hidden">
                                    <input type="radio" name="is_visible" value="0" id="new_content_is_visible_0" 
                                        x-model="new_content_is_visible" class="hidden">
                                    
                                    <button type="button" class="inline-flex items-center cursor-pointer" 
                                        x-on:click="new_content_is_visible =! new_content_is_visible">
                                        <div class="w-12 h-6 p-1 flex rounded-full"  
                                            :class="new_content_is_visible ? 'justify-end bg-primary' : 'justify-start bg-gray-400'">
                                            <div class="w-4 h-4 m-0 rounded-full bg-white"></div>
                                        </div>
                                        <span class="text-sm ms-2" x-text="new_content_is_visible ? 'Ditampilkan' : 'Tidak Ditampilkan'"></span>
                                    </button>
                                </div>

                                <!-- TITLE -->
                                <div class="mb-4">
                                    <x-input-label for="new_content_name" :value="__('Judul Konten')"/>
                                    <small class="block w-full text-xs mb-2 font-bold text-primary-30">Akan menjadi menu header di Beranda</small>
                                    <div class="bg-primary-10 border border-primary-20 p-2 rounded mb-2">
                                        <p class="uppercase mb-1 text-xs font-bold">Contoh :</p>
                                        <img draggable="false" src="{{ asset('image/ContohHeader.png') }}" class="w-full h-auto" alt="">
                                    </div>
                                    <x-text-input id="new_content_name" name="name" type="text" 
                                        class="mt-1 block w-full" autocomplete="off" placeholder="Judul Konten"/>
                                    <x-input-error class="mt-1" :messages="$errors->get('name')" />
                                </div>

                                <!-- IMAGE -->
                                <div class="">
                                    <p class="block mb-0.5 font-medium text-sm text-gray-700">Gambar Konten</p>
                                    <small class="block w-full text-xs mb-2 font-bold text-primary-30">File Gambar (JPG/JPEG/PNG). Akan menjadi konten yang ditampilkan di beranda.</small>
                                    <input type="file" name="image" id="image" class="hidden"/>
                                    <small class="block w-full text-xs mb-2 font-bold text-danger">Gambar Konten belum diisi.</small>
                                    <div class="relative block w-1/2 aspect-[1920/1080] lg:flex lg:items-center lg:justify-center rounded-sm overflow-hidden bg-white border-2 border-primary-30 focus:ring-primary-70 focus:border-primary-70 p-1 group">
                                        <img draggable="false" id="image_preview" src="/design/EMPTY-CONTENT.png" class="object-contain rounded-sm" alt="GAMBAR KONTEN BARU" >
                                    </div>
                                    <p id="imageerrorcontainer"
                                        class="hidden w-1/2 items-center  gap-2 p-2.5 rounded-md border-2 text-xs my-2 bg-danger-10/40  border-danger-30 text-danger">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        <span id="imageerror" class="max-w-[calc(100%-1.375rem)] break-words">Lorem ipsum dolor sit amet consectetur adipisicing elit. Illo nostrum eaque a voluptatum ad repellat.</span>
                                    </p>
                                    <label class="block" for="image">
                                        <p id="gantiimage"
                                            class="hidden px-1 mt-2 text-xs font-bold tracking-wider text-gray-400 cursor-pointer hover:text-gray-500 transition-all ease-in-out duration-200 w-fit">
                                            Ganti Gambar Konten
                                        </p>
                                        <div id="pilihimage" class="flex mt-2 w-1/2 justify-center items-center gap-2.5 p-2.5 border-2 border-primary-20 rounded-md cursor-pointer text-primary-50 hover:bg-primary-10/25 transition-all ease-in-out duration-200 hover:ring-primary-70 hover:border-primary-70">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                            </svg>
                                            <p class="font-bold text-sm">
                                                Pilih File
                                            </p>
                                        </div>
                                    </label>
                                    @error('image')
                                        <p class="block mt-1 text-xs font-medium text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <!-- SAVE CONTENT CHANGES BUTTON -->
                            <button type="submit" id="storeNewContent" class="update-content w-full bg-primary hover:bg-primary-70 text-xs font-bold py-2 text-center tracking-wide text-white rounded-md">SIMPAN PERUBAHAN</button>
                        </div>
                    </form>
                `;

                $(`#content-editor-container`).html("");
                $(`#content-editor-container`).html(htmlContent);
            });
        });
    </script>
</body>
</html>

