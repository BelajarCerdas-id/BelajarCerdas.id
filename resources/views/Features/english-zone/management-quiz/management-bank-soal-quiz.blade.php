@include('components/sidebar_beranda', [
    'headerSideNav' => 'Bank Soal Quiz',
    'linkBackButton' => route('EZ.managementPassageDetail.view', [$level_id, $passage_type]),
    'backButton' => "<i class='fa-solid fa-chevron-left'></i>",
]);

@if (Auth::user()->role === 'Administrator')
    <div class="home-beranda z-[-1] md:z-0 mt-[80px] md:mt-0">
        <div class="content-beranda">
            <!--- alert succes after success insert quiz questions ----->
            <div id="alert-success-insert-bank-soal-quiz"></div>

            <!--- alert succes after success insert quiz questions ----->
            <div id="alert-success-delete-bank-soal-quiz"></div>

            <main>
                <section class="flex flex-row md:items-center justify-between gap-8">

                    <!--- search bar --->
                    <label class="input input-bordered flex items-center gap-2 w-52 md:w-max">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 opacity-70" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1111 3a7.5 7.5 0 015.65 13.65z" />
                        </svg>
                        <input id="search_question" type="search" class="grow text-sm"
                            placeholder="Cari soal..." autocomplete="OFF"/>
                    </label>

                    <form id="quiz-bank-soal-quiz-form" enctype="multipart/form-data">

                        <!--- button bulkupload quiz soal english zone --->
                        <div class="flex flex-start md:justify-end">
                            <button type="button"
                                class="bg-[#4189e0] hover:bg-blue-500 text-white font-bold h-10 px-4 sm:px-9 rounded-lg shadow-md transition-all text-sm flex gap-2 items-center justify-center"
                                onclick="my_modal_1.showModal()">
                                <i class="fa-solid fa-circle-plus"></i>
                                Bulk Upload Soal
                            </button>
                        </div>

                        <!--- modal bulkupload quiz soal english zone --->
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
                                        <input id="file-bulkUpload-word" name="bulkUpload-soal-quiz-english-zone"
                                            class="hidden" onchange="previewWord(event, 'bulkUpload-word')"
                                            type="file" accept=".docx">
                                        <span id="error-bulkUpload-soal-quiz-english-zone"
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
                    <!--- daftar list soal quiz --->
                    <div id="container-bank-soal-quiz" data-level-id="{{ $level_id }}" data-passage-id="{{ $passage_id }}" data-passage-type="{{ $passage_type }}">
                        <div id="grid-list-soal" class="container-accordion mb-8">
                            <!-- show data in ajax -->
                        </div>
                    </div>

                    <div id="empty-message-bank-soal-quiz" class="w-full h-96 hidden">
                        <span class="w-full h-full flex items-center justify-center">
                            Tidak ada daftar list bank soal.
                        </span>
                    </div>
                </section>

            <!---- modal delete question  ---->
            <dialog id="my_modal_2" class="modal">
                <div class="modal-box bg-white">
                    <h3 class="font-bold text-lg text-red-600">Konfirmasi Hapus</h3>
                    <p class="py-4">Soal yang sudah dihapus tidak dapat dikembalikan.
                        Apakah kamu
                        yakin
                        ingin menghapus soal ini?</p>
                    <div class="modal-action">
                        <span id="hapus-modal" class="btn" onclick="closeModal()">Batal</span>
                        <form id="delete-question-form">
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

<script src="{{ asset('js/Features/english-zone/management-quiz/management-bank-soal-quiz.js') }}"></script> <!--- management bank soal quiz ---->

<!--- COMPONENTS ---->
<script src="{{ asset('js/components/clear-error-on-input.js') }}"></script> <!--- clear error on input ---->
<script src="{{ asset('js/components/preview/word-upload-preview.js') }}"></script> <!--- show word ---->
<script src="{{ asset('js/accordion-soal.js') }}"></script> <!-- accordion script -->

<!--- PUSHER LISTENER ---->
<script src="{{ asset('js/pusher-listener/english-zone/bank-soal-quiz-store.js') }}"></script> <!--- pusher listener pada saat insert bank soal quiz ---->
<script src="{{ asset('js/pusher-listener/english-zone/bank-soal-quiz-activate-delete.js') }}"></script> <!--- pusher listener pada saat activate / delete bank soal quiz ---->