@include('components/sidebar_beranda', ['headerSideNav' => 'Management Zoom'])

@if (Auth::user()->role === 'Administrator')
    <div class="home-beranda z-[-1] md:z-0 mt-[40px] md:mt-0">
        <div class="content-beranda">

            <!--- alert succes after success insert zoom ----->
            <div id="alert-success-insert-zoom"></div>

            <!--- alert succes after success update zoom ----->
            <div id="alert-success-update-zoom"></div>

            <!--- alert succes after success delete zoom ----->
            <div id="alert-success-delete-zoom"></div>

            <main class="bg-white shadow-lg border rounded-lg">
                <!--- form insert zoom ----->
                <section class="border-b px-6">
                    <form id="management-zoom-form" autocomplete="OFF" class="py-10">

                        <div class="grid lg:grid-cols-2 gap-6">
                            <!-- Mentor -->
                            <div>
                                <label class="text-sm font-bold opacity-70">
                                    Mentor
                                    <sup class="text-red-500">&#42;</sup>
                                </label>
                                <select id="mentor_id" name="mentor_id"
                                    class="select select-bordered w-full bg-white">
                                    <option value="" class="hidden">Pilih Mentor</option>

                                </select>
                                <span id="error-mentor_id" class="text-red-500 text-xs mt-1 font-bold pt-[2px]"></span>
                            </div>

                            <!-- Link Zoom -->
                            <div class="flex flex-col">
                                <label class="text-sm font-bold opacity-70">
                                    Link Zoom
                                    <sup class="text-red-500">&#42;</sup>
                                </label>
                                <input type="text" id="link_zoom" name="link_zoom" placeholder="Masukkan Link Zoom"
                                    class="bg-white border shadow-lg rounded-md h-12 px-2 text-sm outline-none">
                                <span id="error-link_zoom"
                                    class="text-red-500 text-xs mt-1 font-bold pt-[2px]"></span>
                            </div>

                            <!-- Meeting ID -->
                            <div class="flex flex-col">
                                <label class="text-sm font-bold opacity-70">
                                    Meeting ID
                                    <sup class="text-red-500">&#42;</sup>
                                </label>
                                <input type="text" id="meeting_id" name="meeting_id"
                                    placeholder="Masukkan Meeting ID"
                                    class="bg-white border shadow-lg rounded-md h-12 px-2 text-sm outline-none">
                                <span id="error-meeting_id"
                                    class="text-red-500 text-xs mt-1 font-bold pt-[2px]"></span>
                            </div>

                            <!-- passcode -->
                            <div class="flex flex-col">
                                <label class="text-sm font-bold opacity-70">
                                    Passcode
                                    <sup class="text-red-500">&#42;</sup>
                                </label>
                                <input type="text" id="zoom_passcode" name="zoom_passcode"
                                    placeholder="Masukkan Zoom Passcode"
                                    class="bg-white border shadow-lg rounded-md h-12 px-2 text-sm outline-none">
                                <span id="error-zoom_passcode"
                                    class="text-red-500 text-xs mt-1 font-bold pt-[2px]"></span>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end mt-8">
                            <button type="button" id="submit-button"
                                class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold text-sm">
                                Simpan
                            </button>
                        </div>

                    </form>
                </section>

                <!--- table zoom list --->
                <section class="py-6 m-4">
                    <span class="text-lg font-bold opacity-70">LIST ZOOM</span>

                    <!--- search bar --->
                    <label class="input input-bordered flex items-center gap-2 w-66 md:w-max mt-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 opacity-70" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1111 3a7.5 7.5 0 015.65 13.65z" />
                        </svg>
                        <input id="search_mentor" type="search" class="grow text-sm"
                            placeholder="Cari Mentor..." />
                    </label>

                    <div class="overflow-x-auto mt-4 pb-14">
                        <table class="table" id="table-management-zoom">
                            <thead class="thead-table-management-zoom hidden">
                                <tr>
                                    <th class="th-table text-black opacity-70">No</th>
                                    <th class="th-table text-black opacity-70">Mentor</th>
                                    <th class="th-table text-black opacity-70">Link Zoom</th>
                                    <th class="th-table text-black opacity-70">Meeting ID</th>
                                    <th class="th-table text-black opacity-70">Passcode</th>
                                    <th class="th-table text-black opacity-70">
                                        <i class="fa-solid fa-ellipsis-vertical cursor-pointer"></i>
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="tbody-table-management-zoom">
                                <!-- show data in ajax -->
                            </tbody>
                        </table>

                        <div class="pagination-container-management-zoom flex justify-center my-4 sm:my-0"></div>

                        <div id="empty-message-management-zoom" class="w-full h-96 hidden">
                            <span class="w-full h-full flex items-center justify-center">
                                Tidak ada daftar zoom.
                            </span>
                        </div>
                    </div>
                </section>

                <!---- modal edit zoom  ---->
                <dialog id="my_modal_1" class="modal">
                    <div class="modal-box bg-white w-max">

                        <!-- untuk menghilangkan focus input type pada saat open modal  --->
                        <div tabindex="-1"></div> <!-- Tambahkan ini -->

                        <form id="edit-zoom-form" autocomplete="OFF">
                            <span class="text-xl font-bold flex justify-center">Edit Zoom</span>

                            <!--- Link Zoom--->
                            <div class="flex flex-col mt-4 w-96">
                                <label class="text-sm font-bold opacity-70">
                                    Link Zoom
                                    <sup class="text-red-500">&#42;</sup>
                                </label>
                                <input type="text" id="link_zoom_id" name="link_zoom"
                                    placeholder="Masukkan Link Zoom"
                                    class="bg-white border shadow-lg rounded-md h-12 px-2 text-sm outline-none">
                                <span id="error-link_zoom"
                                    class="text-red-500 text-xs mt-1 font-bold pt-[2px]"></span>
                            </div>

                            <!-- Meeting ID -->
                            <div class="flex flex-col mt-4">
                                <label class="text-sm font-bold opacity-70">
                                    Meeting ID
                                    <sup class="text-red-500">&#42;</sup>
                                </label>
                                <input type="text" id="meeting_id_id" name="meeting_id"
                                    placeholder="Masukkan Meeting ID"
                                    class="bg-white border shadow-lg rounded-md h-12 px-2 text-sm outline-none">
                                <span id="error-meeting_id"
                                    class="text-red-500 text-xs mt-1 font-bold pt-[2px]"></span>
                            </div>

                            <!-- Passcode -->
                            <div class="flex flex-col mt-4">
                                <label class="text-sm font-bold opacity-70">
                                    Passcode
                                    <sup class="text-red-500">&#42;</sup>
                                </label>
                                <input type="text" id="zoom_passcode_id" name="zoom_passcode"
                                    placeholder="Masukkan Zoom Passcode"
                                    class="bg-white border shadow-lg rounded-md h-12 px-2 text-sm outline-none">
                                <span id="error-zoom_passcode"
                                    class="text-red-500 text-xs mt-1 font-bold pt-[2px]"></span>
                            </div>

                            <!-- Submit Button -->
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
                <dialog id="my_modal_2" class="modal">
                    <div class="modal-box bg-white">
                        <h3 class="font-bold text-lg text-red-600">Konfirmasi Hapus</h3>
                        <p class="py-4">Zoom yang sudah dihapus tidak dapat dikembalikan.
                            Apakah kamu
                            yakin
                            ingin menghapus zoom ini?</p>
                        <div class="modal-action">
                            <span id="hapus-modal" class="btn" onclick="closeModal()">Batal</span>
                            <form id="delete-zoom-form">
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

<script src="{{ asset('js/Features/english-zone/management-zoom/management-zoom.js') }}"></script> <!--- management zoom ---->

<!--- COMPONENTS ---->
<script src="{{ asset('js/components/clear-error-on-input.js') }}"></script> <!--- clear error on input ---->
<script src="{{ asset('js/components/english-zone/schedule-dropdown.js') }}"></script> <!--- schedule dropdown ---->

<!--- PUSHER LISTENER ---->
<script src="{{ asset('js/pusher-listener/english-zone/management-zoom.js') }}"></script> <!--- pusher listener pada saat CRUD zoom ---->



