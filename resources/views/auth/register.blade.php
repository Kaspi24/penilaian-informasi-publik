<x-guest-layout>
    <div class="w-full md:w-3/4 lg:w-2/3 xl:w-1/4 p-4 md:p-3 xl:p-2">
        <p class="text-center text-3xl text-primary font-bold  mb-4">Selamat Datang</p>
        {{-- <img src="{{ asset('logo/KEMENHUB.png') }}" alt="" class="w-3/4 h-auto mx-auto my-3"> --}}
        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div id="work_unit-field" class="relative mb-4 ">
                <label for="work_unit-name" class="block mb-2 text-base font-medium text-gray-800">Unit Kerja</label>
                <input class="border-2 border-primary-20 text-primary-80 text-sm rounded-md focus:ring-primary-70 focus:border-primary-70 block w-full p-2.5 select-option-custom-placeholder pr-8"
                    type="text" name="work_unit-name" id="work_unit-name" placeholder="Pilih Unit Kerja" autocomplete="off">
                <input class="hidden border-2 border-primary-20 text-primary-80 text-sm bg-primary-10 bg-opacity-25 rounded-md focus:ring-primary-70 focus:border-primary-70 w-full p-2.5 select-option-custom-placeholder pr-[4.25rem]"
                    type="text" name="new-work_unit-name" id="new-work_unit-name" placeholder="Masukkan Nama Unit Kerja Baru" autocomplete="off">
                <span id="work_unit-chevron" class="absolute top-12 right-3 font-bold text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-3.5 h-3.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                    </svg>
                </span>
                
                <span id="cancel-add-new-work_unit" class="hidden absolute top-11 right-3 font-bold text-sm text-gray-400 cursor-pointer italic">
                    Batalkan
                </span>

                <input type="text" name="work_unit" id="work_unit" class="hidden">
                
                <div id="work_unit-options" class="hidden absolute top-[76px] z-10 w-full max-h-40 overflow-y-scroll bg-white custom-scrollbar rounded-b-md shadow border">
                    @foreach ($work_units as $work_unit)
                        <div id="univ-option-{{ $loop->index }}" data-id="{{ $work_unit->id }}" class="work_unit-option-name py-1.5 px-3 text-sm text-gray-700 font-normal hover:bg-primary hover:text-white cursor-pointer">
                            {{ $work_unit->name }}
                        </div>
                    @endforeach
                </div>
                @error('work_unit')
                    <p class="block mt-1 text-xs font-medium text-danger">{{ $message }}</p>
                @enderror
            </div>

            <!-- EMAIL -->
            <div class="mb-4 ">
                <label for="email" class="block mb-2 text-base font-medium text-gray-900">Email</label>
                <input type="email" id="email" name="email"
                    class="border-2 border-primary-20 text-primary-50 text-sm rounded-md focus:ring-primary-70 focus:border-primary-70 block w-full p-2.5 custom-placeholder" 
                    value="{{ old('email') }}" required autocomplete="off">
                    @error('email')
                        <p class="block mt-1 text-xs font-medium text-danger">{{ $message }}</p>
                    @enderror
            </div>

            <!-- USERNAME -->
            <div class="mb-4 ">
                <label for="username" class="block mb-2 text-base font-medium text-gray-900">Username</label>
                <input type="text" id="username" name="username"
                    class="border-2 border-primary-20 text-primary-50 text-sm rounded-md focus:ring-primary-70 focus:border-primary-70 block w-full p-2.5 custom-placeholder" 
                    value="{{ old('username') }}" required autocomplete="off">
                    @error('username')
                        <p class="block mt-1 text-xs font-medium text-danger">{{ $message }}</p>
                    @enderror
            </div>

            <!-- PASSWORD -->
            <div class="mb-6 ">
                <label for="password" class="block mb-2 text-base font-medium text-gray-900">Password</label>
                <input type="password" id="password" name="password" required
                    class="border-2 border-primary-20 text-primary-50 text-sm rounded-md focus:ring-primary-70 focus:border-primary-70 block w-full p-2.5"  autocomplete="off">
                @error('password')
                    <p class="block mt-1 text-xs font-medium text-danger">{{ $message }}</p>
                @enderror
            </div>

            <!-- CONFIRM PASSWORD -->
            <div class="mb-6 ">
                <label for="password_confirmation" class="block mb-2 text-base font-medium text-gray-900">Konfirmasi Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required
                    class="border-2 border-primary-20 text-primary-50 text-sm rounded-md focus:ring-primary-70 focus:border-primary-70 block w-full p-2.5"  autocomplete="off">
                @error('password_confirmation')
                    <p class="block mt-1 text-xs font-medium text-danger">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-start   my-4">
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                    Sudah mendaftar?
                </a>
                
            </div>
            <div class="w-full ">
                <button type="submit" class="text-white bg-primary hover:bg-primary-70 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-md text-sm w-full px-5 py-2.5 text-center">
                    Daftar
                </button>
            </div>

        </form>
    </div>
<x-slot name="scripts">
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script>
        $(document).ready(function () {

            let unit_opt_pos;
            const work_units = {!! json_encode($work_units) !!};

            function isValidCharacterKey(key) {
                return /^[a-zA-Z0-9 !@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]*$/.test(key);
            }

            $("#work_unit-name").on("focusin", function (e) {
                e.preventDefault();
                unit_opt_pos = -1;
                $("#work_unit-options").removeClass("hidden");
                $("#work_unit-chevron").addClass("rotate-180");
            });

            $("#work_unit-name").keypress(function (e) { 
                e.key === "Enter" && e.preventDefault();
            });

            $("#work_unit-name").keydown(function (e) { 
                e.key === "Enter" && e.preventDefault();
            });

            $("#work_unit-name").keyup(function (e) { 
                if(e.key === "ArrowUp") { // UP ARROW KEY
                    unit_opt_pos > 0 && unit_opt_pos --;
                    if(unit_opt_pos >= 0) {
                        $("#univ-option-"+(unit_opt_pos+1)).removeClass("bg-primary text-white");
                        $("#univ-option-"+unit_opt_pos).addClass("bg-primary text-white");
                        var optionTopOffset  = $("#univ-option-"+unit_opt_pos)[0].offsetTop;
                        let currentScrollTop = $("#work_unit-options").scrollTop();
                        if(currentScrollTop > optionTopOffset && unit_opt_pos > 0) {
                            // currentScrollTop-=32;
                            $("#work_unit-options").scrollTop(optionTopOffset);
                        }
                    }
                } else if(e.key === "ArrowDown") { // DOWN ARROW KEY
                    options_count = $(".work_unit-option-name").length;
                    unit_opt_pos < options_count-1 && unit_opt_pos ++;
                    $("#univ-option-"+(unit_opt_pos-1)).removeClass("bg-primary text-white");
                    $("#univ-option-"+unit_opt_pos).addClass("bg-primary text-white");
                    var optionTopOffset  = $("#univ-option-"+unit_opt_pos)[0].offsetTop;
                    let currentScrollTop = $("#work_unit-options").scrollTop();
                    if((currentScrollTop+160) <= optionTopOffset && unit_opt_pos < options_count) {
                        let nextOptHeight = $("#univ-option-"+(unit_opt_pos)).height();
                        currentScrollTop+=nextOptHeight+12;
                        $("#work_unit-options").scrollTop(currentScrollTop);
                    }
                } 
                else if(e.key === "Enter") {
                    e.preventDefault();
                    let cleanStrName    = $("#univ-option-"+unit_opt_pos).text().replace(/\s+/g, ' ').trim();
                    let id              = $("#univ-option-"+unit_opt_pos).attr("data-id");
                    // $("#work_unit-name").attr('placeholder', cleanStrName);
                    $("#work_unit-name").blur();
                    $("#work_unit-name").val(cleanStrName);
                    $("#work_unit").val(id);
                    $("#work_unit-options").addClass("hidden");
                    $("#work_unit-chevron").removeClass("rotate-180");
                } else if (e.key === "ArrowLeft" || e.key === "ArrowRight") {
                    e.preventDefault();
                } else{
                    let keyword = $(this).val();

                    let options = work_units.filter(function(option) {
                        return option.name.toLowerCase().includes(keyword.toLowerCase());
                    })
                    if(isValidCharacterKey(keyword)) {
                        unit_opt_pos = -1;
                        $("#work_unit-options").html("");
                        if (options.length > 0) {
                            options.forEach((option, index) => {
                                let option_element = ""
                                    + "<div id=\"univ-option-"+ index +"\" data-id=\""+ option.id +"\" class=\"work_unit-option-name py-1.5 px-3 text-sm text-gray-700 font-normal hover:bg-primary hover:text-white cursor-pointer\">"
                                        + option.name
                                    + "</div>";
                                $("#work_unit-options").append(option_element);
                            });
                        } else {
                            let add_option_element = ""
                                + "<div id=\"add-new-work_unit-button\" class=\"py-1.5 px-3 flex justify-start items-center gap-2 text-sm text-gray-400 font-medium\">"
                                    // + "<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 24 24\" fill=\"currentColor\" class=\"w-5 h-5\">"
                                    //     + "<path fill-rule=\"evenodd\" d=\"M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25ZM12.75 9a.75.75 0 0 0-1.5 0v2.25H9a.75.75 0 0 0 0 1.5h2.25V15a.75.75 0 0 0 1.5 0v-2.25H15a.75.75 0 0 0 0-1.5h-2.25V9Z\" clip-rule=\"evenodd\" />"
                                    // + "</svg>"
                                    + "<span>Unit Kerja dengan kata kunci tersebut tidak tersedia.</span>"
                                + "</div>";
                            $("#work_unit-options").append(add_option_element);
                        }
                    }
                }
            });

            $("#work_unit-field").on("click", ".work_unit-option-name", function () {
                let cleanStrName    = $(this).text().replace(/\s+/g, ' ').trim();
                let id              = $(this).attr("data-id");
                // $("#work_unit-name").attr('placeholder', cleanStrName);
                $("#work_unit-name").val(cleanStrName);
                $("#work_unit").val(id);
                $("#work_unit-options").addClass("hidden");
                $("#work_unit-chevron").removeClass("rotate-180");
            });

            // $("#work_unit-field").on("click", "#add-new-work_unit-button", function () {
            //     $("#work_unit-name").addClass("hidden");
            //     $("#work_unit-options").addClass("hidden");
            //     $("#work_unit-chevron").addClass("hidden");
            //     $("#cancel-add-new-work_unit").removeClass("hidden");
            //     $("#new-work_unit-name").removeClass("hidden");
            //     $("#new-work_unit-name").addClass("block");
            //     $("#work_unit").val(-1);
            // });

            // $("#cancel-add-new-work_unit").click(function (e) { 
            //     e.preventDefault();
            //     $(this).addClass("hidden");
            //     $("#new-work_unit-name").val("");
            //     $("#new-work_unit-name").removeClass("block");
            //     $("#new-work_unit-name").addClass("hidden");
            //     $("#work_unit-name").removeClass("hidden");
            //     $("#work_unit").val("");
            //     $("#work_unit-name").val("");
            //     $("#work_unit-chevron").removeClass("hidden");
            //     $("#work_unit-chevron").removeClass("rotate-180");
            //     work_units.forEach((option, index) => {
            //         let option_element = ""
            //             + "<div id=\"univ-option-"+ index +"\" data-id=\""+ option.id +"\" class=\"work_unit-option-name py-1.5 px-3 text-sm text-gray-700 font-normal hover:bg-primary hover:text-white cursor-pointer\">"
            //                 + option.name
            //             + "</div>";
            //         $("#work_unit-options").append(option_element);
            //     });
            //     $("#add-new-work_unit-button").remove();
            // });
        });
    </script>
</x-slot>
</x-guest-layout>