@include('components/sidebar_beranda', [
    'headerSideNav' => 'Management Sesi',
    'linkBackButton' => route('EZ.managementLevel.view'),
    'backButton' => "<i class='fa-solid fa-chevron-left'></i>",
])

@if (Auth::user()->role === 'Administrator')
    <div class="home-beranda z-[-1] md:z-0 mt-[40px] md:mt-0">
        <div class="content-beranda">
            <!--- alert succes after success insert session ----->
            <div id="alert-success-insert-session"></div>

            <!--- alert succes after success update session ----->
            <div id="alert-success-update-session"></div>

            <!--- alert succes after success delete session ----->
            <div id="alert-success-delete-session"></div>

            <main class="bg-white shadow-lg border h-max rounded-lg">
                <!--- form action insert session --->
                <section class="p-6">
                    <form id="management-session-form" enctype="multipart/form-data">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 w-full">
                            <div class="w-full">
                                <!---- Level Id ---->
                                <input type="text" id="level_id" name="level_id" value="{{ $levelId }}" class="hidden">

                                <!---- Session Name---->
                                <label class="text-sm">Sesi<sup class="text-red-500 pl-1">&#42;</sup></label>
                                <div class="flex relative max-w-lg mt-2 gap-2">
                                    <div class="flex flex-col w-full">
                                        <input type="text" id="session_name" name="session_name"
                                            class="w-full bg-white shadow-lg h-11 border-gray-200 border outline-none rounded-full text-xs px-2
                                                    focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue]"
                                            value="" placeholder="Masukkan Nama Sesi">
                                        <span id="error-session_name"
                                            class="text-red-500 text-xs mt-1 font-bold pt-[2px]"></span>
                                    </div>
                                    <button id="submit-button-insert" type="button"
                                        class="bg-[#4189e0] hover:bg-blue-500 text-white font-bold py-2 px-6 rounded-full shadow-md transition-all h-max text-md">
                                        Tambah
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </section>

                <div class="border mx-6"></div>

                <!--- table session list --->
                <section class="p-6">
                    <div id="container-management-session" class="overflow-x-auto pb-14" data-level-id="{{ $levelId }}">
                        <table class="table" id="table-management-session">
                            <thead class="thead-table-management-session hidden">
                                <tr>
                                    <th class="th-table text-black opacity-70">No</th>
                                    <th class="th-table text-black opacity-70">Level</th>
                                    <th class="th-table text-black opacity-70">Sesi</th>
                                    <th class="th-table text-black opacity-70">
                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="table-list-management-session">
                                <!-- show data in ajax -->
                            </tbody>
                        </table>

                        <div class="pagination-container-management-session flex justify-center my-4 sm:my-0"></div>

                        <div id="empty-message-management-session" class="w-full h-96 hidden">
                            <span class="w-full h-full flex items-center justify-center">
                                Tidak ada daftar sesi pada level ini.
                            </span>
                        </div>
                    </div>
                </section>

                <!---- modal edit session  ---->
                <dialog id="my_modal_1" class="modal">
                    <div class="modal-box bg-white w-max">

                        <!-- untuk menghilangkan focus input type pada saat open modal  --->
                        <div tabindex="-1"></div> <!-- Tambahkan ini -->

                        <form id="edit-session-form" autocomplete="OFF">
                            <span class="text-xl font-bold flex justify-center">Edit Sesi</span>

                            <!--- Session Name--->
                            <div class="flex flex-col mt-4 w-96">
                                <label class="mb-2 text-sm">Sesi<sup class="text-red-500 pl-1">&#42;</sup></label>
                                <input type="text" id="session_name_id" name="session_name"
                                    class="w-full bg-white shadow-lg h-11 border-gray-200 border outline-none rounded-full text-xs px-2
                                        focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue]"
                                    value="" placeholder="Masukkan Nama Sesi">
                                <span id="error-session_name" class="text-red-500 text-xs mt-1 font-bold pt-[2px]"></span>
                            </div>

                            <div class="flex justify-end mt-8">
                                <button id="submit-button-update" type="button"
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

                <!---- modal delete session  ---->
                <dialog id="my_modal_2" class="modal">
                    <div class="modal-box bg-white">
                        <h3 class="font-bold text-lg text-red-600">Konfirmasi Hapus</h3>
                        <p class="py-4">Sesi yang sudah dihapus tidak dapat dikembalikan.
                            Apakah kamu
                            yakin
                            ingin menghapus sesi ini?</p>
                        <div class="modal-action">
                            <span id="hapus-modal" class="btn" onclick="closeModal()">Batal</span>
                            <form id="delete-session-form">
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

<script src="{{ asset('js/Features/english-zone/management-session/management-session.js') }}"></script>

<!--- COMPONENTS ---->
<script src="{{ asset('js/components/clear-error-on-input.js') }}"></script> <!--- clear error on input ---->

<!--- PUSHER LISTENER ---->
<script src="{{ asset('js/pusher-listener/english-zone/management-session.js') }}"></script> <!--- pusher listener pada saat CRUD sesi ---->