<x-guest-layout>
    <div class="w-full md:w-3/4 lg:w-2/3 xl:w-1/4 p-4 md:p-3 xl:p-2">
        <p class="text-center text-3xl text-primary font-bold  px-2">Selamat Datang</p>
        <img src="{{ asset('logo/KEMENHUB.png') }}" alt="" class="w-3/4 h-auto mx-auto my-3">
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- USERNAME -->
            <div class="mb-4 px-2">
                <label for="username" class="block mb-2 text-base font-medium text-gray-900">Username</label>
                <input type="text" id="username" name="username"
                    class="border-2 border-primary-20 text-primary-50 text-sm rounded-md focus:ring-primary-70 focus:border-primary-70 block w-full p-2.5 custom-placeholder" 
                    value="{{ old('username') }}" required autocomplete="off">
                    @error('username')
                        <p class="block mt-1 text-xs font-medium text-danger">{{ $message }}</p>
                    @enderror
            </div>

            <!-- PASSWORD -->
            <div class="mb-6 px-2">
                <label for="password" class="block mb-2 text-base font-medium text-gray-900">Password</label>
                <input type="password" id="password" name="password" required
                    class="border-2 border-primary-20 text-primary-50 text-sm rounded-md focus:ring-primary-70 focus:border-primary-70 block w-full p-2.5"  autocomplete="off">
                @error('password')
                    <p class="block mt-1 text-xs font-medium text-danger">{{ $message }}</p>
                @enderror
            </div>

            <div class="w-full px-2">
                <button type="submit" class="text-white bg-primary hover:bg-primary-70 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-md text-sm w-full px-5 py-2.5 text-center">
                    Masuk
                </button>
            </div>
        </form>
    </div>
</x-guest-layout>