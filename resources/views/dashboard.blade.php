<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-primary leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    @include('components.alert')
    
    <!-- PAGE CONTENT WRAPPER -->
    <div class="py-6">
        <!-- GREETING FOR EVERYONE -->
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-sm">
                <div class="p-4 text-primary-70">
                    <p>
                        Selamat datang 
                        <span class="font-extrabold text-primary-50">
                            {{ Auth::user()->name ? Auth::user()->name : Auth::user()->username }}
                        </span>
                    </p>
                </div>
            </div>
        </div>

        @if (Auth::user()->role == "RESPONDENT")
            <!-- COUNT USER PROFILE COMPLETION PERCENTAGE -->
            @php
                $field_count        = 8;
                $empty_field_count  = 0;
                if (Auth::user()->name     == null) { $empty_field_count++; }
                if (Auth::user()->username == null) { $empty_field_count++; }
                if (Auth::user()->email    == null) { $empty_field_count++; }
                if (Auth::user()->phone    == null) { $empty_field_count++; }
                if (Auth::user()->whatsapp == null) { $empty_field_count++; }
                if (Auth::user()->work_unit->head_name == null) { $empty_field_count++; }
                if (Auth::user()->work_unit->phone     == null) { $empty_field_count++; }
                if (Auth::user()->work_unit->email     == null) { $empty_field_count++; }
                if ($empty_field_count == 0) {
                    $profile_completed = 100;
                } else {
                    $profile_completed = (($field_count-$empty_field_count)/$field_count)*100;
                }
            @endphp
            <!-- USER PROFILE NOT COMPLETED -->
            @if ($profile_completed != 100)
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-4">
                    <div class="bg-white overflow-hidden shadow-sm rounded-sm">
                        <div class="p-4 text-primary-70">
                            <div class="w-full bg-primary-10 text-primary-100 p-10 rounded-sm shadow-inner">
                                <p class="text-lg lg:text-xl mb-4">
                                    Saat ini Anda belum melengkapi <span class="font-bold text-primary">Profile Responden</span> dan <span class="font-bold text-primary">Informasi Unit Kerja</span> Anda.<br>
                                    Harap lengkapi terlebih dahulu pada halaman <span class="font-bold text-primary hover:underline"><a href="{{ route('profile.edit') }}">Profile</a></span> untuk bisa mulai mengisi kuesioner.
                                </p>
                                <p class="mb-2">Kelengkapan Profile Anda saat ini :</p>
                                <div class="flex justify-between items-center w-full">
                                    <div class="w-[calc(100%-8.75rem)] box-border bg-gray-50 border border-primary-20 rounded overflow-hidden">
                                        <div style="width: {{ $profile_completed }}%;" class="h-full bg-warning">
                                            <p class="leading-8 text-primary pl-2 font-bold">{{ $profile_completed }}%</p>
                                        </div>
                                    </div>
                                    <a href="{{ route('profile.edit') }}" class="flex items-center w-32 py-1.5 pl-4 pr-3 bg-primary text-white rounded-sm font-semibold hover:bg-primary-70 hover:text-warning ease-in-out duration-150">
                                        <span class="mr-2 text-sm">
                                            LENGKAPI
                                        </span>
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5">
                                            <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm4.28 10.28a.75.75 0 0 0 0-1.06l-3-3a.75.75 0 1 0-1.06 1.06l1.72 1.72H8.25a.75.75 0 0 0 0 1.5h5.69l-1.72 1.72a.75.75 0 1 0 1.06 1.06l3-3Z" clip-rule="evenodd" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-4">
                    <div class="bg-white overflow-hidden shadow-sm rounded-sm">
                        <div class="p-4 text-primary-70">
                            KERJAKAN SANA
                        </div>
                    </div>
                </div>
            @endif
        @else
            
        @endif
    </div>

</x-app-layout>
