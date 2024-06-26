<nav x-data="{ open: false }" class="bg-white border-b border-primarbg-primary-10">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 lg:h-[4.5rem]">
            <!-- Logo -->
            <div class="shrink-0 flex items-center  max-w-[70%] lg:max-w-[30%]">
                <a href="{{ route('dashboard') }}" class="flex w-fit gap-2 lg:gap-3 items-center">
                    <img src="{{ asset('logo/KEMENHUB.png') }}" class="h-10 w-auto" alt="">
                    <span class="text-primary font-extrabold mb-0.5 lg:mb-1 sm:hidden lg:inline-block">
                        <p class="text-[0.7rem] lg:text-xs xl:text-sm font-bold tracking-tight xl:tracking-normal uppercase break-words">Penilaian Anugerah<br>Keterbukaan Informasi Publik</p>
                        <p class="text-[0.65rem] lg:text-[0.7rem] tracking-tighter lg:tracking-tight text-primary-30">
                            Kementerian Perhubungan Republik Indonesia
                        </p>
                    </span>
                </a>
            </div>

            <div class="flex">
                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    <x-nav-link :href="route('questionnaire.index')" :active="request()->routeIs('questionnaire.index')">
                        {{ __('Penilaian') }}
                    </x-nav-link>
                    @if ( Auth::user()->role !== "RESPONDENT")
                        <x-nav-link :href="route('question.index')" :active="request()->routeIs('question.index')">
                            {{ __('Pertanyaan') }}
                        </x-nav-link>
                    @endif
                    @if ( Auth::user()->role === "ADMIN")
                        <x-nav-link :href="route('work-unit.index')" :active="request()->routeIs('work-unit.index')">
                            {{ __('Unit Kerja') }}
                        </x-nav-link>
                        <x-nav-link :href="route('user.index')" :active="request()->routeIs('user.index')">
                            {{ __('Pengguna') }}
                        </x-nav-link>
                        <x-nav-link :href="route('jury.index')" :active="request()->routeIs('jury.index')">
                            {{ __('Juri') }}
                        </x-nav-link>
                    @endif
                </div>

                <!-- Settings Dropdown -->
                <div class="hidden sm:flex sm:items-center sm:ms-6">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-primary-50 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                <div>{{ Auth::user()->name ? explode(" ",Auth::user()->name)[0] : Auth::user()->username }}</div>
    
                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>
    
                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>
    
                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
    
                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-primary-40 hover:text-primary-50 hover:bg-primary-10 focus:outline-none focus:bg-primary-10 focus:text-primary-50 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('questionnaire.index')" :active="request()->routeIs('questionnaire.index')">
                {{ __('Penilaian') }}
            </x-responsive-nav-link>
            @if ( Auth::user()->role !== "RESPONDENT")
                <x-responsive-nav-link :href="route('question.index')" :active="request()->routeIs('question.index')">
                    {{ __('Pertanyaan') }}
                </x-responsive-nav-link>
            @endif
            @if ( Auth::user()->role === "ADMIN")
                <x-responsive-nav-link :href="route('work-unit.index')" :active="request()->routeIs('work-unit.index')">
                    {{ __('Unit Kerja') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('user.index')" :active="request()->routeIs('user.index')">
                    {{ __('Pengguna') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('jury.index')" :active="request()->routeIs('jury.index')">
                    {{ __('Juri') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-primary-70">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-primary-50">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
