@include('components/sidebar_beranda', ['headerSideNav' => 'School Subscription'])

@if (Auth::user()->role === 'Administrator')
    <div class="home-beranda z-[-1] md:z-0 mt-[80px] md:mt-0">
        <div class="content-beranda mt-[120px]">

            <!---- ALERT SUCCESS INSERT SCHOOL PARTNER ---->
            <div id="alert-success-insert-school-partner"></div>

            <span class="text-lg font-bold opacity-70">LIST SCHOOL PARTNER</span>

            <main class="bg-white shadow-lg border h-max rounded-lg mt-8">
                <section>
                    <div class="my-8 flex flex-col md:flex-row md:items-center md:justify-between gap-8 px-6">
                        <!--- search bar --->
                        <label class="input input-bordered flex items-center gap-2 w-66 md:w-max">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 opacity-70" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1111 3a7.5 7.5 0 015.65 13.65z" />
                            </svg>
                            <input id="search_school" type="search" class="grow text-sm"
                                placeholder="Cari sekolah..." />
                        </label>

                        <!--- button bulkupload school partner --->
                        <button type="button"
                            class="w-max bg-green-500 hover:bg-green-600 text-white font-bold h-10 px-6 rounded-lg shadow-md transition-all text-sm flex gap-2 items-center justify-center"
                            onclick="my_modal_1.showModal()">
                            <i class="fa-solid fa-circle-plus"></i>
                            Bulk Upload
                        </button>
                    </div>

                    <!--- form action bulk upload school partner --->
                    <form id="school-partner-form" enctype="multipart/form-data">

                        <!--- modal bulkupload soal pembahasan --->
                        <dialog id="my_modal_1" class="modal">
                            <div class="modal-box bg-white w-max !max-h-[600px]">
                                <span class="text-md flex justify-center font-bold opacity-70">Upload Soal</span>

                                <!--- show bulkUpload word errors --->
                                <div id="error-bulkUpload" class="my-4 max-h-42 overflow-y-auto"></div>

                                <div class="w-full mt-8">
                                    <div class="w-full h-auto">
                                        <div class="text-xs mt-1">
                                            <span>Maksimum ukuran file 10MB. <br> File dapat dalam format
                                                .xlsx.</span>
                                        </div>
                                        <div class="upload-icon">
                                            <div class="flex flex-col max-w-[260px]">
                                                <div id="excelPreview" class="max-w-[280px] cursor-pointer mt-4">
                                                    <div id="excelPreviewContainer-bulkUpload-excel"
                                                        class="bg-white shadow-lg rounded-lg w-max py-2 pr-4 border-[1px] border-gray-200 hidden">
                                                        <div class="flex items-center">
                                                            <img id="logo-bulkUpload-excel" class="w-[56px] h-max">
                                                            <div class="mt-2 leading-5">
                                                                <span id="textPreview-bulkUpload-excel"
                                                                    class="font-bold text-sm"></span><br>
                                                                <span id="textSize-bulkUpload-excel"
                                                                    class="text-xs"></span>
                                                                <span id="textCircle-bulkUpload-excel"
                                                                    class="relative top-[-2px] text-[5px]"></span>
                                                                <span id="textPages-bulkUpload-excel"
                                                                    class="text-xs"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div
                                        class="content-upload w-[385px] h-9 bg-[#4189e0] hover:bg-blue-500 text-white font-bold rounded-lg mt-6 mb-2">
                                        <label for="file-bulkUpload-excel"
                                            class="w-full h-full flex justify-center items-center cursor-pointer gap-2">
                                            <i class="fa-solid fa-arrow-up-from-bracket"></i>
                                            <span>Upload File</span>
                                        </label>
                                        <input id="file-bulkUpload-excel" name="bulkUpload-school-partner"
                                            class="hidden" onchange="previewExcel(event, 'bulkUpload-excel')"
                                            type="file" accept=".xlsx">
                                        <span id="error-bulkUpload-school-partner"
                                            class="text-red-500 font-bold text-xs pt-2"></span>
                                    </div>
                                </div>

                                <!-- Tombol Kirim -->
                                <div class="flex justify-end mt-8 z-[-1]">
                                    <button id="submit-button"
                                        class="bg-[#4189e0] hover:bg-blue-500 text-white font-bold py-2 px-6 rounded-lg shadow-md transition-all outline-none">
                                        Kirim
                                    </button>
                                </div>
                            </div>
                    </form>
                    <form method="dialog" class="modal-backdrop">
                        <button>close</button>
                    </form>
                    </dialog>
                </section>

                <!---- table list school partner subscription ---->
                <section class="relative px-6 pb-6">
                    <div class="overflow-x-auto">
                        <table class="table" id="table-school-partner-list">
                            <thead class="thead-table-school-partner-list hidden">
                                <tr>
                                    <th class="th-table text-black opacity-70">No</th>
                                    <th class="th-table text-black opacity-70">Nama Sekolah</th>
                                    <th class="th-table text-black opacity-70">NPSN</th>
                                    <th class="th-table text-black opacity-70">Nama Kepala Sekolah</th>
                                    <th class="th-table text-black opacity-70">NIK Kepala Sekolah</th>
                                    <th class="th-table text-black opacity-70">Detail</th>
                                </tr>
                            </thead>
                            <tbody id="tbody-school-partner-list">
                                {{-- show data in ajax --}}
                            </tbody>
                        </table>

                        <div class="pagination-container-school-partner-list flex justify-center my-4 sm:my-0"></div>

                        <div id="empty-message-school-partner-list" class="w-full h-96 hidden">
                            <span class="w-full h-full flex items-center justify-center">
                                Tidak ada sekolah yang terdaftar.
                            </span>
                        </div>
                    </div>
                </section>
            </main>
        </div>
    </div>
@else
    <div class="flex flex-col min-h-screen items-center justify-center">
        <p>ALERT SEMENTARA</p>
        <p>You do not have access to this pages.</p>
    </div>
@endif

<script src="{{ asset('js/school-partner/form-action-school-partner.js') }}"></script> <!--- form action school partner ---->

<!--- COMPONENTS ---->
<script src="{{ asset('js/components/preview/excel-upload-preview.js') }}"></script> <!--- show excel ---->

<!--- PUSHER LISTENER ---->
<script src="{{ asset('js/pusher-listener/school-partner/list-school-partner-subscription-listener.js') }}"></script> <!--- pusher listener list school partner subscription ---->
