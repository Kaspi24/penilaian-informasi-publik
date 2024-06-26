<x-app-layout x-data="{showEndExamPopUp : false}">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-primary leading-tight">
            {{ __('Penilaian') }}
        </h2>
    </x-slot>

    @include('components.alert')

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-sm">
                <div class="p-4 text-primary-70">
                    <livewire:questionnaire-table />
                </div>
            </div>
        </div>
    </div>

    <div id="setJuryModal" class="fixed hidden z-[2220] inset-0">
        <div class="absolute z-[2222] inset-0 bg-primary-90 bg-opacity-30 flex justify-center items-center py-4">
            <div class="bg-white w-10/12 md:w-1/2 lg:w-2/5 xl:w-1/3 rounded-md p-5 lg:p-8 flex flex-col justify-center items-center">
                <div class="w-full text-center mb-3">
                    <p class="text-lg lg:text-xl text-primary font-extrabold tracking-wide mb-4">
                        TETAPKAN JURI
                    </p>
                    <p class="font-semibold text-left text-primary-50 mb-2">
                        Anda akan menetapkan juri untuk Penilaian Tanggapan Penilaian Unit Kerja di bawah ini :
                    </p>
                    <div id="work_unit_name_container" class="text-primary text-left">
                    </div>
                </div>
                <div class="w-full mb-4">
                    <label for="jury_id" class="w-full text-primary-50 block m-0 my-2 font-semibold text-left">Silakan pilih juri yang akan ditugaskan</label>
                    <select name="jury_id" id="jury_id" class="border-2 border-primary-20 text-primary-50 text-sm rounded-md focus:ring-primary-70 focus:border-primary-70 block w-full p-2.5" @disabled($juries->count() <= 0)>
                        <option value="" selected hidden>Pilih Juri</option>
                        @forelse ($juries as $jury)
                            <option value="{{ $jury->id }}">{{ $jury->name }}</option>
                        @empty
                            <option value="" selected hidden>Belum ada juri yang didaftarkan.</option>
                        @endforelse
                    </select>
                </div>

                <div class="w-full flex justify-center items-center gap-4">
                    <button id="closeSetJuryModal" type="button" class="block w-[49%] text-primary bg-white border border-primary focus:ring-4 focus:outline-none focus:ring-blue-200 font-medium hover:font-semibold rounded-md text-sm py-2 text-center">
                        KEMBALI
                    </button>
                    <button id="set_jury_btn" type="button" class="block w-[49%] text-white bg-primary-20 font-medium rounded-md text-sm py-2 text-center" disabled>
                        TETAPKAN
                    </button>
                </div>
            </div>
        </div>
    </div>

    <x-slot name="scripts">
        
        <script>
            $(document).ready(function () {
                let workUnitToBeSet;

                window.addEventListener('setJuryForAll', event => {
                    workUnitToBeSet = event.detail[0].users;
                    $("#setJuryModal").removeClass("hidden");
                    $("#work_unit_name_container").html("");
                    if (event.detail[0].total > 5) {
                        let i = 0;
                        $.each(event.detail[0].work_units, function (id, name) { 
                            $("#work_unit_name_container").append(`
                                <p class="text-xs rounded-md p-1 px-2 w-full bg-primary text-warning mt-1.5 uppercase font-medium">${name}</p>
                            `);
                            i++;
                            if (i==5) {
                                return false;
                            }
                        });
                        $("#work_unit_name_container").append(`
                            <p class="rounded-md p-1 w-fit mt-1 font-bold">Dan ${parseInt(event.detail[0].total)-5} lainnya</p>
                        `);
                    } else {
                        $.each(event.detail[0].work_units, function (id, name) { 
                            $("#work_unit_name_container").append(`<p class="text-xs rounded-md p-1 px-2 w-full bg-primary text-warning mt-1.5 uppercase font-medium">${name}</p>`);
                        });
                    }
                });

                $("#jury_id").change(function (e) { 
                    e.preventDefault();
                    if ($(this).val() != "") {
                        $("#set_jury_btn").attr("disabled", false);
                        $("#set_jury_btn").removeClass("bg-primary-20");
                        $("#set_jury_btn").addClass("bg-primary hover:bg-primary-70 border border-primary focus:ring-4 focus:outline-none focus:ring-blue-300");
                    } else {
                        $("#set_jury_btn").attr("disabled", true);
                        $("#set_jury_btn").removeClass("bg-primary hover:bg-primary-70 border border-primary focus:ring-4 focus:outline-none focus:ring-blue-300");
                        $("#set_jury_btn").addClass("bg-primary-20");
                    }
                });

                $("#closeSetJuryModal").click(function (e) { 
                    e.preventDefault();
                    $("#setJuryModal").addClass("hidden");
                    $("#work_unit_name_container").html("");
                    $("#set_jury_btn").attr("disabled", true);
                    $("#set_jury_btn").removeClass("bg-primary hover:bg-primary-70 border border-primary focus:ring-4 focus:outline-none focus:ring-blue-300");
                    $("#set_jury_btn").addClass("bg-primary-20");
                    $("#jury_id").val("");
                });

                $("#set_jury_btn").click(function (e) { 
                    e.preventDefault();
                    let jury_id = $("#jury_id").val();
                    Livewire.dispatch('setJuryForAllWorkUnit', {id_arr: workUnitToBeSet, jury_id: jury_id})
                    $("#setJuryModal").addClass("hidden");
                    $("#work_unit_name_container").html("");
                    $("#set_jury_btn").attr("disabled", true);
                    $("#set_jury_btn").removeClass("bg-primary hover:bg-primary-70 border border-primary focus:ring-4 focus:outline-none focus:ring-blue-300");
                    $("#set_jury_btn").addClass("bg-primary-20");
                    $("#jury_id").val("");
                    Swal.fire({
                        title: 'Berhasil',
                        text: 'Juri berhasil ditetapkan.',
                        icon: 'success',
                        showConfirmButton : false,
                        timer: 2000
                    });
                });
            });
        </script>
    </x-slot>
</x-app-layout>
