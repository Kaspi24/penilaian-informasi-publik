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
    <x-slot name="scripts">
        <script>
            $(document).ready(function () {
                let user = @json(Auth::user());
                const reader = new FileReader();

                reader.onload = function(e) {
                    $("#image_preview").attr("src", e.target.result);
                    // document.getElementById('image_preview').src = e.target.result;
                }

                $("#profile_picture").change(function (e) {
                    e.preventDefault();

                    let profile_picture     = document.getElementById("profile_picture");
                    let profile_pictureFile = document.getElementById("profile_picture").files[0];

                    if(profile_pictureFile){
                        let fileExt = profile_pictureFile.type;
                        if (fileExt === "image/png" || fileExt === "image/jpeg" || fileExt === "image/jpg") {
                            if(profile_pictureFile.size < 1048576){
                                if($("#profile_pictureerrorcontainer").hasClass("flex")){
                                    $("#profile_pictureerrorcontainer").removeClass("flex");
                                    $("#profile_pictureerrorcontainer").addClass("hidden");
                                }
                                if($("#gantiprofile_picture").hasClass("hidden")){
                                    $("#gantiprofile_picture").removeClass("hidden");
                                    $("#gantiprofile_picture").addClass("block");
                                }
                                if($("#pilihprofile_picture").hasClass("flex")){
                                    $("#pilihprofile_picture").removeClass("flex");
                                    $("#pilihprofile_picture").addClass("hidden");
                                }
                                reader.readAsDataURL(profile_pictureFile);
                            }else{
                                if($("#profile_pictureerrorcontainer").hasClass("hidden")){
                                    $("#profile_pictureerrorcontainer").removeClass("hidden");
                                    $("#profile_pictureerrorcontainer").addClass("flex");
                                }
                                if(user.profile_picture != ""){
                                    if($("#gantiprofile_picture").hasClass("hidden")){
                                        $("#gantiprofile_picture").removeClass("hidden");
                                        $("#gantiprofile_picture").addClass("block");
                                    }
                                    if($("#pilihprofile_picture").hasClass("flex")){
                                        $("#pilihprofile_picture").removeClass("flex");
                                        $("#pilihprofile_picture").addClass("hidden");
                                    }
                                } else {
                                    if($("#gantiprofile_picture").hasClass("block")){
                                        $("#gantiprofile_picture").removeClass("block");
                                        $("#gantiprofile_picture").addClass("hidden");
                                    }
                                    if($("#pilihprofile_picture").hasClass("hidden")){
                                        $("#pilihprofile_picture").removeClass("hidden");
                                        $("#pilihprofile_picture").addClass("flex");
                                    }
                                }
                                $("#profile_pictureerror").text("Ukuran maksimal gambar 1MB");
                                profile_picture.value = null
                            }
                        }else{
                            if($("#profile_pictureerrorcontainer").hasClass("hidden")){
                                $("#profile_pictureerrorcontainer").removeClass("hidden");
                                $("#profile_pictureerrorcontainer").addClass("flex");
                            }
                            if(user.profile_picture != ""){
                                if($("#gantiprofile_picture").hasClass("hidden")){
                                    $("#gantiprofile_picture").removeClass("hidden");
                                    $("#gantiprofile_picture").addClass("block");
                                }
                                if($("#pilihprofile_picture").hasClass("flex")){
                                    $("#pilihprofile_picture").removeClass("flex");
                                    $("#pilihprofile_picture").addClass("hidden");
                                }
                            } else {
                                if($("#gantiprofile_picture").hasClass("block")){
                                    $("#gantiprofile_picture").removeClass("block");
                                    $("#gantiprofile_picture").addClass("hidden");
                                }
                                if($("#pilihprofile_picture").hasClass("hidden")){
                                    $("#pilihprofile_picture").removeClass("hidden");
                                    $("#pilihprofile_picture").addClass("flex");
                                }
                            }
                            $("#profile_pictureerror").text("Hanya dapat menerima file gambar (JPG/JPEG/PNG)");
                            profile_picture.value = null
                        }
                    }
                });
            });
        </script>
    </x-slot>
</x-app-layout>
