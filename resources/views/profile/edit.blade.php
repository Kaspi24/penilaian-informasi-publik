<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-primary leading-tight">
            Profile
        </h2>
    </x-slot>

    @include('components.alert')

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow rounded-sm">
                <div class="max-w-xl mx-auto">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            @if (Auth::user()->role === 'RESPONDENT')
                <div class="p-4 sm:p-8 bg-white shadow rounded-sm">
                    <div class="max-w-xl mx-auto">
                        @include('profile.partials.update-work-unit-information-form')
                    </div>
                </div>
            @endif

            <div class="p-4 sm:p-8 bg-white shadow rounded-sm">
                <div class="max-w-xl mx-auto">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            @if (Auth::user()->role === 'RESPONDENT' || Auth::user()->role === 'JURY')
                <div class="p-4 sm:p-8 bg-white shadow rounded-sm">
                    <div class="max-w-xl mx-auto">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
