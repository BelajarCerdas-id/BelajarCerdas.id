@include('components/sidebar_beranda', [
    'headerSideNav' => 'Management Unit',
    'linkBackButton' => route('EZ.managementLevel.view'),
    'backButton' => "<i class='fa-solid fa-chevron-left'></i>",
])

@if (Auth::user()->role === 'Administrator')
    <div class="home-beranda z-[-1] md:z-0 mt-[40px] md:mt-0">
        <div class="content-beranda">
            <!--- alert succes after success insert unit ----->
            <div id="alert-success-insert-unit"></div>

            <!--- alert succes after success update unit ----->
            <div id="alert-success-update-unit"></div>

            <!--- alert succes after success delete unit ----->
            <div id="alert-success-delete-unit"></div>

            <main class="bg-white shadow-lg border h-max rounded-lg">
                <!--- form action insert level --->
                <section class="p-6">
                    <form id="management-unit-form" data-level-id="{{ $id }}" autocomplete="OFF">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 w-full">
                            <div class="w-full">
                                <label class="text-sm">Nama Unit<sup class="text-red-500 pl-1">&#42;</sup></label>
                                <div class="flex relative max-w-lg mt-2 gap-2">
                                    <div class="flex flex-col w-full">
                                        <input type="text" id="unit_name" name="unit_name"
                                            class="w-full bg-white shadow-lg h-11 border-gray-200 border outline-none rounded-full text-xs px-2
                                                    focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue]"
                                            value="" placeholder="Masukkan Nama Unit">
                                        <span id="error-unit_name"
                                            class="text-red-500 text-xs mt-1 font-bold pt-[2px]"></span>
                                    </div>
                                    <button id="submit-button" type="button"
                                        class="bg-[#4189e0] hover:bg-blue-500 text-white font-bold py-2 px-6 rounded-full shadow-md transition-all h-max text-md">
                                        Tambah
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </section>

                <div class="border"></div>

                <!--- table unit list --->
                <section class="p-6">
                    <div id="container-management-unit" class="overflow-x-auto mt-4 pb-14"
                        data-level-id="{{ $id }}">
                        <table class="table" id="table-management-unit">
                            <thead class="thead-table-management-unit hidden">
                                <tr>
                                    <th class="th-table text-black opacity-70">No</th>
                                    <th class="th-table text-black opacity-70 py-2 px-3 text-center">Nama Unit</th>
                                    <th class="th-table text-black opacity-70 py-2 px-3 text-center">
                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="table-list-management-unit">
                                <!-- show data in ajax -->
                            </tbody>
                        </table>

                        <div class="pagination-container-management-unit flex justify-center my-4 sm:my-0"></div>

                        <div id="empty-message-management-unit" class="w-full h-96 hidden">
                            <span class="w-full h-full flex items-center justify-center">
                                Tidak ada daftar unit pada level ini.
                            </span>
                        </div>
                    </div>
                </section>

                <!---- modal edit unit  ---->
                <dialog id="my_modal_2" class="modal">
                    <div class="modal-box bg-white w-max">

                        <!-- untuk menghilangkan focus input type pada saat open modal  --->
                        <div tabindex="-1"></div> <!-- Tambahkan ini -->

                        <form id="edit-unit-form" autocomplete="OFF">
                            <span class="text-xl font-bold flex justify-center">Edit Unit</span>

                            <!--- Unit Name--->
                            <div class="flex flex-col mt-4 w-96">
                                <label class="mb-2 text-sm">Nama Level<sup class="text-red-500 pl-1">&#42;</sup></label>
                                <input type="text" id="unit_name_id" name="unit_name"
                                    class="w-full bg-white shadow-lg h-11 border-gray-200 border outline-none rounded-full text-xs px-2
                                    focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue]"
                                    value="" placeholder="Masukkan Nama Unit">
                                <span id="error-unit_name" class="text-red-500 font-bold text-xs pt-2"></span>
                            </div>

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

                <!---- modal delete unit  ---->
                <dialog id="my_modal_3" class="modal">
                    <div class="modal-box bg-white">
                        <h3 class="font-bold text-lg text-red-600">Konfirmasi Hapus</h3>
                        <p class="py-4">Unit yang sudah dihapus tidak dapat dikembalikan.
                            Apakah kamu
                            yakin
                            ingin menghapus unit ini?</p>
                        <div class="modal-action">
                            <span id="hapus-modal" class="btn" onclick="closeModal()">Batal</span>
                            <form id="delete-unit-form">
                                <button class="btn btn-error text-white">
                                    Ya, Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                    <form method="dialog" class="modal-backdrop">
                        <button>close</button>
                    </form>
                </dialog>
            </main>
        </div>
    </div>
@else
    <div class="flex flex-col min-h-screen items-center justify-center">
        <p>ALERT SEMENTARA</p>
        <p>You do not have access to this pages.</p>
    </div>
@endif


<script src="{{ asset('js/Features/english-zone/management-unit/management-unit.js') }}"></script>

<!--- COMPONENTS ---->
<script src="{{ asset('js/components/clear-error-on-input.js') }}"></script> <!--- clear error on input ---->

<!--- PUSHER LISTENER ---->
<script src="{{ asset('js/pusher-listener/english-zone/management-unit.js') }}"></script> <!--- pusher listener pada saat CRUD unit ---->
