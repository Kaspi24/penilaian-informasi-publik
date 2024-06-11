<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kuesioner') }}
        </h2>
    </x-slot>

    @include('components.alert')

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6">
            <div class="bg-white shadow-sm rounded-sm">
                <div class="p-4 text-primary-70">
                    <p class="text-xl font-bold">Kuesioner Penilaian Informasi Publik</p>
                </div>
            </div>
        </div>
        
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6">
            <div class="bg-white shadow-sm rounded-sm">
                <div class="p-4 text-primary-70">
                    <p class="text-xl font-bold">Kuesioner Penilaian Informasi Publik</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
