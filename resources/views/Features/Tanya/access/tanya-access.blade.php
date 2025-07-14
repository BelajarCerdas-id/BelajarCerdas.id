@include('components/sidebar_beranda', ['headerSideNav' => 'Libur TANYA'])

@if (Auth::user()->role === 'Administrator')
    <div class="home-beranda z-[-1] md:z-0 mt-[80px] md:mt-0">
        <div class="content-beranda">
            <!--- alert success insert data ---->
            <div id="alert-success-insert-tanya-access"></div>
            <!--- alert success update data libur tanya ---->
            <div id="alert-success-update-tanya-access"></div>
            <!--- alert failed insert data ---->
            <div id="alert-failed-insert-tanya-access"></div>

            <!--- content libur tanya ---->
            <main>
                <section class="bg-white shadow-lg rounded-lg p-4 border border-gray-200 h-80">
                    <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-4">

                        <span class="text-xl font-bold">Libur TANYA</span>

                        <div class="flex justify-end mt-4 md:my-0">
                            <button onclick="uploadJadwalAccess(this)"
                                class="w-max bg-[#4189e0] hover:bg-blue-500 text-white font-bold p-3 rounded-lg shadow-md transition-all text-xs">
                                Buat Libur TANYA
                            </button>
                        </div>
                    </div>
                    <div class="overflow-x-auto h-full">
                        <table class="table w-full border-collapse border border-gray-300">
                            <thead id="thead-table-tanya-access" class="hidden font-bold">
                                <tr>
                                    <th class="!text-center border border-gray-300 text-black">
                                        Tanggl Mulai
                                    </th>
                                    <th class="!text-center border border-gray-300 text-black">
                                        Tanggal Akhir
                                    </th>
                                    <th class="!text-center border border-gray-300 text-black">
                                        Status Tanggal Libur
                                    </th>
                                    <th class="!text-center border border-gray-300 text-black">
                                        Status TANYA
                                    </th>
                                    <th class="!text-center border border-gray-300 text-black">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="tbody-table-tanya-access">
                                <!-- show data in ajax -->
                            </tbody>
                        </table>

                        <div class="pagination-container-tanya-access flex justify-center my-4 sm:my-0">
                        </div>

                        <div id="emptyMessage-riwayat-tanya-access" class="hidden shadow-lg rounded-md h-96">
                            <span class="w-full h-full flex items-center justify-center">
                                Belum ada libur tanya.
                            </span>
                        </div>
                    </div>

                    <!--- modal untuk create libur tanya --->
                    <dialog id="my_modal_1" class="modal">
                        <div class="modal-box bg-white w-[85%] md:w-max h-[620px]">
                            <form id="form-insert-tanya-access" class="form-insert">
                                <!---- tanggal mulai access ---->
                                <div class="w-full md:w-max relative flex flex-col gap-2 mt-4">
                                    <span class="text-sm font-bold text-gray-600">Tanggal Mulai</span>
                                    <input type="text" id="datepicker-insert-tanggal-mulai" name="tanggal_mulai"
                                        class="w-full md:w-[385px] bg-white shadow-lg h-12 border-gray-200 border-[2px] outline-none rounded-md px-2 cursor-pointer
                                        focus-within:border-[1px] focus-within:border-[dodgerblue] focus-within:shadow-[0_0_9px_0_dodgerblue]"
                                        value="" placeholder="dd-mm-yyyy">
                                    <i class="fa-regular fa-calendar absolute top-[60%] right-4"></i>
                                </div>
                                <span id="error-insert-tanggal_mulai" class="text-red-500 font-bold text-xs"></span>
                                <!---- tanggal akhir access ---->
                                <div class="w-full relative flex flex-col gap-2 mt-4">
                                    <span class="text-sm font-bold text-gray-600">Tanggal Akhir</span>
                                    <input type="text" id="datepicker-insert-tanggal-akhir" name="tanggal_akhir"
                                        class="w-full md:w-[385px] bg-white shadow-lg h-12 border-gray-200 border-[2px] outline-none rounded-md px-2 cursor-pointer focus-within:border-[1px]
                                        focus-within:border-[dodgerblue] focus-within:shadow-[0_0_9px_0_dodgerblue]"
                                        value="" placeholder="dd-mm-yyyy">
                                    <i class="fa-regular fa-calendar absolute top-[60%] right-4"></i>
                                </div>
                                <span id="error-insert-tanggal_akhir" class="text-red-500 font-bold text-xs"></span>
                                <!---- button submit ---->
                                <div class="flex justify-end mt-8">
                                    <button
                                        class="bg-[#4189e0] hover:bg-blue-500 text-white font-bold py-2 px-6 rounded-lg shadow-md transition-all">
                                        Simpan
                                    </button>
                                </div>
                            </form>
                        </div>
                        <form method="dialog" class="modal-backdrop">
                            <button>close</button>
                        </form>
                    </dialog>

                    <!--- modal untuk edit libur tanya --->
                    <dialog id="my_modal_2" class="modal">
                        <div class="modal-box bg-white w-[85%] md:w-max h-[620px]">
                            <form id="form-update-tanya-access" class="form-update">
                                <!---- tanggal mulai access ---->
                                <div class="w-full relative flex flex-col gap-2 mt-4">
                                    <span class="text-sm font-bold text-gray-600">Tanggal Mulai</span>
                                    <input type="text" id="datepicker-tanggal-mulai" name="tanggal_mulai"
                                        class="w-full md:w-[385px] bg-white shadow-lg h-12 border-gray-200 border-[2px] outline-none rounded-md px-2 cursor-pointer
                                        focus-within:border-[1px] focus-within:border-[dodgerblue] focus-within:shadow-[0_0_9px_0_dodgerblue]"
                                        value="" placeholder="dd-mm-yyyy">
                                    <i class="fa-regular fa-calendar absolute top-[60%] right-4"></i>
                                </div>
                                <span id="error-update-tanggal_mulai" class="text-red-500 font-bold text-xs"></span>
                                <!---- tanggal akhir access ---->
                                <div class="w-full relative flex flex-col gap-2 mt-4">
                                    <span class="text-sm font-bold text-gray-600">Tanggal Akhir</span>
                                    <input type="text" id="datepicker-tanggal-akhir" name="tanggal_akhir"
                                        class="w-full md:w-[385px] bg-white shadow-lg h-12 border-gray-200 border-[2px] outline-none rounded-md px-2 cursor-pointer focus-within:border-[1px]
                                        focus-within:border-[dodgerblue] focus-within:shadow-[0_0_9px_0_dodgerblue]"
                                        value="" placeholder="dd-mm-yyyy">
                                    <i class="fa-regular fa-calendar absolute top-[60%] right-4"></i>
                                </div>
                                <span id="error-update-tanggal_akhir" class="text-red-500 font-bold text-xs"></span>
                                <!---- button submit ---->
                                <div class="flex justify-end mt-8">
                                    <button
                                        class="bg-[#4189e0] hover:bg-blue-500 text-white font-bold py-2 px-6 rounded-lg shadow-md transition-all">
                                        Simpan
                                    </button>
                                </div>
                            </form>
                        </div>
                        <form method="dialog" class="modal-backdrop">
                            <button>close</button>
                        </form>
                    </dialog>

                    <!--- modal untuk show history upload libur TANYA --->
                    <dialog id="my_modal_3" class="modal">
                        <div class="modal-box bg-white w-[85%] md:w-full text-center">
                            <span class="text-2xl">History</span>
                            <div class="flex items-center justify-between mt-6">
                                <div class="flex items-center gap-2">
                                    <i class="fa-solid fa-circle-user text-5xl"></i>
                                    <div class="flex flex-col text-start">
                                        <span id="text-nama_lengkap"></span>
                                        <span id="text-status" class="text-sm"></span>
                                        <span id="text-updated_at" class="text-xs leading-6"></span>
                                    </div>
                                </div>
                                <div class="">
                                    <span class="text-[#4189e0] text-sm">Publisher</span>
                                </div>
                            </div>
                        </div>
                        <form method="dialog" class="modal-backdrop">
                            <button>close</button>
                        </form>
                    </dialog>

                </section>
            </main>
        </div>
    </div>
@else
    <p>You do not have access to this pages.</p>
@endif

<script src="{{ asset('js/Tanya/access/form-tanya-access.js') }}"></script> <!--- form crud tanya access (create, update) ---->
<script src="{{ asset('js/Tanya/access/open-modal-form-tanya-access.js') }}"></script> <!--- open modal & datepicker ---->

<!--- components ---->
<script src="{{ asset('js/components/btn-close-alert-success.js') }}"></script> <!--- clear error on input ---->

<!--- PUSHER LISTENER TANYA ---->
<script src="{{ asset('js/pusher-listener/tanya/tanya-access.js') }}"></script> <!--- untuk mendengarkan ketika administrator insert / update tanya access ---->
