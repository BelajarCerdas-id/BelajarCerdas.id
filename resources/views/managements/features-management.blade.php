@include('components/sidebar_beranda', ['headerSideNav' => 'Features Management'])

@if (Auth::user()->role === 'Administrator')
    <div class="home-beranda z-[-1] md:z-0 mt-[80px] md:mt-0">
        <div class="content-beranda">

            <!-- ALERT SUCCESS INSERT & UPDATE FEATURE -->
            <div id="alert-success-insert-data-fitur"></div>
            <div id="alert-success-update-data-fitur"></div>

            <main class="bg-white shadow-lg border h-max rounded-lg">
                <section>
                    <!-- FORM INSERT OFFICE ACCOUNTS -->
                    <form id="form-features-list" autocomplete="OFF">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 p-6 w-full">
                            <div class="w-full">
                                <label class="text-sm">Nama Fitur<sup class="text-red-500 pl-1">&#42;</sup></label>
                                <div class="flex relative max-w-lg mt-2">
                                    <div class="flex gap-2 w-full">
                                        <input type="text" id="nama_fitur-insert" name="nama_fitur-insert"
                                            class="w-full bg-white shadow-lg h-11 border-gray-200 border-[2px] outline-none rounded-full text-xs px-2
                                            focus:border-[1px] focus:border-[dodgerblue] focus:shadow-[0_0_9px_0_dodgerblue]"
                                            value="{{ @old('nama_fitur') }}" placeholder="Masukkan Nama Fitur">
                                        <button
                                            class="bg-[#4189e0] hover:bg-blue-500 text-white font-bold py-2 px-6 rounded-full shadow-md transition-all h-max text-md">
                                            Tambah
                                        </button>
                                    </div>
                                </div>
                                <span id="error-nama_fitur-insert" class="text-red-500 font-bold text-xs pt-2"></span>
                            </div>
                        </div>
                    </form>
                </section>

                <div class="border-b-2 border-gray-200 my-4"></div>

                <section class="relative p-6">
                    <span class="text-lg font-bold opacity-70">FEATURES LIST</span>

                    <!--- table daftar office users --->
                    <div class="overflow-x-auto mt-4">
                        <table class="table" id="table-features-list">
                            <thead class="thead-table-features-list">
                                <tr>
                                    <th class="th-table text-black opacity-70">No</th>
                                    <th class="th-table text-black opacity-70">Nama Fitur</th>
                                    <th class="th-table text-black opacity-70">Edit</th>
                                </tr>
                            </thead>
                            <tbody id="tbody-features-list">
                                {{-- show data in ajax --}}
                            </tbody>
                        </table>

                        <div class="pagination-container-features-list flex justify-center my-4 sm:my-0"></div>

                        <div id="empty-message-features-list" class="w-full h-96 hidden">
                            <span class="w-full h-full flex items-center justify-center">
                                Tidak ada fitur yang terdaftar.
                            </span>
                        </div>
                    </div>
                </section>

                <!-- Modal for displaying edit feature -->
                <dialog id="my_modal_1" class="modal">
                    <div class="modal-box bg-white w-max">
                        <form id="form-edit-feature-list" autocomplete="OFF">
                            <span class="text-xl font-bold flex justify-center">Edit Fitur</span>

                            <div class="mt-4 w-80">
                                <label class="text-sm">Nama Fitur</label>
                                <input type="text" id="nama_fitur-update" name="nama_fitur-update"
                                    class="w-full bg-white shadow-lg h-11 border-gray-200 border-[1px] outline-none rounded-full text-xs px-2 mt-2"
                                    value="" placeholder="Masukkan Nama Fitur">
                                <span id="error-nama_fitur-update" class="text-red-500 text-xs mt-1 font-bold"></span>
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
                        <button>Close</button>
                    </form>
                </dialog>
            </main>
        </div>
    </div>
@else
    <p>You do not have access to this pages.</p>
@endif

<script src="{{ asset('js/managements/features-management/form-action-features-management.js') }}"></script> <!--- form action features management ---->

<!--- COMPONENTS ---->
<script src="{{ asset('js/components/clear-error-on-input.js') }}"></script> <!--- clear error on input ---->

<!--- PUSHER LISTENER ---->
<script src="{{ asset('js/pusher-listener/managements/features-list-management-listener.js') }}"></script> <!--- pusher listener features list management ---->
