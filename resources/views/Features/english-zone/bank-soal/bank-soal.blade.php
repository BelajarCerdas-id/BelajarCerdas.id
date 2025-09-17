@include('components/sidebar_beranda', ['headerSideNav' => 'Bank Soal'])

@if (Auth::user()->role === 'Administrator')
    <div class="home-beranda z-[-1] md:z-0 mt-[40px] md:mt-0">
        <div class="content-beranda">
            <!--- alert succes after success insert questions ----->
            <div id="alert-success-insert-bank-soal"></div>

            <!--- alert succes after success update level name ----->
            <div id="alert-success-update-level"></div>

            <main class="bg-white shadow-lg border h-max rounded-lg">
                <section>
                    <form id="bank-soal-form" enctype="multipart/form-data">

                        <!--- button bulkupload soal pembahasan --->
                        <div class="flex justify-end p-6">
                            <button type="button"
                                class="bg-[#4189e0] hover:bg-blue-500 text-white font-bold h-10 px-6 rounded-lg shadow-md transition-all text-sm flex gap-2 items-center justify-center"
                                onclick="my_modal_1.showModal()">
                                <i class="fa-solid fa-circle-plus"></i>
                                Bulk Upload Soal
                            </button>
                        </div>

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
                                                .docx.</span>
                                        </div>
                                        <div class="upload-icon">
                                            <div class="flex flex-col max-w-[260px]">
                                                <div id="wordPreview" class="max-w-[280px] cursor-pointer mt-4">
                                                    <div id="wordPreviewContainer-bulkUpload-word"
                                                        class="bg-white shadow-lg rounded-lg w-max py-2 pr-4 border-[1px] border-gray-200 hidden">
                                                        <div class="flex items-center">
                                                            <img id="logo-bulkUpload-word" class="w-[56px] h-max">
                                                            <div class="mt-2 leading-5">
                                                                <span id="textPreview-bulkUpload-word"
                                                                    class="font-bold text-sm"></span><br>
                                                                <span id="textSize-bulkUpload-word"
                                                                    class="text-xs"></span>
                                                                <span id="textCircle-bulkUpload-word"
                                                                    class="relative top-[-2px] text-[5px]"></span>
                                                                <span id="textPages-bulkUpload-word"
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
                                        <label for="file-bulkUpload-word"
                                            class="w-full h-full flex justify-center items-center cursor-pointer gap-2">
                                            <i class="fa-solid fa-arrow-up-from-bracket"></i>
                                            <span>Upload File</span>
                                        </label>
                                        <input id="file-bulkUpload-word" name="bulkUpload-soal-pembahasan"
                                            class="hidden" onchange="previewWord(event, 'bulkUpload-word')"
                                            type="file" accept=".docx">
                                        <span id="error-bulkUpload-soal-pembahasan"
                                            class="text-red-500 font-bold text-xs pt-2"></span>
                                    </div>
                                </div>


                                <!-- Tombol Kirim -->
                                <div class="flex justify-end mt-8 z-[-1]">
                                    <button type="button" id="submit-button"
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

                <section class="py-4">
                    <!--- table daftar list bank soal --->
                    <div class="overflow-x-auto m-4 pb-10">
                        <table class="table" id="table-bank-soal">
                            <thead class="thead-table-bank-soal hidden">
                                <tr>
                                    <th class="th-table text-black opacity-70">No</th>
                                    <th class="th-table text-black opacity-70">Level</th>
                                    <th class="th-table text-black opacity-70">Status Bank Soal</th>
                                    <th class="th-table text-black opacity-70">Action</th>
                                    <th class="th-table text-black opacity-70">Detail</th>
                                </tr>
                            </thead>
                            <tbody id="table-list-bank-soal">
                                {{-- show data in ajax --}}
                            </tbody>
                        </table>

                        <div class="pagination-container-bank-soal flex justify-center my-4 sm:my-0"></div>

                        <div id="empty-message-bank-soal" class="w-full h-96 hidden">
                            <span class="w-full h-full flex items-center justify-center">
                                Tidak ada daftar list bank soal.
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

<script src="{{ asset('js/Features/english-zone/bank-soal/form-action-bank-soal.js') }}"></script> <!--- form action upload bank soal ---->
<script src="{{ asset('js/Features/english-zone/bank-soal/daftar-list-bank-soal.js') }}"></script> <!--- daftar list bank soal ---->

<!--- COMPONENTS ---->
<script src="{{ asset('js/components/clear-error-on-input.js') }}"></script> <!--- clear error on input ---->
<script src="{{ asset('js/components/preview/word-upload-preview.js') }}"></script> <!--- show word ---->


<!--- PUSHER LISTENER ---->
<script src="{{ asset('js/pusher-listener/english-zone/bank-soal-store.js') }}"></script> <!--- pusher listener pada saat insert bank soal ---->
<script src="{{ asset('js/pusher-listener/english-zone/bank-soal-activate.js') }}"></script> <!--- pusher listener pada saat activate bank soal ---->
<script src="{{ asset('js/pusher-listener/english-zone/bank-soal-edit-level.js') }}"></script> <!--- pusher listener pada saat edit nama level bank soal ---->
