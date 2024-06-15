<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kuesioner') }}
        </h2>
    </x-slot>

    @include('components.alert')

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!--  -->
            <div class="bg-white shadow-sm rounded-sm mb-6">
                <div class="p-4 text-primary-70">
                    <p class="text-xl font-bold">Kuesioner Penilaian Informasi Publik</p>
                </div>
            </div>

            <!--  -->
            <div class="bg-white shadow-sm rounded-sm mb-6">
                <div class="p-4 text-primary-70">
                    <p class="text-xl font-bold">Kuesioner Penilaian Informasi Publik</p>
                    <a href="{{ route('questionnaire.start') }}">{{ $answer_count > 0 ? 'Lanjutkan' : 'Mulai' }}  Isi Kuesioner</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
